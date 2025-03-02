<?php
session_start();
if(empty($_SESSION["username"])){
    header("location:../login.php");
}
else {
    include("office_header.php");
    $office = $_SESSION["office_id"];

    $con=new connec();
    $sql="SELECT r.req_type, r.order_id, u.name, i.model, i.serial, b.brand, i.date_add, i.price, r.req_status, r.action, r.notes
    FROM req r
    JOIN users u ON r.user_id = u.id
    JOIN items i ON r.asset_id = i.id
    JOIN brand b ON i.brand_id = b.id
    WHERE req_type='Maintenance' AND r.req_status IN ('Incomplete', 'Pending') AND u.office_id = '$office';";
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
            "brand" => $row["brand"],
            "serial" => $row["serial"],
            "date_add" => $row["date_add"],
            "price" => $row["price"],
            "req_status" => $row["req_status"],
            "action" => $row["action"],
            "notes" => $row["notes"]
        );
    }
    ?>
    <head>
        <title>Maintenance Requests</title>
    </head>
    <main>
        <?php
        if (empty($orders)) {
            ?>
            <div class="container-fluid px-4">
                <h2 class="mt-4">Maintenance Requests</h2>
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <p>No maintenance requests found.</p>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="container-fluid px-4">
                <h2 class="mt-4">Maintenance Requests</h2>
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
                                }
                                ?>)</h4>
                                <table class="table " id="maintenancereq<?php echo $order_id; ?>" width="100%" cellspacing="0">
                                    <thead class="table-blue">
                                        <tr>
                                            <th>Brand</th>
                                            <th>Asset Model</th>
                                            <th>Asset Serial</th>
                                            <th>Asset Price</th>
                                            <th>Request Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($order_data["order_data"] as $row) {
                                        $date_add = new DateTime($row["date_add"]);
                                        $today = new DateTime();
                                        $years = $today->diff($date_add)->y;

                                        ?>
                                        <tr>
                                            <td><?php echo $row["brand"]; ?></td>
                                            <td><?php echo $row["model"]; ?></td>
                                            <td><?php echo $row["serial"]; ?></td>
                                            <td>₱ <?php echo number_format($row["price"]); ?></td>
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
                                        <td colspan="5">
                                            <h5>Notes:</h5>
                                            <p><?php echo $row["notes"]; ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <tr>
                                        <?php
                                            if ($row["req_status"] == 'Pending'){
                                                $serials = array_column($order_data["order_data"], "serial");
                                                ?>
                                                <td colspan="9">
                                                    <a class="btn btn-primary" style="color: #ffffff" href='repair_maintenancereq.php?order=<?php echo $order_id; ?>'>Repair</a>
                                                    <a class="btn btn-primary" style="color: #ffffff" href='dispose_maintenancereq.php?order=<?php echo $order_id; ?>'>Dispose</a>
                                                    <a class="btn btn-danger" style="color: #ffffff" href='cancel_maintenancereq.php?order=<?php echo $order_id; ?>&serial=<?php echo json_encode($serials); ?>'>Cancel</a>
                                                </td>
                                                <?php
                                            }
                                            else if ($row["req_status"] == 'Incomplete') {
                                                ?>
                                                <td colspan="9">
                                                    <a class="btn btn-primary" style="color: #ffffff" href='complete_maintenancereq.php?order=<?php echo $order_id; ?>'>Complete</a>
                                                </td>
                                                <?php
                                            }
                                            ?>
                                        </tr>
                                    </tfoot>
                                </table>
                                <script>
                                    new DataTable('#maintenancereq<?php echo $order_id; ?>', {
                                        "paging": false,
                                        "lengthChange": true,
                                        "searching": false,
                                        "ordering": true,
                                        "info": false,
                                        "autoWidth": false
                                    });
                                </script>
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