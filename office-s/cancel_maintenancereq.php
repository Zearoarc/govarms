<?php
include("../conn.php");
$order=$_GET['order'];
$serials=json_decode($_GET['serial'], true);
$tbl="req";
$con=new connec();

$con->delete_order($tbl,$order);

$sql = "UPDATE assets SET status = 'Available' WHERE serial IN ('" . implode("', '", $serials) . "')";
$con->update($sql, "Data Updated Successfully");

header("Location: office_maintenancereq.php");
exit;
?>