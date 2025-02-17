<?php
session_start();
if(isset($_POST["btn_insert"])){
    include("../conn.php");
    $type = $_POST["type_new"];
    $supplier = $_POST["supplier_new"];
    $model = $_POST["model_new"];
    $dept = $_POST["dept_new"];
    $division = $_POST["division_new"];
    $serial = $_POST["serial_new"];

    
    $con=new connec();
    $sql="INSERT INTO assets VALUES(0,'$type', '$supplier', '$model', '$dept', '$division', '$serial')";
    $con->insert($sql, "Data Inserted Successfully");
    header("location:admin_assets.php");
}

if(empty($_SESSION["username"])){
    header("location:admin_index.php");
}

else{
    include("admin_header.php");

    $con=new connec();
    ?>
        <section>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-10">
                            <h5 class="text-center mt-2">Add New Asset</h5>

                            <form method="post">
                                <div class="container" style="color:#353133">

                                    <label for="type_new"><b>Type</b></label>
						            <select name="type_new" id="type_new" class="form-control" required>
                                        <option value="" disabled selected>Select Type</option>
                                        <?php
                                            // Retrieve type data from the database
                                            $sql_type = "SELECT id, type FROM asset_type";
                                            $result_type = $con->select_by_query($sql_type);
                                            if($result_type->num_rows > 0){
                                                while($row_type = $result_type->fetch_assoc()){
                                                    ?>
                                                    <option value="<?php echo $row_type["id"]; ?>"><?php echo $row_type["type"]; ?></option>
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </select><br>

                                    <label for="supplier_new"><b>Supplier</b></label>
                                    <select name="supplier_new" id="supplier_new" class="form-control" required>
                                        <option value="" disabled selected>Select Supplier</option>
                                        <?php
                                            // Retrieve supplier data from the database
                                            $sql_supplier = "SELECT id, supplier FROM supplier";
                                            $result_supplier = $con->select_by_query($sql_supplier);
                                            if($result_supplier->num_rows > 0){
                                                while($row_supplier = $result_supplier->fetch_assoc()){
                                                    ?>
                                                    <option value="<?php echo $row_supplier["id"]; ?>"><?php echo $row_supplier["supplier"]; ?></option>
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </select><br>

                                    <label for="model_new"><b>Model</b></label>
                                    <input type="text" name="model_new" id="model_new" class="form-control" required><br>

                                    <label for="dept_new"><b>Department</b></label>
                                    <select name="dept_new" id="dept_new" class="form-control" required>
                                        <option value="" disabled selected>Select Department</option>
                                        <?php
                                            // Retrieve department data from the database
                                            $sql_dept = "SELECT id, department FROM department";
                                            $result_dept = $con->select_by_query($sql_dept);
                                            if($result_dept->num_rows > 0){
                                                while($row_dept = $result_dept->fetch_assoc()){
                                                    ?>
                                                    <option value="<?php echo $row_dept["id"]; ?>"><?php echo $row_dept["department"]; ?></option>
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </select><br>

                                    <label for="division_new"><b>Division</b></label>
                                    <select name="division_new" id="division_new" class="form-control" required>
                                    </select><br>

                                    <label for="serial_new"><b>Serial</b></label>
                                    <input type="text" name="serial_new" id="serial_new" class="form-control" required><br>

                                    <a href="admin_assets.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
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
