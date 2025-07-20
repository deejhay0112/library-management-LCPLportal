// chart.js
const ctx = document.getElementById('visitorChart').getContext('2d');

// Initialize the Chart.js stacked bar chart
const visitorChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'March', 'April', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'],
        datasets: [
            {
                label: 'Male Visitors',
                data: [], // Data will be fetched and populated here
                backgroundColor: 'rgba(0, 123, 255, 0.5)', // Blue for Male Visitors
                borderColor: 'rgba(0, 123, 255, 1)', // Blue border color
                borderWidth: 1
            },
            {
                label: 'Female Visitors',
                data: [], // Data will be fetched and populated here
                backgroundColor: 'rgba(255, 192, 203, 0.5)', // Pink for Female Visitors
                borderColor: 'rgba(255, 192, 203, 1)', // Pink border color
                borderWidth: 1
            },
            {
                label: 'LGBT Visitors',
                data: [], // Data will be fetched and populated here
                backgroundColor: 'rgba(40, 167, 69, 0.5)', // Green for LGBT Visitors
                borderColor: 'rgba(40, 167, 69, 1)', // Green border color
                borderWidth: 1
            },
            {
                label: 'Total Visitors',
                data: [], // Data will be fetched and populated here
                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Light blue for total visitors
                borderColor: 'rgba(75, 192, 192, 1)', // Light blue border color
                borderWidth: 1
            }
        ]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                stacked: true // Enable stacking on the y-axis
            },
            x: {
                stacked: true // Enable stacking on the x-axis
            }
        }
    }
});

// Function to update the chart with fetched data
function updateChart(visitorData, maleData, femaleData, lgbtData) {
    visitorChart.data.datasets[0].data = maleData; // Male Visitors
    visitorChart.data.datasets[1].data = femaleData; // Female Visitors
    visitorChart.data.datasets[2].data = lgbtData; // LGBT Visitors
    visitorChart.data.datasets[3].data = visitorData; // Total Visitors
    visitorChart.update();
}

// Fetch the visitor data dynamically based on the selected year
function fetchVisitorData(year) {
    fetch(`fetch_visitor_data.php?year=${year}`) // Adjust the URL to your PHP script
        .then(response => response.json())
        .then(data => {
            if (data.visitors && data.male && data.female && data.lgbt) {
                updateChart(data.visitors, data.male, data.female, data.lgbt); // Update the chart with visitor data
            } else {
                console.error('No visitor data found:', data.error);
            }
        })
        .catch(error => console.error('Error fetching visitor data:', error));
}

// Event listener for the Fetch Data button
document.getElementById('fetch-visitor-data').addEventListener('click', function() {
    const year = document.getElementById('chart-year').value;
    if (year) {
        fetchVisitorData(year); // Fetch data for the selected year
    } else {
        alert('Please select a year.'); // Alert if no year is selected
    }
});

// Initial fetch can be set if you want to load data for a default year (optional)
document.addEventListener('DOMContentLoaded', function() {
    const defaultYear = new Date().getFullYear(); // Get the current year
    document.getElementById('chart-year').value = defaultYear; // Set it as the default selection
    fetchVisitorData(defaultYear); // Fetch data for the current year initially
});
