<?php
session_start();
include('../log.php');
if (isset($_POST["btn_delete"])) {
    include("../conn.php");
    $id=$_GET['id'];
    $table="items";
    $con=new connec();

    log_supplydelete($id, $_SESSION["employee_id"], $_SESSION["office_id"]);
    error_log($_SESSION["employee_id"]);
    $con->delete($table,$id);
    header("location:office_inventory.php");
}

if (empty($_SESSION["username"])) {
    header("location:office_index.php");
}

else {
    include("office_header.php");

    if (isset($_GET['id'])) {
        $id=$_GET['id']; 

        $con=new connec();
        $sql="SELECT i.id, t.type, t.category, i.amount, i.threshold, o.office, i.price
        FROM items i
        INNER JOIN supply_type t ON i.supply_type_id = t.id
        INNER JOIN office o ON i.office_id = o.id
        WHERE i.id = $id";
        $result=$con->select_by_query($sql);
    }

    ?>
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10">
                    <h5 class="text-center mt-2">Delete Suppply Info</h5>

                    <form method="post">
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                ?>
                                <div class="container" style="padding-bottom: 50px">
                                    <label for="type_new"><b>Type</b></label>
                                    <input type="text" name="type_new" id="type_new" class="form-control" value="<?php echo $row["type"]; ?>" readonly required><br>

                                    <label for="quantity_new"><b>Quantity</b></label>
                                    <input type="number" name="quantity_new" id="quantity_new" class="form-control" value="<?php echo $row["amount"]; ?>" readonly required><br>

                                    <label for="threshold_new"><b>Threshold</b></label>
                                    <input type="number" name="threshold_new" id="threshold_new" class="form-control" value="<?php echo $row["threshold"]; ?>" readonly required><br>

                                    <label for="office_display"><b>Office</b></label>
                                    <input type="text" name="office_display" class="form-control" value="<?php echo $row["office"]; ?>" readonly required><br>
                                    <input type="hidden" name="office_new" id="office_new" value="<?php echo $office_id; ?>">

                                    <label for="price_new"><b>Price</b></label>
                                    <input type="number" name="price_new" id="price_new" class="form-control" value="<?php echo $row["price"]; ?>" readonly required><br>

                                    <a href="office_inventory.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
                                    <button type="submit" class="btn" name="btn_delete" style="background-color:#3741c9; color:white">Delete</button><br><br><br>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <?php
    include("office_footer.php");
}
?>