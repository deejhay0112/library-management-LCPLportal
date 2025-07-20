$(document).ready(function() {
    // Set the date input to today's date for header-date-picker, borrow-date, and search-date
    const today = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD
    $('#header-date-picker').val(today); // Set the date picker to today for charts
    $('#borrow-date').val(today); // Set the date picker for borrowed books
    $('#search-date').val(today); // Set the date picker for visitor data

    // Function to update the existing pie chart (Education Levels)
   function updateEducationChart(date) {
    console.log('Updating education chart for date:', date); // Debug log
    $.ajax({
        url: 'fetch_education_data.php',
        type: 'GET',
        data: { date: date },
        dataType: 'json',
        success: function(response) {
            console.log('Education chart response:', response);
            if (response) {
                pieChart.data.datasets[0].data = [
                    response.ElementaryCount,
                    response.HighschoolCount,
                    response.ShSCount,
                    response.CollegeCount,
                    response.PostGradCount
                ];
                pieChart.update(); // Update chart
            }
        },
        error: function(xhr, status, error) {
            console.error('Error updating education chart:', status, error);
        }
    });
}

    // Function to update the existing bar chart (Gender Distribution)
    function updateGenderChart(date) {
        fetch(`fetch_gender_data.php?date=${date}`)
            .then(response => response.json())
            .then(data => {
                const maleCount = data.male || 0;
                const femaleCount = data.female || 0;
                const lgbtCount = data.lgbt || 0;

                genderChart.data.datasets[0].data = [maleCount];  // Update male count
                genderChart.data.datasets[1].data = [femaleCount];  // Update female count
                genderChart.data.datasets[2].data = [lgbtCount];  // Update LGBTQ count
                genderChart.update();  // Refresh the chart with the new data
            })
            .catch(error => console.error('Error fetching gender data:', error));
    }

    // Function to fetch borrowed books
    function fetchBorrowedBooks(date) {
        console.log('Fetching borrowed books for date:', date);
        $('#borrowed-count').text('Loading...');

        $.ajax({
            url: 'fetch_borrowed_books.php',
            type: 'GET',
            data: { date: date },
            dataType: 'json',
            success: function(response) {
                console.log('Server response:', response);
                if (response.success && response.total_borrowed !== undefined) {
                    $('#borrowed-count').text(response.total_borrowed);
                } else {
                    $('#borrowed-count').text('0');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching borrowed books:', error);
                $('#borrowed-count').text('Error fetching data');
            }
        });
    }


    // Function to fetch total visitors for a given date
    function fetchTotalVisitors(date) {
        fetch(`fetch_visitors.php?date=${date}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('visitor-count').textContent = data.total_visitors || 0;
            })
            .catch(error => {
                document.getElementById('visitor-count').textContent = '0';
            });
    }
    function updateEducationChart(date) {
        if (!pieChart) {
            console.warn('pieChart is not initialized or is undefined.');
            return;
        }
    
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
                        response.PostGradCount
                    ];
                    pieChart.update();
                } else {
                    console.warn("Response from 'fetch_education_data.php' is empty or undefined.");
                }
            },
            error: function() {
                console.error('Error fetching education data');
            }
        });
    }
    
    // Function to update all charts and borrowed books based on the selected date
    function updateChartsAndBooksForDate(date) {
        updateEducationChart(date);
        updateGenderChart(date);
        fetchBorrowedBooks(date);
        fetchTotalVisitors(date);
    }

    // Set the initial data for today's date on page load
    updateChartsAndBooksForDate(today);

    // Event listener for date picker change
    $('#header-date-picker, #borrow-date, #search-date').on('change', function() {
        const selectedDate = $(this).val();
        updateChartsAndBooksForDate(selectedDate);
    });

    // Event listener for fetch buttons
    $('#fetch-chart-data').on('click', function() {
        const selectedDate = $('#chart-date').val();
        if (selectedDate) {
            updateGenderChart(selectedDate);
        }
    });

    $('#fetch-borrowed-data').on('click', function() {
        const selectedDate = $('#borrow-date').val();
        fetchBorrowedBooks(selectedDate);
    });

    $('#fetch-data').on('click', function() {
        const selectedDate = $('#search-date').val();
        fetchTotalVisitors(selectedDate);
    });
});
