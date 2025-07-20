<?php
include 'db.php'; // Include database connection

header('Content-Type: application/json'); // Set JSON header

// Check which endpoint is requested using a 'type' query parameter
$type = isset($_GET['type']) ? $_GET['type'] : 'metrics';

if ($type === 'metrics') {
    // Fetch data for totalBooks (Quantity), uniqueAuthors, mostRecentDate, and mostCommonAuthor
    $sql = "SELECT `Quantity`, `Author_Title`, `Date Acquired` FROM book";
    $result = $conn->query($sql);

    $totalBooks = 0; // Total Books now counted as Quantity
    $uniqueAuthors = [];
    $acquisitionDates = [];
    $authorFrequency = [];

    while ($row = $result->fetch_assoc()) {
        // Use Quantity for Total Books count
        $quantity = intval($row['Quantity']);
        if ($quantity > 0) { // Ensure positive values only
            $totalBooks += $quantity;
        }

        $uniqueAuthors[] = $row['Author_Title'];
        $acquisitionDates[] = $row['Date Acquired'];

        if (isset($authorFrequency[$row['Author_Title']])) {
            $authorFrequency[$row['Author_Title']]++;
        } else {
            $authorFrequency[$row['Author_Title']] = 1;
        }
    }

    $uniqueAuthorsCount = count(array_unique($uniqueAuthors));
    $mostRecentDate = max($acquisitionDates);
    $mostCommonAuthor = array_search(max($authorFrequency), $authorFrequency);

    // Return the calculated metrics as JSON
    echo json_encode([
        "totalBooks" => $totalBooks,  // Total Books now uses Quantity
        "uniqueAuthors" => $uniqueAuthorsCount,
        "mostRecentDate" => $mostRecentDate,
        "mostCommonAuthor" => $mostCommonAuthor
    ]);
} elseif ($type === 'books') {
    // Fetch Quantity as BookId and Title
    $sql = "SELECT `Quantity`, `Title` FROM book";
    $result = $conn->query($sql);

    $books = [];
    while ($row = $result->fetch_assoc()) {
        $books[] = [
            "BookId" => $row['Quantity'], // Use Quantity as BookId
            "Title" => $row['Title']
        ];
    }

    // Return the books data as JSON
    echo json_encode($books);
} else {
    echo json_encode(["error" => "Invalid request type. Use 'type=metrics' or 'type=books'."]);
}

$conn->close();
?>
