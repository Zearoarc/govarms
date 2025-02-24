<?php
session_start();
if (isset($_POST["btn_update"])) {
    include("../conn.php");
    $brand = $_POST["brand_new"];

    $id=$_GET['id'];
    $con=new connec();

    $sql="UPDATE brand SET brand='$brand' WHERE id='$id'";
    $con->update($sql, "Data Updated Successfully");
    header("location:office_brandtypes.php");
}

if (empty($_SESSION["username"])) {
    header("location:../login.php");
}

else {
    include("office_header.php");

    if(isset($_GET['id'])){
        $id=$_GET['id'];

        $con = new connec();
        $tbl='brand';
        $result=$con->select($tbl, $id);
        $row=$result->fetch_assoc();
    }

    ?>
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10">
                    <h5 class="text-center mt-2">Edit Brand Info</h5>

                    <form method="post">
                        <div class="container">

                            <label for="brand_new"><b>Brand</b></label>
                            <input type="text" name="brand_new" id="brand_new" class="form-control" value="<?php echo $row["brand"] ?>" required><br>

                            <a href="office_brandtypes.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
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