var monthlyPredVisitorCtx = document.getElementById('monthlypredVisitorChart').getContext('2d');  // Unique context variable
var monthlyVisitorChart = new Chart(monthlyPredVisitorCtx, {
    type: 'line',  // Line chart for monthly data
    data: {
        labels: [],  // Empty labels array to be populated dynamically
        datasets: [{
            label: 'Predicted Visitor Count (Monthly)',
            data: [],  // Empty data array to be populated dynamically
            backgroundColor: 'rgba(54, 162, 235, 0.2)',  // Light blue background
            borderColor: 'rgba(54, 162, 235, 1)',  // Blue border for line
            borderWidth: 2,
            fill: false,  // Do not fill under the line
            tension: 0.1  // Smooth curve for the line chart
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true  // Ensure the y-axis starts at 0
            },
            x: {
                ticks: {
                    autoSkip: false  // Show all month labels
                }
            }
        }
    }
});

// Function to update the chart with new data from the Flask API
function updateMonthlyVisitorChart() {
    console.log("Fetching data from /monthly_visitor_forecast");
    fetch('http://127.0.0.1:5000/monthly_visitor_forecast')  // Flask API for monthly forecast
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok " + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            console.log("Data received:", data);  // Log the received data to ensure it's correct
            monthlyVisitorChart.data.labels = data.labels;  // Update chart labels (months)
            monthlyVisitorChart.data.datasets[0].data = data.visitor_count;  // Update chart data (monthly visitor counts)

            // Re-render the chart with the new data
            monthlyVisitorChart.update();
        })
        .catch(error => {
            console.error('Error fetching monthly visitor forecast data:', error);  // Log errors
        });
}

setInterval(updateMonthlyVisitorChart, 5000);
updateMonthlyVisitorChart();
