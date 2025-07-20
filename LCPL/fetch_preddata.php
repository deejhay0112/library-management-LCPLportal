<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include 'db.php';

// Get the 'filter' parameter (daily, weekly, monthly) and sanitize input
$allowed_filters = ['daily', 'weekly', 'monthly'];
$filter = isset($_GET['filter']) && in_array($_GET['filter'], $allowed_filters) ? $_GET['filter'] : 'daily';

// Helper function to handle missing dates
function fillMissingDates($data, $interval = 'daily') {
    $filledData = [];
    $dateFormat = ($interval === 'monthly') ? 'Y-m' : 'Y-m-d';

    // Get the date range
    $start = new DateTime($data[0]['Date']);
    $end = new DateTime(end($data)['Date']);
    $intervalSpec = ($interval === 'weekly') ? 'P1W' : ($interval === 'monthly' ? 'P1M' : 'P1D');
    $period = new DatePeriod($start, new DateInterval($intervalSpec), $end->modify('+1 day'));

    // Fill missing dates with 0 visitors
    $dataMap = [];
    foreach ($data as $row) {
        $dataMap[$row['Date']] = $row['visitors'];
    }

    foreach ($period as $date) {
        $formattedDate = $date->format($dateFormat);
        $filledData[] = [
            "Date" => $formattedDate,
            "visitors" => isset($dataMap[$formattedDate]) ? $dataMap[$formattedDate] : 0
        ];
    }

    return $filledData;
}

// Query based on the selected filter
switch ($filter) {
    case 'weekly':
        // Group data by week
        $query = "SELECT DATE_FORMAT(`Date`, '%Y-%u') AS Date, COUNT(`ID No.`) AS visitors 
                  FROM male_1 
                  GROUP BY DATE_FORMAT(`Date`, '%Y-%u') 
                  ORDER BY `Date`";
        break;

    case 'monthly':
        // Group data by month
        $query = "SELECT DATE_FORMAT(`Date`, '%Y-%m') AS Date, COUNT(`ID No.`) AS visitors 
                  FROM male_1 
                  GROUP BY DATE_FORMAT(`Date`, '%Y-%m') 
                  ORDER BY `Date`";
        break;

    case 'daily':
    default:
        // Default: Group data by day
        $query = "SELECT `Date`, COUNT(`ID No.`) AS visitors 
                  FROM male_1 
                  GROUP BY `Date` 
                  ORDER BY `Date`";
        break;
}

// Execute the query
$result = $conn->query($query);

if ($result) {
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            "Date" => $row['Date'],
            "visitors" => (int)$row['visitors']
        ];
    }

    // Fill missing dates for continuous time series
    if (!empty($data)) {
        $filledData = fillMissingDates($data, $filter);
        echo json_encode($filledData);
    } else {
        echo json_encode(["message" => "No data available for the selected filter."]);
    }
} else {
    echo json_encode(["error" => "Failed to fetch data: " . $conn->error]);
}

// Close the connection
$conn->close();
?>
