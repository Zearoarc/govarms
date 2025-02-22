<?php
if (!isset($_SESSION)) {
    session_start();
}

$mysqli = new mysqli("localhost", "root", "", "arms_db");
if ($mysqli->connect_errno != 0) {
    die($mysqli->connect_error);
}

$month = date('m');
$year = date('Y');
$office = $_SESSION["office_id"];

$sql = "SELECT r.req_status, u.office_id
FROM req r
JOIN users u ON r.user_id = u.id
WHERE MONTH(r.date_add) = '$month' AND YEAR(r.date_add) = '$year' AND u.office_id = '$office'";
$result = $mysqli->query($sql);
$data = [];
if (empty($data)) {
    $data = [0];
}
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}

$sql = "SELECT r.req_status, u.office_id
FROM res r
JOIN users u ON r.user_id = u.id
WHERE MONTH(r.date_add) = '$month' AND YEAR(r.date_add) = '$year' AND u.office_id = '$office'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}
echo json_encode($data);