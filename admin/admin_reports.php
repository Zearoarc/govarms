<?php
session_start();
include("admin_header.php");
?> 
<head>
        <title>Generate Reports</title>
    </head>

<div class="container-fluid px-4">
    <h2 class="mt-4">Generate Reports</h2>
    <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card shadow bg-white text-white mb-4">
                    <div class="card-body" style="color: black;" >Export Products</div>
                    <div class="card-footer align-items-right justify-content" >
                        <a class="btn btn-primary" href="excel_assets.php">Excel</a>
                        <a class="btn btn-primary" href="pdf_assets.php">CSV</a>
                        
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card shadow bg-white text-white mb-4">
                    <div class="card-body"style="color: black;">Export Employees</div>
                    <div class="card-footer alignRight justify-content">
                    <a class="btn btn-primary" href="excel_users.php">Excel</a>
                    <a class="btn btn-primary" href="#">CSV</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card shadow bg-white text-white mb-4">
                    <div class="card-body" style="color: black;">Export Requests</div>
                    <div class="card-footer align-right justify-content">
                    <a class="btn btn-primary" href="#">Excel</a>
                    <a class="btn btn-primary" href="#">CSV</a>
                    </div>
                </div>
            </div>
</div>


<?php
include("admin_footer.php");
?>