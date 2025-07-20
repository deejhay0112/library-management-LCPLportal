<?php
session_start();

// Include the database connection file
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = $_POST['username'];
    
    // Verify if the username exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Username verified; store it in session and redirect to step 2
        $_SESSION['username_verified'] = $username;
        header("Location: forgot_password.php?verified=true");
    } else {
        // Redirect back with an error if username not found
        header("Location: forgot_password.php?error=Username not found");
    }
    exit();
}
?>
