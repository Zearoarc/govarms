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
        <title>Manage Office Suppliers</title>
    </head>

    <main>
        <div class="container-fluid px-4">
            <h2 class="mt-4">Manage Office Suppliers</h2>
            <div class="card shadow mb-4">
                <div class="card-body">
                        <div class="table-responsive">
                            <table class="display" id="users" width="100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Office</th>
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
                                                    <td><?php echo $row["name"]; ?></td>
                                                    <td><?php echo $row["office"]; ?></td>
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
                            <script>
                                new DataTable('#users', {
                                    "paging": false,
                                    "lengthChange": true,
                                    "searching": true,
                                    "ordering": true,
                                    "info": true,
                                    "autoWidth": false,
                                    "layout": {
                                        topStart: {
                                            buttons: [
                                                {
                                                    "text": 'Add',
                                                    "action": function (e, dt, node, config) {
                                                        window.location.href = 'add_user.php?id=<?php echo $_SESSION["employee_id"]; ?>';
                                                    },
                                                    "attr": {
                                                        "class": 'btn btn-primary', // Add CSS classes here
                                                        "style": 'margin-left: 3px;'
                                                    }
                                                }
                                            ]
                                        }
                                    }
                                });
                            </script>
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