$(document).ready(function () {
    const today = new Date().toISOString().split('T')[0];
    $('#chart-date, #header-date-picker').val(today); // Set both date pickers to today's date

    let genderChart;

    // Function to render the gender chart with specific ages in the tooltip
    function renderGenderChart(data, title, ageRange = null) {
        const maleCount = data.male || 0;
        const femaleCount = data.female || 0;
        const lgbtCount = data.lgbt || 0;

        // Ensure ages are properly initialized
        const maleAges = Array.isArray(data.ages?.male) ? data.ages.male : [];
        const femaleAges = Array.isArray(data.ages?.female) ? data.ages.female : [];
        const lgbtAges = Array.isArray(data.ages?.lgbt) ? data.ages.lgbt : [];

        const ctx = document.getElementById('genderChart').getContext('2d');
        if (genderChart) genderChart.destroy();

        genderChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Male', 'Female', 'LGBTQ'],
                datasets: [{
                    label: 'User Count',
                    data: [maleCount, femaleCount, lgbtCount],
                    backgroundColor: ['rgba(0, 123, 255, 0.6)', 'rgba(255, 192, 203, 0.6)', 'rgba(40, 167, 69, 0.6)'],
                    borderColor: ['rgba(0, 123, 255, 1)', 'rgba(255, 192, 203, 1)', 'rgba(40, 167, 69, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: title },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                const gender = tooltipItem.label;
                                const count = tooltipItem.raw;

                                let ageList = "None";
                                // Retrieve correct age list based on gender
                                switch (gender) {
                                    case "Male":
                                        ageList = maleAges.length > 0 ? `Ages: ${maleAges.join(", ")}` : "None";
                                        break;
                                    case "Female":
                                        ageList = femaleAges.length > 0 ? `Ages: ${femaleAges.join(", ")}` : "None";
                                        break;
                                    case "LGBTQ":
                                        ageList = lgbtAges.length > 0 ? `Ages: ${lgbtAges.join(", ")}` : "None";
                                        break;
                                }

                                const rangeText = ageRange ? ` (Age Range: ${ageRange})` : "";
                                return `${gender}: ${count}${rangeText}\n${ageList}`;
                            }
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true, title: { display: true, text: 'User Count' } },
                    x: { title: { display: true, text: 'Gender' } }
                }
            }
        });
    }

    // Function to fetch daily gender data
    function fetchDailyGenderData(date) {
        $.ajax({
            url: 'fetch_gender_data.php',
            type: 'GET',
            data: { date: date },
            dataType: 'json',
            success: function (data) {
                console.log("Fetched Data:", data); // Log the fetched data
                renderGenderChart(data, `Daily User Distribution by Gender (${date})`);
            },
            error: function () {
                console.error('Error fetching daily gender data.');
            }
        });
    }

    // Function to fetch gender data with age range filtering
    function fetchGenderDataWithAgeRange(date, ageRange) {
        $.ajax({
            url: 'fetch_gender_data.php',
            type: 'GET',
            data: { date: date, age_range: ageRange },
            dataType: 'json',
            success: function (data) {
                console.log("Fetched Data with Age Range:", data); // Log the fetched data
                renderGenderChart(data, `User Distribution by Gender (Age Range: ${ageRange})`, ageRange);
            },
            error: function () {
                console.error('Error fetching gender data with age range.');
            }
        });
    }

    $('#fetch-chart-data').on('click', function () {
        const selectedDate = $('#header-date-picker').val();
        const selectedAgeRange = $('#age-range').val();
        if (selectedDate && selectedAgeRange) {
            fetchGenderDataWithAgeRange(selectedDate, selectedAgeRange);
        } else {
            alert("Please select both a date in the dashboard date picker and an age range.");
        }
    });

    $('#fetch-daily-data').on('click', function () {
        const selectedDate = $('#header-date-picker').val();
        if (selectedDate) fetchDailyGenderData(selectedDate);
    });

    $('#reset-daily-data').on('click', function () {
        const selectedDate = $('#header-date-picker').val();
        $('#age-range').val('0-10');
        fetchDailyGenderData(selectedDate);
    });

    $('#header-date-picker').on('change', function () {
        const selectedDate = $(this).val();
        $('#chart-date').val(selectedDate);
        fetchDailyGenderData(selectedDate);
    });

    fetchDailyGenderData(today);
});
