<?php

session_start();
include("header.php");
$con = new connec();

if (empty($_SESSION["username"])) {
    header("location:login.php");
} else {
    $id = $_SESSION["employee_id"];
    $sql_req = "SELECT r.req_type, r.order_id, u.name, a.model, a.serial, s.supplier, t.type, o.office, r.req_status, r.action
    FROM req r
    JOIN users u ON r.user_id = u.id
    JOIN assets a ON r.asset_id = a.id
    JOIN supplier s ON a.supplier_id = s.id
    JOIN asset_type t ON a.type_id = t.id
    JOIN office o ON a.office_id = o.id
    WHERE r.user_id = '$id' AND r.req_type = 'Asset' AND r.req_status = 'Complete'";
    $result_req = $con->select_by_query($sql_req);


    $sql_res="SELECT r.reserve_id, u.name, a.model, a.serial, s.supplier, t.type, o.office, r.date_start, r.date_end, r.req_status
    FROM res r
    JOIN users u ON r.user_id = u.id
    JOIN assets a ON r.asset_id = a.id
    JOIN supplier s ON a.supplier_id = s.id
    JOIN asset_type t ON a.type_id = t.id
    JOIN office o ON a.office_id = o.id
    WHERE r.req_status = 'Complete';";
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
                    <table class="table " id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Supplier</th>
                                        <th>Type</th>
                                        <th>Asset Model</th>
                                        <th>Asset Serial</th>
                                        <th>Office</th>
                                        <th>Request Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if($result_req->num_rows>0){
                                            while($row=$result_req->fetch_assoc()){
                                                ?>
                                                <tr>
                                                    <td><?php echo $row["supplier"]; ?></td>
                                                    <td><?php echo $row["type"]; ?></td>
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
                                        }
                                        ?>
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid px-4">
            <h2 class="mt-4">Reservations</h2>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Supplier</th>
                                <th>Type</th>
                                <th>Asset Model</th>
                                <th>Asset Serial</th>
                                <th>Office</th>
                                <th>Date Start</th>
                                <th>Date End</th>
                                <th>Request Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result_res->num_rows > 0) {
                            while ($row_res = $result_res->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td><?php echo $row_res["supplier"]; ?></td>
                                    <td><?php echo $row_res["type"]; ?></td>
                                    <td><?php echo $row_res["model"]; ?></td>
                                    <td><?php echo $row_res["serial"]; ?></td>
                                    <td><?php echo $row_res["office"]; ?></td>
                                    <td><?php echo $row_res["date_start"]; ?></td>
                                    <td><?php echo $row_res["date_end"]; ?></td>
                                    <td><?php echo $row_res["req_status"]; ?></td>
                                </tr>
                                <?php
                            }
                            }
                            ?>
                        </tbody>
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