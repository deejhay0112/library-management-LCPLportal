<?php
header('Content-Type: application/json');
include 'db.php'; // Database connection file

// Get the date from the request, or use today's date as default
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Debugging: Check if the date is being passed correctly
error_log("Selected Date: " . $date);

// SQL Query to fetch visitors grouped by hour (8 AM to 6 PM) for the selected date
$query = "SELECT DATE_FORMAT(time, '%h %p') AS formatted_hour, COUNT(*) AS visitor_count
          FROM male_1
          WHERE DATE(time) = '$date' AND HOUR(time) BETWEEN 8 AND 18
          GROUP BY HOUR(time)
          ORDER BY HOUR(time)";

$result = mysqli_query($conn, $query);

// Check if the query executes correctly
if (!$result) {
    error_log("MySQL Error: " . mysqli_error($conn));
    echo json_encode(['error' => 'Database query failed']);
    exit();
}

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = [
        'hour' => $row['formatted_hour'], // Formatted as AM/PM
        'count' => $row['visitor_count']
    ];
}

// Check if data is fetched
if (empty($data)) {
    error_log("No data found for date: " . $date);
}

// Return JSON response
echo json_encode($data);
?>
