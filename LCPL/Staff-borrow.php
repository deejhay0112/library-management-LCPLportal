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
<link
  rel="stylesheet"
  href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
/>
  <!-- Styling -->
    <style>
/* General Styles */
body {
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #25274d;
}


/* Main Content */
.main-content {
    margin-left: 270px;
    padding: 40px 20px;
}

/* Form Container */
.forms-container {
    display: flex;
    justify-content: space-between;
    gap: 20px;
}

.form-section {
    width: 48%;
    padding: 30px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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

#borrowSearchResults {
    position: absolute; /* Positions the dropdown directly below the input */
    top: 100%; /* Aligns it directly below the input */
    left: 0; /* Aligns with the input field */
    width: 100%; /* Matches input width */
    background-color: #fff; /* White background */
    border: 1px solid #ddd; /* Subtle border */
    border-top: none; /* Prevents double border when close to input */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Adds a shadow for clarity */
    max-height: 200px; /* Limits the height of suggestions */
    overflow-y: auto; /* Enables vertical scrolling when content overflows */
    z-index: 1000; /* Ensures dropdown appears above other elements */
}

/* Individual suggestion items */
#borrowSearchResults div {
    padding: 10px; /* Adds padding for better spacing */
    cursor: pointer; /* Makes it clear the items are clickable */
    transition: background-color 0.2s ease; /* Smooth hover effect */
}

#borrowSearchResults div:hover {
    background-color: #f1f1f1; /* Light gray hover effect */
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

/* Sidebar Toggle Button */
.header__toggle {
    color: #25274d;
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

  <!-- Main Content Container -->
<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Main Content Container -->
<div class="container mx-auto my-10 p-6">
  <div class="flex justify-center">
    <!-- Form Card -->
    <div class="w-full max-w-3xl bg-white shadow-lg rounded-lg overflow-hidden">
      <div class="bg-blue-600 p-4 text-center">
        <h2 class="text-white text-2xl font-semibold">Borrow Book</h2>
      </div>
      <div class="p-6">
        <form
          id="borrowBookForm"
          action="borrow_book_submit.php"
          method="POST"
          enctype="multipart/form-data"
        >
         <div class="relative w-full">
    <label for="borrow_book_name" class="block text-gray-700 font-semibold mb-2">
        Book Name:
    </label>
    <input
        id="borrow_book_name"
        name="book_name"
        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-500"
        placeholder="Search by title, author, or accession number"
        oninput="searchBooks()"
        required
    />
    <!-- Search Results -->
    <div 
        id="borrowSearchResults" 
        class="border border-gray-300 rounded-lg p-2 bg-white shadow-lg"
        style="display: none;">
    </div>
</div>


          <!-- Borrower's Name -->
          <div class="mb-4">
            <label
              for="borrower_name"
              class="block text-gray-700 font-semibold mb-2"
            >
              Borrower's Name:
            </label>
            <input
              type="text"
              id="borrower_name"
              name="borrower_name"
              class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-500"
              placeholder="Enter borrower's name"
              required
            />
          </div>

          <!-- Address -->
          <div class="mb-4">
            <label for="address" class="block text-gray-700 font-semibold mb-2">
              Address:
            </label>
            <input
              type="text"
              id="address"
              name="address"
              class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-500"
              placeholder="Enter borrower's address"
              required
            />
          </div>

          <!-- Contact Number -->
          <div class="mb-4">
            <label
              for="contact_number"
              class="block text-gray-700 font-semibold mb-2"
            >
              Contact Number:
            </label>
            <input
              type="number"
              id="contact_number"
              name="contact_number"
              class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-500"
              placeholder="Enter borrower's contact number"
              required
            />
          </div>

          <!-- ID Type -->
          <div class="mb-4">
            <label
              for="id_type"
              class="block text-gray-700 font-semibold mb-2"
            >
              ID Type:
            </label>
            <select
              id="id_type"
              name="id_type"
              class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-500"
              required
            >
              <option value="" disabled selected>Select ID type</option>
              <option value="Student ID">Student ID</option>
              <option value="Driver's License">Driver's License</option>
              <option value="National ID">National ID</option>
              <option value="Passport">Passport</option>
            </select>
          </div>

          <!-- ID Number -->
          <div class="mb-4">
            <label
              for="id_number"
              class="block text-gray-700 font-semibold mb-2"
            >
              ID Number:
            </label>
            <input
              type="number"
              id="id_number"
              name="id_number"
              class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-500"
              placeholder="Enter ID number"
              required
            />
          </div>

         <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
  <!-- Borrow Date -->
<div>
    <label
      for="borrow_date"
      class="block text-gray-700 font-semibold mb-2"
    >
      Borrow Date:
    </label>
    <input
      type="date"
      id="borrow_date"
      name="borrow_date"
      class="w-full px-3 py-2 bg-gray-200 text-gray-700 rounded-lg focus:outline-none"
      readonly
      required
    />
</div>


  <!-- Return Date -->
  <div>
    <label
      for="return_date"
      class="block text-gray-700 font-semibold mb-2"
    >
      Return Date:
    </label>
    <input
      type="date"
      id="return_date"
      name="return_date"
      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-500"
      required
    />
  </div>
</div>


          <!-- Camera Section -->
          <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2"
              >Capture ID Image:</label
            >
            <div class="flex flex-col items-center">
              <button
                type="button"
                id="startCameraButton"
                class="mb-2 px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-600"
              >
                Start Camera
              </button>
              <video
                id="cameraFeed"
                class="w-full hidden rounded-lg"
                autoplay
              ></video>
              <canvas id="canvas" class="hidden"></canvas>
              <button
                id="captureButton"
                type="button"
                class="hidden px-4 py-2 mt-2 bg-blue-600 text-white rounded hover:bg-blue-500"
              >
                Capture Image
              </button>
              <div id="capturedImageContainer" class="mt-4"></div>
              <button
                id="saveButton"
                type="button"
                class="hidden px-4 py-2 mt-2 bg-green-600 text-white rounded hover:bg-green-500"
              >
                Save Image
              </button>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="flex justify-center mt-6">
            <button
              type="submit"
              class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-500 transition-all duration-300"
            >
              Borrow Book
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Tailwind JS (Optional for custom configurations) -->
<script src="https://cdn.tailwindcss.com"></script>


  
    <script src="assets/js/searchBooks.js"></script>
        <script src="assets/js/main.js"></script>
    
    <script>
// Get DOM elements
const startCameraButton = document.getElementById('startCameraButton');
const cameraFeed = document.getElementById('cameraFeed');
const canvas = document.getElementById('canvas');
const captureButton = document.getElementById('captureButton');
const capturedImageContainer = document.getElementById('capturedImageContainer');
const saveButton = document.getElementById('saveButton');
const borrowBookForm = document.getElementById('borrowBookForm'); // Form reference

// Start the camera
function startCamera() {
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                cameraFeed.srcObject = stream;
                cameraFeed.style.display = 'block';
                captureButton.style.display = 'block';
                startCameraButton.style.display = 'none';
            })
            .catch(error => {
                console.error('Camera access error:', error);
                alert('Unable to access the camera. Check permissions.');
            });
    } else {
        alert('Camera not supported in this browser.');
    }
}
// Capture an image
function captureImage(event) {
    event.preventDefault(); // Prevent form submission
    
    const context = canvas.getContext('2d');
    canvas.width = cameraFeed.videoWidth;
    canvas.height = cameraFeed.videoHeight;
    context.drawImage(cameraFeed, 0, 0, canvas.width, canvas.height);

    const imageData = canvas.toDataURL('image/png');
    capturedImageContainer.innerHTML = `<img src="${imageData}" alt="Captured Image" style="max-width: 100%; max-height: 300px;">`;

    // Add hidden input to the form for the image
    let hiddenInput = document.getElementById('id_picture');
    if (!hiddenInput) {
        hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'id_picture';
        hiddenInput.id = 'id_picture';
        borrowBookForm.appendChild(hiddenInput);
    }
    hiddenInput.value = imageData; // Assign base64 data

    cameraFeed.style.display = 'none';
    captureButton.style.display = 'none';
    saveButton.style.display = 'block';
}

// Save the captured image to the server
function saveCapturedImage(event) {
    event.preventDefault(); // Prevent form submission
    alert('Image captured successfully and included in the form. Please submit the form to save it.');
}

// Event Listeners
startCameraButton.addEventListener('click', startCamera);
captureButton.addEventListener('click', captureImage);
saveButton.addEventListener('click', saveCapturedImage);

// Capture form submission to show SweetAlert
document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission
    
    // Show SweetAlert before submitting the form
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to borrow this book?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Borrow it!',
        cancelButtonText: 'No, Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // If confirmed, submit the form and reload the page
            this.submit(); // Submit the form
            Swal.fire(
                'Success!',
                'The book has been borrowed.',
                'success'
            ).then(() => {
                // Reload the current page after the confirmation
                window.location.reload(); // Reload the current page
            });
        } else {
            // If canceled, no action is taken
            console.log('Borrowing canceled');
        }
    });
});




// Set today's date and restrict return date to within 3 days
document.addEventListener('DOMContentLoaded', function () {
    const borrowDateField = document.getElementById('borrow_date');
    const returnDateField = document.getElementById('return_date');

    // Get today's date
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are zero-based
    const day = String(today.getDate()).padStart(2, '0');
    const formattedDate = `${year}-${month}-${day}`;

    // Set the borrow date field to today's date
    borrowDateField.value = formattedDate;

    // Set the minimum return date to today
    returnDateField.min = formattedDate;

    // Set the maximum return date to 3 days from today
    const maxReturnDate = new Date();
    maxReturnDate.setDate(today.getDate() + 3); // Add 3 days
    const maxYear = maxReturnDate.getFullYear();
    const maxMonth = String(maxReturnDate.getMonth() + 1).padStart(2, '0');
    const maxDay = String(maxReturnDate.getDate()).padStart(2, '0');
    returnDateField.max = `${maxYear}-${maxMonth}-${maxDay}`;

    // Pre-fill the return date with today's date as the default
    returnDateField.value = formattedDate;
});
</script>
<script>
    document.getElementById("contact_number").addEventListener("input", function (e) {
    this.value = this.value.replace(/[^0-9]/g, ""); // Removes non-numeric characters
});

document.getElementById("id_number").addEventListener("input", function (e) {
    this.value = this.value.replace(/[^0-9]/g, ""); // Removes non-numeric characters
});

</script>
<script>
document.getElementById("contact_number").addEventListener("input", function (e) {
    this.value = this.value.replace(/[^0-9]/g, ""); // Removes non-numeric characters
    if (this.value.length > 11) {
        this.value = this.value.slice(0, 11); // Limits input to 11 digits
    }
});

document.querySelector("form").addEventListener("submit", function (e) {
    const contactNumber = document.getElementById("contact_number").value;
    if (contactNumber.length !== 11) {
        e.preventDefault(); // Prevent form submission
        Swal.fire({
            title: "Invalid Contact Number",
            text: "Contact number must be exactly 11 digits.",
            icon: "error",
        });
    }
});

</script>


</body>
</html>
