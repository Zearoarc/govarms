<?php

// Start session
session_start();

// Include database connection
include("conn.php");
$con=new connec();
// Check if asset_id is set
if (isset($_GET["asset_id"])) {
    $asset_id = $_GET["asset_id"];

    // Update res table
    $sql_update_res = "UPDATE res SET action = 'returned' WHERE asset_id = '$asset_id'";
    $con->update($sql_update_res, "Data Updated Successfully");

    // Update assets table
    $sql_update_assets = "UPDATE assets SET status = 'Available' WHERE id = '$asset_id'";
    $con->update($sql_update_assets, "Data Updated Successfully");

    // Redirect to assets page
    header("location:assets.php");
} else {
    // Handle error
    echo "Error: asset_id not set";
}

?>