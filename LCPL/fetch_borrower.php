<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json");

// Database credentials
$host = "localhost";
$user = "u708474031_lms";
$password = "Jedjelodex69";
$database = "u708474031_lms";

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Validate the input
if (isset($_GET['id_number']) && !empty(trim($_GET['id_number']))) {
    $id_number = trim($_GET['id_number']);

    // Prepare the SQL query to fetch borrower details
    $query = "SELECT borrower_name, address, contact_number, return_date 
              FROM borrowed_books WHERE id_number = ? LIMIT 1";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        echo json_encode(["error" => "Error preparing statement: " . $conn->error]);
        exit;
    }

    // Bind and execute the query
    $stmt->bind_param("s", $id_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $borrower = $result->fetch_assoc();
        echo json_encode($borrower);
    } else {
        echo json_encode(["error" => "No borrower found with this ID number"]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "No ID number provided"]);
}

$conn->close();
?>
