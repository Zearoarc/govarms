<?php
// Include the database connection file

function log_req($order_id, $user_id, $office_id, $req_type, $req_status) {
    $con = new connec();
    $sql = "SELECT u.name
    FROM users u
    WHERE u.id = '$user_id'";
    $result = $con->select_by_query($sql);
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $combined_notes = "$req_type Order ID: $order_id by $name $req_status";
    $sql = "INSERT INTO logs (office_id, notes, log_date) VALUES ($office_id, '$combined_notes', NOW())";
    $con->insert($sql, "Log submitted successfully");
}

function log_res($reserve_id, $user_id, $office_id, $req_type, $req_status) {
    $con = new connec();
    $sql = "SELECT u.name
    FROM users u
    WHERE u.id = '$user_id'";
    $result = $con->select_by_query($sql);
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $combined_notes = "$req_type Reserve ID: $reserve_id by $name $req_status";
    $sql = "INSERT INTO logs (office_id, notes, log_date) VALUES ($office_id, '$combined_notes', NOW())";
    $con->insert($sql, "Log submitted successfully");
}

function log_assetreq($order_id, $user_id, $office_id, $req_type, $req_status, $asset_id) {
    $con = new connec();
    $sql = "SELECT u.name, i.serial
    FROM req r
    JOIN users u ON r.user_id = u.id
    JOIN items i ON r.asset_id = i.id
    WHERE r.order_id = $order_id AND r.asset_id = $asset_id";
    $result = $con->select_by_query($sql);
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $serial = $row['serial'];
    $combined_notes = "$req_type Order ID: $order_id by $name $req_status - assigned serial $serial";
    $sql = "INSERT INTO logs (office_id, notes, log_date) VALUES ($office_id, '$combined_notes', NOW())";
    $con->insert($sql, "Log submitted successfully");
}

function log_assetres($reserve_id, $user_id, $office_id, $req_type, $req_status, $asset_id) {
    $con = new connec();
    $sql = "SELECT u.name, i.serial
    FROM res r
    JOIN users u ON r.user_id = u.id
    JOIN items i ON r.asset_id = i.id
    WHERE r.reserve_id = $reserve_id AND r.asset_id = $asset_id";
    $result = $con->select_by_query($sql);
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $serial = $row['serial'];
    $combined_notes = "$req_type Reserve ID: $reserve_id by $name $req_status - assigned serial $serial";
    $sql = "INSERT INTO logs (office_id, notes, log_date) VALUES ($office_id, '$combined_notes', NOW())";
    $con->insert($sql, "Log submitted successfully");
}

function log_assetadd($office_id, $asset_type, $serial) {
    $con = new connec();
    $combined_notes = "Added new asset: $asset_type ($serial) to Inventory";
    $sql = "INSERT INTO logs (office_id, notes, log_date) VALUES ($office_id, '$combined_notes', NOW())";
    $con->insert($sql, "Log submitted successfully");
}

function log_assetedit($asset_id, $serial, $user_id, $office_id, $changes) {
    $con = new connec();
    $sql = "SELECT u.name
    FROM users u
    WHERE u.id = '$user_id'";
    $result = $con->select_by_query($sql);
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $sql = "SELECT t.type
    FROM items i
    JOIN asset_type t ON i.asset_type_id = t.id
    WHERE i.id = '$asset_id'";
    $result = $con->select_by_query($sql);
    $row = $result->fetch_assoc();
    $asset_type = $row['type'];
    $combined_notes = "Edited asset information for $asset_type ($serial) by $name - $changes";
    $sql = "INSERT INTO logs (office_id, notes, log_date) VALUES ($office_id, '$combined_notes', NOW())";
    $con->insert($sql, "Log submitted successfully");
}

function log_assetdelete($asset_id, $user_id, $office_id) {
    $con = new connec();
    $sql = "SELECT u.name
    FROM users u
    WHERE u.id = '$user_id'";
    $result = $con->select_by_query($sql);
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $sql = "SELECT i.model, i.serial, t.type
    FROM items i
    JOIN asset_type t ON i.asset_type_id = t.id
    WHERE i.id = '$asset_id'";
    $result = $con->select_by_query($sql);
    $row = $result->fetch_assoc();
    $model = $row['model'];
    $serial = $row['serial'];
    $type = $row['type'];
    $combined_notes = "Deleted asset: $type ($model, $serial) by $name";
    $sql = "INSERT INTO logs (office_id, notes, log_date) VALUES ($office_id, '$combined_notes', NOW())";
    $con->insert($sql, "Log submitted successfully");
}

function log_supplyreq($order_id, $user_id, $office_id, $req_type, $req_status, $quantity, $supply_id) {
    $con = new connec();
    $sql = "SELECT u.name, t.type
    FROM supp s
    JOIN users u ON s.user_id = u.id
    JOIN supply_type t ON s.supply_type_id = t.id
    WHERE s.order_id = $order_id AND s.supply_type_id = $supply_id";
    $result = $con->select_by_query($sql);
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $type = $row['type'];
    $combined_notes = "$req_type Order ID: $order_id by $name $req_status - $type ($quantity)";
    $sql = "INSERT INTO logs (office_id, notes, log_date) VALUES ($office_id, '$combined_notes', NOW())";
    $con->insert($sql, "Log submitted successfully");
}

function log_supplyadd($office_id, $quantity, $type) {
    $con = new connec();
    $combined_notes = "Added $quantity $type to Inventory";
    $sql = "INSERT INTO logs (office_id, notes, log_date) VALUES ($office_id, '$combined_notes', NOW())";
    $con->insert($sql, "Log submitted successfully");
}

function log_supplyedit($supply_id, $user_id, $office_id, $changes) {
    $con = new connec();
    $sql = "SELECT u.name
    FROM users u
    WHERE u.id = '$user_id'";
    $result = $con->select_by_query($sql);
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $sql = "SELECT t.type
    FROM items i
    JOIN supply_type t ON i.supply_type_id = t.id
    WHERE i.id = '$supply_id'";
    $result = $con->select_by_query($sql);
    $row = $result->fetch_assoc();
    $supply_type = $row['type'];
    $combined_notes = "Edited supply information for $supply_type by $name - $changes";
    $sql = "INSERT INTO logs (office_id, notes, log_date) VALUES ($office_id, '$combined_notes', NOW())";
    $con->insert($sql, "Log submitted successfully");
}

function log_supplydelete($supply_id, $user_id, $office_id) {
    $con = new connec();
    $sql = "SELECT u.name
    FROM users u
    WHERE u.id = '$user_id'";
    $result = $con->select_by_query($sql);
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $sql = "SELECT t.type
    FROM items i
    JOIN supply_type t ON i.supply_type_id = t.id
    WHERE i.id = '$supply_id'";
    $result = $con->select_by_query($sql);
    $row = $result->fetch_assoc();
    $supply_type = $row['type'];
    $combined_notes = "Deleted supply: $supply_type by $name";
    $sql = "INSERT INTO logs (office_id, notes, log_date) VALUES ($office_id, '$combined_notes', NOW())";
    $con->insert($sql, "Log submitted successfully");
}

function log_adduser($office_id, $name, $email, $contact, $user_role) {
    $con = new connec();
    $combined_notes = "Added new user: $name ($email, $contact, $user_role)";
    $sql = "INSERT INTO logs (office_id, notes, log_date) VALUES ($office_id, '$combined_notes', NOW())";
    $con->insert($sql, "Log submitted successfully");
}

function log_edituser($user_id, $office_id, $changes) {
    $con = new connec();
    $sql = "SELECT u.name
    FROM users u
    WHERE u.id = '$user_id'";
    $result = $con->select_by_query($sql);
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $combined_notes = "Updated user information for $name - $changes";
    $sql = "INSERT INTO logs (office_id, notes, log_date) VALUES ($office_id, '$combined_notes', NOW())";
    $con->insert($sql, "Log submitted successfully");
}

function log_deleteuser($user_id, $office_id, $name) {
    $con = new connec();
    $combined_notes = "Deleted user: $name";
    $sql = "INSERT INTO logs (office_id, notes, log_date) VALUES ($office_id, '$combined_notes', NOW())";
    $con->insert($sql, "Log submitted successfully");
}

function log_assetreturn($asset_id, $user_id, $office_id) {
    $con = new connec();
    $sql = "SELECT u.name, i.serial, t.type, r.date_end
    FROM res r
    JOIN users u ON r.user_id = u.id
    JOIN items i ON r.asset_id = i.id
    JOIN asset_type t ON i.asset_type_id = t.id
    WHERE r.asset_id = '$asset_id' AND r.action = 'none'";
    $result = $con->select_by_query($sql);
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $serial = $row['serial'];
    $type = $row['type'];
    $date_end = $row['date_end'];
    $status = '';
    $days_diff = '';
    $date_diff = date_diff(date_create($date_end), date_create(date("Y-m-d")));
    if ($date_end < date("Y-m-d")) {
        $status = 'overdue';
        $days_diff = $date_diff->days;
        $days_diff = " by $days_diff days";
    } elseif (date("Y-m-d") < $date_end) {
        $status = 'returned early';
        $days_diff = $date_diff->days;
        $days_diff = " by $days_diff days";
    } else {
        $status = 'on time';
        $days_diff = '';
    }
    $combined_notes = "Returned asset: $type ($serial) by $name - $status$days_diff";
    $sql = "INSERT INTO logs (office_id, notes, log_date) VALUES ($office_id, '$combined_notes', NOW())";
    $con->insert($sql, "Log submitted successfully");
}
?>