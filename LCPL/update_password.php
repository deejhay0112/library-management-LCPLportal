<?php
include 'db.php';

// Set PHP timezone
date_default_timezone_set('UTC'); // Change 'UTC' to your desired timezone

$response = [
    'success' => false,
    'message' => 'An unknown error occurred.'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'], $_POST['token'])) {
    $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $token = urldecode($_POST['token']);
    $currentDateTime = date("Y-m-d H:i:s");

    // Check if the token exists in the database and is not expired
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ? AND token_expiry > ?");
    $stmt->bind_param("ss", $token, $currentDateTime);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $username = $user['username'];

        // Update the password and clear the token
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE username = ?");
        $stmt->bind_param("ss", $newPassword, $username);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Your password has been reset successfully.';
        } else {
            $response['message'] = 'Failed to update the password.';
        }
    } else {
        $response['message'] = 'Token not found or expired. Password update cannot proceed.';
    }
} else {
    $response['message'] = 'Invalid request.';
}

// Output JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
