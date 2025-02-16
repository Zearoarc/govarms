<?php
session_start();

if (isset($_POST["btn_delete"])) {
    include("../conn.php");
    $model=$_GET['model'];
    $type=$_GET['type'];
    $supplier=$_GET['supplier'];
    $dept=$_GET['dept'];
    $division=$_GET['division'];

    $con = new connec();
    $sql = "SELECT a.id
        FROM assets a
        INNER JOIN asset_type t ON a.type_id = t.id
        INNER JOIN supplier s ON a.supplier_id = s.id
        INNER JOIN department dept ON a.department_id = dept.id
        INNER JOIN division d ON a.division_id = d.id
        WHERE a.model='$model'
        AND t.type='$type'
        AND s.supplier='$supplier'
        AND dept.department='$dept'
        AND d.division='$division'";
    $result = $con->select_by_query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row["id"];
            $con->delete("assets", $id);
        }
    }
    header("location:admin_assets.php");
}

if (empty($_SESSION["username"])) {
    header("location:admin_index.php");
}

else {
    include("admin_header.php");

    if (isset($_GET['model'], $_GET['type'], $_GET['supplier'], $_GET['dept'], $_GET['division'])) {
        $model=$_GET['model'];
        $type=$_GET['type'];
        $supplier=$_GET['supplier'];
        $dept=$_GET['dept'];
        $division=$_GET['division'];

        $con = new connec();
        $sql="SELECT a.id, t.type, s.supplier, a.model, a.serial, a.division_id, dept.department, d.division
        FROM assets a
        INNER JOIN asset_type t ON a.type_id = t.id
        INNER JOIN supplier s ON a.supplier_id = s.id
        INNER JOIN department dept ON a.department_id = dept.id
        INNER JOIN division d ON a.division_id = d.id
        WHERE a.model='$model'
        AND t.type='$type'
        AND s.supplier='$supplier'
        AND dept.department='$dept'
        AND d.division='$division'";
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

                                    <label for="supplier"><b>Supplier</b></label>
                                    <input type="text" name="supplier" id="supplier" class="form-control" value="<?php echo $row["supplier"]; ?>" readonly>

                                    <label for="model"><b>Model</b></label>
                                    <input type="text" name="model" id="model" class="form-control" value="<?php echo $row["model"]; ?>" readonly>

                                    <label for="dept"><b>Department</b></label>
                                    <input type="text" name="dept" id="dept" class="form-control" value="<?php echo $row["department"]; ?>" readonly>

                                    <label for="division"><b>Division</b></label>
                                    <input type="text" name="division" id="division" class="form-control" value="<?php echo $row["division"]; ?>" readonly>

                                    <label for="serial"><b>Serial</b></label>
                                    <input type="text" name="serial" id="serial" class="form-control" value="<?php echo $row["serial"]; ?>" readonly>
                                </div>
                                <?php
                            }
                        }
                        ?>
                        <div class="container">
                            <a href="admin_assets.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
                            <button type="submit" class="btn" name="btn_delete" style="background-color:#3741c9; color:white">Delete</button><br><br><br>
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