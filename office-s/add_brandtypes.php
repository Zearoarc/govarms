<?php
session_start();
if (isset($_POST["btn_insert"])) {
    include("../conn.php");
    $brand = $_POST["brand_new"];

    $con = new connec();
    $sql = "INSERT INTO brand VALUES(0, '$brand')";
    $con->insert($sql, "Data Inserted Successfully");
    header("location:office_brandtypes.php");
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
                    <h5 class="text-center mt-2">Add New Brand</h5>

                    <form method="post">
                        <div class="container">

                            <label for="brand_new"><b>Brand</b></label>
                            <input type="text" name="brand_new" id="brand_new" class="form-control" required><br>

                            <a href="office_brandtypes.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
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