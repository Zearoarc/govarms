<?php
session_start();
if(empty($_SESSION["username"])){
    header("location:../login.php");
}
else {
    include("admin_header.php");

    $con=new connec();
    $sql="SELECT r.reserve_id, u.name, a.model, a.serial, s.supplier, o.office, r.date_start, r.date_end, r.req_status, r.req_level
    FROM res r
    JOIN users u ON r.user_id = u.id
    JOIN assets a ON r.asset_id = a.id
    JOIN supplier s ON a.supplier_id = s.id
    JOIN office o ON a.office_id = o.id
    WHERE r.req_level='admin' AND r.req_status IN ('Incomplete', 'Pending');";
    $result=$con->select_by_query($sql);

    // Group reservations by reserve ID
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
        <title>Asset Reservation Requests</title>
    </head>
    <main>
        <?php
        if (empty($reserves)) {
            ?>
            <div class="container-fluid px-4">
                <h2 class="mt-4">Asset Reservation Requests</h2>
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <p>No asset reservation requests found.</p>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="container-fluid px-4">
                <h2 class="mt-4">Asset Reservation Requests</h2>
                <?php
                foreach ($reserves as $reserve_id => $reserve_data) {
                    ?>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <h4>Reservation ID: <?php echo $reserve_id; ?> (<?php echo $reserve_data["user_name"]; ?>)</h4>
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
                                                $serials = array_column($reserve_data["reserve_data"], "serial");
                                                ?>
                                                <td colspan="9">
                                                    <a class="btn btn-primary" style="color: #ffffff" href='approve_assetres.php?reserve=<?php echo $reserve_id; ?>'>Approve</a>
                                                    <a class="btn btn-danger" style="color: #ffffff" href='cancel_assetres.php?reserve=<?php echo $reserve_id; ?>&serial=<?php echo json_encode($serials); ?>'>Cancel</a>
                                                </td>
                                                <?php
                                            }
                                            else if ($row["req_status"] == 'Incomplete') {
                                                ?>
                                                <td colspan="9">
                                                    <a class="btn btn-primary" style="color: #ffffff" href='complete_assetres.php?reserve=<?php echo $reserve_id; ?>'>Complete</a>
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