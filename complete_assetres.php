<?php
session_start();
include("conn.php");
include('log.php');
$reserve=$_GET['reserve'];
$con=new connec();

$sql="SELECT * FROM res WHERE reserve_id = '$reserve'";
$result=$con->select_by_query($sql);

if($result->num_rows > 0){
    while($row = $result->fetch_assoc()) {
        log_assetres($reserve, $row["user_id"], $_SESSION["office_id"], 'Asset reservation', 'received', $row["asset_id"]);
    }
}

$sql="UPDATE res SET req_status = 'Complete' WHERE reserve_id='$reserve'";
$con->update($sql, "Data Updated Successfully");

header("Location: index.php");
exit;
?>