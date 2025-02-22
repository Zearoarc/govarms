<?php

session_start();
include("header.php");
$con = new connec();

if (empty($_SESSION["username"])) {
    header("location:login.php");
} else {
    $id = $_SESSION["employee_id"];
    $sql="SELECT u.id, u.name, u.email, u.contact, u.date_add, o.office, u.user_role, (SELECT COUNT(r.id) FROM req r WHERE r.user_id = u.id) AS request_count
    FROM users u
    JOIN office o ON u.office_id = o.id
    WHERE u.id = '$id'";
    $result=$con->select_by_query($sql);
    $row = $result->fetch_assoc();

    $sql_req = "SELECT r.req_type, r.asset_type_id, t.type, t.category, r.date_add, r.date_expected, r.order_id, u.name, r.amount, r.req_status, r.action
    FROM req r
    JOIN users u ON r.user_id = u.id
    JOIN asset_type t ON r.asset_type_id = t.id
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
            "asset_type_id" => $row_req["asset_type_id"],
            "type" => $row_req["type"],
            "category" => $row_req["category"],
            "date_add" => $row_req["date_add"],
            "date_expected" => $row_req["date_expected"],
            "name" => $row_req["name"],
            "amount" => $row_req["amount"],
            "req_status" => $row_req["req_status"],
            "action" => $row_req["action"]
        );
    }


    $sql_res="SELECT r.reserve_id, u.name, a.model, a.serial, b.brand, o.office, r.date_start, r.date_end, r.req_status
    FROM res r
    JOIN users u ON r.user_id = u.id
    JOIN assets a ON r.asset_id = a.id
    JOIN brand b ON a.brand_id = b.id
    JOIN office o ON a.office_id = o.id
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
            "brand" => $row_res["brand"],
            "serial" => $row_res["serial"],
            "office" => $row_res["office"],
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
                            <p><b>Office:</b> <?php echo $row["office"]; ?></p>
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
                <h2 class="mt-4">Asset Requests</h2>
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
                                            <th>Asset Type</th>
                                            <th>Asset Category</th>
                                            <th>Date Requested</th>
                                            <th>Date Expected</th>
                                            <th>Amount</th>
                                            <th>Request Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($order_data["order_data"] as $row) {
                                        ?>
                                        <tr>
                                            <td style="width: 250px;"><?php echo $row["type"]; ?></td>
                                            <td><?php echo $row["category"]; ?></td>
                                            <td><?php echo $row["date_add"]; ?></td>
                                            <td><?php echo $row["date_expected"]; ?></td>
                                            <td><?php echo $row["amount"]; ?></td>
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
                                            <td style="width: 120px;">
                                            <?php
                                                if ($row["req_status"] == 'Incomplete' && $row["action"] == 'none') {
                                                    ?>
                                                    <a class='btn btn-primary btn-sm' href='complete_assetreq_one.php?order=<?php echo $order_id; ?>&asset_type=<?php echo $row["asset_type_id"]; ?>'>Received</a>
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
                                            if ($row["req_status"] == 'Incomplete' && $row["action"] == 'none') {
                                                ?>
                                                <td colspan="9">
                                                    <a class="btn btn-primary" style="color: #ffffff" href='complete_assetreq.php?order=<?php echo $order_id; ?>'>Received All</a>
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
        <?php
        if (empty($reserves)) {
            ?>
            <div class="container-fluid px-4">
                <h2 class="mt-4">Asset Reservations</h2>
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
                                            <th>Brand</th>
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
                                            <td><?php echo $row["brand"]; ?></td>
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

    <?php

    include("footer.php");
}

?>