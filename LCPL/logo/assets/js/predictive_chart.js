document.addEventListener('DOMContentLoaded', () => {
    // Initialize forecast chart if canvas is found
    const forecastCanvas = document.getElementById('forecastVisitorChart');
    if (forecastCanvas) {
        const forecastCtx = forecastCanvas.getContext('2d');
        var forecastVisitorChart = new Chart(forecastCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Predicted Visitor Count',
                    data: [],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    fill: false,
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    },
                    x: {
                        ticks: {
                            autoSkip: true,
                            maxTicksLimit: 10
                        }
                    }
                }
            }
        });

        // Function to update the forecast visitor chart
        function updateForecastChart() {
            console.log("Fetching data from /visitor_forecast");
            fetch('http://127.0.0.1:5000/visitor_forecast')
                .then(response => response.json())
                .then(data => {
                    forecastVisitorChart.data.labels = data.labels;
                    forecastVisitorChart.data.datasets[0].data = data.visitor_count;
                    forecastVisitorChart.update();
                })
                .catch(error => console.error('Error fetching forecast visitor data:', error));
        }

        // Poll the server every 5 seconds for new data
        setInterval(updateForecastChart, 5000);
        updateForecastChart();
    } else {
        console.warn('Forecast Visitor Chart canvas element not found.');
    }

    // Add event listener for the Fetch button if it exists
    const fetchButton = document.getElementById('fetch-data');
    if (fetchButton) {
        fetchButton.addEventListener('click', fetchVisitorCount);
    } else {
        console.warn('Fetch button element not found.');
    }

    // Define fetchVisitorCount function only if search-date exists
    const searchDateInput = document.getElementById('search-date');
    if (searchDateInput) {
        function fetchVisitorCount() {
            const selectedDate = searchDateInput.value;
            fetch('http://127.0.0.1:5000/visitor_forecast')
                .then(response => response.json())
                .then(data => {
                    const dateIndex = data.labels.indexOf(selectedDate);
                    const visitorCountDisplay = document.getElementById('visitor-count');

                    if (visitorCountDisplay && dateIndex !== -1) {
                        visitorCountDisplay.textContent = data.visitor_count[dateIndex];
                    } else if (visitorCountDisplay) {
                        visitorCountDisplay.textContent = 'N/A';
                    }
                })
                .catch(error => console.error('Error fetching visitor count data:', error));
        }
    } else {
        console.warn('Search date input element not found.');
    }
});
