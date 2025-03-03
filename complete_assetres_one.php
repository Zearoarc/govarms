<?php
session_start();
include("conn.php");
include('log.php');
$reserve=$_GET['reserve'];
$asset_id=$_GET['asset_id'];
$con=new connec();

$sql="SELECT * FROM res WHERE reserve_id = '$reserve' AND asset_id = '$asset_id'";
$result=$con->select_by_query($sql);

log_assetres($reserve, $row["user_id"], $_SESSION["office_id"], 'Asset reservation', 'received', $asset_id);

$sql="UPDATE res SET req_status = 'Complete' WHERE reserve_id='$reserve' AND asset_id='$asset_id'";
$con->update($sql, "Data Updated Successfully");

header("Location: index.php");
exit;
?>