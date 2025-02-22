<?php
include("../conn.php");
$reserve=$_GET['reserve'];
$con=new connec();

$sql="UPDATE res SET req_status = 'Incomplete' WHERE reserve_id='$reserve'";
$con->update($sql, "Data Updated Successfully");

header("Location: office_assetres.php");
exit;
?>