<?php
include("../conn.php");
$con = new connec();

function filterData(&$str){
    $str = preg_replace("/\r?\n/", "", $str);
    if(strstr($str,'"')) $str = '"' . str_replace('"', '""', $str) . '"';
    $str = str_replace(",", "\,", $str);
}

$fileName = "assets-data_" . date('Y-m-d') . ".csv";

$fields = array('ID', 'TYPE', 'SUPPLIER', 'MODEL', 'SERIAL', 'OFFICE', 'DATE ADDED', 'COST', 'SALVAGE', 'USEFUL LIFE(YEARS)', 'REPAIR', 'TIMES REPAIRED', 'STATUS');

$csvData = implode(",", array_values($fields)) . "\n";

$sql = "SELECT a.id, t.type, s.supplier, a.model, a.serial, o.office, a.date_add, a.cost, a.salvage, a.useful_life, a.repair, a.repair_times, a.status
    FROM assets a
    INNER JOIN asset_type t ON a.type_id = t.id
    INNER JOIN supplier s ON a.supplier_id = s.id
    INNER JOIN office o ON a.office_id = o.id";
$result=$con->select_by_query($sql);
if($result->num_rows>0){
    while($row = $result->fetch_assoc()) {
        $lineData = array($row['id'], $row['type'], $row['supplier'], $row['model'], $row['serial'], $row['office'], $row['date_add'], $row['cost'], $row['salvage'], $row['useful_life'], $row['repair'], $row['repair_times'], $row['status'],);
        foreach ($lineData as &$field) {
            filterData($field);
        }
        $csvData .= implode(",", array_values($lineData)) . "\n";
    }
}

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=\"$fileName\"");

echo $csvData;

exit();
?>