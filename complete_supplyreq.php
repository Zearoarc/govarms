<?php
session_start();
include("conn.php");
include('log.php');
$order=$_GET['order'];
$con=new connec();

$sql="SELECT * FROM supp WHERE order_id = '$order'";
$result=$con->select_by_query($sql);

if($result->num_rows > 0){
    while($row = $result->fetch_assoc()) {
        log_supplyreq($order, $row["user_id"], $_SESSION["office_id"], 'Supply request', 'received', $row["amount"], $row["supply_type_id"]);
    }
}

$sql="UPDATE supp SET req_status = 'Complete' WHERE order_id='$order'";
$con->update($sql, "Data Updated Successfully");

header("Location: index.php");
exit;
?>