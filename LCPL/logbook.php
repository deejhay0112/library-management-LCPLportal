<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Library Logbook</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #0f172a;
            color: #ffffff;
        }

        .box-container {
            background: #1e293b;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
            max-width: 600px;
            width: 100%;
            padding: 25px;
            margin: 20px;
            box-sizing: border-box;
        }

        /* Header Styling */
        .header-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .header-container img {
            width: 80px;
            margin-bottom: 10px;
        }

        .header-container h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #f8fafc;
        }

        .header-container p {
            font-size: 0.9rem;
            color: #94a3b8;
        }

        /* Section Headings */
        .form-section h3 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #38bdf8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #38bdf8;
            display: inline-block;
            padding-bottom: 5px;
        }

        /* Labels */
        .form-container label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #cbd5e1;
            margin-bottom: 5px;
        }

        /* Input and Select Styling */
        .form-container input,
        .form-container select {
            background-color: #334155;
            border: 1px solid #475569;
            border-radius: 8px;
            padding: 8px 10px;
            color: #f8fafc;
            font-size: 0.9rem;
        }

        .form-container input:focus,
        .form-container select:focus {
            outline: none;
            border-color: #38bdf8;
            box-shadow: 0 0 8px rgba(56, 189, 248, 0.4);
        }

        /* Submit Button */
        .form-container button {
            background-color: #38bdf8;
            color: #ffffff;
            font-weight: 700;
            text-transform: uppercase;
            border: none;
            border-radius: 8px;
            padding: 10px;
            font-size: 1rem;
            margin-top: 10px;
            transition: background 0.3s ease;
        }

        .form-container button:hover {
            background-color: #007bff;
        }

        /* Readonly Input */
        .readonly-input {
            background-color: #1e293b;
            border-color: #334155;
            cursor: not-allowed;
        }

        @media (max-width: 768px) {
            .box-container {
                padding: 20px;
                margin: 10px;
            }

            .header-container h2 {
                font-size: 1.5rem;
            }
        }
        .form-label {
        color: #ffffff; /* Sets label text color to white */
    }
    ::placeholder { 
    color: #cbd5e1; /* Light gray placeholder text */
    opacity: 1; /* Ensures full visibility */
}

input::placeholder, 
textarea::placeholder {
    color: #cbd5e1 !important; /* Forces lighter color */
    font-weight: 400; /* Optional: Normal weight */
}
/* Visually hide number input spinner */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    opacity: 0;
    pointer-events: none;
}

input[type="number"] {
    -moz-appearance: textfield; /* Hides spinner in Firefox */
}
/* Visit Details Container */
.visit-details-container {
    background-color: #1e293b;
    border: 1px solid #334155;
    border-radius: 8px;
    padding: 20px;
    margin-top: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Section Header */
.visit-details-header {
    font-size: 1.2rem;
    font-weight: 600;
    color: #38bdf8;
    text-align: center;
    margin-bottom: 15px;
    border-bottom: 2px solid #38bdf8;
    display: inline-block;
    padding-bottom: 5px;
}

/* Date and Time Display */
.date-time-display {
    background-color: #334155;
    color: #f8fafc;
    border: none;
    border-radius: 6px;
    padding: 10px;
    display: block;
    text-align: center;
    font-size: 1rem;
    font-weight: 600;
}
/* Center Container */
.center-container {
    display: flex;
    justify-content: center; /* Horizontally center */
    align-items: center;     /* Vertically center */
    flex-direction: column;  /* Stack children vertically */
    min-height: 100vh;
    text-align: center;
}

/* Box Container */
.box-container {
    background: #1e293b;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 20px;
    max-width: 600px;
    width: 100%;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
}

/* Date and Time Container */
.visit-details-container {
    background-color: #1e293b;
    border: 1px solid #334155;
    border-radius: 8px;
    padding: 20px;
    max-width: 600px;
    width: 100%;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Section Header */
.visit-details-header {
    font-size: 1.2rem;
    font-weight: 600;
    color: #38bdf8;
    text-align: center;
    margin-bottom: 15px;
    border-bottom: 2px solid #38bdf8;
    display: inline-block;
    padding-bottom: 5px;
}

/* Date and Time Display */
.date-time-display {
    background-color: #334155;
    color: #f8fafc;
    border: none;
    border-radius: 6px;
    padding: 10px;
    display: block;
    text-align: center;
    font-size: 1rem;
    font-weight: 600;
}

    </style>
</head>

<body>
    <main class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="box-container">
            <!-- Header Section -->
            <div class="header-container">
                <img src="logo/vls_logo.jpg" alt="Library Logo">
                <h2>Visitor Library Logbook</h2>
                <p>Please log your visit to the library.</p>
            </div>

            <!-- Form Section -->
            <form id="logbookForm" action="insert_logbook.php" method="POST" class="form-container">
                <!-- Personal Information -->
                <div class="form-section">
                    <h3>Personal Information</h3>
                    <div class="row mb-3">
                        <div class="col-md-8">
                           <label for="name" class="form-label text-white">Full Name</label>
                            <input type="text" id="name" name="name" placeholder="Full Name" required class="form-control">
                        </div>
                        <div class="col-md-4">
                           <label for="age" class="form-label text-white">Age</label>
                            <input type="number" id="age" name="age" placeholder="Age" required class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                             <label for="school" class="form-label text-white">School/Institution</label>
                            <input type="text" id="school" name="school" placeholder="School/Institution" required class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="form-section">
                    <h3>Additional Information</h3>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="educationLevel">Educational Level</label>
                            <select name="education_level" id="educationLevel" required class="form-select">
                                 <option value="">Select Education Type</option>
                                <option value="elementary">Elementary</option>
                                <option value="junior_high">Junior High</option>
                                <option value="senior_high">Senior High</option>
                                <option value="college">College</option>
                                <option value="post_graduate">Post Graduate</option>
                                <option value="out_of_school">Out of School</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="occupation">Visitor Type</label>
                            <select name="occupation" id="occupation" required class="form-select">
                                <option value="">Select Visitor Type</option>
                                <option value="student">Student</option>
                                <option value="employed">Employed</option>
                                <option value="unemployed">Unemployed</option>
                                <option value="self_employed">Self-employed</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="gender">Gender</label>
                            <select name="sex" id="gender" required class="form-select">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="LGBTQ">LGBTQ</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Visit Details -->
               <div class="form-section" style="display: none;">
    <h3>Visit Details</h3>
    <div class="row g-2">
        <div class="col-md-6">
            <label for="dateInput">Date</label>
            <input type="text" id="dateInput" name="date" placeholder="Date" class="form-control readonly-input" readonly>
        </div>
        <div class="col-md-6">
            <label for="timeInput">Time</label>
            <input type="text" id="timeInput" name="time" placeholder="Time" class="form-control readonly-input" readonly>
        </div>
    </div>
</div>


                <!-- Submit Button -->
                <button type="submit" class="w-100">Log Visit</button>
            </form>
          <!-- Date and Time Display Section (Outside the form) -->
 <div class="visit-details-container mt-4">
    <h3 class="visit-details-header">Current Date & Time</h3>
    <div class="row g-2">
        <div class="col-md-6">
            <label for="dateDisplay" class="form-label">Date</label>
            <span id="dateDisplay" class="form-control date-time-display">Loading...</span>
        </div>
        <div class="col-md-6">
            <label for="timeDisplay" class="form-label">Time</label>
            <span id="timeDisplay" class="form-control date-time-display">Loading...</span>
        </div>
    </div>
</div>
</div>

</div>

        </div>
        
       
    </main>
    <!-- JavaScript Section -->
    <script src="assets/js/openModal.js"></script>
    <script src="assets/js/checkboxHandler.js"></script>
    <script src="assets/js/searchBooks.js"></script>
    <script src="assets/js/educationLevelHandler.js"></script>

    <script>
        // Logbook Form Submission with AJAX
        document.getElementById('logbookForm').addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(this);
            fetch('insert_logbook.php', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        title: data.success ? 'Success!' : 'Error!',
                        text: data.message,
                        icon: data.success ? 'success' : 'error',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        if (data.success) window.location.href = 'logbook.php';
                    });
                }).catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error!', 'An unexpected error occurred. Please try again.', 'error');
                });
        });
    </script>
        <script>
         // Show SweetAlert2 popup when the page is loaded
        window.onload = function () {
            Swal.fire({
                title: 'Data Privacy Act Consent',
                html: `
                    <p>Please review and accept our Data Privacy Act.</p>
                    <p>By clicking "Submit", you agree to our terms and conditions.</p>
                    <div>
                        <input type="checkbox" id="privacyCheckbox"> 
                        <label for="privacyCheckbox">I agree to the Data Privacy Act</label>
                    </div>
                `,
                showCancelButton: false,
                confirmButtonText: 'Submit',
                focusConfirm: false,
                preConfirm: () => {
                    if (!document.getElementById('privacyCheckbox').checked) {
                        Swal.showValidationMessage('You must agree to the Data Privacy Act to proceed.');
                    }
                },
                allowOutsideClick: false,   // Disable closing by clicking outside
                allowEscapeKey: false,     // Disable closing by pressing escape key
                backdrop: true,
                didOpen: () => {
                    // Disable the Submit button until checkbox is checked
                    const submitButton = Swal.getConfirmButton();
                    submitButton.disabled = !document.getElementById('privacyCheckbox').checked;

                    document.getElementById('privacyCheckbox').addEventListener('change', function () {
                        submitButton.disabled = !this.checked;
                    });
                }
            });
        };

        // Handle form submission (this is where you'd handle your actual form)
        document.getElementById('submitButton').addEventListener('click', function () {
            // If needed, add form submission logic here
            alert("Form submitted successfully!");
        });
    </script>
    <script>
 // Prevent the user from using the back button to return to this page
    window.history.pushState(null, null, window.location.href);
    window.onpopstate = function() {
        window.history.go(1);  // Push forward if they try to go back
    };
</script>

<script>
    <script>
        // Automatically redirect if the user goes back to this page
        if (performance.navigation.type === 2) {
            // The user is coming from the back/forward cache
            window.location.href = 'notuser.php'; // Redirect to notuser.php
        }

        // Optional: You can also use the popstate event to catch back navigation
        window.onpopstate = function() {
            window.location.href = 'notuser.php';
        };
    </script>
</script>
<script>
document.getElementById("age").addEventListener("input", function () {
    // Remove any non-numeric characters and leading zeros
    this.value = this.value.replace(/[^0-9]/g, '').replace(/^0+/, '');

    // Cap the value at 100
    if (parseInt(this.value, 10) > 100) {
        this.value = 100;
    }
});

</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const timeDisplay = document.getElementById('timeDisplay');
    const dateDisplay = document.getElementById('dateDisplay');

    // Function to update the time every second
    function updateTime() {
        const now = new Date();
        
        // Format time (HH:MM:SS AM/PM)
        const formattedTime = now.toLocaleTimeString([], { 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit',
            hour12: true 
        });
        timeDisplay.textContent = formattedTime;

        // Format date (e.g., Monday, January 1, 2024)
        const formattedDate = now.toLocaleDateString('en-US', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
        dateDisplay.textContent = formattedDate;
    }

    updateTime(); // Call immediately on load
    setInterval(updateTime, 1000); // Update every second
});

</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const educationLevel = document.getElementById("educationLevel");
    const occupationType = document.getElementById("occupation");
    const schoolInput = document.getElementById("school");

    function manageFields() {
        const visitorType = occupationType.value;

        // When Visitor Type is Employed, Unemployed, or Self-employed
        if (["employed", "unemployed", "self_employed"].includes(visitorType)) {
            schoolInput.value = "Not in school";
            schoolInput.setAttribute("readonly", true);

            // Enable only Post Graduate and Out of School
            Array.from(educationLevel.options).forEach(option => {
                if (["post_graduate", "out_of_school"].includes(option.value)) {
                    option.disabled = false; // Enable these two options
                } else {
                    option.disabled = true; // Disable other options
                }
            });
            educationLevel.value = ""; // Reset dropdown to empty
        }
        // When Visitor Type is Student
        else if (visitorType === "student") {
            schoolInput.value = "";
            schoolInput.removeAttribute("readonly");

            // Disable Post Graduate and Out of School
            Array.from(educationLevel.options).forEach(option => {
                if (["post_graduate", "out_of_school"].includes(option.value)) {
                    option.disabled = true; // Disable these options
                } else {
                    option.disabled = false; // Enable other options
                }
            });
            educationLevel.value = ""; // Reset dropdown to empty
        }
        // Default case: Enable all options
        else {
            schoolInput.value = "";
            schoolInput.removeAttribute("readonly");

            // Enable all options
            Array.from(educationLevel.options).forEach(option => {
                option.disabled = false;
            });
            educationLevel.value = ""; // Reset dropdown
        }
    }

    // Event listener for Visitor Type changes
    occupationType.addEventListener("change", manageFields);
});
</script>



</body>

</html>
