<?php
include("../conn.php");
$reserve=$_GET['reserve'];
$con=new connec();

$sql="UPDATE res SET req_status = 'Complete' WHERE reserve_id='$reserve '";
$con->update($sql, "Data Updated Successfully");

header("Location: admin_assetreq.php");
exit;
?>