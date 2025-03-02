<?php
session_start();
include('../log.php');
if(isset($_POST["btn_update"])){
    include("../conn.php");
    $model=$_GET['model'];
    $type=$_GET['type'];
    $brand=$_GET['brand'];
    $office=$_GET['office'];
    $con=new connec();
    $sql="SELECT i.id, i.asset_type_id, t.type, i.brand_id, b.brand, i.model, i.serial, i.office_id, o.office, i.status, i.borrow_times, i.repair_times
    FROM items i
    INNER JOIN asset_type t ON i.asset_type_id = t.id
    INNER JOIN brand b ON i.brand_id = b.id
    INNER JOIN office o ON i.office_id = o.id
    WHERE i.model='$model'
    AND t.type='$type'
    AND b.brand='$brand'
    AND o.office='$office'
    AND i.status='Available'";
    $result=$con->select_by_query($sql);
    $changes = "";

    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $id = $row["id"];
            $old_type = $row["asset_type_id"];
            $old_brand = $row["brand_id"];
            $old_model = $row["model"];
            $old_serial = $row["serial"];
            $new_type = $_POST["type_new" . $id];
            $new_brand = $_POST["brand_new" . $id];
            $new_model = $_POST["model_new" . $id];
            $new_serial = $_POST["serial_new" . $id];

            if($old_type != $new_type) {
                $changes .= "Type changed from $old_type to $new_type, ";
            }
            if($old_brand != $new_brand) {
                $changes .= "Brand changed from $old_brand to $new_brand, ";
            }
            if($old_model != $new_model) {
                $changes .= "Model changed from $old_model to $new_model, ";
            }
            if($old_serial != $new_serial) {
                $changes .= "Serial changed from $old_serial to $new_serial";
            }
            $changes = rtrim($changes, ", ");
            $sql = "UPDATE items SET asset_type_id='$new_type', brand_id='$new_brand', model='$new_model', serial='$new_serial' WHERE id='$id'";
            $con->update($sql, "Data Updated Successfully");
            if (!empty($changes)) {
                log_assetedit($id, $old_serial, $_SESSION["employee_id"], $_SESSION["office_id"], $changes);
            }
        }
    }
    header("location:office_inventory.php");
}

if(empty($_SESSION["username"])){
    header("location:office_index.php");
}

else{
    include("office_header.php");
    
    
    if(isset($_GET['model'], $_GET['type'], $_GET['brand'], $_GET['office'])){
        $model=$_GET['model'];
        $type=$_GET['type'];
        $brand=$_GET['brand'];
        $office=$_GET['office'];

        $con=new connec();
        $sql="SELECT i.id, t.type, b.brand, i.model, i.serial, i.office_id, o.office, i.status, i.borrow_times, i.repair_times
        FROM items i
        INNER JOIN asset_type t ON i.asset_type_id = t.id
        INNER JOIN brand b ON i.brand_id = b.id
        INNER JOIN office o ON i.office_id = o.id
        WHERE i.model='$model'
        AND t.type='$type'
        AND b.brand='$brand'
        AND o.office='$office'
        AND i.status='Available'";
        $result=$con->select_by_query($sql);
    }

    ?>
        <section>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-10">
                            <h5 class="text-center mt-2">Edit Asset Info</h5>

                            <form method="post">
                                
                                <?php
                                    if($result->num_rows>0){
                                        while($row=$result->fetch_assoc()){
                                            ?>
                                            <div class="container" style ="padding-bottom: 50px">

                                                <label for="type_new<?php echo $row["id"]; ?>"><b>Type</b></label>
                                                <select name="type_new<?php echo $row["id"]; ?>" id="type_new<?php echo $row["id"]; ?>" class="form-control" required>
                                                    <?php
                                                        // Retrieve type data from the database
                                                        $sql_type = "SELECT id, type FROM asset_type";
                                                        $result_type = $con->select_by_query($sql_type);
                                                        if($result_type->num_rows > 0){
                                                            while($row_type = $result_type->fetch_assoc()){
                                                                ?>
                                                                <option value="<?php echo $row_type["id"]; ?>" <?php if($row["type"] == $row_type["type"]) echo "selected"; ?>><?php echo $row_type["type"]; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                    ?>
                                                </select><br>
                                                
                                                <label for="brand_new<?php echo $row["id"]; ?>"><b>Brand</b></label>
                                                <select name="brand_new<?php echo $row["id"]; ?>" id="brand_new<?php echo $row["id"]; ?>" class="form-control" required>
                                                    <?php
                                                        // Retrieve brand data from the database
                                                        $sql_brand = "SELECT id, brand FROM brand";
                                                        $result_brand = $con->select_by_query($sql_brand);
                                                        if($result_brand->num_rows > 0){
                                                            while($row_brand = $result_brand->fetch_assoc()){
                                                                ?>
                                                                <option value="<?php echo $row_brand["id"]; ?>" <?php if($row["brand"] == $row_brand["brand"]) echo "selected"; ?>><?php echo $row_brand["brand"]; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                    ?>
                                                </select><br>

                                                <label for="model_new<?php echo $row["id"]; ?>"><b>Model</b></label>
                                                <input type="text" name="model_new<?php echo $row["id"]; ?>" id="model_new<?php echo $row["id"]; ?>" class="form-control" value="<?php echo $row["model"] ?>" required><br>

                                                <label for="serial_new<?php echo $row["id"]; ?>"><b>Serial</b></label>
                                                <input type="text" name="serial_new<?php echo $row["id"]; ?>" id="serial_new<?php echo $row["id"]; ?>" class="form-control" value="<?php echo $row["serial"] ?>" required><br>

                                                <label for="borrow_new<?php echo $row["id"]; ?>"><b>Times Borrowed</b></label>
                                                <input type="number" name="borrow_new<?php echo $row["id"]; ?>" id="borrow_new<?php echo $row["id"]; ?>" class="form-control" value="<?php echo $row["borrow_times"]; ?>" required readonly><br>

                                                <label for="repair_new<?php echo $row["id"]; ?>"><b>Times Repaired</b></label>
                                                <input type="number" name="repair_new<?php echo $row["id"]; ?>" id="repair_new<?php echo $row["id"]; ?>" class="form-control" value="<?php echo $row["repair_times"]; ?>" required readonly><br>
                                            </div>
                                            <?php
                                        }
                                    }
                                ?>
                                <div class="container">
                                    <a href="office_inventory.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
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
