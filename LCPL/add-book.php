<?php
include 'db.php'; // Ensure db.php has the correct database connection setup

// Initialize a variable to store the number of books
$next_no_of_books = 1;

// Fetch the highest number of books from the database
$sql = "SELECT MAX(`No of Books`) as max_no FROM book"; 
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $next_no_of_books = $row['max_no'] + 1; // Increment the highest number by one
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $no_of_books = $conn->real_escape_string($_POST['no_of_books']);
    $author = $conn->real_escape_string($_POST['author']);
    $author_title = $conn->real_escape_string($_POST['author_title']);
    $accession_no = $conn->real_escape_string($_POST['accession_no']);
    $call_no = $conn->real_escape_string($_POST['call_no']);
    $quantity = $conn->real_escape_string($_POST['quantity']);
    $unit = $conn->real_escape_string($_POST['unit']);
    $date_acquired = $conn->real_escape_string($_POST['date_acquired']);
    
    // Insert query
    $sql = "INSERT INTO book (`No of Books`, Author_Title, `Title`, `Accession No`, `Call No`, Quantity, Unit, `Date Acquired`)
    VALUES ('$no_of_books', '$author', '$author_title', '$accession_no', '$call_no', '$quantity', '$unit', '$date_acquired')";

if ($conn->query($sql) === TRUE) {
    echo "<script>
        Swal.fire({
            title: 'Success!',
            text: 'New book added successfully.',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then(function() {
            // Refresh the page after success
            window.location.reload();
        });
    </script>";
} else {
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'An error occurred while adding the book: " . $conn->error . "',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    </script>";
        }
        echo "<script>window.location.href='books.php';</script>"; // Redirect after insert
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
?>
