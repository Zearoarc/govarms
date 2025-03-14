<?php
session_start();
if (isset($_POST["btn_delete"])) {
    include("../conn.php");
    $id=$_GET['id'];
    $table="asset_type";
    $con=new connec();

    $con->delete($table,$id);
    header("location:office_assettypes.php");
}

if (empty($_SESSION["username"])) {
    header("location:../login.php");
}

else {
    include("office_header.php");

    if(isset($_GET['id'])){
        $id=$_GET['id'];

        $con = new connec();
        $sql="SELECT a.type, a.category, u.id, u.unit_name, a.date_expected
        FROM asset_type a
        JOIN unit u ON a.unit_id = u.id
        WHERE a.id = '$id'";
        $result=$con->select_by_query($sql);
        $row=$result->fetch_assoc();
    }

    ?>
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10">
                    <h5 class="text-center mt-2">Delete Type Info</h5>

                    <form method="post">
                        <div class="container">

                        <label for="type_new"><b>Type</b></label>
                            <input type="text" name="type_new" id="type_new" class="form-control" value="<?php echo $row["type"] ?>" readonly required><br>

                            <label for="category_new"><b>Category</b></label>
                            <input name="category_new" id="category_new" class="form-control" value="<?php echo $row["category"] ?>" readonly required><br>

                            <label for="unit_new"><b>Unit</b></label>
                            <input type="text" name="unit_new" id="unit_new" class="form-control" value="<?php echo $row["unit_name"] ?>" readonly required><br>

                            <label for="date_expected_new"><b>Expected Time of Delivery</b></label>
                            <input type="text" name="date_expected_new" id="date_expected_new" class="form-control" value="<?php echo $row["date_expected"] ?>" readonly required><br>

                            <a href="office_manageoffices.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
                            <button type="submit" class="btn btn-danger" name="btn_delete" style="color:white">Delete</button><br><br><br>

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