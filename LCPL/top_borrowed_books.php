<?php
include 'db.php'; // Your database connection file

// Get filter and category from query parameters
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'daily';
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : ''; // Get the category filter

// Date filter logic for the different filters (daily, weekly, monthly)
switch ($filter) {
    case 'weekly':
        $dateCondition = "DATE_SUB(CURDATE(), INTERVAL 7 DAY)"; // Last 7 days
        break;
    case 'monthly':
        $dateCondition = "DATE_SUB(CURDATE(), INTERVAL 1 MONTH)"; // Last 1 month
        break;
    default:
        $dateCondition = "CURDATE()"; // Today's date for daily filter
        break;
}

// SQL query to fetch the top borrowed books based on the filter and category
$sql = "SELECT 
            b.Title, 
            b.Category, 
            COUNT(bb.book_id) AS BorrowedCount, 
            DATE(bb.borrow_date) AS BorrowDate
        FROM borrowed_books bb
        JOIN book b ON bb.book_id = b.`Book Id`
        WHERE bb.borrow_date >= $dateCondition"; // Filter by date condition

// If a category filter is provided, add it to the SQL query
if (!empty($categoryFilter)) {
    $sql .= " AND b.Category = '" . $conn->real_escape_string($categoryFilter) . "'";
}

$sql .= " GROUP BY b.Title, b.Category, BorrowDate
          ORDER BY BorrowedCount DESC";

// Execute the query
$result = $conn->query($sql);

// Initialize the data array to store the results
$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Return the data as a JSON response
echo json_encode($data);
?>
