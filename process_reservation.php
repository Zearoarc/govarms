<?php
session_start();
// Include the database connection file
include('conn.php');
include('log.php');

// Retrieve the reservation data from the $_POST array
$assets = $_GET['assets'];
$user_id = $_POST['user_id'];
$notes = $_POST['notes'];
$decodedAssets = json_decode(urldecode($assets), true);
error_log($assets);



// Validate the reservation data
if (empty($assets)) {
    // Handle invalid data
    header('Location: error.php');
    exit;
}



// Create a new reservation record in the database
$con = new connec();
$sql_order = "SELECT MAX(reserve_id) FROM res";
$result_order = $con->select_by_query($sql_order);
$row_order = $result_order->fetch_assoc();
$max_order_id = $row_order['MAX(reserve_id)'] + 1;
error_log($max_order_id);

foreach ($decodedAssets as $asset) {
    $assets_id = $asset["id"];
    $assets_amount = $asset["amount"];
    $assets_date = $asset["date_expected"];
    $assets_start = $asset["start_date"];
    $assets_end = $asset["end_date"];

    for ($i = 0; $i < $assets_amount; $i++) {
        $sql = "INSERT INTO res (asset_type_id, date_add, date_expected, reserve_id, user_id, date_start, date_end, req_status, notes)
                        VALUES('$assets_id', CURDATE(), DATE_ADD(CURDATE(), INTERVAL $assets_date DAY), '$max_order_id', '$user_id', '$assets_start', '$assets_end', 'Pending', '$notes')";
        $con->insert($sql, "Borrowing submitted successfully");
    }
}
log_res($max_order_id, $user_id, $_SESSION["office_id"], 'Asset reservation', 'created');

// Send a notification to the administrator
// ...

// Redirect the user to a confirmation page
header('Location: assetres.php');
exit;
?>