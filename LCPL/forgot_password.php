<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="assets/css/login.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- SVG Divider -->
    <svg class="divider" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#f5f5f5" fill-opacity="1" d="M0,240 C480,320 960,80 1440,160 L1440,320 L0,320 Z"></path>
    </svg>

    <div class="container">
        <div class="login-box">
            <h2>Forgot Password</h2>
            <!-- Step 1: Enter Email -->
            <form id="forgotPasswordForm">
                <div class="input-box">
                    <input type="email" name="email" required>
                    <label>Enter your Email</label>
                </div>
                <button type="submit" class="btn">Send Reset Link</button>
            </form>
        </div>
    </div>

    <script>
        // AJAX form submission
       $('#forgotPasswordForm').on('submit', function(e) {
    e.preventDefault(); // Prevent default form submission
    
    $.ajax({
        url: 'send_reset_link.php',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            // Display the response as a SweetAlert
            Swal.fire({
                icon: response.icon,
                title: response.title,
                text: response.message
            }).then((result) => {
                // Check if the SweetAlert has been confirmed (e.g., "OK" clicked)
                if (result.isConfirmed && response.success) {
                    // Redirect to the login page if the reset link was sent successfully
                    window.location.href = 'admin-login.php';
                }
            });
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while processing your request.'
            });
        }
    });
});
    </script>
</body>
</html>
