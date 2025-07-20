// Function to fetch user data based on a specific date
function fetchDataByDate(date) {
    fetch(`fetch_data_by_date.php?date=${date}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data) {
                document.getElementById('visitor-count').textContent = data.total_visitors || 0;
                document.getElementById('male-count').textContent = data.total_male_users || 0;
                document.getElementById('female-count').textContent = data.total_female_users || 0;
                document.getElementById('lgbt-count').textContent = data.total_lgbt_users || 0;
            } else {
                console.error('Data not found in response:', data);
            }
        })
        .catch(error => {
            console.error('Error fetching data by date:', error);
        });
}




