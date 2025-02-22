<?php
session_start();
include("../conn.php");
$con = new connec();

function filterData(&$str){
    if ($str === null) {
        $str = '';
    }
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str,'"')) $str = '"' . str_replace('"', '""', $str) . '"';
}
$office = $_SESSION["office_id"];

$fields = array('ORDER ID', 'REQUEST TYPE', 'USER', 'ASSET', 'SERIAL', 'TYPE', 'STATUS');

$csvData = implode(",", array_values($fields)) . "\n";

$sql = "SELECT r.order_id, r.req_type, u.name, a.model, a.serial, t.type, r.req_status, o.office
    FROM req r
    JOIN users u ON r.user_id = u.id
    JOIN office o ON u.office_id = o.id
    JOIN asset_type t ON r.asset_type_id = t.id
    LEFT JOIN assets a ON r.asset_id = a.id
    WHERE u.office_id = $office
    ORDER BY r.order_id ASC";
$result=$con->select_by_query($sql);
if($result->num_rows>0){
    $officeName = '';
    while($row = $result->fetch_assoc()) {
        if (empty($officeName)) {
            $officeName = $row['office'];
        }
        $lineData = array($row['order_id'], $row['req_type'], $row['name'], $row['model'], $row['serial'], $row['type'], $row['req_status']);
        foreach ($lineData as &$value) {
            filterData($value);
        }
        $csvData .= implode(",", array_values($lineData)) . "\n";
    }
    $fileName = "request-data_" . strtolower($officeName) . "_" . date('Y-m-d') . ".csv";

    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=\"$fileName\"");

    echo $csvData;
    exit();
} else {
    // Handle empty result set
    header("HTTP/1.1 404 Not Found");
    echo "No data found for export.";
    exit();
}
?>