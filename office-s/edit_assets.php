<?php
session_start();

if(isset($_POST["btn_update"])){
    include("../conn.php");
    $model=$_GET['model'];
    $type=$_GET['type'];
    $brand=$_GET['brand'];
    $office=$_GET['office'];
    $con=new connec();
    $sql="SELECT a.id, t.type, b.brand, a.model, a.serial, o.office
        FROM assets a
        INNER JOIN asset_type t ON a.type_id = t.id
        INNER JOIN brand b ON a.brand_id = b.id
        INNER JOIN office o ON a.office_id = o.id
        WHERE a.model='$model'
        AND t.type='$type'
        AND b.brand='$brand'
        AND o.office='$office'";
    $result=$con->select_by_query($sql);

    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $id = $row["id"];
            $type = $_POST["type_new" . $id];
            $brand = $_POST["brand_new" . $id];
            $model = $_POST["model_new" . $id];
            $office = $_POST["office_new" . $id];
            $serial = $_POST["serial_new" . $id];

            $sql = "UPDATE assets SET type_id='$type', brand_id='$brand', model='$model', office_id='$office', serial='$serial' WHERE id='$id'";
            $con->update($sql, "Data Updated Successfully");
        }
    }
    header("location:office_assets.php");
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
        $sql="SELECT a.id, t.type, b.brand, a.model, a.serial, a.office_id, o.office, a.status
        FROM assets a
        INNER JOIN asset_type t ON a.type_id = t.id
        INNER JOIN brand b ON a.brand_id = b.id
        INNER JOIN office o ON a.office_id = o.id
        WHERE a.model='$model'
        AND t.type='$type'
        AND b.brand='$brand'
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
                                                <input type="text" name="id" id="id" class="form-control" value="<?php echo $row["id"]; ?>" readonly><br>

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

                                                <label for="office_new<?php echo $row["id"]; ?>"><b>Office</b></label>
                                                <select name="office_new<?php echo $row["id"]; ?>" id="office_new<?php echo $row["id"]; ?>" class="form-control" required>
                                                <?php
                                                        // Retrieve office data from the database
                                                        $sql_office = "SELECT id, office FROM office";
                                                        $result_office = $con->select_by_query($sql_office);
                                                        if($result_office->num_rows > 0){
                                                            while($row_office = $result_office->fetch_assoc()){
                                                                ?>
                                                                <option value="<?php echo $row_office["id"]; ?>" <?php if($row["office"] == $row_office["office"]) echo "selected"; ?>><?php echo $row_office["office"]; ?></option>
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
                                    <a href="office_assets.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
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
