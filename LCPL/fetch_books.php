<?php
include 'db.php'; // Database connection file

$query = "SELECT `Book Id` AS BookId, `No of Books`, Author_Title, Title, `Accession No`, `Call No`, Quantity, Unit, `Date Acquired`, category 
          FROM book"; // Fetch all books with the category
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr id='row-{$row['BookId']}'>"; // Use BookId alias
        echo "<td>" . htmlspecialchars($row['No of Books']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Author_Title']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Title']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Accession No']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Call No']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Quantity']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Unit']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Date Acquired']) . "</td>";
        echo "<td>" . htmlspecialchars($row['category']) . "</td>"; // Add category column
        echo "<td>
    <button class='button edit-button' data-id='" . $row['BookId'] . "' onclick='openEditModal(" . $row['BookId'] . ")'>
        <span class='material-symbols-outlined'>edit</span> 
    </button>
    <button class='button delete-button' data-id='" . $row['BookId'] . "' onclick='deleteRow(" . $row['BookId'] . ")'>
        <span class='material-symbols-outlined'>delete</span> 
    </button>
</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='10'>No books found</td></tr>"; // Updated colspan to 10
}

$conn->close();
?>
