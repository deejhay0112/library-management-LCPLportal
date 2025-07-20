<?php
header('Content-Type: application/json');

// Include your database connection file
include 'db.php'; // Ensure the path is correct

// Check if the connection exists
if (!$conn) {
    die(json_encode(['error' => 'Connection failed: ' . mysqli_connect_error()]));
}

// Get the date from the request
$date = isset($_GET['date']) ? $_GET['date'] : '';

// Prepare the query to fetch total visitors based on the date
$query = "SELECT COUNT(*) as total_visitors FROM male_1 WHERE Date = '$date'"; // Make sure your table structure is correct

$result = $conn->query($query);

// Check if the query was successful
if ($result) {
    $data = $result->fetch_assoc();
    echo json_encode($data); // Return the total visitors count
} else {
    echo json_encode(['error' => 'Query error: ' . $conn->error]);
}

// Close the connection
$conn->close();
?>
