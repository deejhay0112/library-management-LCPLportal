<?php
// Set header to JSON
header('Content-Type: application/json');

// Include PHPMailer classes
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'db.php';

$response = [
    'icon' => 'error',
    'title' => 'Error',
    'message' => 'An unknown error occurred.'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];
    
    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Generate a new unique reset token and expiry time
        $token = bin2hex(random_bytes(50));
        $tokenExpiry = date("Y-m-d H:i:s", strtotime("+1 hour"));
        
        // Update the database with the new reset token and expiry time
        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, token_expiry = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $tokenExpiry, $email);
        
        if ($stmt->execute()) {
            // Send the email with the reset link using PHPMailer
            $mail = new PHPMailer(true);
            try {
                // SMTP server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.hostinger.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'tmkalawmemorial@lcplportal.net'; // Your Gmail address
            $mail->Password   = 'Keodorok@law0112'; // Your 16-character App Password
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;

            // Email content
            $mail->setFrom('tmkalawmemorial@lcplportal.net', 'Your App Name');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $resetLink = "https://lcplportal.net/LCPL/reset_password.php?token=" . urlencode($token);
            $mail->Body = "Click the following link to reset your password: <a href='$resetLink'>$resetLink</a>. This link will expire in 1 hour.";

            $mail->send();

                // Set success response
                $response['icon'] = 'success';
                $response['title'] = 'Success';
                $response['message'] = 'Password reset link has been sent to your email.';
            } catch (Exception $e) {
                // Set error response with mailer error
                $response['message'] = 'Failed to send the email. Mailer Error: ' . $mail->ErrorInfo;
            }
        } else {
            // Error updating the database with the new token
            $response['message'] = 'Failed to update reset token. Please try again.';
        }
    } else {
        // Set error response if email not found
        $response['message'] = 'Email address not found.';
    }
}

// Output JSON response
echo json_encode($response);
?>
