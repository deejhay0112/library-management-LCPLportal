<?php
include 'db.php'; // Database connection

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Prepare and execute delete query
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect to admin_account.php with a 'deleted' status
        header("Location: admin_account.php?status=deleted");
        exit;
    } else {
        // Redirect with an error status if deletion failed
        header("Location: admin_account.php?status=error");
        exit;
    }

    $stmt->close();
}

$conn->close();
?>
