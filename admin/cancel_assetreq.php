<?php
include("../conn.php");
$order=$_GET['order'];
$serials=json_decode($_GET['serial'], true);
$con=new connec();

$con->delete_order($order);

$sql = "UPDATE assets SET status = 'Available' WHERE serial IN ('" . implode("', '", $serials) . "')";
$con->update($sql, "Data Updated Successfully");

header("Location: admin_assetreq.php");
exit;
?>