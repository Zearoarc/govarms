<?php
include("../conn.php");
$order=$_GET['order'];
$asset_type=$_GET['asset_type'];
$con=new connec();

$sql="UPDATE req SET req_status = 'Complete' WHERE order_id='$order' AND asset_type_id='$asset_type'";
$con->update($sql, "Data Updated Successfully");

header("Location: office_assetreq.php");
exit;
?>