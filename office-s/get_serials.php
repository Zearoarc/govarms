<?php
include("../conn.php");
$model = $_POST['model'];
$con = new connec();
$sql = "SELECT serial FROM assets WHERE model = '$model' AND status='Available'";
$result = $con->select_by_query($sql);
echo "<option value='' selected disabled >Select Serial</option>";
while ($row = $result->fetch_assoc()) {
    echo "<option value='" . $row["serial"] . "'>" . $row["serial"] . "</option>";
}
?>