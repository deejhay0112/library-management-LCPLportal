<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="assets/css/login.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery for AJAX -->
</head>
<body>
    <!-- SVG Divider -->
    <svg class="divider" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#f5f5f5" fill-opacity="1" d="M0,240 C480,320 960,80 1440,160 L1440,320 L0,320 Z"></path>
    </svg>

    <div class="container">
        <div class="login-box">
            <h2>Reset Password</h2>
            <form id="resetPasswordForm">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                <div class="input-box">
                    <input type="password" name="new_password" required>
                    <label>New Password</label>
                </div>
                <button type="submit" class="btn">Update Password</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#resetPasswordForm').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                $.ajax({
                    url: 'update_password.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json', // Expect JSON response
                    success: function(response) {
                        // Handle the success or error response from update_password.php
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message
                            }).then(() => {
                                window.location.href = 'admin-login.php';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An unexpected error occurred.'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
