
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: admin-login.php");
    exit();
}

// Prevent caching for this page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: 0");
header("Pragma: no-cache");
?>
<?php
// Include your database connection file
require 'db.php'; // Adjust this path to your actual db.php file

// Define current page for active class logic
$currentPage = 'logbook'; // Set the current page name as needed

// Fetch borrowed books from the database, including new fields
$sql = "SELECT id, book_name, borrower_name, address, contact_number, id_type, id_number, id_picture, borrow_date, return_date, return_status FROM borrowed_books";
$result = $conn->query($sql);

// Ensure the correct Content-Type for HTML output
header("Content-Type: text/html; charset=UTF-8");

// Start HTML output
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="logo/vls_logo.jpg">
    <title></title>
    <link rel="stylesheet" href="style.css"> <!-- Optional CSS -->
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/borrowbook.css">

</head>
        <style>
        .table-wrapper {
            margin: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow-x: auto; /* Enables horizontal scrolling */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px; /* Ensures table columns are fully visible */
        }

        table thead {
            background-color: #007bff;
            text-align: left;
            font-weight: bold;
        }

        table th, table td {
            padding: 12px 15px;
            text-align: left;
            white-space: nowrap; /* Prevents text from wrapping */
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tbody tr:hover {
            background-color: #eaeaea;
            cursor: pointer;
        }

        th {
            border-bottom: 2px solid #ddd;
            font-size: 16px;
        }

        td {
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        .status {
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            color: #fff;
            text-transform: capitalize;
        }

        .status.returned {
            background-color: #4caf50;
        }

        .status.pending {
            background-color: #ff9800;
        }

        .status.overdue {
            background-color: #f44336;
        }

        .modal-img {
            max-width: 80%;
            max-height: 80%;
            border-radius: 5px;
        }
        /* Late Return Status */
.status.late-return {
    background-color: #ff4d4d; /* Light red for Late Return */
    color: white;
}

/* Optional: Pending Status */
.status.pending {
    background-color: #ffcc00; /* Yellow for Pending */
    color: #333;
}

/* Returned Status */
.status.returned {
    background-color: #4caf50; /* Green for Returned */
    color: white;
}

/* Not Returned Status */
.status.not-returned {
    background-color: #f44336; /* Dark Red for Not Returned */
    color: white;
}

    </style>
<body>

<title>Sidebar menu responsive</title>
</head>
<body id="body-pd">
    <header class="header" id="header">
        <div class="header__toggle">
            <i class='bx bx-menu' id="header-toggle"></i>
        </div>
         <div class="header__refresh" style="margin-left: auto; cursor: pointer;">
        <i class="bx bx-refresh" onclick="refreshPage()" title="Refresh Page" style="font-size: 20px;"></i>
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
                    <a href="books.php" class="nav__link <?php echo ($currentPage == 'library_management') ? 'active' : ''; ?>">
                        <i class='bx bx-grid-alt nav__icon'></i>
                        <span class="nav__name">Library Management</span>
                    </a>

                    <a href="logbookAdmin.php" class="nav__link <?php echo ($currentPage == 'logbook') ? 'active' : ''; ?>">
                        <i class='bx bx-message-square-detail nav__icon'></i>
                        <span class="nav__name">Logbook</span>
                    </a>

                    <a href="dashboard.php" class="nav__link <?php echo ($currentPage == 'dashboard') ? 'active' : ''; ?>">
                        <i class='bx bx-bar-chart-alt-2 nav__icon'></i>
                        <span class="nav__name">Analytics</span>
                    </a>

                    <a href="transaction_book.php" class="nav__link <?php echo ($currentPage == 'transaction_books') ? 'active' : ''; ?>">
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

<!-- Main Content -->
<div class="main-content">
    <div class="filter-section">
    <label for="dateFilter">Filter by:</label>
    <select id="dateFilter">
        <option value="borrow_date">Borrow Date</option>
        <option value="return_date">Return Date</option>
    </select>

    <label for="startDate">Start Date:</label>
    <input type="date" id="startDate">

    <label for="endDate">End Date:</label>
    <input type="date" id="endDate">

    <button id="filterBtn">Apply Filter</button>
</div>


</div>

<div class="table-wrapper"> <!-- Added class for the table wrapper to control scroll -->
   <?php
   // SQL query to fetch borrowed books along with the book name (Title)
$query = "SELECT bb.*, b.Title AS book_name
          FROM borrowed_books bb
          JOIN book b ON bb.book_id = b.`Book Id`";  // Joining borrowed_books with book table

$result = $conn->query($query);

            if ($result->num_rows > 0) {
                echo "<table id='booksTable'>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Book Name</th>
                                <th>Borrower Name</th>
                                <th>Address</th>
                                <th>Contact Number</th>
                                <th>ID Type</th>
                                <th>ID Number</th>
                                <th>ID Picture</th>
                                <th>Borrow Date</th>
                                <th>Return Date</th>
                                <th>Return Status</th>
                            </tr>
                        </thead>
                        <tbody>";
        
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
            <td>" . $row["id"] . "</td>
            <td>" . htmlspecialchars($row["book_name"]) . "</td>
            <td>" . htmlspecialchars($row["borrower_name"]) . "</td>
            <td>" . htmlspecialchars($row["address"]) . "</td>
            <td>" . htmlspecialchars($row["contact_number"]) . "</td>
            <td>" . htmlspecialchars($row["id_type"]) . "</td>
            <td>" . htmlspecialchars($row["id_number"]) . "</td>
            <td>";
                    if (!empty($row["id_picture"])) {
                        echo "<img src='/LCPL/id_pictures/" . $row["id_picture"] . "' alt='ID Picture' width='50' height='50' onclick='openModal(\"/LCPL/id_pictures/" . $row["id_picture"] . "\")'>";
                    } else {
                        echo "N/A";
                    }
                    echo "</td>
                            <td>" . htmlspecialchars($row["borrow_date"]) . "</td>
                            <td>" . htmlspecialchars($row["return_date"]) . "</td>
                            <td><span class='status " . strtolower($row["return_status"]) . "'>" . htmlspecialchars($row["return_status"]) . "</span></td>
                          </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No records found.</p>";
            }
            $conn->close();
            ?>
</div>

<!-- JavaScript for Date Filtering -->
<script>
    document.getElementById('filterBtn').addEventListener('click', () => {
        const table = document.getElementById('booksTable');
        const filterType = document.getElementById('dateFilter').value;
        const startDate = new Date(document.getElementById('startDate').value);
        const endDate = new Date(document.getElementById('endDate').value);
        const rows = Array.from(table.querySelectorAll('tbody tr'));

        rows.forEach(row => {
            const dateCell = filterType === 'borrow_date' ? row.children[3].textContent : row.children[4].textContent;
            const rowDate = new Date(dateCell.trim());

            // Check if the row date falls within the selected range
            if (rowDate >= startDate && rowDate <= endDate) {
                row.style.display = ''; // Show the row
            } else {
                row.style.display = 'none'; // Hide the row
            }
        });
    });
    

    function refreshPage() {
        location.reload();
    }


</script>
<script>
        function openModal(imageUrl) {
            var modal = document.createElement("div");
            modal.style.position = "fixed";
            modal.style.top = "0";
            modal.style.left = "0";
            modal.style.width = "100%";
            modal.style.height = "100%";
            modal.style.backgroundColor = "rgba(0, 0, 0, 0.7)";
            modal.style.display = "flex";
            modal.style.alignItems = "center";
            modal.style.justifyContent = "center";

            var img = document.createElement("img");
            img.src = imageUrl;
            img.classList.add("modal-img");

            modal.appendChild(img);
            modal.onclick = function() { modal.remove(); };
            document.body.appendChild(modal);
        }
    </script>
<script src="assets/js/main.js"></script>
</body>
</html>