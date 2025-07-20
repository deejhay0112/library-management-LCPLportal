


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized Access</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            text-align: center;
            padding: 50px;
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .container h1 {
            font-size: 2.5rem;
            color: #e74c3c;
        }
        .container p {
            font-size: 1.2rem;
            color: #555;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Unauthorized Access</h1>
    <p>You do not have permission to access this page.</p>
</div>

<script>
    // Prevent the back button by pushing a new state into history
    window.history.pushState(null, null, window.location.href);
    window.history.back();
    window.history.forward();
</script>

</body>
</html>
