<?php
session_start();
if(empty($_SESSION["username"])){
    header("location:../login.php");
}
else {
    include("admin_header.php");

    $con=new connec();
    $sql="SELECT u.id, u.name, u.email, u.contact, u.date_add, o.office, u.user_role
    FROM users u
    JOIN office o ON u.office_id = o.id
    WHERE u.user_role = 'Office Supplier'";
    $result=$con->select_by_query($sql);
    ?>
    <head>
        <title>Admin Manage</title>
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
                                        <th>Name</th>
                                        <th>Contact</th>
                                        <th>Email</th>
                                        <th>Date Added</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if($result->num_rows>0){
                                            while($row=$result->fetch_assoc()){
                                                ?>
                                                <tr>
                                                    <td><?php echo $row["id"]; ?></td>
                                                    <td><?php echo $row["name"]; ?></td>
                                                    <td><?php echo $row["contact"]; ?></td>
                                                    <td><?php echo $row["email"]; ?></td>
                                                    <td><?php echo $row["date_add"]; ?></td>
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