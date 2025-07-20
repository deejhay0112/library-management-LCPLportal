<?php
include 'db.php';

// Check if the 'date' parameter is passed; default to an empty string if not
$date = isset($_GET['date']) ? $_GET['date'] : ''; 

// If a date is provided, apply it to the query, otherwise, return an error
if (!empty($date)) {
    $date = $conn->real_escape_string($date);  // Escape the date to prevent SQL injection
    $dateFilter = "AND Date = '$date'"; // Use the 'Date' column in the query
} else {
    // Return error if no date is provided
    header('Content-Type: application/json');
    echo json_encode(["error" => "No date provided"]);
    exit();
}

// Initialize counts
$studentCount = 0;
$retireeCount = 0;

try {
    // Query for Students with date filter
    $studentQuery = "SELECT COUNT(*) as count FROM male_1 
                     WHERE (Elementry = 'Yes' OR Highschool = 'Yes' OR Shs = 'Yes' OR College = 'Yes')
                     $dateFilter";  // Filter by date
    $studentResult = $conn->query($studentQuery);
    if ($studentResult) {
        $studentCount = $studentResult->fetch_assoc()['count'] ?? 0;
    } else {
        throw new Exception("Error fetching students: " . $conn->error);
    }

    // Query for Retirees with date filter
    $retireeQuery = "SELECT COUNT(*) as count FROM male_1 
                     WHERE ((Osy = 'Yes' AND CAST(Age AS UNSIGNED) >= 60) 
                     OR (PostGrad = 'Yes' AND CAST(Age AS UNSIGNED) >= 60))
                     $dateFilter";  // Filter by date
    $retireeResult = $conn->query($retireeQuery);
    if ($retireeResult) {
        $retireeCount = $retireeResult->fetch_assoc()['count'] ?? 0;
    } else {
        throw new Exception("Error fetching retirees: " . $conn->error);
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode([
        "students" => (int)$studentCount,
        "retirees" => (int)$retireeCount
    ]);

} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(["error" => $e->getMessage()]);
}

$conn->close();
?>
