<?php
include 'db.php'; // Ensure this file includes the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $conn->real_escape_string($_POST['id']);
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $role = $conn->real_escape_string($_POST['role']);  // Capture the role

    // Prepare the SQL query based on whether a password is provided
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username = '$username', email = '$email', password = '$hashed_password', role = '$role' WHERE id = '$id'";
    } else {
        $sql = "UPDATE users SET username = '$username', email = '$email', role = '$role' WHERE id = '$id'";
    }

    // Execute the query and redirect based on success or failure
    if ($conn->query($sql) === TRUE) {
        header("Location: admin_account.php?status=updated");
    } else {
        header("Location: admin_account.php?status=error");
    }

    // Close the database connection
    $conn->close();
}
?>
