<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $idNumber = $_POST['id_number'] ?? '';
    $borrowerName = $_POST['borrower_name'] ?? '';
    $address = $_POST['address'] ?? '';
    $contactNumber = $_POST['contact_number'] ?? '';
    $returnDate = $_POST['return_date'] ?? NULL;
    $returnStatus = $_POST['return_status'] ?? '';

    // Check if required fields are filled
    if (empty($idNumber) || empty($borrowerName) || empty($address) || empty($contactNumber) || empty($returnStatus)) {
        header("Location: staff-return.php?error=All fields are required!");
        exit();
    }

    try {
        // Verify if record exists
        $sqlCheck = "SELECT * FROM borrowed_books WHERE id_number = ?";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bind_param("s", $idNumber);
        $stmtCheck->execute();
        $result = $stmtCheck->get_result();

        if ($result->num_rows === 0) {
            header("Location: staff-return.php?error=Record not found for ID: $idNumber");
            exit();
        }
        $stmtCheck->close();

        // Automatically set return_date to today's date if return_status is 'Not Returned'
        if ($returnStatus === 'Not Returned') {
            $returnDate = date('Y-m-d'); // Set today's date in 'YYYY-MM-DD' format
        }

        // Update record in the database
        $sql = "UPDATE borrowed_books SET 
                    borrower_name = ?, 
                    address = ?, 
                    contact_number = ?, 
                    return_date = ?, 
                    return_status = ? 
                WHERE id_number = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("ssssss", $borrowerName, $address, $contactNumber, $returnDate, $returnStatus, $idNumber);

        if ($stmt->execute()) {
            header("Location: staff-return.php?success=Return status updated successfully!");
        } else {
            header("Location: staff-return.php?error=Failed to update record.");
        }

        $stmt->close();
    } catch (Exception $e) {
        header("Location: staff-return.php?error=Error: " . $e->getMessage());
    }

    $conn->close();
}
?>
