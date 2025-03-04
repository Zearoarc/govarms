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

$sql = "SELECT DISTINCT r.order_id, DATE_FORMAT(r.date_add, '%Y-%m') AS date
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
$sql = "SELECT DISTINCT r.reserve_id, DATE_FORMAT(r.date_add, '%Y-%m') AS date
FROM res r
JOIN users u ON r.user_id = u.id
WHERE MONTH(r.date_add) = '$month' AND YEAR(r.date_add) = '$year' AND u.office_id = '$office'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}
$sql = "SELECT DISTINCT s.order_id, DATE_FORMAT(s.date_add, '%Y-%m') AS date
FROM supp s
JOIN users u ON s.user_id = u.id
WHERE MONTH(s.date_add) = '$month' AND YEAR(s.date_add) = '$year' AND u.office_id = '$office'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}


$onemonth = date('m', strtotime('-1 month'));
$oneyear = date('Y', strtotime('-1 month'));
$sql = "SELECT DISTINCT r.order_id, DATE_FORMAT(r.date_add, '%Y-%m') AS date
FROM req r
JOIN users u ON r.user_id = u.id
WHERE MONTH(r.date_add) = '$onemonth' AND YEAR(r.date_add) = '$oneyear' AND u.office_id = '$office'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}
$sql = "SELECT DISTINCT r.reserve_id, DATE_FORMAT(r.date_add, '%Y-%m') AS date
FROM res r
JOIN users u ON r.user_id = u.id
WHERE MONTH(r.date_add) = '$onemonth' AND YEAR(r.date_add) = '$oneyear' AND u.office_id = '$office'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}
$sql = "SELECT DISTINCT s.order_id, DATE_FORMAT(s.date_add, '%Y-%m') AS date
FROM supp s
JOIN users u ON s.user_id = u.id
WHERE MONTH(s.date_add) = '$onemonth' AND YEAR(s.date_add) = '$oneyear' AND u.office_id = '$office'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}


$twomonth = date('m', strtotime('-2 months'));
$twoyear = date('Y', strtotime('-2 months'));
$sql = "SELECT DISTINCT r.order_id, DATE_FORMAT(r.date_add, '%Y-%m') AS date
FROM req r
JOIN users u ON r.user_id = u.id
WHERE MONTH(r.date_add) = '$twomonth' AND YEAR(r.date_add) = '$twoyear' AND u.office_id = '$office'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}
$sql = "SELECT DISTINCT r.reserve_id, DATE_FORMAT(r.date_add, '%Y-%m') AS date
FROM res r
JOIN users u ON r.user_id = u.id
WHERE MONTH(r.date_add) = '$twomonth' AND YEAR(r.date_add) = '$twoyear' AND u.office_id = '$office'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}
$sql = "SELECT DISTINCT s.order_id, DATE_FORMAT(s.date_add, '%Y-%m') AS date
FROM supp s
JOIN users u ON s.user_id = u.id
WHERE MONTH(s.date_add) = '$twomonth' AND YEAR(s.date_add) = '$twoyear' AND u.office_id = '$office'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}

$threemonth = date('m', strtotime('-3 months'));
$threeyear = date('Y', strtotime('-3 months'));
$sql = "SELECT DISTINCT r.order_id, DATE_FORMAT(r.date_add, '%Y-%m') AS date
FROM req r
JOIN users u ON r.user_id = u.id
WHERE MONTH(r.date_add) = '$threemonth' AND YEAR(r.date_add) = '$threeyear' AND u.office_id = '$office'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}
$sql = "SELECT DISTINCT r.reserve_id, DATE_FORMAT(r.date_add, '%Y-%m') AS date
FROM res r
JOIN users u ON r.user_id = u.id
WHERE MONTH(r.date_add) = '$threemonth' AND YEAR(r.date_add) = '$threeyear' AND u.office_id = '$office'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}
$sql = "SELECT DISTINCT s.order_id, DATE_FORMAT(s.date_add, '%Y-%m') AS date
FROM supp s
JOIN users u ON s.user_id = u.id
WHERE MONTH(s.date_add) = '$threemonth' AND YEAR(s.date_add) = '$threeyear' AND u.office_id = '$office'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}

$fourmonth = date('m', strtotime('-4 months'));
$fouryear = date('Y', strtotime('-4 months'));
$sql = "SELECT DISTINCT r.order_id, DATE_FORMAT(r.date_add, '%Y-%m') AS date
FROM req r
JOIN users u ON r.user_id = u.id
WHERE MONTH(r.date_add) = '$fourmonth' AND YEAR(r.date_add) = '$fouryear' AND u.office_id = '$office'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}
$sql = "SELECT DISTINCT r.reserve_id, DATE_FORMAT(r.date_add, '%Y-%m') AS date
FROM res r
JOIN users u ON r.user_id = u.id
WHERE MONTH(r.date_add) = '$fourmonth' AND YEAR(r.date_add) = '$fouryear' AND u.office_id = '$office'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}
$sql = "SELECT DISTINCT s.order_id, DATE_FORMAT(s.date_add, '%Y-%m') AS date
FROM supp s
JOIN users u ON s.user_id = u.id
WHERE MONTH(s.date_add) = '$fourmonth' AND YEAR(s.date_add) = '$fouryear' AND u.office_id = '$office'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}

$fivemonth = date('m', strtotime('-5 months'));
$fiveyear = date('Y', strtotime('-5 months'));
$sql = "SELECT DISTINCT r.order_id, DATE_FORMAT(r.date_add, '%Y-%m') AS date
FROM req r
JOIN users u ON r.user_id = u.id
WHERE MONTH(r.date_add) = '$fivemonth' AND YEAR(r.date_add) = '$fiveyear' AND u.office_id = '$office'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}
$sql = "SELECT DISTINCT r.reserve_id, DATE_FORMAT(r.date_add, '%Y-%m') AS date
FROM res r
JOIN users u ON r.user_id = u.id
WHERE MONTH(r.date_add) = '$fivemonth' AND YEAR(r.date_add) = '$fiveyear' AND u.office_id = '$office'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}
$sql = "SELECT DISTINCT s.order_id, DATE_FORMAT(s.date_add, '%Y-%m') AS date
FROM supp s
JOIN users u ON s.user_id = u.id
WHERE MONTH(s.date_add) = '$fivemonth' AND YEAR(s.date_add) = '$fiveyear' AND u.office_id = '$office'";
$result = $mysqli->query($sql);
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}

echo json_encode($data);