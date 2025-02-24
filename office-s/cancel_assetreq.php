<?php
include("../conn.php");
$order=$_GET['order'];
$con=new connec();
$con->delete_order($order);

header("Location: office_assetreq.php");
exit;
?>