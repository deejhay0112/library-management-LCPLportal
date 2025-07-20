
<?php
header('Content-Type: application/json');

// Include your database connection file
include 'db.php'; // Replace with the actual path to your db connection file

// Query to count visitors by month
$query = "
    SELECT 
        months.month AS month, 
        COALESCE(COUNT(male_1.`ID No.`), 0) AS total_visitors
    FROM (
        SELECT 1 AS month UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
        UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 
        UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12
    ) AS months
    LEFT JOIN male_1 ON MONTH(male_1.Date) = months.month AND YEAR(male_1.Date) = 2024
    GROUP BY months.month;
";

$result = $conn->query($query);

if ($result) {
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Output the data as JSON
    echo json_encode($data);
} else {
    echo json_encode(['error' => 'Query error: ' . $conn->error]);
}

$conn->close();
?>


