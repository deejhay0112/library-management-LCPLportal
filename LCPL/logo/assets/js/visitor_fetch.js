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
            document.getElementById('visitor-count').textContent = data.total_visitors !== undefined ? data.total_visitors : 0;
        })
        .catch(error => {
            console.error('Error fetching total visitors:', error);
            document.getElementById('visitor-count').textContent = 0; // Reset count on error
        });
}

// Call the function when the page loads to set today's date
window.onload = function() {
    const today = new Date(); // Get today's date
    const formattedDate = today.toISOString().split('T')[0]; // Format: YYYY-MM-DD

    // Set the search date input's value to today's date
    document.getElementById('search-date').value = formattedDate; 

    // Fetch total visitors for today's date
    fetchTotalVisitors(formattedDate);
};

// Event listener for button click to fetch data for the selected date


