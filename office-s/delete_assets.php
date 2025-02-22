<?php
session_start();

if (isset($_POST["btn_delete"])) {
    include("../conn.php");
    $model=$_GET['model'];
    $type=$_GET['type'];
    $brand=$_GET['brand'];
    $office=$_GET['office'];

    $con = new connec();
    $sql = "SELECT a.id
    FROM assets a
    INNER JOIN asset_type t ON a.type_id = t.id
    INNER JOIN brand b ON a.brand_id = b.id
    INNER JOIN office o ON a.office_id = o.id
    WHERE a.model='$model'
    AND t.type='$type'
    AND b.brand='$brand'
    AND o.office='$office'";
    $result = $con->select_by_query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row["id"];
            $con->delete("assets", $id);
        }
    }
    header("location:office_assets.php");
}

if (empty($_SESSION["username"])) {
    header("location:office_index.php");
}

else {
    include("office_header.php");

    if (isset($_GET['model'], $_GET['type'], $_GET['brand'], $_GET['office'])) {
        $model=$_GET['model'];
        $type=$_GET['type'];
        $brand=$_GET['brand'];
        $office=$_GET['office'];

        $con = new connec();
        $sql="SELECT a.id, t.type, b.brand, a.model, a.serial, o.office_id, o.office
        FROM assets a
        INNER JOIN asset_type t ON a.type_id = t.id
        INNER JOIN brand b ON a.brand_id = b.id
        INNER JOIN office o ON a.office_id = o.id
        WHERE a.model='$model'
        AND t.type='$type'
        AND b.brand='$brand'
        AND o.office='$office'";
        $result = $con->select_by_query($sql);
    }

    ?>
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10">
                    <h5 class="text-center mt-2">Delete Asset Info</h5>

                    <form method="post">
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                ?>
                                <div class="container" style="padding-bottom: 50px">
                                    <label for="id"><b>ID</b></label>
                                    <input type="text" name="id" id="id" class="form-control" value="<?php echo $row["id"]; ?>" readonly>

                                    <label for="type"><b>Type</b></label>
                                    <input type="text" name="type" id="type" class="form-control" value="<?php echo $row["type"]; ?>" readonly>

                                    <label for="brand"><b>Brand</b></label>
                                    <input type="text" name="brand" id="brand" class="form-control" value="<?php echo $row["brand"]; ?>" readonly>

                                    <label for="model"><b>Model</b></label>
                                    <input type="text" name="model" id="model" class="form-control" value="<?php echo $row["model"]; ?>" readonly>

                                    <label for="office"><b>Office</b></label>
                                    <input type="text" name="office" id="office" class="form-control" value="<?php echo $row["office"]; ?>" readonly>

                                    <label for="serial"><b>Serial</b></label>
                                    <input type="text" name="serial" id="serial" class="form-control" value="<?php echo $row["serial"]; ?>" readonly>
                                </div>
                                <?php
                            }
                        }
                        ?>
                        <div class="container">
                            <a href="office_assets.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
                            <button type="submit" class="btn" name="btn_delete" style="background-color:#3741c9; color:white">Delete</button><br><br><br>
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