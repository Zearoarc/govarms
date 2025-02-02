<?php
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "arms_db";

    $connection = new mysqli($servername, $username, $password, $database);

    $sql = "DELETE FROM manage WHERE id=$id";
    $connection->query($sql);
}

header("location: admin_manage.php");
exit;
