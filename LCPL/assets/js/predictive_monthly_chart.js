var monthlyPredVisitorCtx = document.getElementById('monthlypredVisitorChart').getContext('2d');
var monthlyVisitorChart = new Chart(monthlyPredVisitorCtx, {
    type: 'line',
    data: {
        labels: [],
        datasets: [{
            label: 'Predicted Visitor Count (Monthly)',
            data: [],
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2,
            fill: false,
            tension: 0.1
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true },
            x: { ticks: { autoSkip: false } }
        }
    }
});

function updateMonthlyVisitorChart() {
    console.log("Fetching data from Hostinger server");
    fetch('https://lcplportal.net/predictions.json')
        .then(response => response.json())
        .then(data => {
            console.log("Data received:", data);
            const labels = data.map(item => item.MonthYear);
            const visitorCounts = data.map(item => item.PredictedVisitors);
            monthlyVisitorChart.data.labels = labels;
            monthlyVisitorChart.data.datasets[0].data = visitorCounts;
            monthlyVisitorChart.update();
        })
        .catch(error => console.error('Error fetching data:', error));
}

setInterval(updateMonthlyVisitorChart, 5000);
updateMonthlyVisitorChart();
