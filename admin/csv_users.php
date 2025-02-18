<?php
include("../conn.php");
$con = new connec();

function filterData(&$str){
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if(strstr($str,'"')) $str = '"' . str_replace('"', '""', $str) . '"';
}

$fileName = "employee-data_" . date('Y-m-d') . ".csv";

$fields = array('ID', 'NAME', 'EMAIL', 'DEPARTMENT', 'DIVISION', 'CONTACT', 'ROLE', 'DATE ADDED', 'REQUESTS');

$csvData = implode(",", array_values($fields)) . "\n";

$sql = "SELECT u.id, u.name, u.email, u.contact, u.user_role, u.date_add, dept.department, d.division, (SELECT COUNT(r.id) FROM req r WHERE r.user_id = u.id) AS request_count
    FROM users u
    INNER JOIN department dept ON u.dept_id = dept.id
    INNER JOIN division d ON u.division_id = d.id";
$result=$con->select_by_query($sql);
if($result->num_rows>0){
    while($row = $result->fetch_assoc()) {
        $lineData = array($row['id'], $row['name'], $row['email'], $row['department'], $row['division'], $row['contact'], $row['user_role'], $row['date_add'], $row['request_count']);
        $csvData .= implode(",", array_values($lineData)) . "\n";
    }
}

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=\"$fileName\"");

echo $csvData;

exit();
?>