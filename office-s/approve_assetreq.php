<?php
include("../conn.php");
$order=$_GET['order'];
$con=new connec();

$sql="UPDATE req SET req_status = 'Incomplete' WHERE order_id='$order'";
$con->update($sql, "Data Updated Successfully");

header("Location: office_assetreq.php");
exit;
?>