<?php
session_start();
if(empty($_SESSION["username"])){
    header("location:../login.php");
}
else {
    include("office_header.php");

    $con=new connec();
    $sql="SELECT s.id, s.type, s.category, u.unit_name, u.description, s.date_expected
    FROM supply_type s
    JOIN unit u ON s.unit_id = u.id";
    $result=$con->select_by_query($sql);
    ?>
    <head>
        <title>Supply Types</title>
    </head>

    <main>
        <div class="container-fluid px-4">
            <h2 class="mt-4">Supply Types</h2>
            <div class="card shadow mb-4">
                <div class="card-body">
                        <div class="table-responsive">
                            <table class="table " id="supplyTypeTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Supply Type</th>
                                        <th>Category</th>
                                        <th>Unit of Issue</th>
                                        <th>Description</th>
                                        <th>Expected Time</th>
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
                                                    <td><?php echo $row["type"]; ?></td>
                                                    <td style="width: 100px;"><?php echo $row["category"]; ?></td>
                                                    <td style="width: 150px;"><?php echo $row["unit_name"]; ?></td>
                                                    <td style="width: 250px;"><?php echo $row["description"]; ?></td>
                                                    <td><?php echo $row["date_expected"]; ?> days</td>
                                                    <td>
                                                        <a class='btn btn-primary btn-sm' href='edit_supplytypes.php?id=<?php echo $row["id"]; ?>'>Edit</a>
                                                        <a class='btn btn-sm btn-danger' href='delete_supplytypes.php?id=<?php echo $row["id"]; ?>'>Delete</a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                </tbody>
                            </table>
                            <script>
                            new DataTable('#supplyTypeTable', {
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
                                                    window.location.href = 'add_supplytypes.php';
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