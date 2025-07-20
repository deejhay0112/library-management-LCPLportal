// Render the chart using Chart.js
    function renderChart(labels, data) {
        const ctx = document.getElementById("chart").getContext("2d");

        if (chart) {
            chart.destroy();
        }

        chart = new Chart(ctx, {
            type: "line",
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Predicted Daily Visitor Count",
                        data: data,
                        borderColor: "rgba(75, 192, 192, 1)",
                        backgroundColor: "rgba(75, 192, 192, 0.2)",
                        fill: true,
                        tension: 0.4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true },
                    tooltip: { enabled: true },
                },
                scales: {
                    x: { title: { display: true, text: "Date" } },
                    y: { title: { display: true, text: "Visitor Count" }, beginAtZero: true },
                },
            },
        });
    }

    // Update chart dynamically
    async function updateChart() {
        const newDailyData = await fetchDailyData();

        if (newDailyData.length < 4) {
            console.error("Not enough data for training.");
            return;
        }

        const { features, labels } = preprocessDailyData(newDailyData);

        if (features.length === 0 || labels.length === 0) {
            console.error("No valid features or labels for training.");
            return;
        }

        const model = await trainDailyModel(features, labels);
        const lastEntry = newDailyData[newDailyData.length - 1];

        const { predictions, labels: futureLabels } = predictDailyVisitors(model, lastEntry, 31);

        console.log("Final Predictions for December:", predictions);
        console.log("Prediction Labels for December:", futureLabels);

        renderChart(futureLabels, predictions);
    }

    await updateChart();
});