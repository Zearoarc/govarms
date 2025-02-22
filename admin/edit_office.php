<?php
session_start();
if (isset($_POST["btn_update"])) {
    include("../conn.php");
    $office = $_POST["office_new"];

    $id=$_GET['id'];
    $con=new connec();

    $sql="UPDATE office SET office='$office' WHERE id='$id'";
    $con->update($sql, "Data Updated Successfully");
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
                    <h5 class="text-center mt-2">Edit Office Info</h5>

                    <form method="post">
                        <div class="container">

                            <label for="office_new"><b>Office</b></label>
                            <input type="text" name="office_new" id="office_new" class="form-control" value="<?php echo $row["office"] ?>" required><br>

                            <a href="admin_manageoffices.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
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