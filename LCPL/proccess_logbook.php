<?php
// Include the database connection file
include 'db.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $id_no = $_POST['id_no'];
    $school = $_POST['school'];
    $sex = $_POST['sex'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    
    // Handle checkboxes for education levels
    $elementary = isset($_POST['elementary']) ? 1 : 0; // 1 if checked, 0 otherwise
    $highschool = isset($_POST['highschool']) ? 1 : 0;
    $college = isset($_POST['college']) ? 1 : 0;
    $post_grad = isset($_POST['post_grad']) ? 1 : 0;

    // Validate form data (add more validation if needed)
    if (empty($id_no) || empty($school) || empty($sex) || empty($date) || empty($time)) {
        die("Please fill in all required fields.");
    }

    // Prepare the SQL query to insert data into the database
    $sql = "INSERT INTO male_1 (id_no, school, sex, date, time, elementary, highschool, college, post_grad)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare and bind the statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssssssss", $id_no, $school, $sex, $date, $time, $elementary, $highschool, $college, $post_grad);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Log entry added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing the SQL statement: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    echo "Invalid request method.";
}