<?php
// db.php
$servername = "localhost";
$username = "u708474031_lms";  // Change if using another username
$password = "Jedjelodex69";      // Add your database password
$dbname = "u708474031_lms"; // Change this to your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
