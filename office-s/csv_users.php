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

$fields = array('ID', 'NAME', 'EMAIL', 'OFFICE', 'CONTACT', 'ROLE', 'DATE ADDED', 'REQUESTS');

$csvData = implode(",", array_values($fields)) . "\n";

$sql = "SELECT u.id, u.name, u.email, u.contact, u.user_role, u.date_add, o.office, (SELECT COUNT(r.id) FROM req r WHERE r.user_id = u.id) AS request_count
    FROM users u
    INNER JOIN office o ON u.office_id = o.id
    WHERE u.office_id = '$office'";
$result=$con->select_by_query($sql);
if($result->num_rows>0){
    $officeName = '';
    while($row = $result->fetch_assoc()) {
        if (empty($officeName)) {
            $officeName = $row['office'];
        }
        $lineData = array($row['id'], $row['name'], $row['email'], $row['office'], $row['contact'], $row['user_role'], $row['date_add'], $row['request_count']);
        $csvData .= implode(",", array_values($lineData)) . "\n";
    }
    $fileName = "employee-data_" . strtolower($officeName) . "_" . date('Y-m-d') . ".csv";

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