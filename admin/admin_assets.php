<?php
include("admin_header.php");

    $username="root";
    $password="";
    $server_name="localhost";
    $db_name="arms_db";

    $conn = new mysqli($server_name, $username, $password, $db_name);
        if($conn->connect_error)
        {
            die("Connection Failed");}
    // Fetch id, type, asset_model, and department from database
    $sql = "SELECT * FROM assets";
    $result = $conn->query($sql);
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"> ASSETS
            </h6>
        </div>
       
    </div>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assets</title>
  
</head>
<body>
<div class="container-fluid">

<div class="card shadow mb-4">
        <div class="card-header py-3">
            
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addemployee"> Add</button>
            
        </div>
    <table class="table table-bordered">
        <thead class="table-blue">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Type</th>
                <th scope="col">Supplier</th>
                <th scope="col">Asset Model</th>
                <th scope="col">Department</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $supplierLogo = "/images/{$row['supplier']}.jpg";
                        $status = "Available";
                        if($status == null){
                            $status = "Unavailable";
                        }
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['type']}</td>
                                <td>{$row['supplier']}</td>
                                <td>{$row['assetModel']}</td>
                                <td>{$row['department']}</td>
                                <td>{$status}</td>
                                <td>
    <button class ='btn btn-success'><a href='edit.php?updateid='..'' class='text-light'>Edit</a></button>
    <button class ='btn btn-danger'><a href='delete.php?deleteid='..'' class='text-light'>Cancel</a></button>
  </td>
                              </tr>";
                              
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>No records found</td></tr>";
                }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php
    include("admin_footer.php");
?>