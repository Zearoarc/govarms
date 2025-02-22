<?php
session_start();
if (isset($_POST["btn_delete"])) {
    include("../conn.php");
    $id=$_GET['id'];
    $table="office";
    $con=new connec();

    $con->delete($table,$id);
    header("location:admin_manageoffices.php");
}

if (empty($_SESSION["username"])) {
    header("location:../login.php");
}

else {
    include("admin_header.php");

    if(isset($_GET['id'])){
        $id=$_GET['id'];

        $con = new connec();
        $tbl='office';
        $result=$con->select($tbl, $id);
        $row=$result->fetch_assoc();
    }

    ?>
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10">
                    <h5 class="text-center mt-2">Delete Office Info</h5>

                    <form method="post">
                        <div class="container">

                            <label for="office_new"><b>Office</b></label>
                            <input type="text" name="office_new" id="office_new" class="form-control" value="<?php echo $row["office"] ?>" readonly required><br>

                            <a href="admin_manageoffices.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
                            <button type="submit" class="btn btn-danger" name="btn_delete" style="color:white">Delete</button><br><br><br>

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