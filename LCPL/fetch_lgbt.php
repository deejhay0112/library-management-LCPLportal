<?php
header('Content-Type: application/json');

// Include your database connection file
include 'db.php'; // Make sure the path is correct

// Check if the connection exists
if (!$conn) {
    die(json_encode(['error' => 'Connection failed: ' . mysqli_connect_error()]));
}

// Query to fetch records where Sex is 'MALE'
$LGBTQuery = "SELECT * FROM male_1 WHERE Sex = 'LGBT'";
$LGBTResult = $conn->query($LGBTQuery);

// Check if the query was successful
if ($LGBTResult) {
    $lgbtUsers = [];

    // Fetch all rows and store in an array
    while ($row = $LGBTResult->fetch_assoc()) {
        $lgbtUsers[] = $row;
    }

    // Prepare response
    $response = [
        'total_lgbt_users' => count($lgbtUsers),
        'lgbt_users' => $lgbtUsers
    ];

    // Output the JSON response
    echo json_encode($response);
} else {
    // Return an error message if the query fails
    echo json_encode(['error' => 'Query error: ' . $conn->error]);
}

// Close the connection if necessary
$conn->close(); // Uncomment if you want to close the connection here
?>
