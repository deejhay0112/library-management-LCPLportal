<?php
include 'db.php';

$date = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");

// Validate the date format (YYYY-MM-DD)
if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date)) {
    echo json_encode(["error" => "Invalid date format"]);
    exit;
}

// Prepare SQL query to fetch entries for the specified date using a prepared statement
$sql = "SELECT `ID No.`, `School`, `Date`, `Age`, `Sex`, `time`, `Elementry`, `Shs`, `Highschool`, `College`, `PostGrad`, `Osy`, `Occupation`
        FROM male_1 
        WHERE `Date` = ?
        ORDER BY `ID No.` ASC";

// Prepare the statement
if ($stmt = $conn->prepare($sql)) {
    // Bind the date parameter
    $stmt->bind_param("s", $date);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if results are found
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            "ID_No" => $row['ID No.'],
            "School" => $row['School'],
            "Date" => $row['Date'],
            "Age" => $row['Age'],
            "Sex" => $row['Sex'],
            "Time" => $row['time'],
            "Elementary" => $row['Elementry'] === 'Yes' ? 'Yes' : 'No',
            "Highschool" => $row['Highschool'] === 'Yes' ? 'Yes' : 'No',
            "Shs" => $row['Shs'] === 'Yes' ? 'Yes' : 'No',
            "College" => $row['College'] === 'Yes' ? 'Yes' : 'No',
            "PostGrad" => $row['PostGrad'] === 'Yes' ? 'Yes' : 'No',
            "Osy" => $row['Osy'] === 'Yes' ? 'Yes' : 'No',
            "Occupation" => $row['Occupation'] // Added Occupation field
        ];
    }

    // If no records found, return a message
    if (empty($data)) {
        $data = ["message" => "No records found for the selected date"];
    }

    // Output JSON
    header('Content-Type: application/json');
    echo json_encode($data);

    // Close the statement
    $stmt->close();
} else {
    echo json_encode(["error" => "Failed to prepare SQL statement"]);
}

// Close the database connection
$conn->close();
?>
