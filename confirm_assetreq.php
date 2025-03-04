<?php
include("conn.php");
$assets = $_GET['assets'];
error_log($assets);
$user_id = $_GET['user_id'];
$decodedAssets = json_decode(urldecode($assets), true);
$encodedAssets = rawurlencode($assets);
error_log($encodedAssets);

$con=new connec();
$sql = "SELECT * FROM users WHERE id = '$user_id'";
$result=$con->select_by_query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Asset Request</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <h1>Confirm Asset Request (<?php while ($row = $result->fetch_assoc()) {
                echo $row["name"];
            }  ?>)</h1>
        <form action="process_request.php?assets=<?php echo $encodedAssets; ?>" method="post">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Expected Delivery Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($decodedAssets as $asset) { ?>
                        <tr>
                            <td><?php echo $asset['type']; ?></td>
                            <td><?php echo $asset['amount']; ?></td>
                            <td><?php echo date('Y-m-d', strtotime('+' . $asset['date_expected'] . ' days')); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="form-group">
                <label for="notes">Notes:</label>
                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
            </div>
            <a href="javascript:history.back()" class="btn btn-secondary">Back</a>
            <button type="submit" class="btn btn-primary">Confirm Request</button>
        </form>
    </div>
</body>
</html>