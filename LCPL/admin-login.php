<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="assets/css/login.css">
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <!-- Full-screen SVG for Background Curve -->
    <svg class="divider" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#f5f5f5" fill-opacity="1" d="M0,240 C480,320 960,80 1440,160 L1440,320 L0,320 Z"></path>
    </svg>

    <!-- Centered Login Form Container -->
    <div class="container">
        <div class="login-box">
            <img src="logo/vls_logo.jpg" alt="Library Logo" class="nav__logo-img">
            <h2>Admin Login</h2>

            <form id="loginForm" action="login.php" method="POST">
                <div class="input-box">
                    <input id="user" type="text" class="input" name="username" required>
                    <label>Username</label>
                </div>
                <div class="input-box">
                    <input id="pass" type="password" class="input" name="password" required>
                    <label>Password</label>
                </div>
                
               

                <button type="submit" class="btn">Login</button>

                <div class="footer-links">
                    <a href="forgot_password.php" class="forgot-pass">Forgot Password?</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(this);

            fetch('login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Login successful',
                        text: 'Redirecting to your dashboard...',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // Check the user's role and redirect accordingly
                        const redirectUrl = data.role === 'staff' ? 'Staff-borrow.php' : 'dashboard.php';
                        window.location.href = redirectUrl;
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'An error occurred',
                    text: 'Please try again later.'
                });
            });
        });
    </script>
</body>
</html>
