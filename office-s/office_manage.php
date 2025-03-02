<?php
session_start();
if(empty($_SESSION["username"])){
    header("location:../login.php");
}
else {
    include("office_header.php");
    $office_id = $_SESSION['office_id'];

    $con=new connec();
    if (isset($_GET["view"]) && $_GET["view"] == "highest") {
        $sql = "SELECT u.id, u.name, u.contact, u.email, u.date_add, o.office, (SELECT COUNT(r.id) FROM req r WHERE r.user_id = u.id) AS request_count
                FROM users u
                JOIN office o ON u.office_id = o.id
                WHERE u.user_role='Employee'
                AND u.office_id=$office_id
                GROUP BY u.id
                ORDER BY request_count DESC
                LIMIT 1";
    } else {
        $sql = "SELECT u.id, u.name, u.contact, u.email, u.date_add, u.user_role, o.office, (SELECT COUNT(r.id) FROM req r WHERE r.user_id = u.id) AS request_count
                FROM users u
                JOIN office o ON u.office_id = o.id
                WHERE u.user_role='Employee'
                AND u.office_id=$office_id";
    }
    $result=$con->select_by_query($sql);
    ?>
    <head>
        <title>Admin Manage</title>
    </head>

    <main>
        <div class="container-fluid px-4">
            <h2 class="mt-4">Manage Employees</h2>
            <div class="card shadow mb-4">
                <div class="card-body">

                        <div class="table-responsive">
                            <table class="table " id="office_manage" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Contact</th>
                                        <th>Email</th>
                                        <th>Date Added</th>
                                        <th>Requests</th>
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
                                                    <td><?php echo $row["request_count"]; ?></td>
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
                                new DataTable('#office_manage', {
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
    </main>
    </body>

    </html>

<?php
include("office_footer.php");
}
?>