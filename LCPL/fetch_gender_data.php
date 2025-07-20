<?php
header('Content-Type: application/json');

// Include your database connection file
include 'db.php';

// Check database connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Get the date and age range from the request
$date = isset($_GET['date']) ? $_GET['date'] : '';
$age_range = isset($_GET['age_range']) ? $_GET['age_range'] : '';

// Validate the date parameter
if (empty($date)) {
    echo json_encode(['error' => 'Date is required']);
    exit;
}

// Default age range if not provided
$min_age = 0;
$max_age = 100;

// Validate and parse the age range
if (!empty($age_range) && strpos($age_range, '-') !== false) {
    list($min_age, $max_age) = explode('-', $age_range);
    $min_age = (int)$min_age;
    $max_age = (int)$max_age;
}

// Initialize response structure
$response = [
    'male' => 0,
    'female' => 0,
    'lgbt' => 0,
    'ages' => ['male' => [], 'female' => [], 'lgbt' => []]
];

// SQL query to count users by gender (case-insensitive)
$count_sql = "SELECT 
                SUM(CASE WHEN LOWER(Sex) = 'male' THEN 1 ELSE 0 END) AS male,
                SUM(CASE WHEN LOWER(Sex) = 'female' THEN 1 ELSE 0 END) AS female,
                SUM(CASE WHEN LOWER(Sex) = 'lgbtq' THEN 1 ELSE 0 END) AS lgbt
            FROM male_1 
            WHERE Date = ? AND Age BETWEEN ? AND ?";

$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param('sii', $date, $min_age, $max_age);

// SQL query to fetch ages grouped by gender
$ages_sql = "SELECT LOWER(Sex) AS Sex, Age
             FROM male_1
             WHERE Date = ? AND Age BETWEEN ? AND ?";

$ages_stmt = $conn->prepare($ages_sql);
$ages_stmt->bind_param('sii', $date, $min_age, $max_age);

// Execute the count query
if ($count_stmt->execute()) {
    $result = $count_stmt->get_result();
    $data = $result->fetch_assoc();

    $response['male'] = $data['male'] ?? 0;
    $response['female'] = $data['female'] ?? 0;
    $response['lgbt'] = $data['lgbt'] ?? 0;
} else {
    echo json_encode(['error' => 'Count query failed: ' . $count_stmt->error]);
    exit;
}

// Execute the age query
if ($ages_stmt->execute()) {
    $ages_result = $ages_stmt->get_result();
    while ($row = $ages_result->fetch_assoc()) {
        // Debugging: Log the row being processed
        error_log("Row Data: " . print_r($row, true));

        // Group ages by gender
        if ($row['Sex'] === 'male') {
            $response['ages']['male'][] = $row['Age'];
        } elseif ($row['Sex'] === 'female') {
            $response['ages']['female'][] = $row['Age'];
        } elseif ($row['Sex'] === 'lgbtq') {
            $response['ages']['lgbt'][] = $row['Age'];
        }
    }
} else {
    echo json_encode(['error' => 'Age query failed: ' . $ages_stmt->error]);
    exit;
}

// Debugging: Log the final response
error_log("Final Response: " . print_r($response, true));

// Return the JSON response
echo json_encode($response);

// Close the statements and database connection
$count_stmt->close();
$ages_stmt->close();
$conn->close();
?>
