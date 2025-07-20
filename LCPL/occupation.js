
        fetch('Occupation.php')
            .then(response => response.json())
            .then(data => {
                // Extract the data from the response
                if (data.error) {
                    console.error(data.error);
                    return;
                }

                // Set up the pie chart data
                var ctx = document.getElementById('occupationChart').getContext('2d');
                var occupationChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Employed', 'Unemployed', 'Self-Employed', 'Students'],
                        datasets: [{
                            label: 'User Occupations',
                            data: [data.employed, data.unemployed, data.self_employed, data.students],
                            backgroundColor: ['#36A2EB', '#FF6384', '#FFCD56', '#4BC0C0'],
                            borderColor: '#fff',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.label + ': ' + tooltipItem.raw;
                                    }
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });