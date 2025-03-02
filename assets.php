<?php

session_start();
include("header.php");
$con = new connec();

if (empty($_SESSION["username"])) {
    header("location:login.php");
} else {
    $id = $_SESSION["employee_id"];
    $sql_req = "SELECT r.req_type, r.order_id, r.asset_id, u.name, i.model, i.serial, b.brand, t.type, o.office, r.req_status, r.action,
    (SELECT COUNT(*) FROM req WHERE asset_id = r.asset_id AND req_type = 'Maintenance' AND req_status IN ('Incomplete', 'Pending')) AS maintenance_pending
    FROM req r
    JOIN users u ON r.user_id = u.id
    JOIN items i ON r.asset_id = i.id
    JOIN brand b ON i.brand_id = b.id
    JOIN asset_type t ON i.asset_type_id = t.id
    JOIN office o ON i.office_id = o.id
    WHERE r.user_id = '$id' AND r.req_type = 'Asset' AND r.req_status = 'Complete' AND i.status='Requested'";
    $result_req = $con->select_by_query($sql_req);


    $sql_res="SELECT r.reserve_id, r.asset_id, u.name, i.model, i.serial, b.brand, t.type, o.office, r.date_start, r.date_end, r.req_status
    FROM res r
    JOIN users u ON r.user_id = u.id
    JOIN items i ON r.asset_id = i.id
    JOIN brand b ON i.brand_id = b.id
    JOIN asset_type t ON i.asset_type_id = t.id
    JOIN office o ON i.office_id = o.id
    WHERE r.req_status = 'Complete' AND r.action = 'none';";
    $result_res=$con->select_by_query($sql_res);
    ?>

    <head>
        <title>Assets</title>
    </head>

    <main>
        <div class="container-fluid px-4">
            <h2 class="mt-4">Owned Assets</h2>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table " id="assets" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Brand</th>
                                        <th>Type</th>
                                        <th>Asset Model</th>
                                        <th>Asset Serial</th>
                                        <th>Office</th>
                                        <th>Request Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if($result_req->num_rows>0){
                                            while($row=$result_req->fetch_assoc()){
                                                ?>
                                                <tr>
                                                    <td><?php echo $row["brand"]; ?></td>
                                                    <td><?php echo $row["type"]; ?></td>
                                                    <td><?php echo $row["model"]; ?></td>
                                                    <td><?php echo $row["serial"]; ?></td>
                                                    <td><?php echo $row["office"]; ?></td>
                                                    <td style="width: 250px;">
                                                        <?php
                                                        if ($row["req_status"] == "Complete") {
                                                            ?>
                                                            <i class='bx bxs-check-circle large-icon' style='color:#93b858' title="<?php echo $row["req_status"]; ?>"></i>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                    <?php
                                                    if ($row["maintenance_pending"] == 0) {
                                                        ?>
                                                        <a href="confirm_maintenance.php?asset_id=<?php echo $row["asset_id"]; ?>" class="btn btn-primary btn-sm">Maintenance</a>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        In Maintenance
                                                        <?php
                                                    }
                                                    ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                </tbody>
                            </table>
                            <script>
                                new DataTable('#assets', {
                                    "paging": false,
                                    "lengthChange": true,
                                    "searching": true,
                                    "ordering": true,
                                    "info": false,
                                    "autoWidth": false
                                });
                            </script>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid px-4">
            <h2 class="mt-4">Reservations</h2>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="reservations" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Brand</th>
                                <th>Type</th>
                                <th>Asset Model</th>
                                <th>Asset Serial</th>
                                <th>Office</th>
                                <th>Date Start</th>
                                <th>Date End</th>
                                <th>Request Status</th>
                                <th>Reservation Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result_res->num_rows > 0) {
                            while ($row_res = $result_res->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td><?php echo $row_res["brand"]; ?></td>
                                    <td><?php echo $row_res["type"]; ?></td>
                                    <td><?php echo $row_res["model"]; ?></td>
                                    <td><?php echo $row_res["serial"]; ?></td>
                                    <td><?php echo $row_res["office"]; ?></td>
                                    <td><?php echo $row_res["date_start"]; ?></td>
                                    <td><?php echo $row_res["date_end"]; ?></td>
                                    <td style="height: 40px;">
                                        <?php
                                        if ($row_res["req_status"] == "Complete") {
                                            ?>
                                            <i class='bx bxs-check-circle large-icon' style='color:#93b858' title="<?php echo $row_res["req_status"]; ?>"></i>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <td>
                                    <?php
                                    if ($row_res["date_end"] < date("Y-m-d")) {
                                        ?>
                                        <span class="badge badge-danger">Overdue</span>
                                        <?php
                                    } else {
                                        ?>
                                        <span class="badge badge-success">On Track</span>
                                        <?php
                                    }
                                    ?>
                                    </td>
                                    <td>
                                    <a href="return_asset.php?asset_id=<?php echo $row_res["asset_id"]; ?>" class="btn btn-primary btn-sm">Return</a>
                                    </td>
                                </tr>
                                <?php
                                }
                            }
                            ?>
                        </tbody>
                        </table>
                        <script>
                            new DataTable('#reservations', {
                                "paging": false,
                                "lengthChange": true,
                                "searching": true,
                                "ordering": true,
                                "info": false,
                                "autoWidth": false
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </main>





<?php

    include("footer.php");
}
?>