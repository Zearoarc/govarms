<?php
session_start();
if(empty($_SESSION["username"])){
    header("location:../login.php");
}
else {
    include("admin_header.php");

    $con=new connec();
    $tbl="office";
    $result=$con->select_all($tbl);
    ?>
    <head>
        <title>Manage Offices</title>
    </head>

    <main>
        <div class="container-fluid px-4">
            <h2 class="mt-4">Manage Employees</h2>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <a type="button" class="btn btn-primary" href="add_user.php?id=<?php echo $_SESSION["employee_id"]; ?>"> Add</a>
                </div>
                <div class="card-body">

                        <div class="table-responsive">
                            <table class="table " id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Office Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if($result->num_rows>0){
                                            while($row=$result->fetch_assoc()){
                                                ?>
                                                <tr>
                                                    <td><?php echo $row["id"]; ?></td>
                                                    <td><?php echo $row["office"]; ?></td>
                                                    <td>
                                                        <a class='btn btn-primary btn-sm' href='edit_user.php?id=<?php echo $row["id"]; ?>'>Edit</a>
                                                        <a class='btn btn-sm btn-danger' href='delete_user.php?id=<?php echo $row["id"]; ?>'>Delete</a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
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
}
?>