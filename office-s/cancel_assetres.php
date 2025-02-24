<?php
include("../conn.php");
$reserve=$_GET['reserve'];
$con=new connec();
$con->delete_reserve($reserve);

header("Location: office_assetreq.php");
exit;
?>