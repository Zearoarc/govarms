<?php
include("../conn.php");
$reserve=$_GET['reserve'];
$serials=json_decode($_GET['serial'], true);
$table="res";
$con=new connec();

$con->delete_reserve($reserve);

$sql = "UPDATE assets SET status = 'Available' WHERE serial IN ('" . implode("', '", $serials) . "')";
$con->update($sql, "Data Updated Successfully");

header("Location: admin_assetreq.php");
exit;
?>