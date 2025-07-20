<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location:logbookAdmin.php ");
    exit();
}

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: 0");
header("Pragma: no-cache");
?>
<?php
$currentPage = 'logbook';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="logo/vls_logo.jpg">
    <title>Logbook</title>
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/logbookadmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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

    
</head>
<style>
/* Filter container layout */
.filter-container-wrapper {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
    padding: 10px;
    background-color: #1e293b;
    border-radius: 8px;
    color: #ffffff;
}

.filter-container-wrapper label {
    font-weight: 600;
    margin-right: 8px;
    font-size: 0.9rem;
}

.filter-container-wrapper input,
.filter-container-wrapper select {
    background-color: #334155;
    border: 1px solid #475569;
    color: #ffffff;
    border-radius: 6px;
    padding: 6px 8px;
    font-size: 0.9rem;
}

/* Table layout */
.table-wrapper {
    padding: 10px;
    overflow-x: auto; /* Ensures horizontal scrolling if needed */
}

.table-container {
    margin: 0 auto;
    max-width: 100%;
    padding: 20px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
}

table.dataTable {
    margin-top: 15px !important;
    font-size: 0.9rem;
    border-collapse: collapse;
}

table.dataTable th {
    background-color: #2563eb;
    color: #ffffff;
    text-align: center;
}

table.dataTable td {
    text-align: center;
    color: #e5e7eb;
    background-color: white;
}

.header__refresh {
    cursor: pointer;
    display: flex;
    align-items: center;
    font-size: 1.2rem;
    color: #38bdf8;
    margin-left: 10px;
}
/* General Styling */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #0f172a;
    color: #ffffff;
}

.filter-container-wrapper {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 15px;
    padding: 15px;
    background-color: #1e293b;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
}

.filter-container-wrapper label {
    font-weight: 600;
    font-size: 0.9rem;
    margin-right: 5px;
    color: #cbd5e1;
}

.filter-container-wrapper input,
.filter-container-wrapper select {
    background-color: #334155;
    border: 1px solid #475569;
    color: #f8fafc;
    border-radius: 6px;
    padding: 6px 10px;
    font-size: 0.9rem;
}

.filter-container-wrapper input:focus,
.filter-container-wrapper select:focus {
    outline: none;
    border-color: #38bdf8;
    box-shadow: 0 0 5px rgba(56, 189, 248, 0.4);
}

/* Refresh Icon */
.header__refresh {
    display: flex;
    align-items: center;
    font-size: 1.5rem;
    color: #38bdf8;
    cursor: pointer;
    transition: color 0.3s ease;
}

.header__refresh:hover {
    color: #007bff;
}

/* Table Styling */
table.dataTable {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.95rem;
    margin: 0 auto;
}

table.dataTable thead th {
    background-color: #2563eb; /* Blue header */
    color: #ffffff; /* White text */
    font-weight: bold;
    text-align: center;
    padding: 10px;
    border-bottom: 2px solid #1e40af;
}

table.dataTable tbody tr {
    background-color: #ffffff; /* White background for all rows */
    color: #333333; /* Dark text for readability */
}

table.dataTable tbody tr:nth-child(odd) {
    background-color: #f2f2f2; /* Light gray for odd rows */
}

table.dataTable tbody tr:hover {
    background-color: #e0e0e0; /* Slightly darker gray on hover */
    cursor: pointer;
}

table.dataTable td {
    padding: 10px;
    text-align: center;
    border-bottom: 1px solid #e0e0e0; /* Light gray border */
    color: #333333;
}

/* Pagination Styling */
.dataTables_paginate .paginate_button {
    background-color: #f2f2f2;
    color: #333333 !important;
    border: 1px solid #dcdcdc;
    border-radius: 5px;
    padding: 5px 10px;
    margin: 3px;
    transition: background 0.3s ease;
}

.dataTables_paginate .paginate_button:hover {
    background-color: #2563eb;
    color: #ffffff !important;
}

.dataTables_paginate .paginate_button.current {
    background-color: #2563eb;
    color: #ffffff !important;
}






/* Scrollbar Styling */
.table-wrapper::-webkit-scrollbar {
    height: 8px;
}

.table-wrapper::-webkit-scrollbar-track {
    background: #f2f2f2;
}

.table-wrapper::-webkit-scrollbar-thumb {
    background: #2563eb;
    border-radius: 10px;
}

.table-wrapper::-webkit-scrollbar-thumb:hover {
    background: #1e40af;
}


/* General Buttons Styling */
.dt-buttons .dt-button {
    background-color: #2563eb; /* Default blue background */
    color: #ffffff; /* White text */
    border: none;
    padding: 6px 12px;
    margin: 5px 2px;
    font-size: 0.9rem;
    font-weight: 600;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
}

/* Hover Effect */
.dt-buttons .dt-button:hover {
    background-color: #1e40af !important; /* Force darker blue on hover */
    color: #ffffff !important; /* Force white text */
    transform: translateY(-2px); /* Button lift effect */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); /* Subtle shadow */
}

/* Active State (when clicked) */
.dt-buttons .dt-button:active {
    background-color: #1e3a8a !important; /* Even darker blue */
    transform: translateY(0); /* Reset lift */
    box-shadow: none;
}

/* Disabled Buttons Styling */
.dt-buttons .dt-button.disabled, 
.dt-buttons .dt-button:disabled {
    background-color: #e2e8f0 !important; /* Light gray for disabled */
    color: #a0aec0 !important; /* Light gray text */
    box-shadow: none;
    cursor: not-allowed;
    opacity: 0.7;
    transform: none; /* Prevent lift effect */
}

/* Hover Effect on Disabled Buttons (Remove Interaction) */
.dt-buttons .dt-button.disabled:hover,
.dt-buttons .dt-button:disabled:hover {
    background-color: #e2e8f0 !important; /* Keep disabled color */
    color: #a0aec0 !important; /* Keep disabled text color */
    transform: none; /* No lift */
    box-shadow: none; /* No shadow */
}


.dataTables_info {
    color: #cbd5e1;
    margin-top: 10px;
}

.dataTables_paginate .paginate_button {
    color: #38bdf8 !important;
    padding: 5px 10px;
    margin: 0 3px;
    border: 1px solid #475569;
    border-radius: 6px;
    transition: background 0.3s ease;
}

.dataTables_paginate .paginate_button:hover {
    background-color: #2563eb;
    color: #ffffff !important;
}
/* General Table Styles */
table.dataTable {
    border-collapse: collapse;
    font-size: 0.95rem;
    margin-top: 10px;
}

table.dataTable thead {
    background-color: #2563eb;
    color: #ffffff;
}

table.dataTable thead th {
    padding: 10px;
    font-weight: 700;
    text-align: center;
    border-bottom: 2px solid #1e40af;
}

table.dataTable tbody tr {
    background-color: #1e293b;
    color: #f8fafc;
    transition: background 0.3s ease;
}

table.dataTable tbody tr:nth-child(even) {
    background-color: #334155; /* Slightly lighter background for even rows */
}

table.dataTable tbody tr:hover {
    background-color: #475569; /* Highlighted row color */
    cursor: pointer;
}

table.dataTable td {
    padding: 8px 12px;
    text-align: left;
    border-bottom: 1px solid #334155;
}

.dataTables_wrapper {
    margin-top: 10px;
}

/* Export Buttons Styling */
.dt-buttons .dt-button {
    background-color: #2563eb;
    color: #ffffff;
    border: none;
    border-radius: 5px;
    padding: 6px 12px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.dt-buttons .dt-button:hover {
    background-color: #1e40af;
    transform: translateY(-2px);
}

/* Pagination Styling */
.dataTables_paginate .paginate_button {
    color: #2563eb !important;
    background-color: #1e293b;
    border: 1px solid #475569;
    padding: 5px 8px;
    margin: 3px;
    border-radius: 5px;
    transition: background 0.3s ease;
}

.dataTables_paginate .paginate_button:hover {
    background-color: #2563eb;
    color: #ffffff !important;
}

.dataTables_paginate .paginate_button.current {
    background-color: #2563eb;
    color: #ffffff !important;
}

/* Scrollbar Styling */
.table-wrapper {
    overflow-x: auto;
}

.table-wrapper::-webkit-scrollbar {
    height: 8px;
}

.table-wrapper::-webkit-scrollbar-track {
    background: #1e293b;
}

.table-wrapper::-webkit-scrollbar-thumb {
    background: #2563eb;
    border-radius: 5px;
}

.table-wrapper::-webkit-scrollbar-thumb:hover {
    background: #1e40af;
}
/* Container Box for Filters */
.filter-box {
    background-color: #1e293b; /* Dark navy background */
    padding: 15px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 20px;
    justify-content: flex-start;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    margin-bottom: 10px;
}

/* Labels for Inputs */
.filter-box label {
    font-size: 0.9rem;
    font-weight: 600;
    color: #f1f5f9; /* Light text */
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Input and Select Box Styling */
.filter-box .filter-input,
.filter-box .filter-select {
    background-color: #334155; /* Darker input background */
    border: 1px solid #475569; /* Border */
    color: #f8fafc; /* Light text */
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 0.9rem;
    outline: none;
    transition: border 0.3s ease;
}

.filter-box .filter-input:focus,
.filter-box .filter-select:focus {
    border-color: #38bdf8; /* Blue focus border */
    box-shadow: 0 0 6px rgba(56, 189, 248, 0.4);
}

/* Adjust Dropdown Size */
.filter-box .filter-select {
    width: 180px;
}

.filter-box .filter-input {
    width: 250px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .filter-box {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .filter-box .filter-input,
    .filter-box .filter-select {
        width: 100%;
    }
}
/* General styling for filter container */
.filter-box {
    background-color: #1e293b; /* Dark navy background */
    border-radius: 8px;
    padding: 15px 20px;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    margin-bottom: 10px;
}

/* Label styling for inputs and select */
.filter-box label {
    color: #f8fafc; /* Light text */
    font-size: 0.9rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0; /* Remove margin for clean alignment */
}

/* Input and select field styling */
.filter-box input,
.filter-box select {
    background-color: #334155; /* Input background */
    border: 1px solid #475569; /* Border color */
    color: #f8fafc; /* Text color */
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 0.9rem;
    outline: none;
    transition: all 0.3s ease;
}

/* Focus styles */
.filter-box input:focus,
.filter-box select:focus {
    border-color: #38bdf8; /* Highlighted border on focus */
    box-shadow: 0 0 8px rgba(56, 189, 248, 0.4);
}

/* Input placeholder color */
.filter-box input::placeholder {
    color: #94a3b8; /* Lighter text for placeholder */
    font-size: 0.85rem;
}

/* Dropdown styling */
.filter-box select {
    appearance: none; /* Remove default dropdown styling */
    cursor: pointer;
    width: 180px; /* Adjust width */
}

/* Optional: Add arrow for dropdown */
.filter-box select::-ms-expand {
    display: none; /* Remove arrow in IE */
}

/* Adjust input field width */
.filter-box input {
    width: 250px; /* Adjust width */
}

/* Responsive Design */
@media (max-width: 768px) {
    .filter-box {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .filter-box input,
    .filter-box select {
        width: 100%;
    }
}

/* Style for the input field */
#schoolAgeSearch {
    background-color: #1e293b; /* Dark background color */
    color: #ffffff; /* White text color */
    border: 1px solid #475569; /* Border color */
    border-radius: 6px; /* Rounded corners */
    padding: 6px 12px; /* Padding inside the input */
    font-size: 0.9rem; /* Font size */
    transition: all 0.3s ease; /* Smooth transition */
}

/* Style for focus state */
#schoolAgeSearch:focus {
    outline: none; /* Remove default outline */
    border-color: #38bdf8; /* Highlight border color on focus */
    box-shadow: 0 0 8px rgba(56, 189, 248, 0.4); /* Subtle focus shadow */
}

/* Make the placeholder text white */
#schoolAgeSearch::placeholder {
    color: #ffffff; /* White color for placeholder text */
    opacity: 0.7; /* Slight transparency */
}


    </style>
    
</style>
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
    
    <!-- Filter Container at the Top -->
    <main>
        <div class="table-container">
            <div class="filter-scroll-wrapper">
                <div class="filter-container-wrapper">
                    <form method="get" action="logbookAdmin.php">
                        <label for="startDate">Filter by Date:</label>
                        <input type="date" id="startDate" name="date">
                    </form>
      

                    <!-- Refresh Icon -->
                    <div class="header__refresh" style="margin-left: auto; cursor: pointer;">
                        <i class="fas fa-sync-alt" onclick="refreshPage()" title="Refresh Page" style="font-size: 20px;"></i>
                    </div>
                    <!-- Print Button -->
                </div>
            </div>
            <div class="table-wrapper">
                <table id="logbookTable" class="display">
                    <thead>
                        <tr>
                            <th>Visitor No.</th>
                            <th>School</th>
                            <th>Date</th>
                            <th>Age</th>
                            <th>Sex</th>
                            <th>Time</th>
                            <th>Elementary</th>
                            <th>JHS</th>
                            <th>SHS</th>
                            <th>College</th>
                            <th>Post Grad</th>
                            <th>OSY</th>
                            <th>Occupation</th> <!-- New Occupation column -->
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded here by JavaScript -->
                    </tbody>
                </table>
            </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
    <script>
        $(document).ready(function() {
    var table = $('#logbookTable').DataTable({
        "order": [[0, "desc"]], 
        "pageLength": 25,
        "lengthMenu": [10, 25, 50, 100],
        "pagingType": "full_numbers",
        "scrollX": true,
        "columns": [
            { "data": "ID_No" },
            { "data": "School" },
            { "data": "Date" },
            { "data": "Age" },
            { "data": "Sex" },
            { "data": "Time" },
            { "data": "Elementary" },
            { "data": "Highschool" },
            { "data": "Shs" },
            { "data": "College" },
            { "data": "PostGrad" },
            { "data": "Osy" },
            { "data": "Occupation" }  // Added Occupation column
        ],
        "dom": 'Bfrtip',
        "buttons": [
            {
                extend: 'csv',
                filename: function() {
                    const selectedDate = $('#startDate').val() || new Date().toISOString().split('T')[0];
                    return `logbook_record_${selectedDate}`;
                }
            },
            {
                extend: 'excel',
                filename: function() {
                    const selectedDate = $('#startDate').val() || new Date().toISOString().split('T')[0];
                    return `logbook_record_${selectedDate}`;
                }
            },
            {
                extend: 'pdf',
                filename: function() {
                    const selectedDate = $('#startDate').val() || new Date().toISOString().split('T')[0];
                    return `logbook_record_${selectedDate}`;
                }
            },
            {
                extend: 'print',
                filename: function() {
                    const selectedDate = $('#startDate').val() || new Date().toISOString().split('T')[0];
                    return `logbook_record_${selectedDate}`;
                }
            }
        ]
    });
   $("#logbookTable_filter").html(`
    <div class="filter-box">
        <label>Search School or Age: 
            <input type="text" id="schoolAgeSearch" placeholder="Type school name or age">
        </label>
        <label>Search Education Level: 
            <select id="educationLevelSearch">
                <option value="">Select level</option>
                <option value="Elementary">Elementary</option>
                <option value="JHS">JHS</option>
                <option value="SHS">SHS</option>
                <option value="College">College</option>
                <option value="PostGrad">Post Grad</option>
                <option value="OSY">OSY</option>
            </select>
        </label>
    </div>
`);


    // Custom search function for combined School and Age search, and Education Level filter
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var schoolAgeSearch = $('#schoolAgeSearch').val().toLowerCase();
            var educationLevel = $('#educationLevelSearch').val();
            var school = data[1].toLowerCase(); // School column
            var age = data[3]; // Age column
            var levelValue;

            // Check if School or Age matches the search input
            if (schoolAgeSearch) {
                if (!school.includes(schoolAgeSearch) && age !== schoolAgeSearch) {
                    return false;
                }
            }

            // Check if Education Level matches "Yes" in the specified column
            if (educationLevel) {
                switch (educationLevel) {
                    case 'Elementary': levelValue = data[6]; break;
                    case 'JHS': levelValue = data[7]; break;
                    case 'SHS': levelValue = data[8]; break;
                    case 'College': levelValue = data[9]; break;
                    case 'PostGrad': levelValue = data[10]; break;
                    case 'OSY': levelValue = data[11]; break;
                    default: levelValue = '';
                }
                if (levelValue !== "Yes") {
                    return false;
                }
            }

            return true;
        }
    );

    // Trigger custom filtering on School, Age, and Education Level inputs
    $('#schoolAgeSearch, #educationLevelSearch').on('keyup change', function () {
        table.draw();
    });
    
    
   function fetchLogbookData(date) {
        $.ajax({
            url: 'fetch_logbook_ajax.php',
            type: 'GET',
            data: { date: date },
            success: function(data) {
                console.log("Data fetched:", data);
                table.clear().rows.add(data).draw();
            },
            error: function() {
                alert('Error loading data.');
            }
        });
    }
    
    

    const today = new Date().toISOString().split('T')[0];
    $('#startDate').val(today);
    fetchLogbookData(today);

    $('#startDate').on('change', function() {
        const selectedDate = $(this).val();
        fetchLogbookData(selectedDate);
    });
});

function refreshPage() {
    location.reload();
}

document.getElementById('selectMonthYear').addEventListener('change', function () {
    const monthYear = this.value; // Get the selected Month-Year

    if (monthYear) {
        // Make an AJAX request to fetch_logbook_ajax.php
        fetch(`fetch_logbook_ajax.php?monthYear=${monthYear}`)
            .then(response => response.json())
            .then(data => {
                console.log(data); // Debugging: Log fetched data to the console

                // Populate the DataTable with the fetched data
                const table = $('#logbookTable').DataTable();
                table.clear().rows.add(data).draw();
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    }
});


    </script>

    <script src="assets/js/main.js"></script>
</body>
</html>
