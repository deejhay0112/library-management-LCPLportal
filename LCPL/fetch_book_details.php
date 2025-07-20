<?php
include 'db.php';

if (isset($_GET['id'])) {
    $bookId = $conn->real_escape_string($_GET['id']);
    
    // Use the correct column for identifying the book
    $sql = "SELECT * FROM book WHERE `Book Id`='$bookId'"; // Assuming 'Book Id' is the primary key
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row); // Return book details as JSON
    } else {
        // No book found, return an empty JSON object
        echo json_encode(["error" => "Book not found"]); // Indicate that the book was not found
    }
} else {
    // No ID was provided
    echo json_encode(["error" => "No ID provided"]); // Indicate that no ID was given
}

$conn->close();
?>
