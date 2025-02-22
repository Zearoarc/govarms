<?php

session_start();
include("office_header.php");



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
                    <div class="card-body">Total Pending Requests</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">View Details</a>
                        <div class="small text-white"><i class='bx bx-chevron-right' style='color:#ffffff'></i></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white mb-4">
                    <div class="card-body">Asset Requests</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">View Details</a>
                        <div class="small text-white"><i class='bx bx-chevron-right' style='color:#ffffff'></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">Maintenance Requests</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">View Details</a>
                        <div class="small text-white"><i class='bx bx-chevron-right' style='color:#ffffff'></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-danger text-white mb-4">
                    <div class="card-body">Disposal Requests</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">View Details</a>
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
                        <canvas id="statusChart" width="100%" height="40"></canvas>
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
                        <canvas id="trendsChart" width="100%" height="40"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
include("office_footer.php");

?>