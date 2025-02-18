<?php
$mysqli = new mysqli("localhost", "root", "", "arms_db");
if ($mysqli->connect_errno != 0) {
    die($mysqli->connect_error);
}

$month = date('m');
$year = date('Y');

$sql = "SELECT req_status
FROM req
WHERE MONTH(date_add) = '$month' AND YEAR(date_add) = '$year'";
$result = $mysqli->query($sql);
$data = [];
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}

$sql = "SELECT req_status
FROM res
WHERE MONTH(date_add) = '$month' AND YEAR(date_add) = '$year'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}
echo json_encode($data);