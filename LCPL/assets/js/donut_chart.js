$(document).ready(function() {
    // Set the date input to today's date for header-date-picker
    const today = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD
    $('#header-date-picker').val(today); // Set the date picker to today for charts

    // Initialize pieChart (Education Levels)
    const educationCtx = document.getElementById('donutChart')?.getContext('2d');
    let pieChart;
    
    if (educationCtx) {
        pieChart = new Chart(educationCtx, {
            type: 'pie',
            data: {
                labels: ['Elementary', 'High School', 'Senior High School', 'College', 'Postgraduate', 'OSY'],
                datasets: [{
                    data: [0, 0, 0, 0, 0, 0], // Initial data, including OSY count
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',   // Bright Red for Elementary
                        'rgba(54, 162, 235, 0.7)',   // Bright Blue for High School
                        'rgba(255, 206, 86, 0.7)',   // Bright Yellow for Senior High School
                        'rgba(75, 192, 192, 0.7)',   // Bright Green for College
                        'rgba(153, 102, 255, 0.7)',  // Bright Purple for Postgraduate
                        'rgba(255, 159, 64, 0.7)'    // Bright Orange for OSY
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',    // Bright Red border for Elementary
                        'rgba(54, 162, 235, 1)',    // Bright Blue border for High School
                        'rgba(255, 206, 86, 1)',    // Bright Yellow border for Senior High School
                        'rgba(75, 192, 192, 1)',    // Bright Green border for College
                        'rgba(153, 102, 255, 1)',   // Bright Purple border for Postgraduate
                        'rgba(255, 159, 64, 1)'     // Bright Orange border for OSY
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Visitor Education Distribution', // Title for the chart
                        font: {
                            size: 18,  // Use the same size as in the gender chart
                            weight: 'bold'  // Make title bold, same as gender chart
                        },
                        color: '#ffffff', // Set title color to white, same as gender chart
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    },
                    legend: {
                        labels: {
                            color: '#ffffff',  // Set legend labels to white
                            font: {
                                size: 14, // Adjust font size to match gender chart
                                weight: 'bold'  // Make the font bold like in gender chart
                            }
                        }
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
    } else {
        console.warn("Canvas element with id 'donutChart' not found.");
    }

    // Function to update the existing pie chart (Education Levels)
    function updateEducationChart(date) {
        $.ajax({
            url: 'fetch_education_data.php',
            type: 'GET',
            data: { date: date }, // Pass selected date
            dataType: 'json',
            success: function(response) {
                if (response) {
                    pieChart.data.datasets[0].data = [
                        response.ElementaryCount,
                        response.HighschoolCount,
                        response.ShSCount,
                        response.CollegeCount,
                        response.PostGradCount,
                        response.OsyCount // Include OSY count
                    ];
                    pieChart.update(); // Update the chart with new data
                } else {
                    console.warn("Response from 'fetch_education_data.php' is empty or undefined.");
                }
            },
            error: function() {
                console.error('Error fetching education data');
            }
        });
    }

    // Set the initial data for today's date on page load
    updateEducationChart(today);

    // Event listener for date picker change (for education chart)
    $('#header-date-picker').on('change', function() {
        const selectedDate = $(this).val();
        updateEducationChart(selectedDate); // Update the pie chart when date is changed
    });
});
