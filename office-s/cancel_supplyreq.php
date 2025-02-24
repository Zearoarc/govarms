<?php
include("../conn.php");
$order=$_GET['order'];
$tbl="supp";
$con=new connec();

$con->delete_order($tbl,$order);

header("Location: office_supplyreq.php");
exit;
?>