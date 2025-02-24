<?php
session_start();
if(empty($_SESSION["username"])){
    header("location:login.php");
}
else {
    include("header.php");
    $office = $_SESSION["office_id"];

    $con=new connec();
    $sql="SELECT s.id, t.type, t.category, u.unit_name, t.date_expected, s.quantity, s.price, s.date_update
    FROM supplies s
    JOIN supply_type t ON s.type_id = t.id
    JOIN office o ON s.office_id = o.id
    JOIN unit u ON t.unit_id = u.id
    WHERE s.office_id = $office";
    $result=$con->select_by_query($sql);
    
    ?> 
 
    <head>
        <title>Supply Request</title>
    </head>
    <main>
        <div class="container-fluid px-4">
            <h2 class="mt-4">Supply Request</h2>
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
                                    <th>Supply Type</th>
                                    <th>Supply Category</th>
                                    <th>Unit of Issue</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Last Updated</th>
                                    <th>Expected Delivery Date</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                <?php
                                    if($result->num_rows>0){
                                        while($row=$result->fetch_assoc()){
                                            $type_id = str_replace(' ', '_', $row["type"]); // Replace spaces with underscores
                                            ?>
                                            <tr>
                                                <td><input type="checkbox" class="checkbox" value="<?php echo $type_id; ?>" /></td>
                                                <td><?php echo $row["type"]; ?></td>
                                                <td><?php echo $row["category"]; ?></td>
                                                <td><?php echo $row["unit_name"]; ?></td>
                                                <td>₱ <?php echo $row["price"]; ?></td>
                                                <td><?php echo $row["quantity"]; ?></td>
                                                <td><?php echo $row["date_update"]; ?></td>
                                                <td>
                                                    <?php echo date('Y-m-d', strtotime('+' . $row["date_expected"] . ' days')); ?>
                                                    <input type="hidden" id="date-expected-<?php echo $type_id; ?>" value="<?php echo $row["date_expected"]; ?>">
                                                </td>
                                                <td style="width: 150px;">
                                                    <input type="hidden" id="id-<?php echo $type_id; ?>" value="<?php echo $row["id"]; ?>">
                                                    <input type="hidden" id="quantity-<?php echo $type_id; ?>" value="<?php echo $row["quantity"]; ?>">
                                                    <div class="input-group">
                                                        <input type="number" class="form-control form-control-sm" value="1" id="amount-<?php echo $type_id; ?>" max="<?php echo $row["quantity"]; ?>">
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