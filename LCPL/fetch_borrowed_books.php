<?php
// fetch_borrowed_books.php

// Include your database connection file
include 'db.php'; // Ensure you have this

// Check if the date is passed
if (isset($_GET['date'])) {
    $date = $_GET['date'];

    // Prepare SQL statement to fetch borrowed books based on the date
    $sql = "SELECT COUNT(*) as total_borrowed FROM borrowed_books WHERE borrow_date = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'SQL Error: ' . $conn->error]);
        exit();
    }
    
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $data = $result->fetch_assoc();
        // Return data as JSON
        echo json_encode(['success' => true, 'total_borrowed' => $data['total_borrowed']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No data found']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'No date provided']);
}

$conn->close();
?>
