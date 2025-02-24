<?php
session_start();
if (isset($_POST["btn_update"])) {
    include("../conn.php");
    $id=$_GET['id'];
    $type = $_POST["type_new"];
    $category = $_POST["category_new"];
    $unit_id = $_POST["unit_new"];
    $date_expected = $_POST["date_expected_new"];

    $con = new connec();
    $sql="UPDATE supply_type SET type='$type', category='$category', unit_id='$unit_id', date_expected='$date_expected' WHERE id='$id'";
    $con->update($sql, "Data Updated Successfully");
    header("location:admin_supplytypes.php");
}

if (empty($_SESSION["username"])) {
    header("location:../login.php");
}

else {
    include("admin_header.php");

    if(isset($_GET['id'])){
        $id=$_GET['id'];

        $con = new connec();
        $sql="SELECT s.type, s.category, u.id, u.unit_name, s.date_expected
        FROM supply_type s
        JOIN unit u ON s.unit_id = u.id
        WHERE s.id = '$id'";
        $result=$con->select_by_query($sql);
        $row=$result->fetch_assoc();
    }
    ?>
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10">
                    <h5 class="text-center mt-2">Edit Type Info</h5>

                    <form method="post">
                        <div class="container">

                            <label for="type_new"><b>Type</b></label>
                            <input type="text" name="type_new" id="type_new" class="form-control" value="<?php echo $row["type"] ?>" required><br>

                            <label for="category_new"><b>Category</b></label>
                            <select name="category_new" id="category_new" class="form-control" required>
                                <option value="" disabled selected>Select Type</option>
                                <option value="Office" <?php if ($row["category"] == "Office") echo "selected"; ?>>Office</option>
                                <option value="Food and Beverage" <?php if ($row["category"] == "Food and Beverage") echo "selected"; ?>>Food and Beverage</option>
                                <option value="Safety and Security" <?php if ($row["category"] == "Safety and Security") echo "selected"; ?>>Safety and Security</option>
                                <option value="Technology" <?php if ($row["category"] == "Technology") echo "selected"; ?>>Technology</option>
                                <option value="Miscallaneous" <?php if ($row["category"] == "Miscallaneous") echo "selected"; ?>>Miscallaneous</option>
                            </select><br>

                            <label for="unit_new"><b>Unit</b></label>
                            <select name="unit_new" id="unit_new" class="form-control" required>
                                <option value="<?php echo $row["id"] ?>" selected><?php echo $row["unit_name"] ?></option>
                                <?php
                                $con = new connec();
                                $sql_unit = "SELECT id, unit_name FROM unit";
                                $result_unit = $con->select_by_query($sql_unit);
                                while ($row_unit = $result_unit->fetch_assoc()) {
                                    echo "<option value='" . $row_unit['id'] . "'>" . $row_unit['unit_name'] . "</option>";
                                }
                                ?>
                            </select><br>

                            <label for="date_expected_new"><b>Expected Time of Delivery</b></label>
                            <input type="text" name="date_expected_new" id="date_expected_new" class="form-control" value="<?php echo $row["date_expected"] ?>" required><br>

                            <a href="admin_manageusers.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
                            <button type="submit" class="btn" name="btn_update" style="background-color:#3741c9; color:white">Update</button><br><br><br>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <?php
    include("admin_footer.php");
}
?>