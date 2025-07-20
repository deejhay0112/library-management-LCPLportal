<?php
// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json'); // Ensure the response is JSON

include 'db.php'; // Database connection

$response = []; // Prepare an array for the response

// Check if an edit request has been sent
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['book_id'])) {
        $book_id = $conn->real_escape_string($_POST['book_id']);
        $no_of_books = $conn->real_escape_string($_POST['no_of_books']);
        $author = $conn->real_escape_string($_POST['author']);
        $author_title = $conn->real_escape_string($_POST['author_title']);
        $accession_no = $conn->real_escape_string($_POST['accession_no']);
        $call_no = $conn->real_escape_string($_POST['call_no']);
        $quantity = $conn->real_escape_string($_POST['quantity']);
        $unit = $conn->real_escape_string($_POST['unit']);
        $date_acquired = $conn->real_escape_string($_POST['date_acquired']);

        // Update query
        $sql = "UPDATE book SET 
            Author_Title='$author', 
            Title='$author_title', 
            `Accession No`='$accession_no', 
            `Call No`='$call_no', 
            Quantity='$quantity', 
            Unit='$unit', 
            `Date Acquired`='$date_acquired' 
            WHERE `No of Books`='$no_of_books'";

        if ($conn->query($sql) === TRUE) {
            $response['success'] = true;
            $response['message'] = 'Book updated successfully';
        } else {
            $response['success'] = false;
            $response['error'] = $conn->error;
        }
    } else {
        $response['success'] = false;
        $response['error'] = 'Book ID is missing.';
    }
} else {
    $response['success'] = false;
    $response['error'] = 'Invalid request method.';
}

// Send the JSON response back
echo json_encode($response);

$conn->close();
?>
