<?php
session_start();
// Include the database connection file
include('conn.php');
include('log.php');

// Retrieve the request data from the $_POST array
$asset_id = $_GET['asset_id'];
$notes = $_POST['notes'];



// Validate the request data
if (empty($asset_id)) {
    // Handle invalid data
    header('Location: error.php');
    exit;
}



// Create a new request record in the database
$con = new connec();
$sql_order = "SELECT MAX(order_id) FROM req";
$result_order = $con->select_by_query($sql_order);
$row_order = $result_order->fetch_assoc();
$max_order_id = $row_order['MAX(order_id)'] + 1;

$sql = "SELECT * FROM req WHERE asset_id = '$asset_id'";
$result=$con->select_by_query($sql);
$row = $result->fetch_assoc();

$asset_type_id = $row["asset_type_id"];
$user_id = $row["user_id"];

$sql = "INSERT INTO req (req_type, asset_type_id, date_add, order_id, user_id, asset_id, req_status, notes)
                VALUES('Maintenance', '$asset_type_id', CURDATE(), '$max_order_id', '$user_id', '$asset_id', 'Pending', '$notes')";
$con->insert($sql, "Request submitted successfully");
log_req($max_order_id, $user_id, $_SESSION["office_id"], 'Maintenance request', 'created');

// Send a notification to the administrator
// ...

// Redirect the user to a confirmation page
header('Location: assets.php');
exit;
?>