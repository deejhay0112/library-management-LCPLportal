<?php
include 'db.php'; // Your database connection file

// Set the correct timezone
date_default_timezone_set('Asia/Manila'); // Adjust timezone if necessary

header('Content-Type: application/json'); // Set the response to JSON

$response = array(); // Prepare an array for the response

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture POST data
    $school = isset($_POST['school']) ? $_POST['school'] : null;
    $date = isset($_POST['date']) ? $_POST['date'] : null;
    $age = isset($_POST['age']) ? $_POST['age'] : null;
    $sex = isset($_POST['sex']) ? $_POST['sex'] : null;
    $education_level = isset($_POST['education_level']) ? $_POST['education_level'] : null; // Capturing educational level
    $occupation = isset($_POST['occupation']) ? $_POST['occupation'] : null; // Capturing occupation

    // Get the current time
    $time = date('H:i:s'); // Corrected timezone will be applied here

    // Initialize other educational level values based on form data
    $elementary = ($education_level === 'elementary') ? 'Yes' : 'No';
    $highschool = ($education_level === 'junior_high') ? 'Yes' : 'No';
    $seniorhigh = ($education_level === 'senior_high') ? 'Yes' : 'No';
    $college = ($education_level === 'college') ? 'Yes' : 'No';
    $postgrad = ($education_level === 'post_graduate') ? 'Yes' : 'No';
    $osy = ($education_level === 'out_of_school') ? 'Yes' : 'No';

    // Fetch the highest ID from the male_1 table to get the next ID for the new entry
    $highest_id = 1; // Default value if there are no entries yet
    $sql = "SELECT MAX(`ID No.`) AS max_id FROM male_1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $highest_id = $row['max_id'] + 1; // Increment the highest ID for the new entry
    }

    // Insert the data into the database
    $sql = "INSERT INTO male_1 (`ID No.`, `School`, `Date`, `Age`, `Sex`, `time`, `Elementry`, `Highschool`, `Shs`, `College`, `PostGrad`, `Osy`, `Occupation`) 
            VALUES ('$highest_id', '$school', '$date', '$age', '$sex', '$time', '$elementary', '$highschool', '$seniorhigh', '$college', '$postgrad', '$osy', '$occupation')";

    if ($conn->query($sql) === TRUE) {
        $response['success'] = true;
        $response['message'] = 'Logbook entry added successfully!';
    } else {
        $response['success'] = false;
        $response['message'] = 'Error: ' . $conn->error;
    }

    // Return the response as JSON
    echo json_encode($response);

    $conn->close();
}
?>
