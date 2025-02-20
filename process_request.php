<?php
// Include the database connection file
include('conn.php');

// Retrieve the request data from the $_POST array
$assets = $_GET['assets'];
$user_id = $_POST['user_id'];
$notes = $_POST['notes'];
$decodedAssets = json_decode(urldecode($assets), true);
error_log($assets);



// Validate the request data
if (empty($assets)) {
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
error_log($max_order_id);

foreach ($decodedAssets as $asset) {
    $assets_id = $asset["id"];
    $assets_amount = $asset["amount"];
    $assets_date = $asset["date_expected"];
    $sql = "INSERT INTO req (req_type, asset_type_id, date_add, date_expected, order_id, user_id, amount, req_status, notes)
                    VALUES('Asset', '$assets_id', CURDATE(), DATE_ADD(CURDATE(), INTERVAL $assets_date DAY), '$max_order_id', '$user_id', '$assets_amount', 'Pending', '$notes')";
    $con->insert($sql, "Request submitted successfully");
}

// Send a notification to the administrator
// ...

// Redirect the user to a confirmation page
header('Location: assetreq.php');
exit;
?>