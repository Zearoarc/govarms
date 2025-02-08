<?php
include("admin_header.php");

$username = "root";
$password = "";
$server_name = "localhost";
$db_name = "arms_db";

$conn = new mysqli($server_name, $username, $password, $db_name);
if ($conn->connect_error) {
    die("Connection Failed");
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM assets WHERE id=$id";
    $conn->query($sql);

    header("Location: admin_assets.php");
}
