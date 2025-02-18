<?php
include("../conn.php");
$con = new connec();

function filterData(&$str){
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str,'"')) $str = '"' . str_replace('"', '""', $str) . '"';
}

$fileName = "request-data_" . date('Y-m-d') . ".xls";

$fields = array('ORDER ID', 'REQUEST TYPE', 'USER', 'ASSET', 'SERIAL', 'TYPE', 'STATUS');

$excelData = implode("\t", array_values($fields)) . "\n";

$sql = "SELECT r.order_id, r.req_type, u.name, a.model, a.serial, t.type, r.req_status
    FROM req r
    JOIN assets a ON r.asset_id = a.id
    JOIN asset_type t ON a.type_id = t.id
    JOIN users u ON r.user_id = u.id
    ORDER BY r.order_id ASC";
$result=$con->select_by_query($sql);
if($result->num_rows>0){
    while($row = $result->fetch_assoc()) {
        $lineData = array($row['order_id'], $row['req_type'], $row['name'], $row['model'], $row['serial'], $row['type'], $row['req_status']);
        foreach ($lineData as &$value) {
            filterData($value);
        }
        $excelData .= implode("\t", array_values($lineData)) . "\n";
    }
}

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$fileName\"");

echo $excelData;

exit();
?>