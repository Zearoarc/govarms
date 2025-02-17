<?php
include("../conn.php");
$order=$_GET['order'];
$con=new connec();

$sql="UPDATE req SET req_status = 'Incomplete', action = 'repair' WHERE order_id='$order'";
$con->update($sql, "Data Updated Successfully");

header("Location: admin_maintenancereq.php");
exit;
?>