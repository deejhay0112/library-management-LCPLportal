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

        /* Body Styling */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to bottom, #f9f9f9, #e9ecef);
            color: #333;
            line-height: 1.6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        /* Visitor Trends Container */
        .visitor-trends-container {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Main Title */
        h1 {
            text-align: center;
            font-size: 2.5em;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        /* Controls Section */
        .controls {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .controls label, .controls input, .controls select, .controls button {
            font-size: 1.1em;
            padding: 8px;
        }

        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        button:hover:not(:disabled) {
            background: #0056b3;
            transform: scale(1.05);
        }

        /* Cards Section */
        .card-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            width: 300px;
            transition: 0.3s ease;
        }

        .card h3 {
            color: #555;
        }

        .card p {
            font-size: 2em;
            color: #007bff;
        }

        /* Chart Section */
        .chart-container {
            overflow-x: auto;
            margin: 0 auto;
            background: white;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            min-height: 500px;
        }

        #visitChart {
            min-height: 500px;
            width: 100%;
        }

        /* Notification */
        #notification {
            text-align: center;
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <!-- Visitor Trends Container -->
    <div class="visitor-trends-container">
        <h1>Visitor Trends</h1>

        <!-- Controls -->
        <div class="controls">
            <label for="trendSelect">Select Trend:</label>
            <select id="trendSelect" onchange="handleTrendChange()">
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
            </select>

            <label for="startDate">Start Date:</label>
            <input type="date" id="startDate" disabled>

            <label for="endDate">End Date:</label>
            <input type="date" id="endDate" disabled>

            <button id="filterBtn" onclick="fetchTrends()" disabled>Filter</button>
        </div>

        <!-- Notification -->
        <div id="notification"></div>

        <!-- Cards -->
        <div class="card-container">
            <div class="card">
                <h3>Last Week's Total Visits</h3>
                <p id="lastWeekTotal">0</p>
            </div>
            <div class="card">
                <h3>Last Month's Visits</h3>
                <p id="monthlyTotal">0</p>
            </div>
            <div class="card">
                <h3>Yesterday's Total Visits</h3>
                <p id="lastDayTotal">0</p>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="chart-container">
            <canvas id="visitChart"></canvas>
        </div>
    </div>

  <script>
    let chartInstance = null;

    // Shorten to Month, Day, and Year (e.g., "Jan 1, 2024")
    function formatToShortMonthDayYear(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleString('default', { month: 'short', day: 'numeric', year: 'numeric' });
        // Example Output: "Jan 1, 2024"
    }

    // Enable or disable date filter based on trend selection
    function handleTrendChange() {
        const trend = document.getElementById('trendSelect').value;
        const startDate = document.getElementById('startDate');
        const endDate = document.getElementById('endDate');
        const filterBtn = document.getElementById('filterBtn');

        const isDaily = trend === 'daily';
        startDate.disabled = !isDaily;
        endDate.disabled = !isDaily;
        filterBtn.disabled = !isDaily;

        fetchTrends(); // Fetch trends dynamically
    }

    // Fetch totals for the cards
    function fetchTotals() {
        fetch('get_trends.php?trend=last_week_total')
            .then(response => response.json())
            .then(data => document.getElementById('lastWeekTotal').innerText = data.total_visits || 0);

        fetch('get_trends.php?trend=monthly_total')
            .then(response => response.json())
            .then(data => document.getElementById('monthlyTotal').innerText = data.total_visits || 0);

        fetch('get_trends.php?trend=last_day_total')
            .then(response => response.json())
            .then(data => document.getElementById('lastDayTotal').innerText = data.total_visits || 0);
    }

    // Fetch trends dynamically for the chart
    function fetchTrends() {
        const trend = document.getElementById('trendSelect').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        let url = `get_trends.php?trend=${trend}`;
        if (trend === 'daily' && startDate && endDate) {
            if (startDate > endDate) {
                document.getElementById('notification').innerText = "Start date cannot be after end date.";
                return;
            }
            url += `&start_date=${startDate}&end_date=${endDate}`;
        }

        fetch(url)
            .then(response => response.json())
            .then(data => {
                const labels = data.map(row => formatToShortMonthDayYear(row.visit_date || row.visit_week || row.visit_month));
                const values = data.map(row => row.total_visits);
                updateChart(labels, values);
            })
            .catch(error => console.error("Error fetching trends:", error));
    }

    // Update Chart
    function updateChart(labels, values) {
        const ctx = document.getElementById('visitChart').getContext('2d');
        if (chartInstance) chartInstance.destroy();

        chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Visits',
                    data: values,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: { display: true, text: 'Date' }
                    },
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Total Visits' }
                    }
                }
            }
        });
    }

    // Clear Chart when no data is available
    function clearChart() {
        if (chartInstance) chartInstance.destroy();
        chartInstance = null;
    }

    // On page load
    window.onload = function () {
        fetchTotals();
        handleTrendChange();
    };
</script>

</body>
</html>
