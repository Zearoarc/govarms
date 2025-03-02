<?php

session_start();
include("header.php");
$con = new connec();

if (empty($_SESSION["username"])) {
    header("location:login.php");
} else {
    $id = $_SESSION["employee_id"];

    $sql_repair="SELECT i.model, i.serial, f.repair, f.feedback, f.created_at
    FROM feedback f
    JOIN items i ON f.asset_id = i.id
    WHERE f.user_id = $id AND f.action = 'repair'";
    $result_repair=$con->select_by_query($sql_repair);

    $sql_dispose="SELECT i.model, i.serial, f.repair, f.feedback, f.created_at
    FROM feedback f
    JOIN items i ON f.asset_id = i.id
    WHERE f.user_id = $id AND f.action = 'dispose'";
    $result_dispose=$con->select_by_query($sql_dispose);
    ?>

    <head>
        <title>Feedbacks</title>
    </head>

    <main>
        <div class="container-fluid px-4">
            <h2 class="mt-4">Repaired Feedbacks</h2>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table " id="repair_feedback" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Date Created</th>
                                        <th>Model</th>
                                        <th>Serial</th>
                                        <th>Repair #</th>
                                        <th>Feedback</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if($result_repair->num_rows>0){
                                            while($row_repair=$result_repair->fetch_assoc()){
                                                ?>
                                                <tr>
                                                    <td style = "width: 200px"><?php echo $row_repair['created_at']; ?></td>
                                                    <td style = "width: 200px"><?php echo $row_repair["model"]; ?></td>
                                                    <td style = "width: 200px"><?php echo $row_repair["serial"]; ?></td>
                                                    <td style = "width: 150px"><?php echo $row_repair["repair"]; ?></td>
                                                    <td><?php echo $row_repair["feedback"]; ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                </tbody>
                            </table>
                            <script>
                                new DataTable('#repair_feedback', {
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
        <div class="container-fluid px-4">
            <h2 class="mt-4">Disposed Feedbacks</h2>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table " id="dispose_feedback" width="100%" cellspacing="0">
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
                                        if($result_dispose->num_rows>0){
                                            while($row_dispose=$result_dispose->fetch_assoc()){
                                                ?>
                                                <tr>
                                                    <td style = "width: 200px"><?php echo $row_dispose['created_at']; ?></td>
                                                    <td style = "width: 200px"><?php echo $row_dispose["model"]; ?></td>
                                                    <td style = "width: 200px"><?php echo $row_dispose["serial"]; ?></td>
                                                    <td><?php echo $row_dispose["feedback"]; ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                </tbody>
                            </table>
                            <script>
                                new DataTable('#dispose_feedback', {
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

    include("footer.php");
}
?>