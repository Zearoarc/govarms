<?php
session_start();
include("../conn.php");
include('../log.php');
$order=$_GET['order'];
$con=new connec();

$sql="SELECT * FROM req WHERE order_id = '$order'";
$result=$con->select_by_query($sql);
$row = $result->fetch_assoc();

$sql="UPDATE req SET req_status = 'In Transit', action = 'dispose' WHERE order_id='$order'";
$con->update($sql, "Data Updated Successfully");

log_req($order, $row["user_id"], $_SESSION["office_id"], 'Maintenance request', 'for disposal');
header("Location: office_maintenancereq.php");
exit;
?>