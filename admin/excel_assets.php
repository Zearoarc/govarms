<?php
include("../conn.php");
$con = new connec();

function filterData(&$str){
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str,'"')) $str = '"' . str_replace('"', '""', $str) . '"';
}

$fileName = "assets-data_" . date('Y-m-d') . ".xls";

$fields = array('ID', 'TYPE', 'SUPPLIER', 'MODEL', 'SERIAL', 'DEPARTMENT', 'DIVISION');

$excelData = implode("\t", array_values($fields)) . "\n";

$sql = "SELECT a.id, t.type, s.supplier, a.model, a.serial, dept.department, d.division
    FROM assets a
    INNER JOIN asset_type t ON a.type_id = t.id
    INNER JOIN supplier s ON a.supplier_id = s.id
    INNER JOIN department dept ON a.department_id = dept.id
    INNER JOIN division d ON a.division_id = d.id";
    $result=$con->select_by_query($sql);
if($result->num_rows>0){
    while($row = $result->fetch_assoc()) {
        $lineData = array($row['id'], $row['type'], $row['supplier'], $row['model'], $row['serial'], $row['department'], $row['division']);
        $excelData .= implode("\t", array_values($lineData)) . "\n";
    }
}

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$fileName\"");

echo $excelData;

exit();
?>