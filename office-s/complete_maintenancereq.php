<?php
session_start();
include("../conn.php");
include('../log.php');
$order=$_GET['order'];
$con=new connec();

$sql="SELECT r.user_id, u.name, r.asset_id, i.model, i.serial, i.repair_times, t.type, r.req_status, r.action
FROM req r
JOIN users u ON r.user_id = u.id
JOIN items i ON r.asset_id = i.id
JOIN asset_type t ON i.asset_type_id = t.id
WHERE order_id='$order'";
$result=$con->select_by_query($sql);
$row = $result->fetch_assoc();
$action = $row["action"];
$asset_id = $row["asset_id"];
$repair= $row["repair_times"] + 1;
// Feedback form
if (isset($_POST['submit_feedback'])) {
    $feedback = $_POST['feedback'];
    $sql = "INSERT INTO feedback (asset_id, user_id, repair, feedback, action) VALUES ('$asset_id', '$row[user_id]', '$repair', '$feedback', '$action')";
    $con->insert($sql, "Feedback saved successfully");
    if ($action == 'repair') {
        // Update row
        $sql = "UPDATE req SET req_status = 'Complete' WHERE order_id='$order'";
        $con->update($sql, "Data Updated Successfully");
        $sql="UPDATE items SET repair_times = repair_times + 1 WHERE id='$asset_id'";
        $con->update($sql, "Data Updated Successfully");
        log_req($order, $row["user_id"], $_SESSION["office_id"], 'Maintenance request', 'repaired');
    } elseif ($action == 'dispose') {
        $sql = "UPDATE req SET req_status = 'Complete' WHERE order_id='$order'";
        $con->update($sql, "Data Updated Successfully");
        $sql="UPDATE items SET `status` = 'Dispose' WHERE id='$asset_id'";
        $con->update($sql, "Data Updated Successfully");
        log_req($order, $row["user_id"], $_SESSION["office_id"], 'Maintenance request', 'disposed');
    }
    header("Location: office_maintenancereq.php");
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Confirm Asset Request</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <h1>Maintenance Feedback for <?php echo $row["type"] ?> (<?php echo $row["serial"] ?>)</h1>
            <form action="" method="post">
                <div class="form-group">
                    <label for="feedback">Feedback:</label>
                    <textarea class="form-control" name="feedback" id="feedback" rows="3"></textarea>
                </div>
                
                <input type="submit" name="submit_feedback" value="Submit Feedback" class="btn btn-primary">
            </form>
        </div>
    </body>
</html>
<?php
exit;
?>