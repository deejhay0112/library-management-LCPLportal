let visitorChartInstance = null; // Variable to hold the Chart instance

const ctx = document.getElementById('visitorChart').getContext('2d');

// Function to initialize or update the chart
function initializeChart(visitorData, maleData, femaleData, lgbtData) {
    if (visitorChartInstance) {
        visitorChartInstance.destroy(); // Destroy the previous chart instance
    }

    visitorChartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [
                {
                    label: 'Male',
                    data: maleData,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Female',
                    data: femaleData,
                    backgroundColor: 'rgba(255, 99, 132, 0.7)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },
                {
                    label: 'LGBT',
                    data: lgbtData,
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Total',
                    data: visitorData,
                    backgroundColor: 'rgba(153, 102, 255, 0.3)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Keep chart stable
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#FFFFFF',
                        font: {
                            size: 14,
                            family: 'Poppins',
                            weight: 'bold'
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Monthly Visitor Distribution',
                    color: '#FFFFFF',
                    font: {
                        size: 18,
                        family: 'Poppins',
                        weight: 'bold'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: { size: 14, family: 'Poppins', weight: 'bold' },
                    bodyFont: { size: 12, family: 'Poppins' },
                    bodyColor: '#FFFFFF',
                    titleColor: '#FFFFFF',
                    borderColor: '#CCCCCC',
                    borderWidth: 1
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    stacked: true,
                    grid: { color: 'rgba(255, 255, 255, 0.1)' },
                    ticks: {
                        color: '#FFFFFF',
                        font: { family: 'Poppins', size: 12 }
                    }
                },
                x: {
                    stacked: true,
                    grid: { display: false },
                    ticks: {
                        color: '#FFFFFF',
                        font: { family: 'Poppins', size: 12 }
                    }
                }
            }
        }
    });
}

// Fetch the visitor data dynamically based on the selected year
function fetchVisitorData(year) {
    fetch(`fetch_visitor_data.php?year=${year}`)
        .then(response => response.json())
        .then(data => {
            if (data.visitors && data.male && data.female && data.lgbt) {
                initializeChart(data.visitors, data.male, data.female, data.lgbt); // Update the chart
            } else {
                console.error('No visitor data found:', data.error || 'Invalid data format');
            }
        })
        .catch(error => console.error('Error fetching visitor data:', error));
}

// Event listener for Fetch Data button
document.getElementById('fetch-visitor-data').addEventListener('click', () => {
    const year = document.getElementById('chart-year').value;
    if (year) {
        fetchVisitorData(year);
    } else {
        alert('Please select a year.');
    }
});

// Initial Fetch for the current year
document.addEventListener('DOMContentLoaded', () => {
    const currentYear = new Date().getFullYear();
    document.getElementById('chart-year').value = currentYear;
    fetchVisitorData(currentYear);
});
