<?php
session_start();
include("../conn.php");
$con=new connec();

$sql="SELECT r.user_id, u.name, r.asset_id, i.model, i.serial, t.type, r.req_status, r.action
FROM req r
JOIN users u ON r.user_id = u.id
JOIN items i ON r.asset_id = i.id
JOIN asset_type t ON i.asset_type_id = t.id
WHERE order_id='2'";
$result=$con->select_by_query($sql);
$row = $result->fetch_assoc();
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