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
    <title>Library Borrow and Return Books</title>
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</head>
</head>
<body id="body-pd">
    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div>
                <a href="#" class="nav__logo">
                    <img src="logo/vls_logo.jpg" alt="Library Logo" class="nav__logo-img">
                    <span class="nav__logo-name">LIPA CITY PUBLIC LIBRARY</span>
                </a>
                <div class="nav__list">
                    <a href="Staff-borrow.php" class="nav__link <?php echo ($currentPage == 'library_management') ? 'active' : ''; ?>">
                        <i class='bx bx-grid-alt nav__icon'></i>
                        <span class="nav__name">Library Management</span>
                    </a>
                    
                    <div class="nav__list">
                    <a href="staff-return.php" class="nav__link <?php echo ($currentPage == 'library_management') ? 'active' : ''; ?>">
                        <i class='bx bx-grid-alt nav__icon'></i>
                        <span class="nav__name">Library Management</span>
                    </a>

                    <a href="transaction_book_staff.php" class="nav__link <?php echo ($currentPage == 'transaction_books') ? 'active' : ''; ?>">
                        <i class='bx bx-book nav__icon'></i>
                        <span class="nav__name">Transaction Books</span>
                    </a>

            <a href="logout.php" class="nav__link">
                <i class='bx bx-log-out nav__icon'></i>
                <span class="nav__name">Log Out</span>
            </a>
        </nav>
    </div>
</head>
<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Main Content -->
<div class="min-h-screen flex items-center justify-center bg-[#25274d] p-4">
  <!-- Form Card -->
  <div class="w-full max-w-3xl bg-white shadow-lg rounded-lg overflow-hidden">
    <!-- Header -->
    <div class="bg-blue-600 p-4 text-center">
      <h2 class="text-white text-2xl font-semibold">Return Book</h2>
    </div>

    <!-- Form Content -->
    <div class="p-6">
      <form id="returnBookForm" action="return_book_submit.php" method="POST">
        <!-- Book ID or Borrower ID Number -->
        <div class="mb-4">
          <label for="id_number" class="block text-gray-700 font-semibold mb-2">Enter ID Number:</label>
          <input
            type="text"
            id="id_number"
            name="id_number"
            placeholder="Enter Book ID or Borrower ID Number"
            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-500"
            required
          />
        </div>

        <!-- Borrower's Name -->
        <div class="mb-4">
          <label for="borrower_name" class="block text-gray-700 font-semibold mb-2">Borrower's Name:</label>
          <input
            type="text"
            id="borrower_name"
            name="borrower_name"
            placeholder="Enter Borrower's Name"
            class="w-full px-3 py-2 border bg-gray-100 rounded-lg focus:outline-none"
            readonly
            required
          />
        </div>

        <!-- Address -->
        <div class="mb-4">
          <label for="address" class="block text-gray-700 font-semibold mb-2">Address:</label>
          <input
            type="text"
            id="address"
            name="address"
            placeholder="Enter Borrower's Address"
            class="w-full px-3 py-2 border bg-gray-100 rounded-lg focus:outline-none"
            readonly
            required
          />
        </div>

        <!-- Contact Number -->
        <div class="mb-4">
          <label for="contact_number" class="block text-gray-700 font-semibold mb-2">Contact Number:</label>
          <input
            type="text"
            id="contact_number"
            name="contact_number"
            placeholder="Enter Borrower's Contact Number"
            class="w-full px-3 py-2 border bg-gray-100 rounded-lg focus:outline-none"
            readonly
            required
          />
        </div>

        <!-- Return Date -->
        <div class="mb-4">
          <label for="return_date" class="block text-gray-700 font-semibold mb-2">Return Date:</label>
          <input
            type="date"
            id="return_date"
            name="return_date"
            class="w-full px-3 py-2 border bg-gray-100 rounded-lg focus:outline-none"
            readonly
            required
          />
        </div>
        <div class="mb-4">
            <label for="return_status" class="block text-gray-700 font-semibold mb-2">Return Status:</label>
        <select 
        id="return_status" 
        name="return_status" 
        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-500" 
        required
        >
        <option value="Pending">Pending</option>
        <option value="Returned" selected>Returned</option>
        <option value="Late Return">Late Return</option>
        <option value="Not Returned">Not Returned</option>
    </select>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-center mt-6">
          <button
            type="submit"
            class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-500 transition-all duration-300"
          >
            Submit
          </button>
        </div>
      </form>
    </div>
  </div>
</div>




    <!-- Styling -->
    <style>
/* General Styles */
body {
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
    background-color:  #25274d;
}

/* Header & Navbar */
.header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background-color: #25274d;
    padding: 10px 20px;
    z-index: 10;
}



/* Main Content */
.main-content {
    margin-left: 270px;
    padding: 40px 20px;
}

/* Form Container */
.form-section {
    width: 70%;
    padding: 30px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    margin-left: 270px;
    margin-top: 80px;
    transition: transform 0.3s ease;
}

.form-section:hover {
    transform: translateY(-5px);
}

h2 {
    font-size: 22px;
    font-weight: bold;
    color: #333;
    margin-bottom: 20px;
}

/* Form Fields */
.form-group {
    margin-bottom: 20px;
    position: relative;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #555;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 12px 15px;
    box-sizing: border-box;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 16px;
    transition: border-color 0.3s ease, background-color 0.3s ease;
}

.form-group input[type="text"]:focus,
.form-group select:focus {
    border-color: #007bff;
    outline: none;
    background-color: #e6f1ff;
}

/* Buttons */
button {
    padding: 12px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

button:hover {
    background-color: #0056b3;
    transform: translateY(-3px);
}

/* Camera Section */
.camera-container {
    margin-top: 30px;
    text-align: center;
}

#cameraFeed {
    border: 1px solid #ddd;
    margin-top: 10px;
    border-radius: 10px;
    width: 100%;
    max-width: 450px;
}

#canvas {
    border: 1px solid #ddd;
    margin-top: 10px;
    border-radius: 10px;
    max-width: 100%;
}

/* Input Styling */
input[type="text"] {
    padding: 12px;
    font-size: 16px;
    border-radius: 8px;
    border: 1px solid #ccc;
    width: calc(100% - 24px);
}

input[type="text"]:focus {
    border-color: #007bff;
    outline: none;
}

/* Search Results Styling */
#searchResults {
    display: none;
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid #ddd;
    background-color: #fff;
    width: 100%;
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

#searchResults div {
    padding: 10px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

#searchResults div:hover {
    background-color: #f1f1f1;
}

/* Sidebar Toggle Button */
.header__toggle {
    color: white;
    font-size: 28px;
    cursor: pointer;
    display: none;
}

/* Responsive Design */
@media (max-width: 768px) {
    .l-navbar {
        width: 220px;
    }

    .main-content {
        margin-left: 240px;
    }

    .forms-container {
        flex-direction: column;
        align-items: center;
    }

    .form-section {
        width: 80%;
        margin-bottom: 30px;
    }

    .nav__logo-name {
        font-size: 18px;
    }
}

@media (max-width: 480px) {
    .l-navbar {
        width: 100%;
        height: auto;
        position: static;
    }

    .nav__list {
        flex-direction: row;
        justify-content: space-between;
        padding: 10px 0;
    }

    .nav__logo-name {
        font-size: 14px;
    }

    .main-content {
        margin-left: 0;
        padding: 20px;
    }

    .form-section {
        width: 100%;
        margin-bottom: 20px;
    }

    button {
        width: 100%;
    }
}

/* Camera Section */
.camera-container {
    margin-top: 20px;
    text-align: center;
}

#cameraFeed {
    border: 1px solid #ccc;
    margin-top: 10px;
    max-width: 100%;
    border-radius: 10px;
}

button {
    padding: 12px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    margin-top: 10px;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

button:hover {
    background-color: #0056b3;
    transform: translateY(-3px);
}

#canvas {
    display: none;
    border: 1px solid #ccc;
    margin-top: 10px;
    border-radius: 5px;
    max-width: 100%;
}


</style>

    <script>// fetch_borrower_details.js

// Function to search borrower details by ID
function searchById() {
    const idNumber = document.getElementById('id_number').value.trim();  // Get ID number
    console.log("Entered ID Number:", idNumber);  // Debug log to confirm ID number

    if (idNumber.length === 0) {
        console.log("No ID number provided.");  // Log if the input is empty
        return;  // If no ID entered, do nothing
    }

    // Fetch borrower details using the entered ID number
    console.log(`Making request with ID: ${idNumber}`); // Debug log before the fetch request
    fetch(`https://lcplportal.net/LCPL/fetch_borrower.php?id_number=${encodeURIComponent(idNumber)}`)
        .then(response => response.json())  // Parse JSON response
        .then(data => {
            console.log("Fetched Data:", data);  // Log fetched data for debugging

            // Check if there's an error in the response (no borrower found)
            if (data.error) {
                console.log(data.error);
                return;  // Exit the function early without updating form fields
            }

            // Populate the form fields with fetched data
            document.getElementById('borrower_name').value = data.borrower_name || '';
            document.getElementById('address').value = data.address || '';
            document.getElementById('contact_number').value = data.contact_number || '';

            // Check if 'return_date' exists or not
            if (!data.return_date || data.return_date.trim() === '') {
                // Set the return_date to today's date in YYYY-MM-DD format
                const today = new Date();
                const formattedDate = today.toISOString().split('T')[0]; // Format date as YYYY-MM-DD
                document.getElementById('return_date').value = formattedDate;
                console.log("Return date was empty. Set to today's date:", formattedDate);
            } else {
                document.getElementById('return_date').value = data.return_date;
            }
        })
        .catch(error => {
            console.error("Fetch Error:", error);
        });
}

// Ensure the script runs after the DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Call searchById when the ID input field is updated
    document.getElementById('id_number').addEventListener('input', searchById);
});

</script>

    <script src="assets/js/searchBooks.js"></script>
        <script src="assets/js/main.js"></script>
    
    <script>
    // Add event listener for form submission
    document.getElementById('returnBookForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent default form submission

    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to submit this form?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, submit it!',
    }).then((result) => {
        if (result.isConfirmed) {
            // If confirmed, submit the form
            e.target.submit();
        }
    });
});

// Check for success or error messages in the URL query parameters
const urlParams = new URLSearchParams(window.location.search);
const successMessage = urlParams.get('success');
const errorMessage = urlParams.get('error');

// Show SweetAlert for success or error after form submission
if (successMessage) {
    Swal.fire({
        title: 'Success!',
        text: successMessage,
        icon: 'success',
        confirmButtonText: 'OK'
    });
} else if (errorMessage) {
    Swal.fire({
        title: 'Error!',
        text: errorMessage,
        icon: 'error',
        confirmButtonText: 'OK'
    });
}

</script>




</body>
</html>