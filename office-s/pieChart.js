const stats = ["Complete", "Incomplete", "Pending"];

fetch("scriptPie.php")
  .then((response) => {
    return response.json();
  })
  .then((data) => {
    console.log(data);
    createPie(data, "pie");
  });

function createPie(chartData, type) {
  if (!Array.isArray(chartData) || chartData.length === 0) {
    console.error("Invalid chart data");
    return;
  }
  const counts = {
    Complete: 0,
    Incomplete: 0,
    Pending: 0,
  };

  for (const num of chartData.map((row) => row.req_status)) {
    counts[num] = counts[num] ? counts[num] + 1 : 1;
  }
  const dataValues = [
    counts["Complete"],
    counts["Incomplete"],
    counts["Pending"],
  ];
  console.log(dataValues);

  // Check if all counts are zero
  if (dataValues.every((value) => value === 0)) {
    // Display "No Data Yet" message
    const chartContainer = document.getElementById("statusChartContainer");
    chartContainer.innerHTML = "<p>No Data Yet</p>";
    return;
  }

  const ctx = document.getElementById("statusChart").getContext("2d");
  statusChart = new Chart(ctx, {
    type: type,
    data: {
      labels: stats,
      datasets: [
        {
          data: dataValues,
          backgroundColor: ["#93b858", "#ffa83e", "#00b2f1"],
        },
      ],
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
        },
      },
    },
  });
}
