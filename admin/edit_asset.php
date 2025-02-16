<?php
session_start();

if(isset($_POST["btn_update"])){
    include("../conn.php");
    $model=$_GET['model'];
    $type=$_GET['type'];
    $supplier=$_GET['supplier'];
    $dept=$_GET['dept'];
    $division=$_GET['division'];
    $con=new connec();
    $sql="SELECT a.id, t.type, s.supplier, a.model, a.serial, dept.department, d.division
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
    $result=$con->select_by_query($sql);

    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $id = $row["id"];
            $type = $_POST["type_new" . $id];
            $supplier = $_POST["supplier_new" . $id];
            $model = $_POST["model_new" . $id];
            $dept = $_POST["dept_new" . $id];
            $division = $_POST["division_new" . $id];
            $serial = $_POST["serial_new" . $id];

            $sql = "UPDATE assets SET type_id='$type', supplier_id='$supplier', model='$model', department_id='$dept', division_id='$division', serial='$serial' WHERE id='$id'";
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
    
    
    if(isset($_GET['model'], $_GET['type'], $_GET['supplier'], $_GET['dept'], $_GET['division'])){
        $model=$_GET['model'];
        $type=$_GET['type'];
        $supplier=$_GET['supplier'];
        $dept=$_GET['dept'];
        $division=$_GET['division'];

        $con=new connec();
        $sql="SELECT a.id, t.type, s.supplier, a.model, a.serial, dept.department, d.division
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

                                                <label for="dept_new<?php echo $row["id"]; ?>"><b>Department</b></label>
                                                <select name="dept_new<?php echo $row["id"]; ?>" id="dept_new<?php echo $row["id"]; ?>" class="form-control" data-row-id="<?php echo $row["id"]; ?>" required>
                                                    <?php
                                                        // Retrieve department data from the database
                                                        $sql_dept = "SELECT id, department FROM department";
                                                        $result_dept = $con->select_by_query($sql_dept);
                                                        if($result_dept->num_rows > 0){
                                                            while($row_dept = $result_dept->fetch_assoc()){
                                                                ?>
                                                                <option value="<?php echo $row_dept["id"]; ?>" <?php if($row["department"] == $row_dept["department"]) echo "selected"; ?>><?php echo $row_dept["department"]; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                    ?>
                                                </select><br>

                                                <label for="division_new<?php echo $row["id"]; ?>"><b>Division</b></label>
                                                <select name="division_new<?php echo $row["id"]; ?>" id="division_new<?php echo $row["id"]; ?>" class="form-control" required>
                                                    <option value="" disabled selected>Select Department First</option>
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
