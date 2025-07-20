<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Trends</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f5f9; /* Light gray background */
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* Peak Hours Container */
        .peak-hours-chart-section {
            background-color: #1e293b; /* Dark blue */
            border-radius: 16px; /* Rounded corners */
            padding: 30px; /* Inner spacing */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3); /* Depth effect */
            margin: 20px auto; /* Centered */
            max-width: 900px; /* Responsive width */
            display: flex;
            flex-direction: column;
            gap: 20px;
            text-align: center; /* Center align title */
        }

        /* Section Title */
        .peak-hours-chart-section h3 {
            font-size: 2rem;
            color: #38bdf8; /* Light blue accent */
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        /* Chart Container */
        .chart-container {
            background-color: #0f172a; /* Slightly darker background for contrast */
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #peakHoursChart {
            width: 100%;
            height: 400px; /* Default chart height */
        }

        /* Responsive */
        @media (max-width: 768px) {
            .peak-hours-chart-section {
                padding: 20px;
            }

            .peak-hours-chart-section h3 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<section class="status-cards">
    <h3 class="text-xl font-semibold mb-4">Book Return Status Summary</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <!-- Pending Card -->
        <div class="bg-yellow-400 text-white rounded-lg shadow p-4">
            <h4 class="text-lg font-bold">Pending</h4>
            <p id="pendingCount" class="text-2xl font-semibold">0</p>
        </div>

        <!-- Returned Card -->
        <div class="bg-green-400 text-white rounded-lg shadow p-4">
            <h4 class="text-lg font-bold">Returned</h4>
            <p id="returnedCount" class="text-2xl font-semibold">0</p>
        </div>

        <!-- Late Return Card -->
        <div class="bg-red-400 text-white rounded-lg shadow p-4">
            <h4 class="text-lg font-bold">Late Return</h4>
            <p id="lateReturnCount" class="text-2xl font-semibold">0</p>
        </div>

        <!-- Not Returned Card -->
        <div class="bg-gray-500 text-white rounded-lg shadow p-4">
            <h4 class="text-lg font-bold">Not Returned</h4>
            <p id="notReturnedCount" class="text-2xl font-semibold">0</p>
        </div>
    </div>
</section>
 <div class="peak-hours-chart-section">
        <h3>Peak Hours Comparison</h3>
        <div class="chart-container">
            <canvas id="peakHoursChart"></canvas>
        </div>
    </div>
    <button onclick="fetchData('daily')">Daily</button>
    <button onclick="fetchData('weekly')">Weekly</button>
    <button onclick="fetchData('monthly')">Monthly</button>

    <!-- Line Chart -->
    <canvas id="lineChart" width="800" height="400"></canvas>
    
  <script>
    document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById("peakHoursChart").getContext('2d');
            const peakHoursChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ["8 AM", "9 AM", "10 AM", "11 AM", "12 PM", "1 PM", "2 PM", "3 PM", "4 PM", "5 PM", "6 PM"],
                    datasets: [{
                        label: "Visitors Count per Hour",
                        data: [15, 25, 30, 45, 60, 70, 55, 65, 85, 90, 75],
                        borderColor: '#38bdf8',
                        backgroundColor: 'rgba(56, 189, 248, 0.2)',
                        borderWidth: 3,
                        pointBackgroundColor: '#38bdf8',
                        pointBorderColor: '#fff',
                        pointRadius: 5,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Hour of the Day',
                                color: '#cbd5e1',
                                font: { size: 14 }
                            },
                            ticks: {
                                color: '#94a3b8'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Number of Visitors',
                                color: '#cbd5e1',
                                font: { size: 14 }
                            },
                            beginAtZero: true,
                            ticks: {
                                color: '#94a3b8'
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Peak Hours of Visitor Comparison',
                            color: '#e2e8f0',
                            font: { size: 18, weight: '600' }
                        },
                        legend: {
                            labels: {
                                color: '#e2e8f0'
                            }
                        }
                    }
                }
            });
        });
</script>
<script>
 let chart;
//topborrowed
// Function to fetch data and render the chart
function fetchData(filter) {
    fetch(top_borrowed_books.php?filter=${filter})
        .then(response => response.json())
        .then(data => {
            const labels = [];
            const borrowedCounts = [];

            // Process fetched data
            data.forEach(item => {
                labels.push(${item.Title} (${item.BorrowDate}));
                borrowedCounts.push(item.BorrowedCount);
            });

            renderChart(labels, borrowedCounts, filter);
        });
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
                label: Top Borrowed Books (${filter}),
                data: borrowedCounts,
                borderColor: 'rgba(54, 162, 235, 1)',  // Line color
                backgroundColor: 'rgba(54, 162, 235, 0.2)', // Gradient fill color
                borderWidth: 2,
                pointBackgroundColor: 'white', // Point color
                pointRadius: 5, // Size of data points
                tension: 0.4, // Smooth line
                fill: true // Enable gradient fill
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Top Borrowed Books Over Time',
                    font: {
                        size: 18,
                        weight: 'bold',
                        family: 'Arial'
                    },
                    color: '#333' // Dark text
                },
                legend: {
                    labels: {
                        font: {
                            size: 12
                        },
                        color: '#333'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 12 },
                    padding: 10,
                    cornerRadius: 5
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Books (Borrow Date)',
                        font: { size: 14, weight: 'bold' },
                        color: '#555'
                    },
                    ticks: {
                        font: { size: 12 },
                        color: '#666'
                    },
                    grid: {
                        color: 'rgba(200, 200, 200, 0.2)'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Borrow Count',
                        font: { size: 14, weight: 'bold' },
                        color: '#555'
                    },
                    ticks: {
                        font: { size: 12 },
                        color: '#666'
                    },
                    grid: {
                        color: 'rgba(200, 200, 200, 0.2)'
                    },
                    beginAtZero: true
                }
            }
        }
    });
}

// Fetch initial data (daily)
fetchData('daily');
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
</body>
</html>
