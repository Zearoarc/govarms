<?php
session_start();

if(isset($_POST["btn_update"])){
    include("../conn.php");
    $model=$_GET['model'];
    $type=$_GET['type'];
    $supplier=$_GET['supplier'];
    $office=$_GET['office'];
    $con=new connec();
    $sql="SELECT a.id, t.type, s.supplier, a.model, a.serial, o.office
        FROM assets a
        INNER JOIN asset_type t ON a.type_id = t.id
        INNER JOIN supplier s ON a.supplier_id = s.id
        INNER JOIN office o ON a.office_id = o.id
        WHERE a.model='$model'
        AND t.type='$type'
        AND s.supplier='$supplier'
        AND o.office='$office'";
    $result=$con->select_by_query($sql);

    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $id = $row["id"];
            $type = $_POST["type_new" . $id];
            $supplier = $_POST["supplier_new" . $id];
            $model = $_POST["model_new" . $id];
            $office = $_POST["office_new" . $id];
            $serial = $_POST["serial_new" . $id];

            $sql = "UPDATE assets SET type_id='$type', supplier_id='$supplier', model='$model', office_id='$office', serial='$serial' WHERE id='$id'";
            $con->update($sql, "Data Updated Successfully");
        }
    }
    header("location:admin_assets.php");
}

if(empty($_SESSION["username"])){
    header("location:admin_index.php");
}

else{
    include("admin_header.php");
    
    
    if(isset($_GET['model'], $_GET['type'], $_GET['supplier'], $_GET['office'])){
        $model=$_GET['model'];
        $type=$_GET['type'];
        $supplier=$_GET['supplier'];
        $office=$_GET['office'];

        $con=new connec();
        $sql="SELECT a.id, t.type, s.supplier, a.model, a.serial, o.office_id, o.office, a.status
        FROM assets a
        INNER JOIN asset_type t ON a.type_id = t.id
        INNER JOIN supplier s ON a.supplier_id = s.id
        INNER JOIN office o ON a.office_id = o.id
        WHERE a.model='$model'
        AND t.type='$type'
        AND s.supplier='$supplier'
        AND o.office='$office'
        AND a.status='Available'";
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
                                                <label for="id"><b>ID</b></label>
                                                <input type="text" name="id" id="id" class="form-control" value="<?php echo $row["id"]; ?>" readonly>

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
                                                
                                                <label for="supplier_new<?php echo $row["id"]; ?>"><b>Supplier</b></label>
                                                <select name="supplier_new<?php echo $row["id"]; ?>" id="supplier_new<?php echo $row["id"]; ?>" class="form-control" required>
                                                    <?php
                                                        // Retrieve supplier data from the database
                                                        $sql_supplier = "SELECT id, supplier FROM supplier";
                                                        $result_supplier = $con->select_by_query($sql_supplier);
                                                        if($result_supplier->num_rows > 0){
                                                            while($row_supplier = $result_supplier->fetch_assoc()){
                                                                ?>
                                                                <option value="<?php echo $row_supplier["id"]; ?>" <?php if($row["supplier"] == $row_supplier["supplier"]) echo "selected"; ?>><?php echo $row_supplier["supplier"]; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                    ?>
                                                </select><br>

                                                <label for="model_new<?php echo $row["id"]; ?>"><b>Model</b></label>
                                                <input type="text" name="model_new<?php echo $row["id"]; ?>" id="model_new<?php echo $row["id"]; ?>" class="form-control" value="<?php echo $row["model"] ?>" required><br>

                                                <label for="office_new<?php echo $row["id"]; ?>"><b>Office</b></label>
                                                <select name="office_new<?php echo $row["id"]; ?>" id="office_new<?php echo $row["id"]; ?>" class="form-control" required>
                                                <?php
                                                        // Retrieve office data from the database
                                                        $sql_office = "SELECT id, office FROM office";
                                                        $result_office = $con->select_by_query($sql_office);
                                                        if($result_office->num_rows > 0){
                                                            while($row_office = $result_office->fetch_assoc()){
                                                                ?>
                                                                <option value="<?php echo $row_officet["id"]; ?>" <?php if($row["office"] == $row_office["office"]) echo "selected"; ?>><?php echo $row_office["office"]; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                    ?>
                                                </select><br>

                                                <label for="serial_new<?php echo $row["id"]; ?>"><b>Serial</b></label>
                                                <input type="text" name="serial_new<?php echo $row["id"]; ?>" id="serial_new<?php echo $row["id"]; ?>" class="form-control" value="<?php echo $row["serial"] ?>" required><br>
                                            </div>
                                            <?php
                                        }
                                    }
                                ?>
                                <div class="container">
                                    <a href="admin_assets.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
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
