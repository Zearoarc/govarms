<?php
session_start();

if (isset($_POST["btn_approve"])) {
    include("../conn.php");
    $order=$_POST['order'];

    $con=new connec();

    $sql="SELECT * FROM req WHERE order_id = '$order'";
    $result=$con->select_by_query($sql);

    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $id = $row["id"];
            $brand=$_POST['brand' . $id];
            $model=$_POST['model' . $id];
            $serial=$_POST['serial' . $id];

            $sql_asset_id = "SELECT id FROM assets WHERE model = '$model' AND serial = '$serial'";
            $result_asset_id = $con->select_by_query($sql_asset_id);
            $row_asset_id=$result_asset_id->fetch_assoc();
            $asset_id=$row_asset_id['id'];

            $sql="UPDATE req SET asset_id = '$asset_id', req_status = 'Incomplete' WHERE id='$id'";
            $con->update($sql, "Data Updated Successfully");

            $sql_assets="UPDATE assets SET status = 'Requested' WHERE model = '$model' AND serial = '$serial'";
            $con->update($sql_assets, "Data Updated Successfully");
        }
    }
    header("Location: office_assetreq.php");
}

if (isset($_POST["btn_complete"])) {
    include("../conn.php");
    $order=$_POST['order'];
    $con=new connec();

    $sql="UPDATE req SET req_status = 'Complete' WHERE order_id='$order'";
    $con->update($sql, "Data Updated Successfully");

    header("Location: office_assetreq.php");
    exit;
}

if(empty($_SESSION["username"])){
    header("location:../login.php");
}
else {
    include("office_header.php");
    $office = $_SESSION["office_id"];

    $con=new connec();
    $sql="SELECT r.id, r.req_type, t.type, r.order_id, u.name, o.office, r.req_status
    FROM req r
    JOIN asset_type t ON r.asset_type_id = t.id
    JOIN users u ON r.user_id = u.id
    JOIN office o ON u.office_id = o.id
    WHERE req_type='Asset' AND r.req_status IN ('Incomplete', 'Pending') AND u.office_id = '$office';";
    $result=$con->select_by_query($sql);

    // Group orders by order ID
    $orders = array();
    while ($row = $result->fetch_assoc()) {
        $order_id = $row["order_id"];
        if (!isset($orders[$order_id])) {
            $orders[$order_id] = array(
                "user_name" => $row["name"],
                "order_data" => array()
            );
        }
        $orders[$order_id]["order_data"][] = array(
            "id" => $row["id"],
            "req_type" => $row["req_type"],
            "type" => $row["type"],
            "office" => $row["office"],
            "req_status" => $row["req_status"]
        );
    }

    $sql_inc = "SELECT r.id, r.req_type, b.brand, a.model, a.serial, t.type, r.order_id, u.name, o.office, r.req_status
    FROM req r
    JOIN assets a ON r.asset_id = a.id
    JOIN brand b ON a.brand_id = b.id
    JOIN asset_type t ON r.asset_type_id = t.id
    JOIN users u ON r.user_id = u.id
    JOIN office o ON u.office_id = o.id
    WHERE req_type='Asset' AND r.req_status IN ('Incomplete')";
    $result_inc = $con->select_by_query($sql_inc);
    $row_inc = $result_inc->fetch_assoc();
    ?>
    <head>
        <title>Asset Requests</title>
    </head>
    <main>
        <?php
        if (empty($orders)) {
            ?>
            <div class="container-fluid px-4">
                <h2 class="mt-4">Asset Requests</h2>
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <p>No asset requests found.</p>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="container-fluid px-4">
                <h2 class="mt-4">Asset Requests</h2>
                <?php
                foreach ($orders as $order_id => $order_data) {
                    ?>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <form method="post">
                                <h4>Order ID: <?php echo $order_id; ?> (<?php echo $order_data["user_name"]; ?>)</h4>
                                <input type="hidden" name="order" value="<?php echo $order_id; ?>">
                                <table class="table " id="dataAssetTable" width="100%" cellspacing="0">
                                    <thead class="table-blue">
                                        <tr>
                                            <th>Type</th>
                                            <th>Brand</th>
                                            <th>Asset Model</th>
                                            <th>Asset Serial</th>
                                            <th>Office</th>
                                            <th>Request Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($order_data["order_data"] as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row["type"]; ?></td>
                                            <?php if ($row["req_status"] == "Pending") { ?>
                                                <td>
                                                    <select id="brand<?php echo $row["id"]; ?>" name="brand<?php echo $row["id"]; ?>" required>
                                                        <option value="" selected disabled>Select Brand</option>
                                                        <?php
                                                        $brand_sql = "SELECT id, brand FROM brand";
                                                        $brand_result = $con->select_by_query($brand_sql);
                                                        while ($brand_row = $brand_result->fetch_assoc()) {
                                                            ?>
                                                            <option value="<?php echo $brand_row["id"]; ?>"><?php echo $brand_row["brand"]; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select id="model<?php echo $row["id"]; ?>" name="model<?php echo $row["id"]; ?>" required>
                                                        <option value="" selected disabled>Select Model</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select id="serial<?php echo $row["id"]; ?>" name="serial<?php echo $row["id"]; ?>" required>
                                                        <option value="" selected disabled>Select Serial</option>
                                                    </select>
                                                </td>
                                            <?php } else { ?>
                                                <td><?php echo $row_inc["brand"]; ?></td>
                                                <td><?php echo $row_inc["model"]; ?></td>
                                                <td><?php echo $row_inc["serial"]; ?></td>
                                            <?php } ?>
                                            <td><?php echo $row["office"]; ?></td>
                                            <td style="height: 40px;">
                                                <?php
                                                if ($row["req_status"] == "Incomplete") {
                                                    ?>
                                                    <i class='bx bxs-info-circle large-icon' style='color:#ffa83e;' title="<?php echo $row["req_status"]; ?>"></i>
                                                    <?php
                                                }
                                                else if ($row["req_status"] == "Pending") {
                                                    ?>
                                                    <i class='bx bxs-time-five large-icon' style='color:#00b2f1' title="<?php echo $row["req_status"]; ?>"></i>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                    ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <?php
                                            if ($row["req_status"] == 'Pending'){
                                                $serials = array_column($order_data["order_data"], "serial");
                                                ?>
                                                <td colspan="9">
                                                    <button type="submit" class="btn btn-primary" name="btn_approve">Approve</button>
                                                    <a class="btn btn-danger" style="color: #ffffff" href='cancel_assetreq.php?order=<?php echo $order_id; ?>'>Cancel</a>
                                                </td>
                                                <?php
                                            }
                                            else if ($row["req_status"] == 'Incomplete') {
                                                ?>
                                                <td colspan="9">
                                                    <button type="submit" class="btn btn-primary" name="btn_complete">Complete</button>
                                                </td>
                                                <?php
                                            }
                                            ?>
                                        </tr>
                                    </tfoot>
                                </table>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php
                    }
                ?>
            </div>
            <?php
        }
        ?>
    </main>
</body>
</html>

<script>
    $(document).ready(function() {
        $('[id^="brand"]').on('change', function() {
            var brandId = $(this).attr('id').replace('brand', '');
            var brand = $(this).val();
            $.ajax({
                type: 'POST',
                url: 'get_models.php',
                data: {brand: brand},
                success: function(data) {
                    $('#model' + brandId).html(data);
                }
            });
        });

        $('[id^="model"]').on('change', function() {
            var modelId = $(this).attr('id').replace('model', '');
            var model = $(this).val();
            $.ajax({
                type: 'POST',
                url: 'get_serials.php',
                data: {model: model},
                success: function(data) {
                    $('#serial' + modelId).html(data);
                }
            });
        });
    });
</script>


<?php
include("office_footer.php");
}
?>