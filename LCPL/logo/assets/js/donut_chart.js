let pieChart;
$(document).ready(function() {
    // Define today's date in the format YYYY-MM-DD
    const today = new Date().toISOString().split('T')[0];

    // Set the date picker to today's date
    $('#datePicker').val(today);

    // Initialize pieChart (Education Levels) if the canvas exists
    const educationCtx = document.getElementById('donutChart')?.getContext('2d');
    
    if (educationCtx) {
        pieChart = new Chart(educationCtx, {
            type: 'pie',
            data: {
                labels: ['Elementary', 'High School', 'Senior High School', 'College', 'Postgraduate', 'OSY'],
                datasets: [{
                    data: [0, 0, 0, 0, 0, 0],  // Initial data, including OSY count
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)' // Color for OSY
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)' // Border color for OSY
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });
    } else {
        console.warn("Canvas element with id 'donutChart' not found.");
    }

    // Function to fetch education data
    function fetchEducationData(date) {
        $.ajax({
            url: 'fetch_education_data.php',
            type: 'GET',
            data: { date: date },
            dataType: 'json',
            success: function(response) {
                if (response) {
                    // Update chart data with counts for each education level, including OSY
                    pieChart.data.datasets[0].data = [
                        response.ElementaryCount,
                        response.HighschoolCount,
                        response.ShSCount,
                        response.CollegeCount,
                        response.PostGradCount,
                        response.OsyCount // Added OSY count
                    ];
                    pieChart.update(); // Update the chart
                } else {
                    console.error('No data available for this date'); // Log no data message
                }
            },
            error: function() {
                console.error('Error fetching data'); // Log error message
            }
        });
    }

    // Fetch education data automatically for today's date
    fetchEducationData(today);

    // Event listener for button click
    $('#fetchData').on('click', function() {
        const selectedDate = $('#datePicker').val();
        fetchEducationData(selectedDate); // Fetch data for the selected date
    });
});
