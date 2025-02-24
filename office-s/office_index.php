<?php

session_start();
include("office_header.php");

$con = new connec();
$sql_thresh = "SELECT s.id, t.type, t.category, s.quantity, s.threshold, o.office, s.price
        FROM supplies s
        INNER JOIN supply_type t ON s.type_id = t.id
        INNER JOIN office o ON s.office_id = o.id
        WHERE s.office_id = " . $_SESSION["office_id"] . " AND s.quantity < s.threshold";
$result_thresh = $con->select_by_query($sql_thresh);

// Count the number of low stock supplies
$lowStockCount = $result_thresh->num_rows;
?>
<head>
    <title>Admin Dashboard</title>
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
                                    (SELECT COUNT(DISTINCT res.reserve_id) FROM res WHERE res.req_status = 'Complete') + 
                                    (SELECT COUNT(DISTINCT s.order_id) FROM supp s WHERE s.req_status = 'Complete') AS total_completed_requests 
                                FROM 
                                    req r 
                                WHERE 
                                    r.req_status = 'Complete';";
                        $result = $con->select_by_query($sql);
                        $total_completed_requests = $result->fetch_assoc()["total_completed_requests"];
                        ?>
                        <p class="text-white">Completed Requests</p>
                        <h2 class="text-white"><?php echo $total_completed_requests; ?></h2>
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
                    $sql = "SELECT u.name, COUNT(r.id) as total_requests
                            FROM users u
                            JOIN req r ON u.id = r.user_id
                            GROUP BY u.id
                            ORDER BY total_requests DESC
                            LIMIT 1";
                    $result = $con->select_by_query($sql);
                    $row = $result->fetch_assoc();
                    ?>
                    <p class="text-white">Highest Requests by <?php echo $row["name"]; ?></p>
                    <h2 class="text-white"><b><?php echo $row["total_requests"]; ?></b></h2>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="office_manage.php?view=highest">View Details</a>
                        <div class="small text-white"><i class='bx bx-chevron-right' style='color:#ffffff'></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">
                        <?php
                        $sql = "SELECT COUNT(*) as total_pending_requests
                                FROM req
                                WHERE req_type = 'Asset' AND req_status = 'Pending'";
                        $result = $con->select_by_query($sql);
                        $total_pending_requests = $result->fetch_assoc()["total_pending_requests"];
                        ?>
                        <p class="text-white">Pending Asset Requests</p>
                        <h2 class="text-white"><?php echo $total_pending_requests; ?></h2>
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
                        <h2 class="text-white"><b><?php echo $lowStockCount; ?></b></h2>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="office_supplies.php?view=low_stock">View Details</a>
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