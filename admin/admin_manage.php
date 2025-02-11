<?php
session_start();
include("admin_header.php");
?>

<head>
    <title>Admin Manage</title>
    <link rel="stylesheet" href="../admin_manage.css">
</head>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Manage Employees</h1>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <a type="button" class="btn btn-primary" href="addemployee.php"> Add</a>
            </div>
            <div class="card-body">

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

                                while ($row = $result->fetch_assoc()) {
                                    echo "
                    <tr>
                    <td>$row[id]</td>
                    <td>$row[name]</td>
                    <td>$row[contact]</td>
                    <td>$row[email]</td>
                    <td>$row[department]</td>
                    <td>$row[date]</td>
                    <td>
                        <a class='btn btn-primary btn-sm' href='editemployee.php?id=$row[id]'>Edit</a>
                        <a class='btn btn-primary btn-sm' href='deleteemployee.php?id=$row[id]'>Delete</a>
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