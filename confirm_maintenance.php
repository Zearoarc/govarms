<?php
include("conn.php");
$asset_id = $_GET['asset_id'];

$con=new connec();
$sql = "SELECT t.type, u.name, b.brand, i.model, i.serial
FROM req r 
JOIN items i ON r.asset_id = i.id
JOIN asset_type t ON r.asset_type_id = t.id
JOIN users u ON r.user_id = u.id
JOIN brand b ON i.brand_id = b.id
WHERE r.asset_id = '$asset_id'";
$result=$con->select_by_query($sql);
$row = $result->fetch_assoc();

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
        <h1>Confirm Maintenance Request (<?php echo $row["name"]; ?>)</h1>
        <form action="process_maintenance.php?asset_id=<?php echo $asset_id; ?>" method="post">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Serial</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $row['type']; ?></td>
                        <td><?php echo $row['brand']; ?></td>
                        <td><?php echo $row['model']; ?></td>
                        <td><?php echo $row['serial']; ?></td>
                    </tr>
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