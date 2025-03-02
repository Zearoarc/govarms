<?php
include("conn.php");
$order=$_GET['order'];
$asset_id=$_GET['asset_id'];
$con=new connec();

$sql="UPDATE req SET req_status = 'Complete' WHERE order_id='$order' AND asset_id='$asset_id'";
$con->update($sql, "Data Updated Successfully");

header("Location: index.php");
exit;
?>