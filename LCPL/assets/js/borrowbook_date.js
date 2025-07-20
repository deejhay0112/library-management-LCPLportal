$(document).ready(function() {
    const today = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD
    $('#header-date-picker').val(today); // Set date picker to today's date

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

    // Fetch data on page load
    fetchBorrowedBooks(today);

    // Fetch data on date picker change
    $('#header-date-picker').on('change', function() {
        const selectedDate = $(this).val();
        fetchBorrowedBooks(selectedDate);
    });
});
