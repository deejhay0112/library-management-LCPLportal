<?php
include 'db.php'; // Ensure this includes your database connection setup

// Ensure the connection to the database is active
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Default to today's date if no date is provided
$date = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");

// Prepare SQL query to fetch entries for the specified date
$sql = "SELECT `ID No.`, `School`, `Date`, `Age`, `Sex`, `time`, `Elementry`, `Shs`, `Highschool`, `College`, `PostGrad`, `Osy`, `Occupation`
        FROM male_1 
        WHERE `Date` = '$date'
        ORDER BY `ID No.` ASC";

// Check if query is executed successfully
if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Echo each table row
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['ID No.']) . "</td>";
            echo "<td>" . htmlspecialchars($row['School']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Age']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Sex']) . "</td>";
            echo "<td>" . htmlspecialchars($row['time']) . "</td>";

            // Display Educational Level based on Yes/No values
            $educLevel = [];
            if ($row['Elementry'] == 'Yes') $educLevel[] = "Elementary";
            if ($row['Shs'] == 'Yes') $educLevel[] = "SHS";
            if ($row['Highschool'] == 'Yes') $educLevel[] = "Highschool";
            if ($row['College'] == 'Yes') $educLevel[] = "College";
            if ($row['PostGrad'] == 'Yes') $educLevel[] = "PostGrad";
            if ($row['Osy'] == 'Yes') $educLevel[] = "Osy";

            // Join all levels with a comma
            echo "<td>" . implode(", ", $educLevel) . "</td>";

            // Display Occupation
            echo "<td>" . htmlspecialchars($row['Occupation']) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='13'>No records found for this date.</td></tr>";
    }
} else {
    echo "Error executing query: " . $conn->error;
}

$conn->close(); // Close the database connection
?>
