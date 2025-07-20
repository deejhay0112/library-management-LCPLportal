<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Inventory Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8f9fa;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .card-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }
        .card {
            width: 250px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            text-align: center;
        }
        .card h3 {
            color: #333;
            margin-bottom: 10px;
        }
        .card p {
            font-size: 1.5em;
            color: #4CAF50;
            margin: 0;
        }
        .book-table {
            margin: 0 auto;
            width: 80%;
            border-collapse: collapse;
            text-align: center;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .book-table th, .book-table td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        .book-table th {
            background-color: #4CAF50;
            color: white;
        }
        .book-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Book Inventory Report</h1>

    <!-- Cards for Metrics -->
    <div class="card-container">
        <div class="card">
            <h3>Total Books</h3>
            <p id="total-books">Loading...</p>
        </div>
        <div class="card">
            <h3>Unique Authors</h3>
            <p id="unique-authors">Loading...</p>
        </div>
        <div class="card">
            <h3>Most Recent Acquisition</h3>
            <p id="recent-date">Loading...</p>
        </div>
        <div class="card">
            <h3>Most Common Author</h3>
            <p id="common-author">Loading...</p>
        </div>
    </div>

    <!-- Table for Books -->
    <h2 style="text-align: center; margin-bottom: 10px;">Books List (No of Books as ID)</h2>
    <table class="book-table">
        <thead>
            <tr>
                <th>Book ID</th>
                <th>Title</th>
            </tr>
        </thead>
        <tbody id="book-table-body">
            <tr>
                <td colspan="2">Loading...</td>
            </tr>
        </tbody>
    </table>

    <script>
        // Fetch Metrics Data
        fetch('book_inventory_report.php?type=metrics')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-books').textContent = data.totalBooks;
                document.getElementById('unique-authors').textContent = data.uniqueAuthors;
                document.getElementById('recent-date').textContent = data.mostRecentDate;
                document.getElementById('common-author').textContent = data.mostCommonAuthor;
            })
            .catch(error => console.error('Error fetching metrics:', error));

        // Fetch Books Data
        fetch('book_inventory_report.php?type=books')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('book-table-body');
                tableBody.innerHTML = ''; // Clear placeholder rows

                // Populate the table with book data
                data.forEach(book => {
                    const row = `
                        <tr>
                            <td>${book.BookId}</td>
                            <td>${book.Title}</td>
                        </tr>
                    `;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });
            })
            .catch(error => console.error('Error fetching books:', error));
    </script>
</body>
</html>
