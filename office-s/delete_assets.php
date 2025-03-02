<?php
session_start();
include('../log.php');
if (isset($_POST["btn_delete"])) {
    include("../conn.php");
    $model=$_GET['model'];
    $type=$_GET['type'];
    $brand=$_GET['brand'];
    $office=$_GET['office'];

    $con = new connec();
    $sql = "SELECT i.id
    FROM items i
    INNER JOIN asset_type t ON i.asset_type_id = t.id
    INNER JOIN brand b ON i.brand_id = b.id
    INNER JOIN office o ON i.office_id = o.id
    WHERE i.model='$model'
    AND t.type='$type'
    AND b.brand='$brand'
    AND o.office='$office'
    AND i.status='Available'";
    $result = $con->select_by_query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row["id"];
            log_assetdelete($id, $_SESSION["employee_id"], $_SESSION["office_id"]);
            $con->delete("items", $id);
        }
    }
    header("location:office_inventory.php");
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
        $sql="SELECT i.id, t.type, b.brand, i.model, i.serial, i.office_id, o.office
        FROM items i
        INNER JOIN asset_type t ON i.asset_type_id = t.id
        INNER JOIN brand b ON i.brand_id = b.id
        INNER JOIN office o ON i.office_id = o.id
        WHERE i.model='$model'
        AND t.type='$type'
        AND b.brand='$brand'
        AND o.office='$office'
        AND i.status='Available'";
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
                                    <label for="type"><b>Type</b></label>
                                    <input type="text" name="type" id="type" class="form-control" value="<?php echo $row["type"]; ?>" readonly><br>

                                    <label for="brand"><b>Brand</b></label>
                                    <input type="text" name="brand" id="brand" class="form-control" value="<?php echo $row["brand"]; ?>" readonly><br>

                                    <label for="model"><b>Model</b></label>
                                    <input type="text" name="model" id="model" class="form-control" value="<?php echo $row["model"]; ?>" readonly><br>

                                    <label for="serial"><b>Serial</b></label>
                                    <input type="text" name="serial" id="serial" class="form-control" value="<?php echo $row["serial"]; ?>" readonly><br>
                                </div>
                                <?php
                            }
                        }
                        ?>
                        <div class="container">
                            <a href="office_inventory.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
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