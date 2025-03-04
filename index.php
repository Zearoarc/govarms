<?php

session_start();
include("header.php");
$con = new connec();

if (empty($_SESSION["username"])) {
    header("location:login.php");
} else {
    $id = $_SESSION["employee_id"];
    $sql="SELECT u.id, u.name, u.email, u.contact, u.date_add, o.office, u.user_role, (SELECT COUNT(r.order_id) FROM req r WHERE r.user_id = u.id) AS request_count, (SELECT COUNT(res.reserve_id) FROM res res WHERE res.user_id = u.id) AS reservation_count
    FROM users u
    JOIN office o ON u.office_id = o.id
    WHERE u.id = '$id'";
    $result=$con->select_by_query($sql);
    $row = $result->fetch_assoc();

    $sql_req = "SELECT r.req_type, r.asset_type_id, i.model, r.asset_id, t.type, t.category, r.date_add, r.date_expected, r.order_id, u.name, r.req_status, r.action, r.notes
    FROM req r
    JOIN users u ON r.user_id = u.id
    JOIN asset_type t ON r.asset_type_id = t.id
    LEFT JOIN items i ON r.asset_id = i.id
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
            "model" => $row_req["model"],
            "asset_id" => $row_req["asset_id"],
            "type" => $row_req["type"],
            "category" => $row_req["category"],
            "date_add" => $row_req["date_add"],
            "date_expected" => $row_req["date_expected"],
            "name" => $row_req["name"],
            "req_status" => $row_req["req_status"],
            "action" => $row_req["action"],
            "notes" => $row_req["notes"]
        );
    }

    $sql_supp = "SELECT s.id, t.type, t.category, s.date_add, s.date_expected, s.order_id, u.name, s.amount, s.req_status, s.notes
    FROM supp s
    JOIN supply_type t ON s.supply_type_id = t.id
    JOIN users u ON s.user_id = u.id
    WHERE s.user_id = '$id' AND s.req_status IN ('Incomplete', 'Pending')";
    $result_supp = $con->select_by_query($sql_supp);

    // Group supply requests by order ID
    $supply_orders = array();
    while ($row_supp = $result_supp->fetch_assoc()) {
        $order_id = $row_supp["order_id"];
        if (!isset($supply_orders[$order_id])) {
            $supply_orders[$order_id] = array(
                "user_name" => $row_supp["name"],
                "order_data" => array()
            );
        }
        $supply_orders[$order_id]["order_data"][] = array(
            "type" => $row_supp["type"],
            "category" => $row_supp["category"],
            "date_add" => $row_supp["date_add"],
            "date_expected" => $row_supp["date_expected"],
            "amount" => $row_supp["amount"],
            "req_status" => $row_supp["req_status"],
            "notes" => $row_supp["notes"]
        );
    }


    $sql_res="SELECT r.asset_type_id, i.model, r.asset_id, t.type, t.category, r.date_add, r.date_expected, r.reserve_id, u.name, r.req_status, r.action, r.notes
    FROM res r
    JOIN users u ON r.user_id = u.id
    JOIN asset_type t ON r.asset_type_id = t.id
    LEFT JOIN items i ON r.asset_id = i.id
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
            "asset_type_id" => $row_res["asset_type_id"],
            "model" => $row_res["model"],
            "asset_id" => $row_res["asset_id"],
            "type" => $row_res["type"],
            "category" => $row_res["category"],
            "date_add" => $row_res["date_add"],
            "date_expected" => $row_res["date_expected"],
            "name" => $row_res["name"],
            "req_status" => $row_res["req_status"],
            "action" => $row_res["action"],
            "notes" => $row_res["notes"]
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
                        <div class="user-profile-info">
                            <h2> <?php echo $row["name"]; ?></h2><br>
                            <p><b>Email:</b> <?php echo $row["email"]; ?></p>
                            <p><b>Contact:</b> <?php echo $row["contact"]; ?></p>
                            <p><b>Office:</b> <?php echo $row["office"]; ?></p>
                            <p><b>User Role:</b> <?php echo $row["user_role"]; ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h5>Profile Actions</h5>
                        <div class="user-profile-actions">
                            <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
                            <a href="change_password.php" class="btn btn-primary">Change Password</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h5>Request Count</h5>
                        <div class="user-request-count">
                            <p><b>Total Requests:</b> <?php echo $row["request_count"]; ?></p>
                        </div>
                        <h5>Borrow Count</h5>
                        <div class="user-reservation-count">
                            <p><b>Total Borrows:</b> <?php echo $row["reservation_count"]; ?></p>
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
                                <h4>Order ID: <?php echo $order_id; ?> (<?php echo $order_data["user_name"];
                                if ($order_data["order_data"][0]["action"] == 'repair') {
                                    echo ' - For Repair';
                                } elseif ($order_data["order_data"][0]["action"] == 'dispose') {
                                    echo ' - For Disposal';
                                }
                                ?>)</h4>
                                <table class="table " id="assetreq<?php echo $order_id; ?>" width="100%" cellspacing="0">
                                    <thead class="table-blue">
                                        <tr>
                                            <th>Asset Type</th>
                                            <th>Category</th>
                                            <th>Model</th>
                                            <th>Date Requested</th>
                                            <th>Date Expected</th>
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
                                            <td><?php echo $row["model"]; ?></td>
                                            <td><?php echo $row["date_add"]; ?></td>
                                            <td><?php echo $row["date_expected"]; ?></td>
                                            <td style="height: 40px;">
                                                <?php
                                                if ($row["req_status"] == "Incomplete") {
                                                    ?>
                                                    <i class='bx bxs-info-circle large-icon' style='color:#ffa83e;' title="<?php echo $row["req_status"]; ?>"></i>
                                                    <?php
                                                } elseif ($row["req_status"] == "In Transit") {
                                                    ?>
                                                    <i class='bx bxs-truck large-icon' style='color:#007BFF' title="<?php echo $row["req_status"]; ?>"></i>
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
                                                    <a class='btn btn-primary btn-sm' href='complete_assetreq_one.php?order=<?php echo $order_id; ?>&asset_id=<?php echo $row["asset_id"]; ?>'>Received</a>
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
                                <script>
                                    new DataTable('#assetreq<?php echo $order_id; ?>', {
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
        <?php
        if (empty($supply_orders)) {
            ?>
            <div class="container-fluid px-4">
                <h2 class="mt-4">Supply Requests</h2>
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <p>No supply requests found.</p>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="container-fluid px-4">
                <h2 class="mt-4">Supply Requests</h2>
                <?php
                foreach ($supply_orders as $order_id => $order_data) {
                    ?>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <h4>Order ID: <?php echo $order_id; ?> (<?php echo $order_data["user_name"]; ?>)</h4>
                                <table class="table " id="supplyreq<?php echo $order_id; ?>" width="100%" cellspacing="0">
                                    <thead class="table-blue">
                                        <tr>
                                            <th>Supply Type</th>
                                            <th>Supply Category</th>
                                            <th>Date Requested</th>
                                            <th>Date Expected</th>
                                            <th>Request Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($order_data["order_data"] as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row["type"]; ?></td>
                                            <td><?php echo $row["category"]; ?></td>
                                            <td><?php echo $row["date_add"]; ?></td>
                                            <td><?php echo $row["date_expected"]; ?></td>
                                            <td style="height: 40px;">
                                                <?php
                                                if ($row["req_status"] == "Incomplete") {
                                                    ?>
                                                    <i class='bx bxs-info-circle large-icon' style='color:#ffa83e;' title="<?php echo $row["req_status"]; ?>"></i>
                                                    <?php
                                                } elseif ($row["req_status"] == "In Transit") {
                                                    ?>
                                                    <i class='bx bxs-truck large-icon' style='color:#007BFF' title="<?php echo $row["req_status"]; ?>"></i>
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
                                            <td colspan="5">
                                                <h5>Notes:</h5>
                                                <p><?php echo $row["notes"]; ?></p>
                                            </td>
                                            <tr>
                                                <?php
                                                if ($row["req_status"] == 'Incomplete') {
                                                    ?>
                                                    <td colspan="9">
                                                        <a class="btn btn-primary" style="color: #ffffff" href='complete_supplyreq.php?order=<?php echo $order_id; ?>'>Received All</a>
                                                    </td>
                                                    <?php
                                                }
                                                ?>
                                            </tr>
                                        </tr>
                                    </tfoot>
                                </table>
                                <script>
                                    new DataTable('#supplyreq<?php echo $order_id; ?>', {
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
        <?php
        if (empty($reserves)) {
            ?>
            <div class="container-fluid px-4">
                <h2 class="mt-4">Borrowing</h2>
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <p>No borrowing found.</p>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="container-fluid px-4">
                <h2 class="mt-4">Borrowing</h2>
                <?php
                foreach ($reserves as $reserve_id => $reserve_data) {
                    ?>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <h4>Reserve ID: <?php echo $reserve_id; ?> (<?php echo $reserve_data["user_name"]; ?>)</h4>
                                <table class="table " id="assetres<?php echo $reserve_id; ?>" width="100%" cellspacing="0">
                                    <thead class="table-blue">
                                        <tr>
                                            <th>Asset Type</th>
                                            <th>Category</th>
                                            <th>Model</th>
                                            <th>Date Requested</th>
                                            <th>Date Expected</th>
                                            <th>Request Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($reserve_data["reserve_data"] as $row) {
                                        ?>
                                        <tr>
                                            <td style="width: 250px;"><?php echo $row["type"]; ?></td>
                                            <td><?php echo $row["category"]; ?></td>
                                            <td><?php echo $row["model"]; ?></td>
                                            <td><?php echo $row["date_add"]; ?></td>
                                            <td><?php echo $row["date_expected"]; ?></td>
                                            <td style="height: 40px;">
                                                <?php
                                                if ($row["req_status"] == "Incomplete") {
                                                    ?>
                                                    <i class='bx bxs-info-circle large-icon' style='color:#ffa83e;' title="<?php echo $row["req_status"]; ?>"></i>
                                                    <?php
                                                } elseif ($row["req_status"] == "In Transit") {
                                                    ?>
                                                    <i class='bx bxs-truck large-icon' style='color:#007BFF' title="<?php echo $row["req_status"]; ?>"></i>
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
                                                    <a class='btn btn-primary btn-sm' href='complete_assetres_one.php?reserve=<?php echo $reserve_id; ?>&asset_id=<?php echo $row["asset_id"]; ?>'>Received</a>
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
                                            <?php
                                            if ($row["req_status"] == 'Incomplete' && $row["action"] == 'none') {
                                                ?>
                                                <td colspan="9">
                                                    <a class="btn btn-primary" style="color: #ffffff" href='complete_assetres.php?reserve=<?php echo $reserve_id; ?>'>Received All</a>
                                                </td>
                                                <?php
                                            }
                                            ?>
                                        </tr>
                                    </tfoot>
                                </table>
                                <script>
                                    new DataTable('#assetres<?php echo $reserve_id; ?>', {
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

    <?php

    include("footer.php");
}

?>