<?php
// Database connection
include 'db.php'; // Adjust the path to your actual connection file

// Get the selected year from the query parameter
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

// SQL queries to get total visitors and counts by gender per month
$totalVisitorsQuery = "SELECT MONTH(`Date`) as month, COUNT(*) as total FROM `male_1` WHERE YEAR(`Date`) = $year GROUP BY MONTH(`Date`)";
$maleVisitorsQuery = "SELECT MONTH(`Date`) as month, COUNT(*) as male FROM `male_1` WHERE YEAR(`Date`) = $year AND Sex = 'MALE' GROUP BY MONTH(`Date`)";
$femaleVisitorsQuery = "SELECT MONTH(`Date`) as month, COUNT(*) as female FROM `male_1` WHERE YEAR(`Date`) = $year AND Sex = 'FEMALE' GROUP BY MONTH(`Date`)";
$lgbtVisitorsQuery = "SELECT MONTH(`Date`) as month, COUNT(*) as lgbt FROM `male_1` WHERE YEAR(`Date`) = $year AND Sex = 'LGBTQ' GROUP BY MONTH(`Date`)";

$totalVisitorsResult = $conn->query($totalVisitorsQuery);
$maleVisitorsResult = $conn->query($maleVisitorsQuery);
$femaleVisitorsResult = $conn->query($femaleVisitorsQuery);
$lgbtVisitorsResult = $conn->query($lgbtVisitorsQuery);

// Initialize arrays for 12 months
$totalVisitors = array_fill(0, 12, 0);
$maleVisitors = array_fill(0, 12, 0);
$femaleVisitors = array_fill(0, 12, 0);
$lgbtVisitors = array_fill(0, 12, 0);

// Populate total visitors data
while ($row = $totalVisitorsResult->fetch_assoc()) {
    $totalVisitors[$row['month'] - 1] = $row['total'];
}

// Populate male visitors data
while ($row = $maleVisitorsResult->fetch_assoc()) {
    $maleVisitors[$row['month'] - 1] = $row['male'];
}

// Populate female visitors data
while ($row = $femaleVisitorsResult->fetch_assoc()) {
    $femaleVisitors[$row['month'] - 1] = $row['female'];
}

// Populate LGBT visitors data
while ($row = $lgbtVisitorsResult->fetch_assoc()) {
    $lgbtVisitors[$row['month'] - 1] = $row['lgbt'];
}

// Return the data as a JSON response
header('Content-Type: application/json');
echo json_encode([
    'visitors' => $totalVisitors,
    'male' => $maleVisitors,
    'female' => $femaleVisitors,
    'lgbt' => $lgbtVisitors
]);
?>
