<?php
$mysqli = new mysqli("localhost", "root", "", "arms_db");
if ($mysqli->connect_errno != 0) {
    die($mysqli->connect_error);
}
$sql = "SELECT req_status
FROM req";
$result = $mysqli->query($sql);
$data = [];
while ($row = $result->fetch_assoc()) {
    array_push($data, $row);
}

echo json_encode($data);