<?php
session_start();
include('../log.php');
if(isset($_POST["btn_update"])){
    include("../conn.php");
    $amount = $_POST["amount_new"];
    $threshold = $_POST["threshold_new"];
    $office = $_POST["office_new"];
    $price = $_POST["price_new"];

    $id=$_GET['id'];
    $con=new connec();

    $sql="SELECT amount, threshold, price
    FROM items
    WHERE id='$id'";
    $result=$con->select_by_query($sql);
    $row=$result->fetch_assoc();
    $old_amount = $row["amount"];
    $old_threshold = $row["threshold"];
    $old_price = $row["price"];
    $changes = "";
    if($old_amount != $amount) {
        $changes .= "Amount changed from $old_amount to $amount, ";
    }
    if($old_threshold != $threshold) {
        $changes .= "Threshold changed from $old_threshold to $threshold, ";
    }
    if($old_price != $price) {
        $changes .= "Price changed from $old_price to $price";
    }
    $changes = rtrim($changes, ", ");

    $sql="UPDATE items SET amount='$amount', threshold='$threshold', office_id='$office', price='$price' WHERE id='$id'";
    $con->update($sql, "Data Updated Successfully");
    log_supplyedit($id, $_SESSION["employee_id"], $_SESSION["office_id"], $changes);
    header("location:office_inventory.php");
}

if(empty($_SESSION["username"])){
    header("location:office_index.php");
}

else{
    include("office_header.php");

    $office_id = $_SESSION["office_id"];

    if(isset($_GET['id'])){
        $id=$_GET['id'];

        $con=new connec();
        $sql="SELECT i.id, t.type, t.category, i.amount, i.threshold, o.office, i.price
        FROM items i
        INNER JOIN supply_type t ON i.supply_type_id = t.id
        INNER JOIN office o ON i.office_id = o.id
        WHERE i.id = $id";
        $result=$con->select_by_query($sql);
        $row=$result->fetch_assoc();
        
        }
    ?>
        <section>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-10">
                            <h5 class="text-center mt-2">Edit Supply Info</h5>

                            <form method="post">
                                <div class="container" style="color:#353133">

                                    <label for="type_new"><b>Type</b></label>
                                    <input type="text" name="type_new" id="type_new" class="form-control" value="<?php echo $row["type"]; ?>" readonly><br>

                                    <label for="amount_new"><b>Amount</b></label>
                                    <input type="number" name="amount_new" id="amount_new" class="form-control" value="<?php echo $row["amount"]; ?>" required><br>

                                    <label for="threshold_new"><b>Threshold</b></label>
                                    <input type="number" name="threshold_new" id="threshold_new" class="form-control" value="<?php echo $row["threshold"]; ?>" required><br>

                                    <label for="office_display"><b>Office</b></label>
                                    <input type="text" name="office_display" class="form-control" value="<?php echo $row["office"]; ?>" readonly required><br>
                                    <input type="hidden" name="office_new" id="office_new" value="<?php echo $office_id; ?>">

                                    <label for="price_new"><b>Price</b></label>
                                    <input type="number" name="price_new" id="price_new" class="form-control" value="<?php echo $row["price"]; ?>" required><br>

                                    <a href="office_inventory.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
                                    <button type="submit" class="btn" name="btn_update" style="background-color:#3741c9; color:white">Update</button><br><br><br>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        </section>
        <?php
    include("office_footer.php");
}
?>
