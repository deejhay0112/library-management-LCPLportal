<?php
include 'db.php'; // Ensure this file includes the database connection

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string(trim($_POST['username']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = trim($_POST['password']);
    $role = $conn->real_escape_string(trim($_POST['role']));  // Capture the role

    // Check if the password is at least 8 characters long
    if (strlen($password) < 8) {
        // Redirect to the admin page with 'short_password' status if the password is too short
        header("Location: admin_account.php?status=short_password");
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email or username already exists in the database
    $checkQuery = "SELECT * FROM users WHERE email = '$email' OR username = '$username'";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        // Redirect to the admin page with 'exists' status if the email/username is already taken
        header("Location: admin_account.php?status=exists");
    } else {
        // Insert the new user into the database, including the role
        $sql = "INSERT INTO users (username, email, password, role) 
                VALUES ('$username', '$email', '$hashed_password', '$role')";

        if ($conn->query($sql) === TRUE) {
            // Redirect to the admin page with 'added' status if the insertion was successful
            header("Location: admin_account.php?status=added");
        } else {
            // Redirect to the admin page with 'error' status if there was a failure
            header("Location: admin_account.php?status=error");
        }
    }

    // Close the database connection
    $conn->close();
}
?>
