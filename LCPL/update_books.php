<?php
include 'db.php'; // Ensure db.php has the correct database connection setup

if (isset($_GET['id'])) {
    $bookId = $conn->real_escape_string($_GET['id']);

    $sql = "SELECT * FROM book WHERE `No of Books` = '$bookId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
        echo json_encode($book); // Send the data as a JSON response
    } else {
        echo json_encode([]); // No book found
    }
}

$conn->close();
?>
