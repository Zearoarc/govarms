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
            <h2 class="mt-4">Manage Offices</h2>
            <div class="card shadow mb-4">
                <div class="card-body">

                        <div class="table-responsive">
                            <table class="display" id="offices" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Office Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if($result->num_rows>0){
                                            while($row=$result->fetch_assoc()){
                                                ?>
                                                <tr>
                                                    <td style="width: 100px;"><?php echo $row["id"]; ?></td>
                                                    <td><?php echo $row["office"]; ?></td>
                                                    <td style="width: 150px;">
                                                        <a class='btn btn-primary btn-sm' href='edit_office.php?id=<?php echo $row["id"]; ?>'>Edit</a>
                                                        <a class='btn btn-sm btn-danger' href='delete_office.php?id=<?php echo $row["id"]; ?>'>Delete</a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                </tbody>
                            </table>
                            <script>
                                new DataTable('#offices', {
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
                                                        window.location.href = 'add_office.php';
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