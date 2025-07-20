<?php
include 'db.php'; // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $bookName = $_POST['book_name'];
    $borrowerName = $_POST['borrower_name'];
    $address = $_POST['address'];
    $contactNumber = $_POST['contact_number'];
    $idType = $_POST['id_type'];
    $idNumber = $_POST['id_number'];
    $borrowDate = $_POST['borrow_date'];
    $returnDate = $_POST['return_date'];
    $idPicture = $_POST['id_picture']; // Captured image (if applicable)

    // Set the default return status to "Pending" when a book is borrowed
    $returnStatus = 'Pending';

    // Validate inputs
    if (
        empty($bookName) || empty($borrowerName) || empty($address) || 
        empty($contactNumber) || empty($idType) || empty($idNumber) || 
        empty($borrowDate)
    ) {
        // Redirect back with an error message (optional)
        header("Location: Staff-borrow.php?error=All fields are required.");
        exit;
    }

    // Fetch the book_id from the book table based on the book_name
    $sql = "SELECT `Book Id` FROM book WHERE Title = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $bookName);
    $stmt->execute();
    $stmt->bind_result($bookId);
    $stmt->fetch();
    $stmt->close();

    // If the book doesn't exist, show an error
    if (!$bookId) {
        header("Location: Staff-borrow.php?error=Book not found.");
        exit;
    }

    // Save ID picture if provided
    $imagePath = null;
    if (!empty($idPicture)) {
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/LCPL/id_pictures/';

        // Create the directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Generate unique filename
        $imagePath = uniqid() . '.png';
        $filePath = $uploadDir . $imagePath;

        // Clean and decode the base64 data
        $idPicture = str_replace('data:image/png;base64,', '', $idPicture);
        $idPicture = str_replace(' ', '+', $idPicture);
        $decodedData = base64_decode($idPicture);

        if ($decodedData === false || !file_put_contents($filePath, $decodedData)) {
            // Redirect back with an error message (optional)
            header("Location: Staff-borrow.php?error=Failed to upload image.");
            exit;
        }
    }

    // Insert data into the borrowed_books table using book_id
    $stmt = $conn->prepare("INSERT INTO borrowed_books (book_id, borrower_name, address, contact_number, id_type, id_number, borrow_date, return_date, return_status, id_picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssssss", $bookId, $borrowerName, $address, $contactNumber, $idType, $idNumber, $borrowDate, $returnDate, $returnStatus, $imagePath);

    if ($stmt->execute()) {
        // Redirect back with a success message (optional)
        header("Location: Staff-borrow.php?success=Book borrowed successfully.");
    } else {
        // Redirect back with an error message (optional)
        header("Location: Staff-borrow.php?error=Failed to submit data.");
    }

    $stmt->close();
    $conn->close();
}
?>
