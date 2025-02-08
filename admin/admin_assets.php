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
        <link rel="stylesheet" href="../assets.css">
 
    </head>
    <main>
    <div class="container-fluid px-4">

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"> ASSETS
                                    <a class="btn btn-primary" href="admin_assets_add.php" role="button">Add</a>
                                </div>

                                <div class="card-body">

                                <div class="table-responsive">
                                    
 
                                <table class="table " id="dataAssetTable" width="100%" cellspacing="0">
                                    <thead class="table-blue">
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Supplier</th>
                                            <th scope="col">Asset Model</th>
                                            <th scope="col">Department</th>
                                            <th scope="col">Status</th>

                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
 
                                                echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['type']}</td>
                                <td>{$row['supplier']}</td>
                                <td>{$row['assetModel']}</td>
                                <td>{$row['department']}</td>
                                <td>{$row['status']}</td>
                                <td>
    <a href='admin_assets_edit.php?id={$row['id']}' class='btn btn-primary'>edit</a>
    <a href='admin_assets_delete.php?id={$row['id']}' class='btn btn-primary'>Delete</a>
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
                                </div>
        </div>
                            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
                            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                            </main>
                            </div>
    <?php
 
    include("admin_footer.php");
    ?>