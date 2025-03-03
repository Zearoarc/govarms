<?php
session_start();
include('../log.php');

if (isset($_POST["btn_approve"])) {
    include("../conn.php");
    $order=$_POST['order'];

    $con=new connec();

    $sql="SELECT * FROM req WHERE order_id = '$order'";
    $result=$con->select_by_query($sql);

    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $id = $row["id"];
            $brand=$_POST['brand' . $id];
            $model=$_POST['model' . $id];
            $serial=$_POST['serial' . $id];

            $sql_asset_id = "SELECT id FROM items WHERE model = '$model' AND serial = '$serial'";
            $result_asset_id = $con->select_by_query($sql_asset_id);
            $row_asset_id=$result_asset_id->fetch_assoc();
            $asset_id=$row_asset_id['id'];

            $sql="UPDATE req SET asset_id = '$asset_id', req_status = 'In Transit' WHERE id='$id'";
            $con->update($sql, "Data Updated Successfully");

            $sql_assets="UPDATE items SET status = 'Requested' WHERE model = '$model' AND serial = '$serial'";
            $con->update($sql_assets, "Data Updated Successfully");

            // Call the log function
            log_assetreq($order, $row["user_id"], $_SESSION["office_id"], 'Asset request', 'approved', $asset_id);
        }
    }
    
    header("Location: office_assetreq.php");
}

if (isset($_POST["btn_complete"])) {
    include("../conn.php");
    $order=$_POST['order'];
    $con=new connec();

    $sql="SELECT * FROM req WHERE order_id = '$order'";
    $result=$con->select_by_query($sql);
    $row = $result->fetch_assoc();

    $sql="UPDATE req SET req_status = 'Incomplete' WHERE order_id='$order'";
    $con->update($sql, "Data Updated Successfully");

    log_req($order, $row["user_id"], $_SESSION["office_id"], 'Asset request', 'waiting to be received');

    header("Location: office_assetreq.php");
    exit;

    
}

if(empty($_SESSION["username"])){
    header("location:../login.php");
}
else {
    include("office_header.php");
    $office = $_SESSION["office_id"];

    $con=new connec();
    if (isset($_GET["view"]) && $_GET["view"] == "pending") {
        $sql = "SELECT r.id, r.req_type, t.type, r.order_id, u.name, o.office, r.req_status, r.notes
                FROM req r
                JOIN asset_type t ON r.asset_type_id = t.id
                JOIN users u ON r.user_id = u.id
                JOIN office o ON u.office_id = o.id
                WHERE req_type = 'Asset' AND req_status = 'Pending' AND u.office_id = '$office';";
    } else {
        $sql="SELECT r.id, r.req_type, t.type, r.order_id, u.name, o.office, r.req_status, r.notes
                FROM req r
                JOIN asset_type t ON r.asset_type_id = t.id
                JOIN users u ON r.user_id = u.id
                JOIN office o ON u.office_id = o.id
                WHERE req_type='Asset' AND r.req_status IN ('Incomplete', 'In Transit', 'Pending') AND u.office_id = '$office';";
    }
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
            "id" => $row["id"],
            "req_type" => $row["req_type"],
            "type" => $row["type"],
            "office" => $row["office"],
            "req_status" => $row["req_status"],
            "notes" => $row["notes"]
        );
    }

    foreach ($orders as $order_id => $order_data) {
        foreach ($order_data["order_data"] as $row) {
            $req_id = $row["id"];
            $sql_inc = "SELECT r.id, r.req_type, b.brand, i.model, i.serial, t.type, r.order_id, u.name, o.office, r.req_status, r.notes
            FROM req r
            LEFT JOIN items i ON r.asset_id = i.id
            LEFT JOIN brand b ON i.brand_id = b.id
            JOIN asset_type t ON r.asset_type_id = t.id
            JOIN users u ON r.user_id = u.id
            JOIN office o ON u.office_id = o.id
            WHERE r.id = '$req_id' AND r.req_status IN ('In Transit', 'Incomplete')";
            $result_inc = $con->select_by_query($sql_inc);
            $row_inc = $result_inc->fetch_assoc();
            $brand[$req_id] = $row_inc["brand"] ?? null;
            $model[$req_id] = $row_inc["model"] ?? null;
            $serial[$req_id] = $row_inc["serial"] ?? null;
        } 
    }

    // $sql_inc = "SELECT r.id, r.req_type, b.brand, i.model, i.serial, t.type, r.order_id, u.name, o.office, r.req_status, r.notes
    // FROM req r
    // JOIN items i ON r.asset_id = i.id
    // JOIN brand b ON i.brand_id = b.id
    // JOIN asset_type t ON r.asset_type_id = t.id
    // JOIN users u ON r.user_id = u.id
    // JOIN office o ON u.office_id = o.id
    // WHERE req_type='Asset' AND r.req_status IN ('Incomplete')";
    // $result_inc = $con->select_by_query($sql_inc);
    // $row_inc = $result_inc->fetch_assoc();
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
                                <form method="post">
                                <h4>Order ID: <?php echo $order_id; ?> (<?php echo $order_data["user_name"]; ?>)</h4>
                                <input type="hidden" name="order" value="<?php echo $order_id; ?>">
                                <table class="table " id="assetreq<?php echo $order_id; ?>" width="100%" cellspacing="0">
                                    <thead class="table-blue">
                                        <tr>
                                            <th>Type</th>
                                            <th>Brand</th>
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
                                            <td><?php echo $row["type"]; ?></td>
                                            <?php if ($row["req_status"] == "Pending") { ?>
                                                <td>
                                                    <select id="brand<?php echo $row["id"]; ?>" name="brand<?php echo $row["id"]; ?>" required>
                                                        <option value="" selected disabled>Select Brand</option>
                                                        <?php
                                                        $brand_sql = "SELECT id, brand FROM brand";
                                                        $brand_result = $con->select_by_query($brand_sql);
                                                        while ($brand_row = $brand_result->fetch_assoc()) {
                                                            ?>
                                                            <option value="<?php echo $brand_row["id"]; ?>"><?php echo $brand_row["brand"]; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select id="model<?php echo $row["id"]; ?>" name="model<?php echo $row["id"]; ?>" required>
                                                        <option value="" selected disabled>Select Model</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select id="serial<?php echo $row["id"]; ?>" name="serial<?php echo $row["id"]; ?>" required>
                                                        <option value="" selected disabled>Select Serial</option>
                                                    </select>
                                                </td>
                                            <?php } else if ($row["req_status"] == "In Transit" || $row["req_status"] == "Incomplete") { ?>
                                                <td><?php echo $brand[$row["id"]]; ?></td>
                                                <td><?php echo $model[$row["id"]]; ?></td>
                                                <td><?php echo $serial[$row["id"]]; ?></td>
                                            <?php } ?>
                                            <td><?php echo $row["office"]; ?></td>
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
                                        </tr>
                                        <tr>
                                            <?php
                                            if ($row["req_status"] == 'Pending'){
                                                $serials = array_column($order_data["order_data"], "serial");
                                                ?>
                                                <td colspan="9">
                                                    <button type="submit" class="btn btn-primary" name="btn_approve">Approve</button>
                                                    <a class="btn btn-danger" style="color: #ffffff" href='cancel_assetreq.php?order=<?php echo $order_id; ?>'>Cancel</a>
                                                </td>
                                                <?php
                                            }
                                            else if ($row["req_status"] == 'In Transit') {
                                                ?>
                                                <td colspan="9">
                                                    <button type="submit" class="btn btn-primary" name="btn_complete">Delivered</button>
                                                </td>
                                                <?php
                                            }
                                            ?>
                                        </tr>
                                    </tfoot>
                                </table>
                                </form>
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
    </main>
</body>
</html>

<script>
    // Store the selected serials in an array
    var selectedSerials = [];

    // Function to update the serial dropdowns
    function updateSerialDropdowns() {
        // Clear the selected serials array
        selectedSerials = [];

        // Get all serial dropdowns
        var serialDropdowns = $('[id^="serial"]');

        // Loop through each serial dropdown
        serialDropdowns.each(function() {
            // Get the current serial dropdown
            var currentDropdown = $(this);

            // Get the selected serial
            var selectedSerial = currentDropdown.val();

            // If a serial is already selected, add it to the array
            if (selectedSerial !== '' && selectedSerial !== 'Select Serial') {
                selectedSerials.push(selectedSerial);
            }
        });

        // Loop through each serial dropdown again to disable the options
        serialDropdowns.each(function() {
            // Get the current serial dropdown
            var currentDropdown = $(this);

            // Loop through each option in the current serial dropdown
            currentDropdown.find('option').each(function() {
                // Get the current option
                var currentOption = $(this);

                // If the current option is not the selected serial and it's already in the array, disable it
                if (currentOption.val() !== currentDropdown.val() && selectedSerials.includes(currentOption.val())) {
                    currentOption.attr('disabled', 'disabled');
                } else {
                    currentOption.removeAttr('disabled');
                }
            });
        });
    }

    // Call the function when the page loads
    $(document).ready(function() {
        // Disable the "Select Serial" option
        $('[id^="serial"]').find('option[value=""]').attr('disabled', 'disabled');

        // Update the serial dropdowns
        updateSerialDropdowns();

        // Update the serial dropdowns when a model is changed
        $('[id^="model"]').on('change', function() {
            var modelId = $(this).attr('id').replace('model', '');
            var model = $(this).val();
            $.ajax({
                type: 'POST',
                url: 'get_serials.php',
                data: {model: model},
                success: function(data) {
                    $('#serial' + modelId).html(data);
                    updateSerialDropdowns();
                }
            });
        });

        // Update the serial dropdowns when a serial is changed
        $('[id^="serial"]').on('change', function() {
            updateSerialDropdowns();
        });

        // Update the serial dropdowns when a serial is changed
        $('[id^="serial"]').on('change', function() {
            var currentDropdown = $(this);
            var selectedSerial = currentDropdown.val();

            // Clear the selected serials array
            selectedSerials = [];

            // Get all serial dropdowns
            var serialDropdowns = $('[id^="serial"]');

            // Loop through each serial dropdown
            serialDropdowns.each(function() {
                // Get the current serial dropdown
                var dropdown = $(this);

                // Get the selected serial
                var serial = dropdown.val();

                // If a serial is already selected, add it to the array
                if (serial !== '' && serial !== 'Select Serial') {
                    selectedSerials.push(serial);
                }
            });

            // Loop through each serial dropdown again to disable the options
            serialDropdowns.each(function() {
                // Get the current serial dropdown
                var dropdown = $(this);

                // Loop through each option in the current serial dropdown
                dropdown.find('option').each(function() {
                    // Get the current option
                    var option = $(this);

                    // If the current option is not the selected serial and it's already in the array, disable it
                    if (option.val() !== dropdown.val() && selectedSerials.includes(option.val())) {
                        option.attr('disabled', 'disabled');
                    } else {
                        option.removeAttr('disabled');
                    }
                });
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('[id^="brand"]').on('change', function() {
            var brandId = $(this).attr('id').replace('brand', '');
            var brand = $(this).val();
            $.ajax({
                type: 'POST',
                url: 'get_models.php',
                data: {brand: brand},
                success: function(data) {
                    $('#model' + brandId).html(data);
                }
            });
        });

        $('[id^="model"]').on('change', function() {
            var modelId = $(this).attr('id').replace('model', '');
            var model = $(this).val();
            $.ajax({
                type: 'POST',
                url: 'get_serials.php',
                data: {model: model},
                success: function(data) {
                    $('#serial' + modelId).html(data);
                }
            });
        });
    });
</script>


<?php
include("office_footer.php");
}
?>