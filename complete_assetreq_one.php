<?php
session_start();
include("conn.php");
include('log.php');
$order=$_GET['order'];
$asset_id=$_GET['asset_id'];
$con=new connec();

$sql="SELECT * FROM req WHERE order_id = '$order' AND asset_id = '$asset_id'";
$result=$con->select_by_query($sql);

log_assetreq($order, $row["user_id"], $_SESSION["office_id"], 'Asset request', 'received', $asset_id);

$sql="UPDATE req SET req_status = 'Complete' WHERE order_id='$order' AND asset_id='$asset_id'";
$con->update($sql, "Data Updated Successfully");

header("Location: index.php");
exit;
?>