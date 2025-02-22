<?php
include("../conn.php");
$order=$_GET['order'];
$con=new connec();

$sql="UPDATE req SET req_status = 'Incomplete', action = 'dispose' WHERE order_id='$order'";
$con->update($sql, "Data Updated Successfully");

header("Location: office_maintenancereq.php");
exit;
?>