<?php
session_start();
include("../conn.php");
$con = new connec();

function filterData(&$str){
    if ($str === null) {
        $str = '';
    }
    $str = preg_replace("/\r?\n/", "", $str);
    if(strstr($str,'"')) $str = '"' . str_replace('"', '""', $str) . '"';
    $str = str_replace(",", "\,", $str);
}
$office = $_SESSION["office_id"];

$fields = array('ID', 'TYPE', 'SUPPLIER', 'MODEL', 'SERIAL', 'OFFICE', 'DATE ADDED', 'COST', 'STATUS');

$csvData = implode(",", array_values($fields)) . "\n";

$sql = "SELECT a.id, t.type, s.supplier, a.model, a.serial, o.office, a.date_add, a.cost, a.status
    FROM assets a
    INNER JOIN asset_type t ON a.type_id = t.id
    INNER JOIN supplier s ON a.supplier_id = s.id
    INNER JOIN office o ON a.office_id = o.id
    WHERE a.office_id = $office";
$result=$con->select_by_query($sql);
if($result->num_rows>0){
    $officeName = '';
    while($row = $result->fetch_assoc()) {
        if (empty($officeName)) {
            $officeName = $row['office'];
        }
        $lineData = array($row['id'], $row['type'], $row['supplier'], $row['model'], $row['serial'], $row['office'], $row['date_add'], $row['cost'], $row['status'],);
        foreach ($lineData as &$field) {
            filterData($field);
        }
        $csvData .= implode(",", array_values($lineData)) . "\n";
    }
    $fileName = "assets-data_" . strtolower($officeName) . "_" . date('Y-m-d') . ".csv";

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