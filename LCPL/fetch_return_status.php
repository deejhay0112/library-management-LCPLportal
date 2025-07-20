<?php
require_once 'db.php';

$response = ["pending" => 0, "returned" => 0, "late_return" => 0, "not_returned" => 0];

try {
    $sql = "SELECT return_status, COUNT(*) AS count FROM borrowed_books GROUP BY return_status";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        switch ($row['return_status']) {
            case 'Pending':
                $response['pending'] = $row['count'];
                break;
            case 'Returned':
                $response['returned'] = $row['count'];
                break;
            case 'Late Return':
                $response['late_return'] = $row['count'];
                break;
            case 'Not Returned':
                $response['not_returned'] = $row['count'];
                break;
        }
    }

    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}

$conn->close();
?>