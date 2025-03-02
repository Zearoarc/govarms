<?php

// Start session
session_start();

// Include database connection
include("conn.php");
// Log asset return
include("log.php");
$con=new connec();

// Check if asset_id is set
if (isset($_GET["asset_id"])) {
    $asset_id = $_GET["asset_id"];

    $sql="SELECT r.user_id, u.name, r.asset_id, i.model, i.serial, i.repair_times, t.type, r.req_status, r.action
    FROM res r
    JOIN users u ON r.user_id = u.id
    JOIN items i ON r.asset_id = i.id
    JOIN asset_type t ON i.asset_type_id = t.id
    WHERE asset_id='$asset_id' AND action = 'none'";
    $result=$con->select_by_query($sql);
    $row = $result->fetch_assoc();

    if (isset($_POST['submit_feedback'])) {
        $feedback = $_POST['feedback'];
        $sql = "INSERT INTO feedback (asset_id, user_id, repair, feedback, action) VALUES ('$asset_id', '$row[user_id]', '$row[repair_times]', '$feedback', 'return')";
        $con->insert($sql, "Feedback saved successfully");
        log_assetreturn($asset_id, $_SESSION['user_id'], $_SESSION['office_id']);

        // Update res table
        $sql_update_res = "UPDATE res SET action = 'returned' WHERE asset_id = '$asset_id'";
        $con->update($sql_update_res, "Data Updated Successfully");

        // Update assets table
        $sql_update_assets = "UPDATE items SET status = 'Available' WHERE id = '$asset_id'";
        $con->update($sql_update_assets, "Data Updated Successfully");

        // Redirect to assets page
        header("location:assets.php");
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
                <h1>Asset Feedback for <?php echo $row["type"] ?> (<?php echo $row["serial"] ?>)</h1>
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
} else {
    // Handle error
    echo "Error: asset_id not set";
}
?>

