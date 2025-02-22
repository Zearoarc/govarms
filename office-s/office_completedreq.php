<?php
session_start();
if(empty($_SESSION["username"])){
    header("location:../login.php");
}
else {
    include("office_header.php");

    $con=new connec();
    $sql="SELECT r.req_type, r.order_id, u.name, a.model, a.serial, s.supplier, o.office, r.req_status, r.action
    FROM req r
    JOIN users u ON r.user_id = u.id
    JOIN assets a ON r.asset_id = a.id
    JOIN supplier s ON a.supplier_id = s.id
    JOIN office o ON a.office_id = o.id
    WHERE r.req_status = 'Complete';";
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
            "req_status" => $row["req_status"],
            "action" => $row["action"]
        );
    }


    $sql="SELECT r.reserve_id, u.name, a.model, a.serial, s.supplier, o.office, r.date_start, r.date_end, r.req_status
    FROM res r
    JOIN users u ON r.user_id = u.id
    JOIN assets a ON r.asset_id = a.id
    JOIN supplier s ON a.supplier_id = s.id
    JOIN office o ON a.office_id = o.id
    WHERE r.req_status = 'Complete';";
    $result=$con->select_by_query($sql);
    

    $reserves = array();
    while ($row = $result->fetch_assoc()) {
        $reserve_id = $row["reserve_id"];
        if (!isset($reserves[$reserve_id])) {
            $reserves[$reserve_id] = array(
                "user_name" => $row["name"],
                "reserve_data" => array()
            );
        }
        $reserves[$reserve_id]["reserve_data"][] = array(
            "model" => $row["model"],
            "supplier" => $row["supplier"],
            "serial" => $row["serial"],
            "office" => $row["office"],
            "date_start" => $row["date_start"],
            "date_end" => $row["date_end"],
            "req_status" => $row["req_status"]
        );
    }

    ?>
    <head>
        <title>Completed Requests</title>
    </head>
    <main>
        <?php
        if (empty($orders) && empty($reserves)) {
            ?>
            <div class="container-fluid px-4">
                <h2 class="mt-4">Completed Requests</h2>
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <p>No completed requests found.</p>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="container-fluid px-4">
                <h2 class="mt-4">Completed Requests</h2>
                <?php
                foreach ($orders as $order_id => $order_data) {
                    ?>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <h4>Order ID: <?php echo $order_id; ?> (<?php echo $order_data["user_name"];
                                if ($order_data["order_data"][0]["action"] == 'repair') {
                                    echo ' - For Repair';
                                } elseif ($order_data["order_data"][0]["action"] == 'dispose') {
                                    echo ' - For Disposal';
                                }?>)</h4>
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
                                                if ($row["req_status"] == "Complete") {
                                                    ?>
                                                    <i class='bx bxs-check-circle large-icon' style='color:#93b858' title="<?php echo $row["req_status"]; ?>"></i>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php
                    }
                ?>
            </div>
            <div class="container-fluid px-4">
                <h2 class="mt-4">Completed Reservations</h2>
                <?php
                foreach ($reserves as $reserve_id => $reserve_data) {
                    ?>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <h4>Reserve ID: <?php echo $reserve_id; ?> (<?php echo $reserve_data["user_name"]; ?>)</h4>
                                <table class="table " id="dataAssetTable" width="100%" cellspacing="0">
                                    <thead class="table-blue">
                                        <tr>
                                            <th>Supplier</th>
                                            <th>Asset Model</th>
                                            <th>Asset Serial</th>
                                            <th>Office</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Request Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($reserve_data["reserve_data"] as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row["supplier"]; ?></td>
                                            <td><?php echo $row["model"]; ?></td>
                                            <td><?php echo $row["serial"]; ?></td>
                                            <td><?php echo $row["office"]; ?></td>
                                            <td><?php echo $row["date_start"]; ?></td>
                                            <td><?php echo $row["date_end"]; ?></td>
                                            <td style="height: 40px;">
                                                <?php
                                                if ($row["req_status"] == "Complete") {
                                                    ?>
                                                    <i class='bx bxs-check-circle large-icon' style='color:#93b858' title="<?php echo $row["req_status"]; ?>"></i>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                    ?>
                                    </tbody>
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
include("office_footer.php");
}
?>