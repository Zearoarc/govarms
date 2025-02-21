<?php
session_start();
if(empty($_SESSION["username"])){
    header("location:../login.php");
}
else {
    include("admin_header.php");

    $con=new connec();
    $sql="SELECT r.req_type, r.order_id, u.name, a.model, a.serial, s.supplier, o.office, r.req_status, r.req_level
    FROM req r
    JOIN users u ON r.user_id = u.id
    JOIN assets a ON r.asset_id = a.id
    JOIN supplier s ON a.supplier_id = s.id
    JOIN office o ON a.office_id = o.id
    WHERE req_type='Asset' AND r.req_level='admin' AND r.req_status IN ('Incomplete', 'Pending');";
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
            "req_type" => $row["req_type"],
            "model" => $row["model"],
            "supplier" => $row["supplier"],
            "serial" => $row["serial"],
            "office" => $row["office"],
            "req_status" => $row["req_status"]
        );
    }
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
                                <h4>Order ID: <?php echo $order_id; ?> (<?php echo $order_data["user_name"]; ?>)</h4>
                                <table class="table " id="dataAssetTable" width="100%" cellspacing="0">
                                    <thead class="table-blue">
                                        <tr>
                                            <th>Supplier</th>
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
                                            <td><?php echo $row["supplier"]; ?></td>
                                            <td><?php echo $row["model"]; ?></td>
                                            <td><?php echo $row["serial"]; ?></td>
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
                                                    <a class="btn btn-primary" style="color: #ffffff" href='approve_assetreq.php?order=<?php echo $order_id; ?>'>Approve</a>
                                                    <a class="btn btn-danger" style="color: #ffffff" href='cancel_assetreq.php?order=<?php echo $order_id; ?>&serial=<?php echo json_encode($serials); ?>'>Cancel</a>
                                                </td>
                                                <?php
                                            }
                                            else if ($row["req_status"] == 'Incomplete') {
                                                ?>
                                                <td colspan="9">
                                                    <a class="btn btn-primary" style="color: #ffffff" href='complete_assetreq.php?order=<?php echo $order_id; ?>'>Complete</a>
                                                </td>
                                                <?php
                                            }
                                            ?>
                                        </tr>
                                    </tfoot>
                                </table>
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


<?php
include("admin_footer.php");
}
?>