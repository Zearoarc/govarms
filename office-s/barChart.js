const currentDate = new Date();
const currentMonthYear = currentDate.getFullYear() + '-' + String(currentDate.getMonth() + 1).padStart(2, '0');
console.log(currentMonthYear);
const currentMonth = currentDate.toLocaleString('default', { month: 'long' });

const oneDate = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1);
const oneMonthYear = oneDate.getFullYear() + '-' + String(oneDate.getMonth() + 1).padStart(2, '0');
console.log(oneMonthYear);
const oneMonth = oneDate.toLocaleString('default', { month: 'long' });

const twoDate = new Date(currentDate.getFullYear(), currentDate.getMonth() - 2);
const twoMonthYear = twoDate.getFullYear() + '-' + String(twoDate.getMonth() + 1).padStart(2, '0');
const twoMonth = twoDate.toLocaleString('default', { month: 'long' });

const threeDate = new Date(currentDate.getFullYear(), currentDate.getMonth() - 3);
const threeMonthYear = threeDate.getFullYear() + '-' + String(threeDate.getMonth() + 1).padStart(2, '0');
const threeMonth = threeDate.toLocaleString('default', { month: 'long' });

const fourDate = new Date(currentDate.getFullYear(), currentDate.getMonth() - 4);
const fourMonthYear = fourDate.getFullYear() + '-' + String(fourDate.getMonth() + 1).padStart(2, '0');
const fourMonth = fourDate.toLocaleString('default', { month: 'long' });

const fiveDate = new Date(currentDate.getFullYear(), currentDate.getMonth() - 5);
const fiveMonthYear = fiveDate.getFullYear() + '-' + String(fiveDate.getMonth() + 1).padStart(2, '0');
const fiveMonth = fiveDate.toLocaleString('default', { month: 'long' });

const months = [fiveMonth, fourMonth, threeMonth, twoMonth, oneMonth, currentMonth];

fetch("scriptBar.php")
  .then((response) => {
    return response.json();
  })
  .then((data) => {
    console.log(data);
    createBar(data, "bar");
  });
function createBar(chartData, type) {
    if (!Array.isArray(chartData) || chartData.length === 0) {
        console.error("Invalid chart data");
        return;
    }
    const counts = {
        [fiveMonthYear]: 0,
        [fourMonthYear]: 0,
        [threeMonthYear]: 0,
        [twoMonthYear]: 0,
        [oneMonthYear]: 0,
        [currentMonthYear]: 0,
    };

    for (const num of chartData.map((row) => row.date)) {
        counts[num] = counts[num] ? counts[num] + 1 : 1;
    }
    const dataValues = [
        counts[fiveMonthYear],
        counts[fourMonthYear],
        counts[threeMonthYear],
        counts[twoMonthYear],
        counts[oneMonthYear],
        counts[currentMonthYear],
    ];
    console.log(dataValues);

    const maxDataValue = Math.max(...dataValues);
    const maxY = maxDataValue > 8 ? 2 + maxDataValue : 10;

    console.log(maxY);

    // Check if all counts are zero
    if (dataValues.every((value) => value === 0)) {
        // Display "No Data Yet" message
        const chartContainer = document.getElementById("trendsChartContainer");
        chartContainer.innerHTML = "<p>No Data Yet</p>";
        return;
    }

    const ctx = document.getElementById("trendsChart").getContext('2d');
    trendsChart = new Chart(ctx, {
        type: type,
        data: {
            labels: months,
            datasets: [
                {
                    label: "Requests",
                    data: dataValues,
                    backgroundColor: 'rgba(0, 123, 255, 1)',
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [
                    {
                        gridLines: {
                            display: false,
                        },
                    }
                ],
                yAxes: [
                    {
                        gridLines: {
                            display: false,
                        },
                        ticks: {
                            min: 0,
                            max: maxY,
                            maxTicksLimit: 5,
                        },
                    }
                ]
            },
            legend: {
                display: false,
            },
        },
    });
    console.log(trendsChart.options);
}
console.log(months);