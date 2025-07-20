?php
// Capture incoming JSON data from POST request
$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    // Process data (e.g., store in a database)
    foreach ($data as $prediction) {
        $date = $prediction['ds'];
        $value = $prediction['yhat'];
        // Example: INSERT INTO predictions (date, predicted_value) VALUES ('$date', '$value');
        // If you want to store the data in your database
    }
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid data"]);
}
?>
