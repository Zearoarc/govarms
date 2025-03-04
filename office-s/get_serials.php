<?php
session_start();
include("../conn.php");
$model = $_POST['model'];
$office = $_SESSION['office_id'];
$con = new connec();
$sql = "SELECT serial FROM items WHERE model = '$model' AND status='Available' AND office_id='$office'";
$result = $con->select_by_query($sql);
echo "<option value='' disabled selected >Select Serial</option>";
while ($row = $result->fetch_assoc()) {
    echo "<option value='" . $row["serial"] . "'>" . $row["serial"] . "</option>";
}
?>