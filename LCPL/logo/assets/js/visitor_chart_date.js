// Fetch available years from the server
    function fetchAvailableYears() {
        fetch('fetch_years.php') // Adjust to the correct path of your PHP file
            .then(response => response.json())
            .then(data => {
                const yearDropdown = document.getElementById('chart-year');
                yearDropdown.innerHTML = ''; // Clear any previous options
                
                if (data.length > 0) {
                    // Create an option for each year
                    data.forEach(year => {
                        const option = document.createElement('option');
                        option.value = year;
                        option.textContent = year;
                        yearDropdown.appendChild(option);
                    });
                } else {
                    // If no data is available, show a "No years available" message
                    const option = document.createElement('option');
                    option.value = "";
                    option.textContent = "No years available";
                    yearDropdown.appendChild(option);
                }
            })
            .catch(error => {
                console.error('Error fetching years:', error);
            });
    }

    // Call the function to fetch years when the page loads
    document.addEventListener('DOMContentLoaded', fetchAvailableYears);