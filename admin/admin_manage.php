<?php
session_start();
include("admin_header.php");
include("admin_sidenavbar.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Manage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel ="Stylesheet" href="../admin_manage.css">
</head>
<body>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"> MANAGE EMPLOYEES</h6>   
        </div>
       
    </div>
</div>

<div class="container-fluid2">

<div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"> EMPLOYEES
            <button type="button" class="btn btn-primary" href="addemployee.php"> Add</button>
            </h6>
            
        </div>
<div class="card-body">

<div>

    <div class="table-responsive">
        <table class="table " id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                <?php 
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "arms_db";

                $connection = new mysqli($servername, $username, $password, $database);
                if ($connection->connect_error) {
                    die("Connection failed: " . $connection->connect_error);
                }

                $sql = "SELECT * FROM manage";
                $result = $connection->query($sql);

                if (!$result) {
                    die("Invalid query: " . $connection->error);
                }

                while($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                    <td>$row[id]</td>
                    <td>$row[name]</td>
                    <td>$row[contact]</td>
                    <td>$row[email]</td>
                    <td>$row[department]</td>
                    <td>$row[date]</td>
                    <td>
                        <a class='btn btn-primary btn-sm' href='/admin/editmanage.php?id=$row[id]'>Edit</a>
                        <a class='btn btn-primary btn-sm' href='/admin/deletemanage.php?id=$row[id]'>Delete</a>
                    </td>
                </tr>

                    ";
                }
                ?>


                
            </tbody>
        </table>
    </div>
</div>
</div>






</div>
        
</div>
</body>
</html>

<?php
include("admin_footer.php");

?>