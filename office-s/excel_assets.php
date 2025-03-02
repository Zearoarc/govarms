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

$fields = array('ID', 'TYPE', 'BRAND', 'MODEL', 'SERIAL', 'OFFICE', 'DATE ADDED', 'PRICE', 'STATUS');

$excelData = implode("\t", array_values($fields)) . "\n";

$sql = "SELECT i.id, t.type, b.brand, i.model, i.serial, o.office, i.date_add, i.price, i.status
    FROM items i
    INNER JOIN asset_type t ON i.asset_type_id = t.id
    INNER JOIN brand b ON i.brand_id = b.id
    INNER JOIN office o ON i.office_id = o.id
    WHERE i.office_id = $office";
    $result=$con->select_by_query($sql);
if($result->num_rows>0){
    $officeName = '';
    while($row = $result->fetch_assoc()) {
        if (empty($officeName)) {
            $officeName = $row['office'];
        }
        $lineData = array($row['id'], $row['type'], $row['brand'], $row['model'], $row['serial'], $row['office'], $row['date_add'], $row['price'], $row['status'],);
        $excelData .= implode("\t", array_values($lineData)) . "\n";
    }
    $fileName = "assets-data_" . strtolower($officeName) . "_" . date('Y-m-d') . ".xls";
    
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$fileName\"");

    echo $excelData;
    exit();
} else {
    // Handle empty result set
    header("HTTP/1.1 404 Not Found");
    echo "No data found for export.";
    exit();
}
?>