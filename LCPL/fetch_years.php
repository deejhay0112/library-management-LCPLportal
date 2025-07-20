<?php
// Database connection
include 'db.php'; // Adjust the path to your actual connection file

// SQL query to get distinct years from the Date column in `male_1` table
$query = "SELECT DISTINCT YEAR(`Date`) as year FROM `male_1` ORDER BY year DESC";
$result = $conn->query($query);

$years = [];

if ($result->num_rows > 0) {
    // Fetch all distinct years
    while ($row = $result->fetch_assoc()) {
        $years[] = $row['year'];
    }
}

// Return the years as a JSON response
header('Content-Type: application/json');
echo json_encode($years);
?>
