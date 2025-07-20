<?php
session_start();
include 'db.php';

// Set response type to JSON
header('Content-Type: application/json');

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if the password has at least 8 characters
    if (strlen($password) < 8) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters long.']);
        exit();
    }

    // Retrieve the hashed password and role for the given username
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $storedHashedPassword = $row['password']; // Store the hashed password from the database
        $role = $row['role']; // Retrieve the user's role (admin or staff)

        // Verify the entered password against the stored hashed password
        if (password_verify($password, $storedHashedPassword)) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role; // Store the role in the session

            // Send success response with the redirect URL based on role
            echo json_encode([
                'success' => true,
                'role' => $role, // Include the role in the response
            ]);
            exit();
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

$conn->close();
?>
