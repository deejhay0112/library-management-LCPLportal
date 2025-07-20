<?php
include 'db.php'; // Ensure the DB connection is correct

// Check if the 'id' POST variable is set
if (isset($_POST['id'])) {
    $bookId = intval($_POST['id']); // Convert to integer (security measure)
    
    // Correct the SQL query with backticks around `Book Id`
    $query = "DELETE FROM book WHERE `Book Id` = ?"; 

    // Prepare and execute the statement
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $bookId); // Bind the book ID as an integer
        $stmt->execute();

        // Check if the deletion affected any rows
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true]); // Return success
        } else {
            echo json_encode(['success' => false, 'message' => 'No rows affected.']); // If no rows were deleted
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . $conn->error]); // Handle statement preparation error
    }

    $conn->close(); // Close the connection
} else {
    echo json_encode(['success' => false, 'message' => 'Book ID not provided.']); // Handle missing book ID
}
?>
