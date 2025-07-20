<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Inventory Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
/* General Styling */
/* General Styling */
body {
    margin: 0;
    padding: 0;
    font-family: 'Poppins', sans-serif;
    background-color: #0f172a;
    color: #f1f5f9;
}

/* Top Borrowed Books Container */
    .container {
            width: 80%;
            margin: 0 auto;
        }

        .combined-chart-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .education-chart-section {
            width: 45%;
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .education-chart-section h3 {
            text-align: center;
            color: #333;
        }

        .chart-container {
            width: 100%;
            padding-top: 20px;
            text-align: center;
        }

        canvas {
            max-width: 100%;
            height: auto;
        }

    </style>
</head>
<div class="top-borrowed-books-container">
    <!-- Title -->
    <h1>Top Borrowed Books</h1>

    <!-- Card Section -->
    <div class="card-container">
        <!-- Pending Card -->
        <div class="card bg-yellow-400 text-white">
            <h3>Pending</h3>
            <p id="pendingCount">0</p>
        </div>

        <!-- Returned Card -->
        <div class="card bg-green-400 text-white">
            <h3>Returned</h3>
            <p id="returnedCount">0</p>
        </div>

        <!-- Late Return Card -->
        <div class="card bg-red-400 text-white">
            <h3>Late Return</h3>
            <p id="lateReturnCount">0</p>
        </div>

        <!-- Not Returned Card -->
        <div class="card bg-gray-500 text-white">
            <h3>Not Returned</h3>
            <p id="notReturnedCount">0</p>
        </div>
    </div>

    <!-- Controls Section -->
    <div class="controls">
        <button onclick="fetchData('daily')">Daily</button>
        <button onclick="fetchData('weekly')">Weekly</button>
        <button onclick="fetchData('monthly')">Monthly</button>
    </div>

    <!-- Chart Section -->
    <div class="chart-container">
        <canvas id="lineChart"></canvas>
    </div>
</div>

 <div class="container">
        <!-- Combined Chart Container -->
        <div class="combined-chart-container">
            <!-- Education Chart Section -->
            <div class="education-chart-section">
                <h3>Occupation Distribution</h3>
                <div class="chart-container">
                    <canvas id="occupationChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- Controls Section -->








    <script>

       let chart;

// Function to fetch data and render the chart
function fetchData(filter) {
    fetch(`top_borrowed_books.php?filter=${filter}`) // Added quotes to fetch URL
        .then(response => response.json())
        .then(data => {
            const labels = [];
            const borrowedCounts = [];

            // Process fetched data
            data.forEach(item => {
                labels.push(`${item.Title} (${item.BorrowDate})`); // Fixed template literal
                borrowedCounts.push(item.BorrowedCount);
            });

            renderChart(labels, borrowedCounts, filter);
        })
        .catch(error => console.error("Error fetching data:", error));
}

// Function to render the line chart
function renderChart(labels, borrowedCounts, filter) {
    const ctx = document.getElementById('lineChart').getContext('2d');

    // Destroy the previous chart instance
    if (chart) {
        chart.destroy();
    }

    chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: `Top Borrowed Books (${filter})`, // Fixed template literal
                data: borrowedCounts,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderWidth: 2,
                pointBackgroundColor: 'white',
                pointRadius: 5,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Top Borrowed Books Over Time',
                    font: { size: 18, weight: 'bold', family: 'Arial' },
                    color: '#333'
                }
            },
            scales: {
                x: {
                    title: { display: true, text: 'Books (Borrow Date)' },
                    ticks: { color: '#666' },
                    grid: { color: 'rgba(200, 200, 200, 0.2)' }
                },
                y: {
                    title: { display: true, text: 'Borrow Count' },
                    ticks: { beginAtZero: true, color: '#666' },
                    grid: { color: 'rgba(200, 200, 200, 0.2)' }
                }
            }
        }
    });
}

// Fetch initial data (daily)
fetchData('daily');


    </script>
    <script>
 
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    fetch('fetch_return_status.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error fetching data:", data.error);
                return;
            }

            // Update the counts in the cards
            document.getElementById('pendingCount').textContent = data.pending;
            document.getElementById('returnedCount').textContent = data.returned;
            document.getElementById('lateReturnCount').textContent = data.late_return;
            document.getElementById('notReturnedCount').textContent = data.not_returned;
        })
        .catch(error => console.error("Error fetching return status counts:", error));
});
</script>
<script>
       fetch('Occupation.php')
    .then(response => response.json())
    .then(data => {
        // Extract the data from the response
        if (data.error) {
            console.error(data.error);
            return;
        }

        // Set up the pie chart data
        var ctx = document.getElementById('occupationChart').getContext('2d');
        var occupationChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Employed', 'Unemployed', 'Self-Employed', 'Students'],
                datasets: [{
                    label: 'User Occupations',
                    data: [data.employed, data.unemployed, data.self_employed, data.students],
                    backgroundColor: ['#36A2EB', '#FF6384', '#FFCD56', '#4BC0C0'],
                    borderColor: '#fff',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });
    })
    .catch(error => {
        console.error('Error fetching data:', error);
    });

    </script>
</body>
</html>
