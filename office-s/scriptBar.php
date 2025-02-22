<?php
$mysqli = new mysqli("localhost", "root", "", "arms_db");
if ($mysqli->connect_errno != 0) {
    die($mysqli->connect_error);
}

$month = date('m');
$year = date('Y');

$sql = "SELECT DATE_FORMAT(date_add, '%Y-%m') AS date
FROM req
WHERE MONTH(date_add) = '$month' AND YEAR(date_add) = '$year'";
$result = $mysqli->query($sql);
$data = [];
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}
$sql = "SELECT DATE_FORMAT(date_add, '%Y-%m') AS date
FROM res
WHERE MONTH(date_add) = '$month' AND YEAR(date_add) = '$year'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}


$onemonth = date('m', strtotime('-1 month'));
$oneyear = date('Y', strtotime('-1 month'));
$sql = "SELECT DATE_FORMAT(date_add, '%Y-%m') AS date
FROM req
WHERE MONTH(date_add) = '$onemonth' AND YEAR(date_add) = '$oneyear'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}
$sql = "SELECT DATE_FORMAT(date_add, '%Y-%m') AS date
FROM res
WHERE MONTH(date_add) = '$onemonth' AND YEAR(date_add) = '$oneyear'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}


$twomonth = date('m', strtotime('-2 months'));
$twoyear = date('Y', strtotime('-2 months'));
$sql = "SELECT DATE_FORMAT(date_add, '%Y-%m') AS date
FROM req
WHERE MONTH(date_add) = '$twomonth' AND YEAR(date_add) = '$twoyear'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}
$sql = "SELECT DATE_FORMAT(date_add, '%Y-%m') AS date
FROM res
WHERE MONTH(date_add) = '$twomonth' AND YEAR(date_add) = '$twoyear'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}

$threemonth = date('m', strtotime('-3 months'));
$threeyear = date('Y', strtotime('-3 months'));
$sql = "SELECT DATE_FORMAT(date_add, '%Y-%m') AS date
FROM req
WHERE MONTH(date_add) = '$threemonth' AND YEAR(date_add) = '$threeyear'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}
$sql = "SELECT DATE_FORMAT(date_add, '%Y-%m') AS date
FROM res
WHERE MONTH(date_add) = '$threemonth' AND YEAR(date_add) = '$threeyear'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}

$fourmonth = date('m', strtotime('-4 months'));
$fouryear = date('Y', strtotime('-4 months'));
$sql = "SELECT DATE_FORMAT(date_add, '%Y-%m') AS date
FROM req
WHERE MONTH(date_add) = '$fourmonth' AND YEAR(date_add) = '$fouryear'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}
$sql = "SELECT DATE_FORMAT(date_add, '%Y-%m') AS date
FROM res
WHERE MONTH(date_add) = '$fourmonth' AND YEAR(date_add) = '$fouryear'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}

$fivemonth = date('m', strtotime('-5 months'));
$fiveyear = date('Y', strtotime('-5 months'));
$sql = "SELECT DATE_FORMAT(date_add, '%Y-%m') AS date
FROM req
WHERE MONTH(date_add) = '$fivemonth' AND YEAR(date_add) = '$fiveyear'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}
$sql = "SELECT DATE_FORMAT(date_add, '%Y-%m') AS date
FROM res
WHERE MONTH(date_add) = '$fivemonth' AND YEAR(date_add) = '$fiveyear'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}

echo json_encode($data);