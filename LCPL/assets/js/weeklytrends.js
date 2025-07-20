let chartInstance = null;

function formatToShortMonthDayYear(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleString('default', { month: 'short', day: 'numeric', year: 'numeric' });
}

// Enable or disable date filter based on trend selection
function handleTrendChange() {
    const trend = document.getElementById('trendSelect').value;
    const startDate = document.getElementById('startDate');
    const endDate = document.getElementById('endDate');
    const filterBtn = document.getElementById('filterBtn');

    const isDaily = trend === 'daily';
    startDate.disabled = !isDaily;
    endDate.disabled = !isDaily;
    filterBtn.disabled = !isDaily;

    fetchTrends();
}

// Fetch totals for the cards
function fetchTotals() {
    fetch('get_trends.php?trend=last_week_total')
        .then(response => response.json())
        .then(data => document.getElementById('lastWeekTotal').innerText = data.total_visits || 0);

    fetch('get_trends.php?trend=monthly_total')
        .then(response => response.json())
        .then(data => document.getElementById('monthlyTotal').innerText = data.total_visits || 0);

    fetch('get_trends.php?trend=last_day_total')
        .then(response => response.json())
        .then(data => document.getElementById('lastDayTotal').innerText = data.total_visits || 0);
}

// Fetch trends dynamically for the chart
function fetchTrends() {
    const trend = document.getElementById('trendSelect').value;
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;

    let url = `get_trends.php?trend=${trend}`;
    if (trend === 'daily' && startDate && endDate) {
        if (startDate > endDate) {
            document.getElementById('notification').innerText = "Start date cannot be after end date.";
            return;
        }
        url += `&start_date=${startDate}&end_date=${endDate}`;
    }

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const labels = data.map(row => formatToShortMonthDayYear(row.visit_date || row.visit_week || row.visit_month));
            const values = data.map(row => row.total_visits);
            updateChart(labels, values);
        });
}

function updateChart(labels, values) {
    const ctx = document.getElementById('visitChart').getContext('2d');
    if (chartInstance) chartInstance.destroy();

    chartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Visits',
                data: values,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.2)',
                borderWidth: 2,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

// On page load
window.onload = function () {
    fetchTotals();
    handleTrendChange();
};
