<?php
header('Content-Type: application/json');

// Include your database connection file
include 'db.php'; // Ensure the path is correct

// Check if the connection exists
if (!$conn) {
    die(json_encode(['error' => 'Connection failed: ' . mysqli_connect_error()]));
}

// Query to fetch records where Sex is 'FEMALE'
$femaleQuery = "SELECT * FROM male_1 WHERE Sex = 'FEMA'"; // Ensure correct value
$femaleResult = $conn->query($femaleQuery);

// Check if the query was successful
if ($femaleResult) {
    $femaleUsers = [];

    // Fetch all rows and store in an array
    while ($row = $femaleResult->fetch_assoc()) {
        $femaleUsers[] = $row;
    }

    // Prepare response
    $response = [
        'total_female_users' => count($femaleUsers),
        'female_users' => $femaleUsers
    ];

    // Log response for debugging
    error_log(json_encode($response)); 

    // Output the JSON response
    echo json_encode($response);
} else {
    // Return an error message if the query fails
    echo json_encode(['error' => 'Query error: ' . $conn->error]);
}

// Close the connection if necessary
$conn->close(); // Uncomment if you want to close the connection here
?>
