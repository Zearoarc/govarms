<?php
session_start();
if(empty($_SESSION["username"])){
    header("location:login.php");
}
else {
    include("header.php");

    $con=new connec();
    $sql="SELECT a.id, a.type, a.category, u.unit_name, u.description, a.date_expected
    FROM asset_type a
    JOIN unit u ON a.unit_id = u.id";
    $result=$con->select_by_query($sql);
    
    ?> 
 
    <head>
        <title>Asset Reservation</title>
    </head>
    <main>
        <div class="container-fluid px-4">
            <h2 class="mt-4">Asset Reservation</h2>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="input-group" style="width: 30%; float: right;">
                        <input type="text" id="search-input" placeholder="Search assets..." class="form-control">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table " id="dataAssetTable" width="100%" cellspacing="0">
                            <thead class="table-blue">
                                <tr>
                                    <th><input type="checkbox" id="selectAll" /></th>
                                    <th>Asset Type</th>
                                    <th>Asset Category</th>
                                    <th>Unit of Issue</th>
                                    <th>Expected Delivery Date</th>
                                    <th>Start and End Date</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                <?php
                                    if($result->num_rows>0){
                                        while($row=$result->fetch_assoc()){
                                            ?>
                                            <tr>
                                                <td><input type="checkbox" class="checkbox" value="<?php echo $row["type"]; ?>" /></td>
                                                <td><?php echo $row["type"]; ?></td>
                                                <td><?php echo $row["category"]; ?></td>
                                                <td><?php echo $row["unit_name"]; ?></td>
                                                <td>
                                                    <?php echo date('Y-m-d', strtotime('+' . $row["date_expected"] . ' days')); ?>
                                                    <input type="hidden" id="date-expected-<?php echo $row["type"]; ?>" value="<?php echo $row["date_expected"]; ?>">
                                                </td>
                                                <td style="width: 250px;">
                                                    <div class="input-group">
                                                        <input type="date" class="form-control form-control-sm" id="start-date-<?php echo $row["type"]; ?>" min="<?php echo date('Y-m-d', strtotime('+' . $row["date_expected"] . ' days')); ?>">
                                                    </div>
                                                    <div class="input-group">
                                                        <input type="date" class="form-control form-control-sm" id="end-date-<?php echo $row["type"]; ?>" min="<?php echo date('Y-m-d', strtotime('+' . ($row["date_expected"] + 7) . ' days')); ?>">
                                                    </div>
                                                </td>
                                                <td style="width: 150px;">
                                                    <input type="hidden" id="id-<?php echo $row["type"]; ?>" value="<?php echo $row["id"]; ?>">
                                                    <div class="input-group">
                                                        <input type="number" class="form-control form-control-sm" value="1" id="amount-<?php echo $row["type"]; ?>">
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>
                                        <a class='btn btn-primary' id='request-btn' href='#'>Request</a>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
 
    <?php
    include("footer.php");
    }
    ?>