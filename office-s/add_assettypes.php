<?php
session_start();
if (isset($_POST["btn_insert"])) {
    include("../conn.php");
    $type = $_POST["type_new"];
    $category = $_POST["category_new"];
    $unit_id = $_POST["unit_new"];
    $date_expected = $_POST["date_expected_new"];

    $con = new connec();
    $sql = "INSERT INTO asset_type VALUES(0, '$type', '$category', '$unit_id', '$date_expected')";
    $con->insert($sql, "Data Inserted Successfully");
    header("location:admin_assettypes.php");
}

if (empty($_SESSION["username"])) {
    header("location:../login.php");
}

else {
    include("office_header.php");
    ?>
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10">
                    <h5 class="text-center mt-2">Add New Asset Type</h5>

                    <form method="post">
                        <div class="container">

                            <label for="type_new"><b>Type</b></label>
                            <input type="text" name="type_new" id="type_new" class="form-control" required><br>

                            <label for="category_new"><b>Category</b></label>
                            <select name="category_new" id="category_new" class="form-control" required>
                                <option value="" disabled selected>Select Type</option>
                                <option value="Hardware">Hardware</option>
                                <option value="Furniture">Furniture</option>
                            </select><br>

                            <label for="unit_new"><b>Unit of Issue</b></label>
                            <select name="unit_new" id="unit_new" class="form-control" required>
                                <option value="" disabled selected>Select Unit</option>
                                <?php
                                $con = new connec();
                                $sql = "SELECT id, unit_name FROM unit";
                                $result=$con->select_by_query($sql);
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['id'] . "'>" . $row['unit_name'] . "</option>";
                                }
                                ?>
                            </select><br>

                            <label for="date_expected_new"><b>Expected Time of Delivery</b></label>
                            <input type="text" name="date_expected_new" id="date_expected_new" class="form-control" required><br>

                            <a href="admin_manageusers.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
                            <button type="submit" class="btn" name="btn_insert" style="background-color:#3741c9; color:white">Insert</button><br><br><br>

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