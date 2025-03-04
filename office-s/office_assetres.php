<?php
session_start();
include('../log.php');

if (isset($_POST["btn_approve"])) {
    include("../conn.php");
    $reserve=$_POST['reserve'];

    $con=new connec();

    $sql="SELECT * FROM res WHERE reserve_id = '$reserve'";
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

            $sql="UPDATE res SET asset_id = '$asset_id', req_status = 'In Transit' WHERE id='$id'";
            $con->update($sql, "Data Updated Successfully");

            $sql_assets="UPDATE items SET borrow_times = borrow_times + 1, status = 'Requested' WHERE model = '$model' AND serial = '$serial'";
            $con->update($sql_assets, "Data Updated Successfully");

            // Call the log function
            log_assetres($reserve, $row["user_id"], $_SESSION["office_id"], 'Asset reservation', 'approved', $asset_id);
        }
    }
    header("Location: office_assetres.php");
}

if (isset($_POST["btn_complete"])) {
    include("../conn.php");
    $reserve=$_POST['reserve'];
    $con=new connec();

    $sql="SELECT * FROM res WHERE reserve_id = '$reserve'";
    $result=$con->select_by_query($sql);
    $row = $result->fetch_assoc();

    $sql="UPDATE res SET req_status = 'Incomplete' WHERE reserve_id='$reserve'";
    $con->update($sql, "Data Updated Successfully");

    log_res($reserve, $row["user_id"], $_SESSION["office_id"], 'Asset reservation', 'waiting to be received');

    header("Location: office_assetres.php");
    exit;
}

if(empty($_SESSION["username"])){
    header("location:../login.php");
}
else {
    include("office_header.php");
    $office=$_SESSION['office_id'];

    $con=new connec();
    $sql="SELECT r.id, r.asset_type_id, t.type, r.reserve_id, u.name, o.office, r.date_start, r.date_end, r.req_status, r.notes
    FROM res r
    JOIN asset_type t ON r.asset_type_id = t.id
    JOIN users u ON r.user_id = u.id
    JOIN office o ON u.office_id = o.id
    WHERE r.req_status IN ('Incomplete', 'In Transit', 'Pending')
    AND u.office_id=$office;";
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
            "id" => $row["id"],
            "asset_type_id" => $row["asset_type_id"],
            "type" => $row["type"],
            "date_start" => $row["date_start"],
            "date_end" => $row["date_end"],
            "req_status" => $row["req_status"],
            "office" => $row["office"],
            "notes" => $row["notes"]
        );
    }

    $sql_inc = "SELECT r.id, b.brand, i.model, i.serial, t.type, r.reserve_id, u.name, o.office, r.date_start, r.date_end, r.req_status
    FROM res r
    JOIN items i ON r.asset_id = i.id
    JOIN brand b ON i.brand_id = b.id
    JOIN asset_type t ON r.asset_type_id = t.id
    JOIN users u ON r.user_id = u.id
    JOIN office o ON u.office_id = o.id
    WHERE r.req_status IN ('Incomplete', 'In Transit')";
    $result_inc = $con->select_by_query($sql_inc);
    $row_inc = $result_inc->fetch_assoc();
    ?>
    <head>
        <title>Asset Borrowing Requests</title>
    </head>
    <main>
        <?php
        if (empty($reserves)) {
            ?>
            <div class="container-fluid px-4">
                <h2 class="mt-4">Asset Borrowing Requests</h2>
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <p>No asset borrowing requests found.</p>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="container-fluid px-4">
                <h2 class="mt-4">Asset Borrowing Requests</h2>
                <?php
                foreach ($reserves as $reserve_id => $reserve_data) {
                    ?>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <form method="post">
                                <h4>Borrowing ID: <?php echo $reserve_id; ?> (<?php echo $reserve_data["user_name"]; ?>)</h4>
                                <input type="hidden" name="reserve" value="<?php echo $reserve_id; ?>">
                                <table class="table " id="assetres<?php echo $reserve_id; ?>" width="100%" cellspacing="0">
                                    <thead class="table-blue">
                                        <tr>
                                            <th>Type</th>
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
                                            <td><?php echo $row["type"]; ?></td>
                                            <?php if ($row["req_status"] == "Pending") { ?>
                                                <td>
                                                    <select id="brand<?php echo $row["id"]; ?>" name="brand<?php echo $row["id"]; ?>" required>
                                                        <option value="" selected disabled>Select Brand</option>
                                                        <?php
                                                        $asset_type_id = $row["asset_type_id"];
                                                        $brand_sql = "SELECT b.id, b.brand, i.model, i.serial FROM items i
                                                                    JOIN brand b ON i.brand_id = b.id
                                                                    WHERE i.asset_type_id=$asset_type_id AND i.status='Available'
                                                                    GROUP BY b.brand";
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
                                            <?php } else { ?>
                                                <td><?php echo $row_inc["brand"]; ?></td>
                                                <td><?php echo $row_inc["model"]; ?></td>
                                                <td><?php echo $row_inc["serial"]; ?></td>
                                            <?php  } ?>
                                            <td><?php echo $row["office"]; ?></td>
                                            <td><?php echo $row["date_start"]; ?></td>
                                            <td><?php echo $row["date_end"]; ?></td>
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
                                                $serials = array_column($reserve_data["reserve_data"], "serial");
                                                ?>
                                                <td colspan="9">
                                                    <button type="submit" class="btn btn-primary" name="btn_approve">Approve</button>
                                                    <a class="btn btn-danger" style="color: #ffffff" href='cancel_assetres.php?reserve=<?php echo $reserve_id; ?>'>Cancel</a>
                                                </td>
                                                <?php
                                            }
                                            else if ($row["req_status"] == 'In Transit') {
                                                ?>
                                                <td colspan="9">
                                                <button type="submit" class="btn btn-primary" name="btn_complete">For Release</button>
                                                </td>
                                                <?php
                                            }
                                            ?>
                                        </tr>
                                    </tfoot>
                                </table>
                                </form>
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