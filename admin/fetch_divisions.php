<?php
include("../conn.php");

$con=new connec();

$departmentId = $_POST['departmentId'];
$rowId = $_POST['rowId'];

$sql_division = "SELECT id, division FROM division WHERE department_id = '$departmentId'";
$result_division = $con->select_by_query($sql_division);

echo '<option value="" disabled selected>Select Division</option>';
if($result_division->num_rows > 0){
    while($row_division = $result_division->fetch_assoc()){
        echo '<option value="' . $row_division["id"] . '">' . $row_division["division"] . '</option>';
    }
}
?>