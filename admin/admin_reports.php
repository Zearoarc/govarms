<?php
session_start();
include("admin_header.php");
include("admin_sidenavbar.php");
?> 

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"> GENERATE REPORTS
            </h6>
        </div>

    </div> 
    <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-white text-white mb-4">
                    <div class="card-body" style="color: black;">Export Products</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-blue stretched-link" href="#">Exel</a>
                        <a class="small text-blue stretched-link" href="#">PDF</a>
                        
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-white text-white mb-4">
                    <div class="card-body"style="color: black;">Export Employees</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-blue stretched-link" href="#">Exel</a>
                    <a class="small text-blue stretched-link" href="#">PDF</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-white text-white mb-4">
                    <div class="card-body" style="color: black;">Export Requests</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-blue stretched-link" href="#">Exel</a>
                    <a class="small text-blue stretched-link" href="#">PDF</a>
                    </div>
                </div>
            </div>
</div>


<?php
include("admin_footer.php");
?>