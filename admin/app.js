const toggleButton = document.getElementById("toggle-btn")
const sidebar = document.getElementById("sidebar")
const navsidebar = document.querySelector('.side')
let isNavSidebarVisible = false

function toggleSidebar(){
    sidebar.classList.toggle('minimize')
    toggleButton.classList.toggle('rotate')

    closeAllSubMenus()
}

function toggleSubMenu(button){

    if(!button.nextElementSibling.classList.contains('show')){
        closeAllSubMenus()
    }

    button.nextElementSibling.classList.toggle('show')
    button.classList.toggle('rotate')

    if(sidebar.classList.contains('minimize')){
        sidebar.classList.toggle('minimize')
        toggleButton.classList.toggle('rotate')
    }
}

function closeAllSubMenus(){
    Array.from(sidebar.getElementsByClassName('show')).forEach(ul=> {
        ul.classList.remove('show')
        ul.previousElementSibling.classList.remove('rotate')
    })
}

function toggleNavSidebar(){
    isNavSidebarVisible = !isNavSidebarVisible
    navsidebar.style.display = isNavSidebarVisible ? 'flex' : 'none'
}



// function refreshData() {
//     // Update KPIs
//     document.getElementById('totalSales').innerText = `$${salesData.totalSales}`;
//     document.getElementById('totalOrders').innerText = salesData.totalOrders;
//     document.getElementById('avgOrderValue').innerText = `$${salesData.avgOrderValue}`;
//     document.getElementById('customerSatisfaction').innerText = `${salesData.customerSatisfaction}%`;

//     // Update Charts
//     updateSalesTrendChart();
//     updateTopProductsChart();
// }

// function updateSalesTrendChart() {
//     const ctx1 = document.getElementById('salesTrendChart').getContext('2d');
//     const salesTrendChart = new Chart(ctx1, {
//         type: 'line',
//         data: {
//             labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6', 'Week 7'],
//             datasets: [{
//                 label: 'Sales Trend',
//                 data: salesData.salesTrend,
//                 borderColor: 'rgba(75, 192, 192, 1)',
//                 fill: false
//             }]
//         }
//     });
// }
        



console.log(toggleButton, sidebar)