<?php
session_start();
if (isset($_POST["btn_insert"])) {
    include("../conn.php");
    $office = $_POST["office_new"];

    $con = new connec();
    $sql = "INSERT INTO office VALUES(0, '$office')";
    $con->insert($sql, "Data Inserted Successfully");
    header("location:admin_manageoffices.php");
}

if (empty($_SESSION["username"])) {
    header("location:../login.php");
}

else {
    include("admin_header.php");

    ?>
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10">
                    <h5 class="text-center mt-2">Add New Office</h5>

                    <form method="post">
                        <div class="container">

                            <label for="office_new"><b>Office</b></label>
                            <input type="text" name="office_new" id="office_new" class="form-control" required><br>

                            <a href="admin_manageoffices.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
                            <button type="submit" class="btn" name="btn_insert" style="background-color:#3741c9; color:white">Insert</button><br><br><br>

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