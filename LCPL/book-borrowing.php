<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Book</title>
    <link rel="stylesheet" href="books.css">
    <style>
        .back-button {
            display: inline-block;
            margin: 10px 0;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1rem;
            cursor: pointer;
            text-align: center;
        }

        .back-button:hover {
            background-color: #0056b3;
        }

        #borrowFormContainer {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            margin-top: 20px;
            max-width: 600px;
            margin: auto;
        }

        #borrowFormContainer form {
            display: flex;
            flex-direction: column;
        }

        #borrowFormContainer label {
            margin-bottom: 5px;
        }

        #borrowFormContainer input, #borrowFormContainer button {
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        #borrowFormContainer button[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        #borrowFormContainer button[type="submit"]:hover {
            background-color: #0056b3;
        }

        #borrowFormContainer button[type="button"] {
            background-color: #6c757d;
            color: white;
            border: none;
            cursor: pointer;
        }

        #borrowFormContainer button[type="button"]:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <header>
        <h1>Library Management System</h1>
        <nav>
            <a href="dashboard.php" class="tab">Dashboard</a>
            <a href="books.php" class="tab">Back to Book List</a>
        </nav>
    </header>
    
    <section>
        <div id="borrowFormContainer">
            <h2>Borrow Book</h2>
            <form id="borrowBookForm">
                <input type="hidden" id="bookId" name="bookId" />
                <label for="bookName">Book Name:</label>
                <input type="text" id="bookName" name="bookName" required />
                <label for="borrowerName">Borrower's Name:</label>
                <input type="text" id="borrowerName" name="borrowerName" required />
                <label for="borrowDate">Borrow Date:</label>
                <input type="date" id="borrowDate" name="borrowDate" required />
                <label for="returnDate">Return Date:</label>
                <input type="date" id="returnDate" name="returnDate" required />
                <button type="submit">Submit Borrow Request</button>
                <button type="button" onclick="window.location.href='books.php'">Cancel</button>
            </form>
        </div>
    </section>

    <script src="script.js"></script>
</body>
</html>
