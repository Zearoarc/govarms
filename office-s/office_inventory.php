<?php
session_start();
if(empty($_SESSION["username"])){
    header("location:../login.php");
}
else {
    include("office_header.php");

    $con=new connec();
    $office = $_SESSION["office_id"];

    // Office Supplies Section
    if (isset($_GET["view"]) && $_GET["view"] == "low_stock") {
        $sql_supplies = "SELECT i.id, t.type, t.category, i.amount, i.threshold, o.office, i.price
                FROM items i
                INNER JOIN supply_type t ON i.supply_type_id = t.id
                INNER JOIN office o ON i.office_id = o.id
                WHERE i.office_id = $office AND i.amount < i.threshold";
    } else {
        $sql_supplies = "SELECT i.id, t.type, t.category, i.amount, i.threshold, o.office, i.price
                FROM items i
                INNER JOIN supply_type t ON i.supply_type_id = t.id
                INNER JOIN office o ON i.office_id = o.id
                WHERE i.office_id = $office";
    }
    $result_supplies=$con->select_by_query($sql_supplies);

    // Office Assets Section
    $sql_assets="SELECT t.type, b.brand, i.model, i.price, i.status, o.office
    FROM items i
    INNER JOIN asset_type t ON i.asset_type_id = t.id
    INNER JOIN brand b ON i.brand_id = b.id
    INNER JOIN office o ON i.office_id = o.id
    WHERE i.type='Asset' AND i.status='Available' AND i.office_id = $office";
    $result_assets=$con->select_by_query($sql_assets);
    $assets = array();
    while ($row = $result_assets->fetch_assoc()) {
        $model = $row["model"];
        $price = $row["price"];
        $type = $row["type"];
        $brand = $row["brand"];
        $status = $row["status"];
        $office = $row["office"];
        $key = $model . "_" . $type . "_" . $brand . "_" . $office;
        if (!isset($assets[$key])) {
            $assets[$key] = array(
                "model" => $model,
                "price" => $price,
                "amount" => 1,
                "type" => $type,
                "brand" => $brand,
                "status" => $status,
                "office" => $office
            );
        } else {
            $assets[$key]["amount"]++;
        }
    }
    ?>

    <head>
        <title>Office Inventory</title>
    </head>
    <main>
    <div class="container-fluid px-4">
        <h2 class="mt-4">Office Inventory</h2>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <?php if (isset($_GET["view"]) && $_GET["view"] == "low_stock") { ?>
                        <h3>Low Stock Supplies</h3>
                        <div class="table-responsive">
                            <table class="table " id="lowStockSuppliesTable" width="100%" cellspacing="0">
                                <thead class="table-blue">
                                    <tr>
                                        <th>Supply Type</th>
                                        <th>Supply Category</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Threshold</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if($result_supplies->num_rows>0){
                                            while($row=$result_supplies->fetch_assoc()){
                                                ?>
                                                <tr>
                                                    <td><?php echo $row["type"]; ?></td>
                                                    <td><?php echo $row["category"]; ?></td>
                                                    <td>₱ <?php echo number_format($row["price"]); ?></td>
                                                    <td><?php echo $row["amount"]; ?></td>
                                                    <td><?php echo $row["threshold"]; ?></td>
                                                    <td style="width: 150px;">
                                                    <a class='btn btn-primary btn-sm' href='edit_supplies.php?id=<?php echo $row["id"]; ?>'>Edit</a>
                                                    <a class='btn btn-sm btn-danger' href='delete_supplies.php?id=<?php echo $row["id"]; ?>'>Delete</a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    ?>
                                </tbody>
                            </table>
                            <script>
                                new DataTable('#lowStockSuppliesTable', {
                                    "paging": false,
                                    "lengthChange": true,
                                    "searching": true,
                                    "ordering": true,
                                    "info": true,
                                    "autoWidth": false,
                                    "layout": {
                                        topStart: {
                                            buttons: [
                                                {
                                                    "text": 'Add',
                                                    "action": function (e, dt, node, config) {
                                                        window.location.href = 'add_supplies.php?id=<?php echo $_SESSION["employee_id"]; ?>';
                                                    },
                                                    "attr": {
                                                        "class": 'btn btn-primary', // Add CSS classes here
                                                        "style": 'margin-left: 3px;'
                                                    }
                                                }
                                            ]
                                        }
                                    }
                                });
                            </script>
                        </div>
                    <?php } else { ?>
                        <div class="row">
                            <div class="col-md-6">
                                <h3>Office Supplies</h3>
                                <div class="table-responsive">
                                    <table class="table " id="officeSuppliesTable" width="100%" cellspacing="0">
                                        <thead class="table-blue">
                                            <tr>
                                                <th>Type</th>
                                                <th>Category</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Threshold</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if($result_supplies->num_rows>0){
                                                    while($row=$result_supplies->fetch_assoc()){
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $row["type"]; ?></td>
                                                            <td><?php echo $row["category"]; ?></td>
                                                            <td>₱ <?php echo number_format($row["price"]); ?></td>
                                                            <td><?php echo $row["amount"]; ?></td>
                                                            <td><?php echo $row["threshold"]; ?></td>
                                                            <td style="width: 150px;">
                                                            <a class='btn btn-primary btn-sm' href='edit_supplies.php?id=<?php echo $row["id"]; ?>'>Edit</a>
                                                            <!-- <a class='btn btn-sm btn-danger' href='delete_supplies.php?id=<?php echo $row["id"]; ?>'>Delete</a> -->
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h3>Office Assets</h3>
                                <div class="table-responsive">
                                    <table class="table " id="officeAssetsTable" width="100%" cellspacing="0">
                                        <thead class="table-blue">
                                            <tr>
                                                <th>Type</th>
                                                <th>Brand</th>
                                                <th>Model</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach ($assets as $asset){
                                                ?>
                                                <tr>
                                                    <td><?php echo $asset["type"]; ?></td>
                                                    <td><?php echo $asset["brand"]; ?></td>
                                                    <td><?php echo $asset["model"]; ?></td>
                                                    <td>₱ <?php echo number_format($asset["price"]); ?></td>
                                                    <td><?php echo $asset["amount"]; ?></td>
                                                    <td>
                                                    <a class='btn btn-primary btn-sm' href='edit_assets.php?model=<?php echo $asset["model"]; ?>&type=<?php echo $asset["type"]; ?>&brand=<?php echo $asset["brand"]; ?>&office=<?php echo $asset["office"]; ?>'>Edit</a>
                                                    <a class='btn btn-sm btn-danger' href='delete_assets.php?model=<?php echo $asset["model"]; ?>&type=<?php echo $asset["type"]; ?>&brand=<?php echo $asset["brand"]; ?>&office=<?php echo $asset["office"]; ?>'>Delete</a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <script>
                                        new DataTable('#officeSuppliesTable', {
                                            "paging": false,
                                            "lengthChange": true,
                                            "searching": true,
                                            "ordering": true,
                                            "info": true,
                                            "autoWidth": false,
                                            "layout": {
                                                topStart: {
                                                    buttons: [
                                                        {
                                                            "text": 'Add',
                                                            "action": function (e, dt, node, config) {
                                                                window.location.href = 'add_supplies.php?id=<?php echo $_SESSION["employee_id"]; ?>';
                                                            },
                                                            "attr": {
                                                                "class": 'btn btn-primary', // Add CSS classes here
                                                                "style": 'margin-left: 3px;'
                                                            }
                                                        }
                                                    ]
                                                }
                                            }
                                        });
                                    </script>
                                    <script>
                                        new DataTable('#officeAssetsTable', {
                                            "paging": false,
                                            "lengthChange": true,
                                            "searching": true,
                                            "ordering": true,
                                            "info": true,
                                            "autoWidth": false,
                                            "layout": {
                                                topStart: {
                                                    buttons: [
                                                        {
                                                            "text": 'Add',
                                                            "action": function (e, dt, node, config) {
                                                                window.location.href = 'add_assets.php?id=<?php echo $_SESSION["employee_id"]; ?>';
                                                            },
                                                            "attr": {
                                                                "class": 'btn btn-primary', // Add CSS classes here
                                                                "style": 'margin-left: 3px;'
                                                            }
                                                        }
                                                    ]
                                                }
                                            }
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        <?php } ?>
    </main>

    <?php
    include("office_footer.php");
}
?>