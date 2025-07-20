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


<?php
include 'db.php'; // Ensure db.php has the correct database connection setup
$currentPage = 'library_management';

// Initialize a variable to store the number of books
$next_no_of_books = 1;

// Fetch the highest number of books from the database
$sql = "SELECT MAX(`No of Books`) as max_no FROM book"; 
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $next_no_of_books = $row['max_no'] + 1; // Increment the highest number by one
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $no_of_books = $conn->real_escape_string($_POST['no_of_books']);
    $author = $conn->real_escape_string($_POST['author']);
    $author_title = $conn->real_escape_string($_POST['author_title']);
    $accession_no = $conn->real_escape_string($_POST['accession_no']);
    $call_no = $conn->real_escape_string($_POST['call_no']);
    $quantity = $conn->real_escape_string($_POST['quantity']);
    $unit = $conn->real_escape_string($_POST['unit']);
    $date_acquired = $conn->real_escape_string($_POST['date_acquired']);
    $category = $conn->real_escape_string($_POST['category']); // Fetch the category input

    // Insert query
    $sql = "INSERT INTO book (`No of Books`, Author_Title, `Title`, `Accession No`, `Call No`, Quantity, Unit, `Date Acquired`, category)
    VALUES ('$no_of_books', '$author', '$author_title', '$accession_no', '$call_no', '$quantity', '$unit', '$date_acquired', '$category')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('New book added successfully');</script>";
        echo "<script>window.location.href='books.php';</script>"; // Redirect after insert
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="icon" type="image/png" href="logo/vls_logo.jpg">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/books.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined&display=swap" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

   <!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables Core -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>

<!-- JSZip for Excel Export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- PDFMake for PDF Export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<!-- Buttons for HTML5 and Print -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>


<style>

/* Hide the DataTables search box */
div.dataTables_filter {
    display: none;
}

/* Force custom styles for DataTables buttons */
div.dt-buttons .dt-button, 
div.dt-buttons .buttons-html5, 
div.dt-buttons .buttons-print {
    background-color: #2563eb !important; /* Blue background */
    color: #ffffff !important; /* White text */
    border: none !important;
    padding: 6px 12px !important;
    margin: 5px 2px !important;
    font-size: 0.9rem !important;
    font-weight: 600 !important;
    border-radius: 6px !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    box-shadow: none !important;
}

/* Hover effect */
div.dt-buttons .dt-button:hover,
div.dt-buttons .buttons-html5:hover,
div.dt-buttons .buttons-print:hover {
    background-color: #1e40af !important; /* Darker blue */
    transform: translateY(-2px) !important; /* Lift effect */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2) !important;
}

/* Active/click effect */
div.dt-buttons .dt-button:active,
div.dt-buttons .buttons-html5:active,
div.dt-buttons .buttons-print:active {
    background-color: #1e3a8a !important; /* Even darker blue */
    transform: translateY(0) !important; /* Reset lift */
    box-shadow: none !important;
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
</head>
<body>
    <div class="container">
        <div class="box-container">
            <!-- Filter input and buttons -->
            <div class="filter-container">
            <input type="text" id="searchInput" class="searchInput" placeholder="Search...">
    <button type="button" class="search-icon-button" onclick="searchBooks()">
        <i class="fas fa-search"></i> <!-- Search Icon -->
    </button>
    </button>
    <button class="button Add-Book-Button" id="openModalBtn">
        <i class="fas fa-plus"></i> <!-- Add Book Icon -->
    </button>
    <button class="button Add-Book-Button" onclick="refreshPage()">
        <i class="fas fa-sync-alt"></i> <!-- Refresh Icon -->
    </button>
</div>
    <div id="buttons-container" style="margin-bottom: 10px;"></div>
            <!-- Scroll buttons aligned to the right -->
            <div class="scroll-container">
                <div class="scroll-up-container">
                    <button class="scroll-button" onclick="scrollToTop()">
                        <i class="fas fa-chevron-up"></i>
                    </button>
                </div>
                <div class="scroll-down-container">
                    <button class="scroll-button" onclick="scrollToBottom()">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>



    </div>
</div>
        </div>
    <div class="table-wrapper">
        
        <table id="booksTable">
            <thead>
                <tr>
    <th>No of Books</th>
    <th>Author Title</th>
    <th>Title</th>
    <th>Accession No</th>
    <th>Call No</th>
    <th>Quantity</th>
    <th>Unit</th>
    <th>Date Acquired</th>
    <th>Category</th> <!-- Added this -->
    <th>Actions</th>
</tr>

            </thead>
            <tbody id='booksTablebody'>
            <?php include 'fetch_books.php'; ?>
            
                                </tbody>
                            </table>
                        </div>
                    </div>
</div>

    <!-- Modal -->
    <div id="bookModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2>Add New Book</h2>
            </div>
            <div class="modal-body">
            <form method="post" action="add-book.php">
    <div class="form-group">
        <label for="no_of_books">No. of Books</label>
        <input type="number" id="no_of_books" name="no_of_books" value="<?php echo htmlspecialchars($next_no_of_books); ?>" readonly>
    </div>
    <div class="form-group">
        <label for="author">Author</label>
        <input type="text" id="author" name="author" required>
    </div>
    <div class="form-group">
        <label for="author_title">Book Title</label>
        <input type="text" id="author_title" name="author_title" required>
    </div>
    <div class="form-group">
        <label for="accession_no">Accession No</label>
        <input type="text" id="accession_no" name="accession_no" required>
    </div>
    <div class="form-group">
        <label for="call_no">Call No</label>
        <input type="text" id="call_no" name="call_no" required>
    </div>
    <div class="form-group">
        <label for="quantity">Quantity</label>
        <input type="number" id="quantity" name="quantity" required>
    </div>
    <div class="form-group">
        <label for="unit">Unit</label>
        <input type="text" id="unit" name="unit" required>
    </div>
    <div class="form-group">
        <label for="date_acquired">Date Acquired</label>
        <input type="date" id="date_acquired" name="date_acquired" required>
    </div>
    <div class="form-group">
        <label for="category">Category</label>
        <input type="text" id="category" name="category" required>
    </div>

    <div class="form-group">
        <input type="submit"class="button Add-Book-Button" value="Add Book">
        <input type="button"class="button Add-Book-Button" value="Cancel" id="closeModalBtn">
    </div>
</form>

            </div>
        </div>
    </div>

    <!-- Edit Modal -->
<!-- Edit Modal -->
<div id="editBookModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close" id="closeEditModal">&times;</span>
            <h2>Edit Book</h2>
        </div>
        <div class="modal-body">
            <form id="editBookForm" method="post" action="edit_book.php">
            <input type="hidden" name="book_id" id="edit_book_id" value="">

                <div class="form-group">
                    <label for="edit_no_of_books">No. of Books</label>
                    <input type="number" id="edit_no_of_books" name="no_of_books" readonly>
                </div>
                <div class="form-group">
                    <label for="edit_author">Author</label>
                    <input type="text" id="edit_author" name="author" required>
                </div>
                <div class="form-group">
                    <label for="edit_author_title">Book Title</label>
                    <input type="text" id="edit_author_title" name="author_title" required>
                </div>
                <div class="form-group">
                    <label for="edit_accession_no">Accession No</label>
                    <input type="text" id="edit_accession_no" name="accession_no" required>
                </div>
                <div class="form-group">
                    <label for="edit_call_no">Call No</label>
                    <input type="text" id="edit_call_no" name="call_no" required>
                </div>
                <div class="form-group">
                    <label for="edit_quantity">Quantity</label>
                    <input type="number" id="edit_quantity" name="quantity" required>
                </div>
                <div class="form-group">
                    <label for="edit_unit">Unit</label>
                    <input type="text" id="edit_unit" name="unit" required>
                </div>
                <div class="form-group">
                    <label for="edit_date_acquired">Date Acquired</label>
                    <input type="date" id="edit_date_acquired" name="date_acquired" required>
                </div>
                <div class="form-group">
                    <input type="submit"class="button Add-Book-Button" value="Update Book">
                    <input type="button"class="button Add-Book-Button" value="Cancel" id="closeEditModalBtn">
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function scrollToTop() {
    const tableWrapper = document.querySelector('.table-wrapper');
    tableWrapper.scrollTo({ top: 0, behavior: 'instant' });
}

function scrollToBottom() {
    const tableWrapper = document.querySelector('.table-wrapper');
    tableWrapper.scrollTo({ top: tableWrapper.scrollHeight, behavior: 'instant' });
}


</script>


<script src="assets/js/book.js"></script>
<script src="assets/js/sweet_alert.js"></script>
<script src="assets/js/main.js"></script>
<!-- PDFMake for DataTables -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
 $(document).ready(function () {
    // Base64 encoded logo image (replace this with your actual base64 string)
    var logoBase64 = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAgAAAQABAAD/4gHYSUNDX1BST0ZJTEUAAQEAAAHIbGNtcwIQAABtbnRyUkdCIFhZWiAH4gADABQACQAOAB1hY3NwTVNGVAAAAABzYXdzY3RybAAAAAAAAAAAAAAAAAAA9tYAAQAAAADTLWhhbmSdkQA9QICwPUB0LIGepSKOAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAlkZXNjAAAA8AAAAF9jcHJ0AAABDAAAAAx3dHB0AAABGAAAABRyWFlaAAABLAAAABRnWFlaAAABQAAAABRiWFlaAAABVAAAABRyVFJDAAABaAAAAGBnVFJDAAABaAAAAGBiVFJDAAABaAAAAGBkZXNjAAAAAAAAAAV1UkdCAAAAAAAAAAAAAAAAdGV4dAAAAABDQzAAWFlaIAAAAAAAAPNUAAEAAAABFslYWVogAAAAAAAAb6AAADjyAAADj1hZWiAAAAAAAABilgAAt4kAABjaWFlaIAAAAAAAACSgAAAPhQAAtsRjdXJ2AAAAAAAAACoAAAB8APgBnAJ1A4MEyQZOCBIKGAxiDvQRzxT2GGocLiBDJKwpai5+M+s5sz/WRldNNlR2XBdkHWyGdVZ+jYgskjacq6eMstu+mcrH12Xkd/H5////2wBDAAkGBwgHBgkICAgKCgkLDhcPDg0NDhwUFREXIh4jIyEeICAlKjUtJScyKCAgLj8vMjc5PDw8JC1CRkE6RjU7PDn/2wBDAQoKCg4MDhsPDxs5JiAmOTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTk5OTn/wAARCAIdAh0DACIAAREBAhEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMAAAERAhEAPwD3GiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACimswUEsQAO5NVJNV06P/WX9qh/2plH9aALtFZL+I9DTGdYsTn0nU/yNRP4q0BAM6taH6Pn+VAG3RWInivw++catacer4/nUqeJNDfONYsBj1nUfzNAGtRVGLVdOm/1WoWkn+7Mp/rVtWV1DKwYHuDQA+iiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKoajqlhpcXmXt5FACON7cn6DqfwrjtV+JtlDuTTraS5Yf8tJDsX8up/SgD0Cqt5f2dim+7uobdexkcLn868obxF4x8RsUsRMsZOCLWPao/4H1H51Ys/hvrN6/najdxW5blssZX/w/WgDrL/4g+H7UERzS3TekMfH5tgVz158UpORZ6ai+jTSZz+A/wAa2LD4baNbgG5e4u27hm2KfwXn9a6Ky0DR7HBttNtY2HRvLBb8zzQB5p/wmPjDVD/oMTgE4/0a13D8yDR/Znj7UuZGv1U9d9wIx/3zuH8q9fqteXUFlay3NxIscMSlmY9AKAPKh8OvEN0Q1xc2qn/ppKzH9AauRfCy7I/e6nAv+7ET/UV1CeJ9RuYhd2fhy8msSMiVpVR2X1VOprZ0XVrXWbFLy0ZjGxIKsMMjDqCPWgDh0+FSD7+ssfpbY/8AZqlT4V2o+/qsxHtEB/WvRKyPFGoTaVoN5fQBDLCoKhhxyQP60XA5J/hXan7mqzAe8QP9aif4VIfuayw+ttn/ANmrvtOleewtZ5ABJJErsAO5AJrN0nVri913WrCR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';

    // Initialize DataTable
    var table = $('#booksTable').DataTable({
        "order": [[0, "asc"]],
        "pageLength": 10,
        "lengthMenu": [10, 25, 50, 100],
        "pagingType": "full_numbers",
        "scrollX": true,
        "dom": 'Bfrtip',
        "buttons": [
            // CSV Export
{
    extend: 'csv',
    text: '<i class="fas fa-file-csv"></i> CSV',
    className: 'btn btn-success',
    filename: function () {
        return `library_books_${new Date().toISOString().split('T')[0]}`;
    },
    exportOptions: {
        columns: ':not(:last-child)' // Exclude Actions column (last column)
    }
},
// Excel Export
{
    extend: 'excel',
    text: '<i class="fas fa-file-excel"></i> Excel',
    className: 'btn btn-success',
    filename: function () {
        return `library_books_${new Date().toISOString().split('T')[0]}`;
    },
    exportOptions: {
        columns: ':not(:last-child)' // Exclude Actions column (last column)
    }
},

            // PDF Export with Logo
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger',
                filename: function () {
                    return `library_books_${new Date().toISOString().split('T')[0]}`;
                },
                orientation: 'portrait',
                pageSize: 'A4',
                exportOptions: {
                    columns: [1, 2, 4, 7, 8] // Export specific columns
                },
                customize: function (doc) {
                    console.log("Customizing PDF Export..."); // Debug message

                    // Add the logo to the PDF header
                    doc.content.splice(0, 0, {
                        alignment: 'center',
                        image: logoBase64, // Logo base64 string
                        width: 70,
                        margin: [0, 0, 0, 10] // Spacing below the logo
                    });

                    // Add a custom title
                    doc.content.splice(1, 0, {
                        text: 'Lipa City Public Library\nLibrary Books Report',
                        alignment: 'center',
                        fontSize: 14,
                        bold: true,
                        margin: [0, 0, 0, 20]
                    });

                    // Footer with page numbers
                    doc['footer'] = function (currentPage, pageCount) {
                        return {
                            text: 'Page ' + currentPage.toString() + ' of ' + pageCount,
                            alignment: 'center',
                            margin: [0, 10, 0, 0]
                        };
                    };

                    // Table Header Style
                    doc.styles.tableHeader = {
                        bold: true,
                        fontSize: 11,
                        color: 'white',
                        fillColor: '#007bff',
                        alignment: 'center'
                    };

                    // Table Content Alignment
                    doc.styles.tableBodyEven = { alignment: 'center' };
                    doc.styles.tableBodyOdd = { alignment: 'center' };

                    console.log("PDF Export Customization Complete."); // Debug message
                }
            },
            // Print Export with Logo
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-info',
                title: '',
                exportOptions: {
                    columns: [1, 2, 4, 7, 8] // Export specific columns
                },
                customize: function (win) {
                    console.log("Customizing Print Export..."); // Debug message

                    $(win.document.body)
                        .css('font-size', '14px')
                        .prepend(
                            '<div style="text-align: center; margin-bottom: 20px;">' +
                                '<img src="' + logoBase64 + '" style="width:70px; display: block; margin: 0 auto;">' +
                                '<h3 style="margin: 10px 0;">Lipa City Public Library</h3>' +
                                '<h4>Library Books Report</h4>' +
                            '</div>'
                        );

                    // Style the table
                    $(win.document.body).find('table')
                        .addClass('display')
                        .css('margin', '20px auto')
                        .css('border-collapse', 'collapse')
                        .css('width', '100%')
                        .css('font-size', '14px');

                    $(win.document.body).find('table th, table td')
                        .css('padding', '8px')
                        .css('text-align', 'center')
                        .css('border', '1px solid #ddd');

                    console.log("Print Export Customization Complete."); // Debug message
                }
            }
        ]
    });

    // Move the buttons to the separate container
    table.buttons().container()
        .appendTo($('#buttons-container'));

    // Debug message when DataTable is initialized
    console.log("DataTable Initialized Successfully.");
});
     

</script>
</body>

</html>
