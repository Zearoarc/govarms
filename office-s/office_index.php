<?php

session_start();
include("office_header.php");
$office=$_SESSION['office_id'];

$con = new connec();
$sql_thresh = "SELECT i.id, t.type, t.category, i.amount, i.threshold, o.office, i.price
        FROM items i
        INNER JOIN supply_type t ON i.supply_type_id = t.id
        INNER JOIN office o ON i.office_id = o.id
        WHERE i.office_id = " . $_SESSION["office_id"] . " AND i.amount < i.threshold";
$result_thresh = $con->select_by_query($sql_thresh);

// Count the number of low stock supplies
$lowStockCount = $result_thresh->num_rows;
?>
<head>
    <title>Office Dashboard</title>
</head>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Dashboard</h1>
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <?php
                        $sql = "SELECT 
                                    COUNT(DISTINCT r.order_id) + 
                                    (SELECT COUNT(DISTINCT res.reserve_id) FROM res JOIN users u ON r.user_id = u.id WHERE res.req_status = 'Complete' AND u.office_id = $office) + 
                                    (SELECT COUNT(DISTINCT s.order_id) FROM supp s JOIN users u ON r.user_id = u.id WHERE s.req_status = 'Complete' AND u.office_id = $office) AS total_completed_requests 
                                FROM req r 
                                JOIN users u ON r.user_id = u.id
                                WHERE r.req_status = 'Complete' AND u.office_id = $office;";
                        $result = $con->select_by_query($sql);
                        $total_completed_requests = $result->fetch_assoc()["total_completed_requests"] ?? 0;
                        ?>
                        <p class="text-white">Completed Requests</p>
                        <h2 class="text-white"><b><?php echo $total_completed_requests; ?></b></h2>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="office_completedreq.php">View Details</a>
                        <div class="small text-white"><i class='bx bx-chevron-right' style='color:#ffffff'></i></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white mb-4">
                    <div class="card-body">
                        <?php
                        $sql = "SELECT COUNT(*) as total_employees
                                FROM users u
                                JOIN office o ON u.office_id = o.id
                                WHERE u.user_role='Employee' AND u.office_id = $office";
                        $result = $con->select_by_query($sql);
                        $row = $result->fetch_assoc();
                        $sql_office = "SELECT office
                        FROM office
                        WHERE id = $office";
                        $result_office = $con->select_by_query($sql_office);
                        $row_office = $result_office->fetch_assoc();
                        ?>
                        <p class="text-white">Number of Employees in <?php echo $row_office["office"]; ?></p>
                        <h2 class="text-white"><b><?php echo $row["total_employees"] ?? 0; ?></b></h2>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="office_manage.php">View Details</a>
                        <div class="small text-white"><i class='bx bx-chevron-right' style='color:#ffffff'></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">
                        <?php
                        $sql = "SELECT COUNT(*) as total_pending_requests
                                FROM req r
                                JOIN users u ON r.user_id = u.id
                                WHERE req_type = 'Asset' AND req_status = 'Pending' AND u.office_id = $office";
                        $result = $con->select_by_query($sql);
                        $total_pending_requests = $result->fetch_assoc()["total_pending_requests"] ?? 0;
                        ?>
                        <p class="text-white">Pending Asset Requests</p>
                        <h2 class="text-white"><b><?php echo $total_pending_requests; ?></b></h2>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="office_assetreq.php?view=pending">View Details</a>
                        <div class="small text-white"><i class='bx bx-chevron-right' style='color:#ffffff'></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-danger text-white mb-4">
                    <div class="card-body">
                        <p class="text-white">Low Stock Supplies</p>
                        <h2 class="text-white"><b><?php echo $lowStockCount ?? 0; ?></b></h2>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="office_inventory.php?view=low_stock">View Details</a>
                        <div class="small text-white"><i class='bx bx-chevron-right' style='color:#ffffff'></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6s">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class='bx bxs-pie-chart'></i>
                        <?php echo date('F'); ?> Requests
                    </div>
                    <div class="card-body">
                        <div id="statusChartContainer">
                            <canvas id="statusChart" width="100%" height="40"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6s">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class='bx bx-bar-chart-alt'></i>
                        Request Trends
                    </div>
                    <div class="card-body">
                        <div id="trendsChartContainer">
                            <canvas id="trendsChart" width="100%" height="40"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
include("office_footer.php");

?>