<?php
session_start();
if(empty($_SESSION["username"])){
    header("location:../login.php");
}
else {
    include("office_header.php");
    $office = $_SESSION["office_id"];

    $con=new connec();
    $sql="SELECT r.req_type, r.order_id, u.name, i.model, i.serial, b.brand, r.req_status, r.action
    FROM req r
    JOIN users u ON r.user_id = u.id
    LEFT JOIN items i ON r.asset_id = i.id
    LEFT JOIN brand b ON i.brand_id = b.id
    WHERE r.req_status = 'Complete' AND u.office_id = '$office';";
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
            "req_status" => $row["req_status"],
            "action" => $row["action"]
        );
    }

    $sql_supp = "SELECT s.id, t.type, t.category, s.date_add, s.date_expected, s.order_id, u.name, s.amount, s.req_status
    FROM supp s
    JOIN supply_type t ON s.supply_type_id = t.id
    JOIN users u ON s.user_id = u.id
    WHERE s.req_status = 'Complete' AND u.office_id = '$office'";
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
            "req_status" => $row_supp["req_status"]
        );
    }


    $sql="SELECT r.reserve_id, u.name, i.model, i.serial, b.brand, o.office, r.date_start, r.date_end, r.req_status
    FROM res r
    JOIN users u ON r.user_id = u.id
    JOIN items i ON r.asset_id = i.id
    JOIN brand b ON i.brand_id = b.id
    JOIN office o ON i.office_id = o.id
    WHERE r.req_status = 'Complete' AND u.office_id = '$office';";
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
            "brand" => $row["brand"],
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
                                <table class="table " id="completedreq<?php echo $order_id; ?>" width="100%" cellspacing="0">
                                    <thead class="table-blue">
                                        <tr>
                                            <th>Brand</th>
                                            <th>Asset Model</th>
                                            <th>Asset Serial</th>
                                            <th>Request Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($order_data["order_data"] as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row["brand"]; ?></td>
                                            <td><?php echo $row["model"]; ?></td>
                                            <td><?php echo $row["serial"]; ?></td>
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
                                <script>
                                    new DataTable('#completedreq<?php echo $order_id; ?>', {
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
            <div class="container-fluid px-4">
                <h2 class="mt-4">Completed Reservations</h2>
                <?php
                foreach ($reserves as $reserve_id => $reserve_data) {
                    ?>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <h4>Reserve ID: <?php echo $reserve_id; ?> (<?php echo $reserve_data["user_name"]; ?>)</h4>
                                <table class="table " id="completedres<?php echo $reserve_id; ?>" width="100%" cellspacing="0">
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
                                <script>
                                    new DataTable('#completedres<?php echo $reserve_id; ?>', {
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
            <div class="container-fluid px-4">
                <h2 class="mt-4">Completed Supply Requests</h2>
                <?php
                foreach ($supply_orders as $order_id => $order_data) {
                    ?>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <h4>Order ID: <?php echo $order_id; ?> (<?php echo $order_data["user_name"]; ?>)</h4>
                                <table class="table " id="completedsupp<?php echo $order_id; ?>" width="100%" cellspacing="0">
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
                                <script>
                                    new DataTable('#completedsupp<?php echo $order_id; ?>', {
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