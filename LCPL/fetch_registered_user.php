<?php
include 'db.php';

// Check if the 'date' parameter is passed; default to an empty string if not
$date = isset($_GET['date']) ? $conn->real_escape_string($_GET['date']) : ''; 

// Date filter condition; empty if no date provided
$dateFilter = !empty($date) ? "WHERE Date = '$date'" : "";

// Initialize counts
$counts = [
    "student" => 0,
    "employed" => 0,
    "unemployed" => 0,
    "self_employed" => 0
];

try {
    // Query to fetch counts for each occupation
    $query = "SELECT Occupation, COUNT(*) as count FROM male_1 $dateFilter GROUP BY Occupation";
    $result = $conn->query($query);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $occupation = strtolower($row['Occupation']);
            if (isset($counts[$occupation])) {
                $counts[$occupation] = (int)$row['count'];
            }
        }
    } else {
        throw new Exception("Error fetching data: " . $conn->error);
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($counts);

} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(["error" => $e->getMessage()]);
}

$conn->close();
?>
