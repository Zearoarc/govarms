const stats = ["Completed", "Incomplete", "Pending"];

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
  const counts = {};

  for (const num of chartData.map((row) => row.stat)) {
    counts[num] = counts[num] ? counts[num] + 1 : 1;
  }
  const dataValues = [
    counts["Completed"],
    counts["Incomplete"],
    counts["Pending"],
  ];
  console.log(dataValues);

  const ctx = document.getElementById("statusChart").getContext("2d");
  statusChart = new Chart(ctx, {
    type: type,
    data: {
      labels: stats,
      datasets: [
        {
          data: dataValues,
          backgroundColor: ["#00ff00", "#ffff00", "#ff0000"],
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
