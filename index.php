<?php

session_start();
include("header.php");
$con = new connec();

if (empty($_SESSION["username"])) {
    header("location:../login.php");
} else {
    $id = $_SESSION["employee_id"];
    $sql="SELECT u.id, u.name, u.email, u.contact, u.date_add, dept.department, d.division, u.user_role, (SELECT COUNT(r.id) FROM req r WHERE r.user_id = u.id) AS request_count
    FROM users u
    INNER JOIN department dept ON u.dept_id = dept.id
    INNER JOIN division d ON u.division_id = d.id
    WHERE u.id = '$id'";
    $result=$con->select_by_query($sql);
    $row = $result->fetch_assoc();

    $sql_req = "SELECT r.req_type, r.order_id, u.name, a.model, a.serial, s.supplier, d.department, dv.division, r.req_status, r.action
    FROM req r
    JOIN users u ON r.user_id = u.id
    JOIN assets a ON r.asset_id = a.id
    JOIN supplier s ON a.supplier_id = s.id
    JOIN department d ON a.department_id = d.id
    JOIN division dv ON a.division_id = dv.id
    WHERE r.user_id = '$id' AND r.req_status IN ('Incomplete', 'Pending')";
    $result_req = $con->select_by_query($sql_req);

    // Group orders by order ID
    $orders = array();
    while ($row_req = $result_req->fetch_assoc()) {
        $order_id = $row_req["order_id"];
        if (!isset($orders[$order_id])) {
            $orders[$order_id] = array(
                "user_name" => $row_req["name"],
                "order_data" => array()
            );
        }
        $orders[$order_id]["order_data"][] = array(
            "req_type" => $row_req["req_type"],
            "model" => $row_req["model"],
            "supplier" => $row_req["supplier"],
            "serial" => $row_req["serial"],
            "department" => $row_req["department"],
            "division" => $row_req["division"],
            "req_status" => $row_req["req_status"],
            "action" => $row_req["action"]
        );
    }


    $sql_res="SELECT r.reserve_id, u.name, a.model, a.serial, s.supplier, d.department, dv.division, r.date_start, r.date_end, r.req_status
    FROM res r
    JOIN users u ON r.user_id = u.id
    JOIN assets a ON r.asset_id = a.id
    JOIN supplier s ON a.supplier_id = s.id
    JOIN department d ON a.department_id = d.id
    JOIN division dv ON a.division_id = dv.id
    WHERE r.user_id = '$id' AND r.req_status IN ('Incomplete', 'Pending');";
    $result_res=$con->select_by_query($sql_res);

    // Group reservations by reserve ID
    $reserves = array();
    while ($row_res = $result_res->fetch_assoc()) {
        $reserve_id = $row_res["reserve_id"];
        if (!isset($reserves[$reserve_id])) {
            $reserves[$reserve_id] = array(
                "user_name" => $row["name"],
                "reserve_data" => array()
            );
        }
        $reserves[$reserve_id]["reserve_data"][] = array(
            "model" => $row_res["model"],
            "supplier" => $row_res["supplier"],
            "serial" => $row_res["serial"],
            "department" => $row_res["department"],
            "division" => $row_res["division"],
            "date_start" => $row_res["date_start"],
            "date_end" => $row_res["date_end"],
            "req_status" => $row_res["req_status"]
        );
    }
    ?>
    <head>
        <title>User Profile</title>
    </head>

    <main>
        <div class="container-fluid px-4">
        <h2 class="mt-4">User  Profile</h2>
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <h5>User Information</h5>
                        <div class="user-profile-info">
                            <p><b>Name:</b> <?php echo $row["name"]; ?></p>
                            <p><b>Email:</b> <?php echo $row["email"]; ?></p>
                            <p><b>Contact:</b> <?php echo $row["contact"]; ?></p>
                            <p><b>Department:</b> <?php echo $row["department"]; ?></p>
                            <p><b>Division:</b> <?php echo $row["division"]; ?></p>
                            <p><b>User Role:</b> <?php echo $row["user_role"]; ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h5>Profile Actions</h5>
                        <div class="user-profile-actions">
                            <a href="#" class="btn btn-primary">Edit Profile</a>
                            <a href="#" class="btn btn-primary">Change Password</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h5>Request Count</h5>
                        <div class="user-request-count">
                            <p><b>Total Requests:</b> <?php echo $row["request_count"]; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <?php
        if (empty($orders)) {
            ?>
            <div class="container-fluid px-4">
                <h2 class="mt-4">Requests</h2>
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
                <h2 class="mt-4">Requests</h2>
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
                                <table class="table " id="dataAssetTable" width="100%" cellspacing="0">
                                    <thead class="table-blue">
                                        <tr>
                                            <th>Supplier</th>
                                            <th>Asset Model</th>
                                            <th>Asset Serial</th>
                                            <th>Department</th>
                                            <th>Division</th>
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
                                            <td><?php echo $row["department"]; ?></td>
                                            <td><?php echo $row["division"]; ?></td>
                                            <td style="height: 40px;">
                                                <?php
                                                if ($row["req_status"] == "Incomplete") {
                                                    ?>
                                                    <i class='bx bxs-info-circle large-icon' style='color:#ffa83e;' title="<?php echo $row["req_status"]; ?>"></i>
                                                    <?php
                                                } elseif ($row["req_status"] == "Pending") {
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
        <?php
        if (empty($reserves)) {
            ?>
            <div class="container-fluid px-4">
                <h2 class="mt-4">Reservations</h2>
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <p>No asset reservations found.</p>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="container-fluid px-4">
                <h2 class="mt-4">Reservations</h2>
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
                                            <th>Department</th>
                                            <th>Division</th>
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
                                            <td><?php echo $row["department"]; ?></td>
                                            <td><?php echo $row["division"]; ?></td>
                                            <td><?php echo $row["date_start"]; ?></td>
                                            <td><?php echo $row["date_end"]; ?></td>
                                            <td style="height: 40px;">
                                                <?php
                                                if ($row["req_status"] == "Incomplete") {
                                                    ?>
                                                    <i class='bx bxs-info-circle large-icon' style='color:#ffa83e;' title="<?php echo $row["req_status"]; ?>"></i>
                                                    <?php
                                                } elseif ($row["req_status"] == "Pending") {
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

    <?php

    include("footer.php");
}

?>