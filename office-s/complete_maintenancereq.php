<?php
include("../conn.php");
$order=$_GET['order'];
$con=new connec();

$sql="SELECT * FROM req WHERE order_id='$order'";
$result=$con->select_by_query($sql);
$row = $result->fetch_assoc();
$action = $row["action"];
$asset_id = $row["asset_id"];

if ($action == 'repair') {
    // Update row
    $sql = "UPDATE req SET req_status = 'Complete' WHERE order_id='$order'";
    $con->update($sql, "Data Updated Successfully");
} elseif ($action == 'dispose') {
    // Delete row
    $sql = "DELETE FROM assets WHERE id='$asset_id'";
    $con->delete($sql, "Data Deleted Successfully");
    $sql = "DELETE FROM req WHERE asset_id='$asset_id'";
    $con->delete($sql, "Data Deleted Successfully");
}

header("Location: office_maintenancereq.php");
exit;
?>