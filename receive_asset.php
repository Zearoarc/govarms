<?php
session_start();
include("conn.php");
include('log.php');
$asset_id = $_GET['asset_id'];
$con=new connec();

$sql="SELECT * FROM req WHERE asset_id = '$asset_id' AND req_type='Maintenance' AND req_status='Incomplete'";
$result=$con->select_by_query($sql);
$row = $result->fetch_assoc();

$order_id = $row['order_id'];
log_assetreq($order_id, $row["user_id"], $_SESSION["office_id"], 'Maintenance request', 'received', $asset_id);

$sql="UPDATE req SET req_status = 'Complete' WHERE order_id='$order_id'";
$con->update($sql, "Data Updated Successfully");

header("Location: assets.php");
exit;
?>