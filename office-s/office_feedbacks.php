<?php
session_start();
if(empty($_SESSION["username"])){
    header("location:../login.php");
}
else {
    include("office_header.php");

    $con=new connec();
    $office_id = $_SESSION["office_id"];

    $sql_return="SELECT i.model, i.serial, f.repair, f.feedback, f.created_at
    FROM feedback f
    JOIN items i ON f.asset_id = i.id
    JOIN users u ON f.user_id = u.id
    WHERE u.office_id = $office_id AND f.action = 'return'";
    $result_return=$con->select_by_query($sql_return);
    ?>
    <main>
        <div class="container-fluid px-4">
            <h2 class="mt-4">Returned Asset Feedbacks</h2>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table " id="return_feedback" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Date Created</th>
                                <th>Model</th>
                                <th>Serial</th>
                                <th>Feedback</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if($result_return->num_rows>0){
                                    while($row_return=$result_return->fetch_assoc()){
                                        ?>
                                        <tr>
                                            <td style = "width: 200px"><?php echo $row_return['created_at']; ?></td>
                                            <td style = "width: 200px"><?php echo $row_return["model"]; ?></td>
                                            <td style = "width: 200px"><?php echo $row_return["serial"]; ?></td>
                                            <td><?php echo $row_return["feedback"]; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                        </tbody>
                    </table>
                    <script>
                        new DataTable('#return_feedback', {
                            "paging": true,
                            "lengthChange": true,
                            "searching": true,
                            "ordering": true,
                            "info": false,
                            "autoWidth": false,
                            "order": [[ 0, "desc" ]]
                        });
                    </script>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php
    include("office_footer.php");
}
?>