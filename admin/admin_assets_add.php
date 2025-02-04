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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $supplier = $_POST['supplier'];
    $assetModel = $_POST['assetModel'];
    $department = $_POST['department'];
    $status = $_POST['status'];
 
    $sql = "INSERT INTO assets(type,supplier,assetModel,department,status)
    VALUES('$type','$supplier','$assetModel','$department','$status')";
 
    $conn->query($sql);
 
    header("Location: admin_assets.php");
}
?>
<!DOCTYPE html>
<html lang="en">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Assets</title>
    <link rel="stylesheet" href="../assets_add.css">
</head>
 
<body>
    <div class="container-fluid">
 
        <form id="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <input type="text" name="type" class="form-control" id="type" placeholder="e.g. Phone">
            </div>
            <div class="mb-3">
                <label for="supplier" class="form-label">Supplier</label>
                <input type="text" name="supplier" class="form-control" id="supplier" placeholder="e.g. Apple">
            </div>
            <div class="mb-3" id="type">
                <label for="assetModel" class="form-label">Asset Model</label>
                <input type="text" name="assetModel" class="form-control" id="assetModel" placeholder="e.g. Iphone 23 PRO MAX">
            </div>
            <div class="mb-3">
                <label for="department" class="form-label">Department</label>
                <input type="text" name="department" class="form-control" id="department" placeholder="e.g. IOS">
            </div>
            <div class="form-check">
                <h6>Status</h6>
                <input class="form-check-input" value="available" type="radio" name="status" id="status">
                <label class="form-check-label" for="status">
                    Available
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" value="unavailable" type="radio" name="status" id="status" checked>
                <label class="form-check-label" for="status">
                    Unavailable
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" value="maintenance" type="radio" name="status" id="status" checked>
                <label class="form-check-label" for="status">
                    Maintenance
                </label>
            </div>
 
            <a class="btn" href="admin_assets.php" role="button" id="cancel">Cancel</a>
 
            <input class="btn btn-primary" type="submit" value="Submit" id="submit">
        </form>
 
    </div>
</body>
 
</html>
 
<?php
 
?>