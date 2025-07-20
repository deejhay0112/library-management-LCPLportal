$(document).ready(function() {
    // Set the date input to today's date
    const today = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD
    $('#borrow-date').val(today);

    // Function to fetch borrowed books
    function fetchBorrowedBooks(date) {
        $('#borrowed-count').text('Loading...'); // Show loading message

        $.ajax({
            url: 'fetch_borrowed_books.php',
            type: 'GET',
            data: { date: date }, // Pass the selected date
            dataType: 'json',
            success: function(response) {
                // Update the display based on the response
                if (response.success) {
                    $('#borrowed-count').text(response.total_borrowed); // Show total borrowed
                } else {
                    $('#borrowed-count').text('Error fetching data');
                }
            },
            error: function() {
                $('#borrowed-count').text('Error fetching data');
            }
        });
    }

    // Fetch borrowed books automatically for today's date
    fetchBorrowedBooks(today);

    // Event listener for button click
    $('#fetch-borrowed-data').on('click', function() {
        const selectedDate = $('#borrow-date').val();
        fetchBorrowedBooks(selectedDate); // Fetch data for the selected date
    });
});
