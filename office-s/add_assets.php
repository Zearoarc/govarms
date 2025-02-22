<?php
session_start();
if(isset($_POST["btn_insert"])){
    include("../conn.php");
    $type = $_POST["type_new"];
    $brand = $_POST["brand_new"];
    $model = $_POST["model_new"];
    $office = $_POST["office_new"];
    $serial = $_POST["serial_new"];
    $date = date('Y-m-d');
    $cost = $_POST["cost_new"];
    // $req = $_POST["req_new"];
    // $res = $_POST["res_new"];

    
    $con=new connec();
    $sql="INSERT INTO assets VALUES(0,'$type', '$brand', '$model', '$serial', '$office', '$date', '$cost', '$salvage', '$useful', '$repair', 0, 'Available')";
    $con->insert($sql, "Data Inserted Successfully");
    header("location:office_assets.php");
}

if(empty($_SESSION["username"])){
    header("location:office_index.php");
}

else{
    include("office_header.php");

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

                                    <label for="brand_new"><b>Brand</b></label>
                                    <select name="brand_new" id="brand_new" class="form-control" required>
                                        <option value="" disabled selected>Select Brand</option>
                                        <?php
                                            // Retrieve brand data from the database
                                            $sql_brand = "SELECT id, brand FROM brand";
                                            $result_brand = $con->select_by_query($sql_brand);
                                            if($result_brand->num_rows > 0){
                                                while($row_brand = $result_brand->fetch_assoc()){
                                                    ?>
                                                    <option value="<?php echo $row_brand["id"]; ?>"><?php echo $row_brand["brand"]; ?></option>
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </select><br>

                                    <label for="model_new"><b>Model</b></label>
                                    <input type="text" name="model_new" id="model_new" class="form-control" required><br>

                                    <label for="office_new"><b>Office</b></label>
                                    <select name="office_new" id="office_new" class="form-control" required>
                                    </select><br>

                                    <label for="serial_new"><b>Serial</b></label>
                                    <input type="text" name="serial_new" id="serial_new" class="form-control" required><br>

                                    <label for="cost_new"><b>Unit Cost</b></label>
                                    <input type="text" name="cost_new" id="cost_new" class="form-control" required><br>

                                    <a href="office_assets.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
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
