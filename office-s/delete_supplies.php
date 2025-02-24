<?php
session_start();

if (isset($_POST["btn_delete"])) {
    include("../conn.php");
    $id=$_GET['id'];
    $table="supplies";
    $con=new connec();

    $con->delete($table,$id);
    header("location:office_supplies.php");
}

if (empty($_SESSION["username"])) {
    header("location:office_index.php");
}

else {
    include("office_header.php");

    if (isset($_GET['id'])) {
        $id=$_GET['id']; 

        $con=new connec();
        $sql="SELECT s.id, t.type, t.category, s.quantity, o.office, s.price
        FROM supplies s
        INNER JOIN supply_type t ON s.type_id = t.id
        INNER JOIN office o ON s.office_id = o.id
        WHERE s.id = $id";
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
                                    <input type="number" name="quantity_new" id="quantity_new" class="form-control" value="<?php echo $row["quantity"]; ?>" readonly required><br>

                                    <label for="office_display"><b>Office</b></label>
                                    <input type="text" name="office_display" class="form-control" value="<?php echo $row["office"]; ?>" readonly required><br>
                                    <input type="hidden" name="office_new" id="office_new" value="<?php echo $office_id; ?>">

                                    <label for="price_new"><b>Price</b></label>
                                    <input type="number" name="price_new" id="price_new" class="form-control" value="<?php echo $row["price"]; ?>" readonly required><br>

                                    <a href="office_supplies.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
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