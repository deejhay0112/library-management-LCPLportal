<?php
include 'db.php'; // Ensure db.php has the correct database connection setup

// List of DDC Codes and Subjects
$ddcList = [
    "computer science" => "000",
    "philosophy" => "100",
    "religion" => "200",
    "social sciences" => "300",
    "language" => "400",
    "science" => "500",
    "technology" => "600",
    "arts" => "700",
    "literature" => "800",
    "history" => "900"
];

// Initialize search term and convert it to lower case
$searchTerm = strtolower($_GET['search']);

// Determine if the search term is a subject
$ddcCode = isset($ddcList[$searchTerm]) ? $ddcList[$searchTerm] : null;

if ($ddcCode) {
    // Prepare your SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM book WHERE `Call No` LIKE ?");
    $likeTerm = '%' . $ddcCode . '%'; // Use DDC code in search
    $stmt->bind_param("s", $likeTerm);
} else {
    // Prepare SQL statement for normal search
    $stmt = $conn->prepare("SELECT * FROM book WHERE `Call No` LIKE ? OR `Author_Title` LIKE ? OR `Title` LIKE ?");
    $likeTerm = '%' . $searchTerm . '%';
    $stmt->bind_param("sss", $likeTerm, $likeTerm, $likeTerm);
}

$stmt->execute();
$result = $stmt->get_result();

$output = '';

// Fetch books from the database with the search query
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $callNo = $row['Call No'];
        // Extract the first four characters before the dot
        $callNoDisplay = strtok($callNo, '.'); // Get everything before the dot
        $callNoDisplay = substr($callNoDisplay, 0, 4); // Limit to 4 characters
        echo "<tr>";
        echo "<td>" . $row['No of Books'] . "</td>";
        echo "<td>" . $row['Author_Title'] . "</td>";
        echo "<td>" . $row['Title'] . "</td>";
        echo "<td>" . $row['Accession No'] . "</td>";
        echo "<td>" . $row['Call No'] . "</td>";
        echo "<td>" . $row['Quantity'] . "</td>";
        echo "<td>" . $row['Unit'] . "</td>";
        echo "<td>" . $row['Date Acquired'] . "</td>";
        echo "<td>
    <button class='button edit-button' onclick='openEditModal({$row['Book Id']})'>
        <i class='bx bx-pencil'></i> <!-- Edit icon -->
    </button>
    <button class='button delete-button' onclick='deleteRow({$row['Book Id']})'>
        <i class='bx bx-trash'></i> <!-- Delete icon -->
    </button>
</td>";


        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='9'>No books found.</td></tr>";
}

$conn->close();
?>