<?php
include("../conn.php");
$order=$_GET['order'];
$con=new connec();

$sql="UPDATE req SET req_status = 'Complete' WHERE order_id='$order'";
$con->update($sql, "Data Updated Successfully");

header("Location: office_maintenancereq.php");
exit;
?>