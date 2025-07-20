<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: admin-login.php");
    exit();
}

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: 0");
header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/books.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
    /* Main layout container */
    .admin-accounts-container {
        display: flex;
        gap: 20px;
        padding: 20px;
        justify-content: space-around;
        align-items: flex-start;
        background-color: #1a1a2e;
        min-height: 100vh;
        color: #fff;
    }
    
    /* Form container styling */
    .form-container {
        width: 30%;
        padding: 20px;
        border-radius: 8px;
        background-color: #f9f9f9;
        color: #333;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .form-container h2 {
        font-size: 1.6em;
        color: #333;
    }

    .form-container label {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .form-container input[type="text"],
    .form-container input[type="email"],
    .form-container input[type="password"] {
        width: 100%;
        padding: 12px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1em;
    }

    .form-container button {
        padding: 12px;
        background-color: #007bff;
        border: none;
        border-radius: 5px;
        color: #fff;
        font-size: 1em;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .form-container button:hover {
        background-color: #0056b3;
    }

    /* Table container styling */
    .table-container {
        width: 65%;
        max-height: 600px;
        overflow-y: auto;
        padding: 20px;
        border-radius: 8px;
        background-color: #f9f9f9;
        color: #333;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    .table-container table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.95em;
        table-layout: auto;
    }

    .table-container th, .table-container td {
        padding: 8px 10px;
        border-bottom: 1px solid #ddd;
        text-align: center;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .table-container th {
        background-color: #007bff;
        color: #fff;
        font-weight: bold;
    }

    .table-container td {
        max-width: 180px; /* Allow email to take more space */
    }

    .table-container .action-buttons {
        display: flex;
        justify-content: center;
        gap: 8px;
    }

    /* Action buttons as links */
    .table-container .action-buttons a {
        color: #fff;
        text-decoration: none;
        padding: 6px 10px;
        border-radius: 5px;
        font-size: 0.85em;
        font-weight: bold;
    }

    .table-container .edit-button {
        background-color: #28a745;
    }

    .table-container .delete-button {
        background-color: #dc3545;
    }

    .table-container .action-buttons a:hover {
        opacity: 0.8;
    }

    /* Modal styling */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    /* Enhanced styling for modal content */
.modal-content {
    background-color: #fff;
    padding: 30px 40px;
    border-radius: 12px;
    width: 400px;
    max-width: 90%;
    color: #333;
    box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.2);
    font-family: Arial, sans-serif;
}

.modal-content h2 {
    margin-bottom: 20px;
    font-size: 1.8em;
    color: #333;
    text-align: center;
    font-weight: bold;
}

.modal-content label {
    font-weight: bold;
    margin-bottom: 5px;
    display: block;
    color: #555;
}

.modal-content input[type="text"],
.modal-content input[type="email"],
.modal-content input[type="password"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 1em;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: border-color 0.3s;
}

.modal-content input[type="text"]:focus,
.modal-content input[type="email"]:focus,
.modal-content input[type="password"]:focus {
    border-color: #007bff;
    outline: none;
}

.button-group {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin-top: 20px;
}

.update-button,
.cancel-button {
    padding: 12px 20px;
    font-size: 1em;
    cursor: pointer;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    transition: background-color 0.3s, transform 0.2s;
}

.update-button {
    background-color: #007bff;
    color: #fff;
}

.update-button:hover {
    background-color: #0056b3;
    transform: translateY(-2px);
}

.cancel-button {
    background-color: #dc3545;
    color: #fff;
}

.cancel-button:hover {
    background-color: #c82333;
    transform: translateY(-2px);
}

</style>
</head>
<body id="body-pd">
    <header class="header" id="header">
        <div class="header__toggle">
            <i class='bx bx-menu' id="header-toggle"></i>
        </div>
    </header>

    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div>
                <a href="#" class="nav__logo">
                    <img src="logo/vls_logo.jpg" alt="Library Logo" class="nav__logo-img">
                    <span class="nav__logo-name">LIPA CITY PUBLIC LIBRARY</span>
                </a>
                <div class="nav__list">
                    <a href="books.php" class="nav__link">
                        <i class='bx bx-grid-alt nav__icon'></i>
                        <span class="nav__name">Library Management</span>
                    </a>
                    <a href="logbookAdmin.php" class="nav__link">
                        <i class='bx bx-message-square-detail nav__icon'></i>
                        <span class="nav__name">Logbook</span>
                    </a>
                    <a href="dashboard.html" class="nav__link">
                        <i class='bx bx-bar-chart-alt-2 nav__icon'></i>
                        <span class="nav__name">Analytics</span>
                    </a>
                    <a href="transaction_book.php" class="nav__link">
                        <i class='bx bx-book nav__icon'></i>
                        <span class="nav__name">Transaction Books</span>
                    </a>
                    <a href="admin_account.php" class="nav__link <?php echo ($currentPage == 'admin_account') ? 'active' : ''; ?>">
                    <i class='bx bx-user nav__icon'></i>
                        <span class="nav__name">Admin</span>
                    </a>
                </div>
            </div>
            <a href="logout.php" class="nav__link">
                <i class='bx bx-log-out nav__icon'></i>
                <span class="nav__name">Log Out</span>
            </a>
        </nav>
    </div>

    <div class="admin-accounts-container">
        <!-- Left Form Section -->
        <div class="admin-accounts-container">
    <!-- Form Container: Add New User Form -->
    <div class="form-container">
        <h2>Add New User</h2>
        <form action="add_account.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>

    <label for="role">Role:</label>
    <select name="role" id="role" required>
    <option value="admin">Admin</option>
    <option value="staff">Staff</option>
    </select>

    <button type="submit">Add Account</button>
</form>

    </div>

    <!-- Table Container: Display Existing Users -->
    <div class="table-container">
        <h2>Manage Accounts</h2>
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php include 'fetch_accounts.php'; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editAccountModal" class="modal">
    <div class="modal-content">
        <h2>Edit Account</h2>
        <form action="edit_account.php" method="POST">
            <input type="hidden" id="edit_id" name="id"> <!-- Hidden ID to update the correct user -->
            
            <!-- Username field -->
            <label for="edit_username">Username:</label>
            <input type="text" id="edit_username" name="username" required>

            <!-- Email field -->
            <label for="edit_email">Email:</label>
            <input type="email" id="edit_email" name="email" required>

            <!-- Role field -->
            <label for="edit_role">Role:</label>
            <select name="role" id="edit_role" required>
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
                <!-- Add other roles as needed -->
            </select>

            <!-- Submit button -->
            <button type="submit">Update Account</button>
            <button type="button" onclick="closeModal('editAccountModal')">Close</button>
        </form>
    </div>
</div>

<!-- Delete Account Confirmation Modal -->
<div id="deleteAccountModal" class="modal">
    <div class="modal-content">
        <h2>Confirm Deletion</h2>
        <p>Are you sure you want to delete this account?</p>
        <form action="delete_account.php" method="POST">
            <input type="hidden" id="delete_id" name="id"> <!-- Hidden ID for deletion -->
            <button type="submit">Yes, Delete</button>
            <button type="button" onclick="closeModal('deleteAccountModal')">Cancel</button>
        </form>
    </div>
</div>

<script>
    // Open the Edit Account Modal
    function openEditModal(id, username, email, role) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_username').value = username;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_role').value = role; // Set the current role in the modal dropdown
        document.getElementById('editAccountModal').style.display = 'flex';
    }

    // Open the Delete Account Confirmation Modal
    function openDeleteModal(id) {
        document.getElementById('delete_id').value = id;
        document.getElementById('deleteAccountModal').style.display = 'flex';
    }

    // Close any modal
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // Close modal if clicked outside
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    };

    // SweetAlert for success notifications on account creation and update
    document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get("status");

    if (status === "added") {
        Swal.fire({
            icon: 'success',
            title: 'Account Created',
            text: 'The account has been created successfully!',
            confirmButtonColor: '#007bff'
        });
    } else if (status === "updated") {
        Swal.fire({
            icon: 'success',
            title: 'Account Updated',
            text: 'The account has been updated successfully!',
            confirmButtonColor: '#007bff'
        });
    } else if (status === "deleted") {
        Swal.fire({
            icon: 'success',
            title: 'Account Deleted',
            text: 'The account has been deleted successfully!',
            confirmButtonColor: '#007bff'
        });
    } else if (status === "error") {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'There was a problem deleting the account.',
            confirmButtonColor: '#d33'
        });
    } else if (status === "short_password") { // Check for the short_password status
        Swal.fire({
            icon: 'error',
            title: 'Password Too Short',
            text: 'Password must be at least 8 characters long.',
            confirmButtonColor: '#d33'
        });
    }
});

</script>

</body>
</html>
