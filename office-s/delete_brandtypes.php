<?php
session_start();
if (isset($_POST["btn_delete"])) {
    include("../conn.php");
    $id=$_GET['id'];
    $table="brand";
    $con=new connec();

    $con->delete($table,$id);
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
                    <h5 class="text-center mt-2">Delete Brand Info</h5>

                    <form method="post">
                        <div class="container">

                            <label for="brand_new"><b>Brand</b></label>
                            <input type="text" name="brand_new" id="brand_new" class="form-control" value="<?php echo $row["brand"] ?>" readonly required><br>

                            <a href="office_brandtypes.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
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