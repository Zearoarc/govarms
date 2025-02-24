<?php

session_start();
include("header.php");
$con = new connec();
if(empty($_SESSION["username"])){
    header("location:login.php");
}
else {
    $id = $_SESSION["employee_id"];

    $sql = "SELECT s.id, t.type, t.category, s.date_add, s.date_expected, s.order_id, u.name, s.amount, s.req_status, s.notes
    FROM supp s
    JOIN supply_type t ON s.supply_type_id = t.id
    JOIN users u ON s.user_id = u.id
    WHERE s.user_id = '$id' AND s.req_status IN ('Complete')";
    $result=$con->select_by_query($sql);
    
    ?> 
 
    <head>
        <title>Supplies</title>
    </head>

    <main>
        <div class="container-fluid px-4">
            <h2 class="mt-4">Owned Assets</h2>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table " id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Category</th>
                                        <th>Amount</th>
                                        <th>Request Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if($result->num_rows>0){
                                            while($row=$result->fetch_assoc()){
                                                ?>
                                                <tr>
                                                    <td><?php echo $row["type"]; ?></td>
                                                    <td><?php echo $row["category"]; ?></td>
                                                    <td><?php echo $row["amount"]; ?></td>
                                                    <td style="width: 250px;">
                                                        <?php
                                                        if ($row["req_status"] == "Complete") {
                                                            ?>
                                                            <i class='bx bxs-check-circle large-icon' style='color:#93b858' title="<?php echo $row["req_status"]; ?>"></i>
                                                            <?php
                                                        }
                                                        ?>
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
    </main>
 
    <?php
    include("footer.php");
    }
    ?>