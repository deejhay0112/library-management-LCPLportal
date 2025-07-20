// Function to fetch total visitors for a given date
function fetchTotalVisitors(date) {
    fetch(`fetch_visitors.php?date=${date}`) // Fetch from your PHP script
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            // Update visitor count
            const visitorCountElement = document.getElementById('visitor-count');
            if (visitorCountElement) {
                visitorCountElement.textContent = data.total_visitors !== undefined ? data.total_visitors : 0;
            } else {
                console.warn('Element #visitor-count not found in the DOM.');
            }
        })
        .catch(error => {
            console.error('Error fetching total visitors:', error);
            document.getElementById('visitor-count').textContent = 0; // Reset count on error
        });
}

// Function to initialize the dashboard with today's date
function initializeDashboard() {
    const today = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD

    // Set the date picker's value to today's date
    const datePickers = ['#header-date-picker', '#search-date']; // Include all relevant date pickers
    datePickers.forEach(picker => {
        if (document.querySelector(picker)) {
            document.querySelector(picker).value = today;
        }
    });

    // Fetch total visitors for today's date
    fetchTotalVisitors(today);
}

// Document Ready Handler
$(document).ready(function() {
    initializeDashboard(); // Initialize dashboard on page load

    // Event listener for changes in the dashboard date picker
    $('#header-date-picker').on('change', function() {
        const selectedDate = $(this).val();
        fetchTotalVisitors(selectedDate); // Fetch visitors for the selected date
    });

    // Event listener for other date pickers (optional)
    $('#search-date').on('change', function() {
        const selectedDate = $(this).val();
        fetchTotalVisitors(selectedDate); // Fetch visitors for the selected date
    });
});
