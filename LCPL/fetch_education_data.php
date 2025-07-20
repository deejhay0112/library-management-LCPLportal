<?php
header('Content-Type: application/json');

// Database connection
include 'db.php';

// Get the date from the query parameter
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d'); // Default to today's date if no date is provided

$sql = "SELECT
SUM(CASE WHEN Elementry = 'Yes' THEN 1 ELSE 0 END) AS ElementaryCount,
SUM(CASE WHEN Highschool = 'Yes' THEN 1 ELSE 0 END) AS HighschoolCount,
SUM(CASE WHEN Shs = 'Yes' THEN 1 ELSE 0 END) AS ShSCount,
SUM(CASE WHEN College = 'Yes' THEN 1 ELSE 0 END) AS CollegeCount,
SUM(CASE WHEN PostGrad = 'Yes' THEN 1 ELSE 0 END) AS PostGradCount,
SUM(CASE WHEN Osy = 'Yes' THEN 1 ELSE 0 END) AS OsyCount -- Added OSY count
FROM male_1
WHERE DATE(Date) = ?"; // Ensure there is no semicolon here

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $date);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Return the response
echo json_encode($data);

$stmt->close();
$conn->close();
?>
