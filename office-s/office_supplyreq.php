<?php
session_start();

if (isset($_POST["btn_approve"])) {
    include("../conn.php");
    $order=$_POST['order'];
    $con=new connec();

    $sql="SELECT * FROM supp WHERE order_id = '$order'";
    $result=$con->select_by_query($sql);

    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()) {
            $id = $row["id"];
            $amount=$_POST['amount' . $id];
            $supply_type_id = $row["supply_type_id"];

            $sql="UPDATE supp SET req_status = 'Incomplete' WHERE id='$id'";
            $con->update($sql, "Data Updated Successfully");

            $sql="UPDATE supplies SET quantity = quantity - '$amount' WHERE type_id='$supply_type_id'";
            $con->update($sql, "Data Updated Successfully");
        }
    }
    header("Location: office_supplyreq.php");
    exit;
}

if (isset($_POST["btn_complete"])) {
    include("../conn.php");
    $order=$_POST['order'];
    $con=new connec();

    $sql="UPDATE supp SET req_status = 'Complete' WHERE order_id='$order'";
    $con->update($sql, "Data Updated Successfully");

    header("Location: office_supplyreq.php");
    exit;
}

if(empty($_SESSION["username"])){
    header("location:../login.php");
}
else {
    include("office_header.php");
    $office = $_SESSION["office_id"];

    $con=new connec();
    $sql="SELECT s.id, s.supply_type_id, t.type, s.amount, s.order_id, u.name, o.office, s.req_status
    FROM supp s
    JOIN supply_type t ON s.supply_type_id = t.id
    JOIN users u ON s.user_id = u.id
    JOIN office o ON u.office_id = o.id
    WHERE s.req_status IN ('Incomplete', 'Pending') AND u.office_id = '$office';";
    $result=$con->select_by_query($sql);

    // Group orders by order ID
    $orders = array();
    while ($row = $result->fetch_assoc()) {
        $order_id = $row["order_id"];
        if (!isset($orders[$order_id])) {
            $orders[$order_id] = array(
                "user_name" => $row["name"],
                "order_data" => array()
            );
        }
        $orders[$order_id]["order_data"][] = array(
            "id" => $row["id"],
            "supply_type_id" => $row["supply_type_id"],
            "type" => $row["type"],
            "amount" => $row["amount"],
            "office" => $row["office"],
            "req_status" => $row["req_status"]
        );
    }
    ?>
    <head>
        <title>Supply Requests</title>
    </head>
    <main>
        <?php
        if (empty($orders)) {
            ?>
            <div class="container-fluid px-4">
                <h2 class="mt-4">Supply Requests</h2>
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <p>No supply requests found.</p>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="container-fluid px-4">
                <h2 class="mt-4">Supply Requests</h2>
                <?php
                foreach ($orders as $order_id => $order_data) {
                    ?>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <form method="post">
                                <h4>Order ID: <?php echo $order_id; ?> (<?php echo $order_data["user_name"]; ?>)</h4>
                                <input type="hidden" name="order" value="<?php echo $order_id; ?>">
                                <table class="table " id="dataAssetTable" width="100%" cellspacing="0">
                                    <thead class="table-blue">
                                        <tr>
                                            <th>Supply Type</th>
                                            <th>Amount</th>
                                            <th>Office</th>
                                            <th>Request Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($order_data["order_data"] as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row["type"]; ?></td>
                                            <td><?php echo $row["amount"]; ?></td>
                                            <input type="hidden" name="amount<?php echo $row["id"]; ?>" value="<?php echo $row["amount"]; ?>">
                                            <td><?php echo $row["office"]; ?></td>
                                            <td style="height: 40px;">
                                                <?php
                                                if ($row["req_status"] == "Incomplete") {
                                                    ?>
                                                    <i class='bx bxs-info-circle large-icon' style='color:#ffa83e;' title="<?php echo $row["req_status"]; ?>"></i>
                                                    <?php
                                                }
                                                else if ($row["req_status"] == "Pending") {
                                                    ?>
                                                    <i class='bx bxs-time-five large-icon' style='color:#00b2f1' title="<?php echo $row["req_status"]; ?>"></i>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                    ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <?php
                                            if ($row["req_status"] == 'Pending'){
                                                ?>
                                                <td colspan="9">
                                                    <button type="submit" class="btn btn-primary" name="btn_approve">Approve</button>
                                                    <a class="btn btn-danger" style="color: #ffffff" href='cancel_supplyreq.php?order=<?php echo $order_id; ?>'>Cancel</a>
                                                </td>
                                                <?php
                                            }
                                            else if ($row["req_status"] == 'Incomplete') {
                                                ?>
                                                <td colspan="9">
                                                    <button type="submit" class="btn btn-primary" name="btn_complete">Complete</button>
                                                </td>
                                                <?php
                                            }
                                            ?>
                                        </tr>
                                    </tfoot>
                                </table>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php
                    }
                ?>
            </div>
            <?php
        }
        ?>
    </main>
</body>
</html>

<script>
    $(document).ready(function() {
        $('[id^="supply_type_id"]').on('change', function() {
            var supplyTypeId = $(this).attr('id').replace('supply_type_id', '');
            var supplyType = $(this).val();
            $.ajax({
                type: 'POST',
                url: 'get_supplies.php',
                data: {supply_type: supplyType},
                success: function(data) {
                    $('#amount' + supplyTypeId).html(data);
                }
            });
        });
    });
</script>

<?php
include("office_footer.php");
}
?>