<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

// Include database connection
include 'db.php';

// Suppress errors and display clean JSON
error_reporting(0);
ini_set('display_errors', 0);

// Default response structure
$response = [];
$data = [];

// Get 'trend', 'start_date', and 'end_date' from GET parameters
$trend = isset($_GET['trend']) ? $_GET['trend'] : 'daily';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;

// Choose query based on trend
switch ($trend) {
    case 'daily':
        // Daily trend with optional date range filtering
        if ($start_date && $end_date) {
            $query = "SELECT Date AS visit_date, COUNT(*) AS total_visits 
                      FROM male_1 
                      WHERE Date BETWEEN '$start_date' AND '$end_date'
                      GROUP BY Date 
                      ORDER BY Date";
        } else {
            $query = "SELECT Date AS visit_date, COUNT(*) AS total_visits 
                      FROM male_1 
                      GROUP BY Date 
                      ORDER BY Date";
        }
        break;

    case 'weekly':
        // Weekly trend
        $query = "SELECT YEARWEEK(Date, 1) AS visit_week, COUNT(*) AS total_visits 
                  FROM male_1 
                  GROUP BY YEARWEEK(Date, 1) 
                  ORDER BY visit_week";
        break;

    case 'monthly':
        // Monthly trend
        $query = "SELECT DATE_FORMAT(Date, '%Y-%m') AS visit_month, COUNT(*) AS total_visits 
                  FROM male_1 
                  GROUP BY DATE_FORMAT(Date, '%Y-%m') 
                  ORDER BY visit_month";
        break;

    case 'last_week_total':
        // Total visits for the last week
        $query = "SELECT COUNT(*) AS total_visits 
                  FROM male_1 
                  WHERE YEARWEEK(Date, 1) = YEARWEEK(CURDATE(), 1) - 1";
        break;

    case 'monthly_total':
        // Total visits for this month
     $query = "SELECT COUNT(*) AS total_visits 
              FROM male_1 
              WHERE DATE_FORMAT(Date, '%Y-%m') = DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH, '%Y-%m')";
    break;

    case 'last_day_total':
        // Total visits for yesterday
        $query = "SELECT COUNT(*) AS total_visits 
                  FROM male_1 
                  WHERE Date = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
        break;

    default:
        // Invalid trend input
        $response = ["error" => "Invalid trend selection"];
        echo json_encode($response);
        exit;
}

// Execute the query
$result = $conn->query($query);

// Handle query results
if ($result === false) {
    // Query failed
    $response = ["error" => "Database query failed", "details" => $conn->error];
} else {
    // Fetch query results
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // For totals, return a single value
        if (in_array($trend, ['last_week_total', 'monthly_total', 'last_day_total'])) {
            $response = $data[0] ?? ["total_visits" => 0];
        } else {
            $response = $data;
        }
    } else {
        // No data found
        $response = [];
    }
}

// Close the database connection
$conn->close();

// Output JSON response
echo json_encode($response);
exit;
?>
