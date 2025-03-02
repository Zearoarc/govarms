<?php
include("../conn.php");
$order=$_GET['order'];
$tbl="req";
$con=new connec();
$con->delete_order($tbl, $order);

header("Location: office_assetreq.php");
exit;
?>