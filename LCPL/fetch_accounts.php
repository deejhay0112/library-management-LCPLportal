<?php
include 'db.php'; // Database connection

$sql = "SELECT id, username, email, role FROM users"; // Fetch data from the database including role
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>"; // Auto-incremented ID from the database
        echo "<td>" . htmlspecialchars($row['username']) . "</td>"; // Display username
        echo "<td>" . htmlspecialchars($row['email']) . "</td>"; // Display email
        echo "<td>" . htmlspecialchars($row['role']) . "</td>"; // Display user role
        echo "<td>";
        echo "<a href='#' class='button edit-button' onclick=\"openEditModal('{$row['id']}', '{$row['username']}', '{$row['email']}', '{$row['role']}')\">
        <i class='fas fa-edit'></i>
      </a> ";
        echo "<a href='#' class='button delete-button' onclick=\"openDeleteModal('{$row['id']}')\">
        <i class='fas fa-trash-alt'></i>
      </a>";
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No accounts found</td></tr>";
}

$conn->close();
?>
