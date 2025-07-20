<?php
session_start();

// Include the database connection file
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password']) && isset($_SESSION['username_verified'])) {
    $newPassword = $_POST['new_password']; // Store plain text password directly (for learning purposes)
    $username = $_SESSION['username_verified'];
    
    // Update the user's password in the database (plain text)
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
    $stmt->bind_param("ss", $newPassword, $username);
    
    if ($stmt->execute()) {
        // Success message, clear the session variable
        unset($_SESSION['username_verified']);
        header("Location: forgot_password.php?success=Password has been reset successfully");
    } else {
        // Redirect back with an error message if update fails
        header("Location: forgot_password.php?error=Error updating the password");
    }
    exit();
} else {
    // Redirect to forgot_password.php if unauthorized access
    header("Location: forgot_password.php");
    exit();
}

?>
