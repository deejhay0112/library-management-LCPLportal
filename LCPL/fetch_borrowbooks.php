<?php
include 'db.php'; // Database connection file

$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT * FROM book WHERE Title LIKE ? OR Author_Title LIKE ? OR `Accession No` LIKE ?"; // Use backticks for column with space
$stmt = $conn->prepare($query);
$searchTerm = '%' . $search . '%';
$stmt->bind_param('sss', $searchTerm, $searchTerm, $searchTerm); // Bind the search term for all 3 fields
$stmt->execute();
$result = $stmt->get_result();

$books = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row; // Store the result in an array
    }
}

echo json_encode($books); // Return results as JSON
$conn->close();
?>
