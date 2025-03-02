<?php
include("../conn.php");
$brand = $_POST['brand'];
$con = new connec();
$sql = "SELECT DISTINCT model FROM items WHERE brand_id = '$brand'";
$result = $con->select_by_query($sql);
echo "<option value='' selected disabled>Select Model</option>";
while ($row = $result->fetch_assoc()) {
    echo "<option value='" . $row["model"] . "'>" . $row["model"] . "</option>";
}
?>