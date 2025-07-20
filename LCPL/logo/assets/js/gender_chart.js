// JavaScript for fetching gender data and creating the chart

let genderChart; // Declare the genderChart variable outside the function

// Function to fetch gender data based on a specific date
function fetchGenderData(date) {
    fetch(`fetch_gender_data.php?date=${date}`) // Pass the selected date to the PHP script
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            const maleCount = data.male || 0;
            const femaleCount = data.female || 0;
            const lgbtCount = data.lgbt || 0;

            // Destroy the previous chart if it exists
            if (genderChart) {
                genderChart.destroy();
            }

            // Create the new bar chart
            const ctx = document.getElementById('genderChart').getContext('2d');
            genderChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Gender'],  // Common label for all genders on the x-axis
                    datasets: [
                        {
                            label: 'Male', // Separate dataset for Male
                            data: [maleCount],
                            backgroundColor: 'rgba(0, 123, 255, 0.6)', // Blue for Male
                            borderColor: 'rgba(0, 123, 255, 1)', // Blue border color for Male
                            borderWidth: 1
                        },
                        {
                            label: 'Female', // Separate dataset for Female
                            data: [femaleCount],
                            backgroundColor: 'rgba(255, 192, 203, 0.6)', // Pink for Female
                            borderColor: 'rgba(255, 192, 203, 1)', // Pink border color for Female
                            borderWidth: 1
                        },
                        {
                            label: 'LGBTQ', // Separate dataset for LGBTQ
                            data: [lgbtCount],
                            backgroundColor: 'rgba(40, 167, 69, 0.6)', // Green for LGBTQ
                            borderColor: 'rgba(40, 167, 69, 1)', // Green border color for LGBTQ
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true // Ensure the y-axis starts at 0
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'User Distribution by Gender'
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error fetching gender data:', error));
}

// Function to set today's date in the date input field
function setTodayDate() {
    const today = new Date().toISOString().split('T')[0]; // Format date as YYYY-MM-DD
    document.getElementById('chart-date').value = today; // Set the input value to today's date
    fetchGenderData(today); // Fetch data for today
}

// Add event listener to fetch button
document.getElementById('fetch-chart-data').addEventListener('click', function() {
    const selectedDate = document.getElementById('chart-date').value;
    if (selectedDate) {
        fetchGenderData(selectedDate);
    } else {
        console.error('No date selected');
    }
});

// Call the function to set today's date and fetch the initial data when the page loads
document.addEventListener('DOMContentLoaded', function() {
    setTodayDate();
});
