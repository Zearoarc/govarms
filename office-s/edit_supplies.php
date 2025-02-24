<?php
session_start();
if(isset($_POST["btn_update"])){
    include("../conn.php");
    $type = $_POST["type_new"];
    $quantity = $_POST["quantity_new"];
    $threshold = $_POST["threshold_new"];
    $office = $_POST["office_new"];
    $price = $_POST["price_new"];
    $date = date('Y-m-d H:i:s');
    // $req = $_POST["req_new"];
    // $res = $_POST["res_new"];

    $id=$_GET['id'];
    $con=new connec();
    $sql="UPDATE supplies SET type_id='$type', quantity='$quantity', threshold='$threshold', office_id='$office', price='$price' WHERE id='$id'";
    $con->update($sql, "Data Updated Successfully");
    header("location:office_supplies.php");
}

if(empty($_SESSION["username"])){
    header("location:office_index.php");
}

else{
    include("office_header.php");

    $office_id = $_SESSION["office_id"];

    if(isset($_GET['id'])){
        $id=$_GET['id'];

        $con=new connec();
        $sql="SELECT s.id, t.type, t.category, s.quantity, s.threshold, o.office, s.price
        FROM supplies s
        INNER JOIN supply_type t ON s.type_id = t.id
        INNER JOIN office o ON s.office_id = o.id
        WHERE s.id = $id";
        $result=$con->select_by_query($sql);
        $row=$result->fetch_assoc();
        
        }
    ?>
        <section>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-10">
                            <h5 class="text-center mt-2">Edit Supply Info</h5>

                            <form method="post">
                                <div class="container" style="color:#353133">

                                    <label for="type_new"><b>Type</b></label>
						            <select name="type_new" id="type_new" class="form-control" value="<?php echo $row["id"]; ?>" required>
                                        <option value="<?php echo $row["id"]; ?>" selected><?php echo $row["type"]; ?></option>
                                        <?php
                                            // Retrieve type data from the database
                                            $sql_type = "SELECT id, type FROM supply_type";
                                            $result_type = $con->select_by_query($sql_type);

                                            // Retrieve existing supply types from the supplies table
                                            $sql_supplies = "SELECT type_id FROM supplies WHERE office_id = '$office_id'";
                                            $result_supplies = $con->select_by_query($sql_supplies);
                                            $existing_types = array();
                                            while($row_supplies = $result_supplies->fetch_assoc()){
                                                $existing_types[] = $row_supplies["type_id"];
                                            }
                                            
                                            if($result_type->num_rows > 0){
                                                while($row_type = $result_type->fetch_assoc()){
                                                    if(!in_array($row_type["id"], $existing_types)){
                                                        ?>
                                                        <option value="<?php echo $row_type["id"]; ?>"><?php echo $row_type["type"]; ?></option>
                                                        <?php
                                                    }
                                                }
                                            }
                                        ?>
                                    </select><br>

                                    <label for="quantity_new"><b>Quantity</b></label>
                                    <input type="number" name="quantity_new" id="quantity_new" class="form-control" value="<?php echo $row["quantity"]; ?>" required><br>

                                    <label for="threshold_new"><b>Threshold</b></label>
                                    <input type="number" name="threshold_new" id="threshold_new" class="form-control" value="<?php echo $row["threshold"]; ?>" required><br>

                                    <label for="office_display"><b>Office</b></label>
                                    <input type="text" name="office_display" class="form-control" value="<?php echo $row["office"]; ?>" readonly required><br>
                                    <input type="hidden" name="office_new" id="office_new" value="<?php echo $office_id; ?>">

                                    <label for="price_new"><b>Price</b></label>
                                    <input type="number" name="price_new" id="price_new" class="form-control" value="<?php echo $row["price"]; ?>" required><br>

                                    <a href="office_supplies.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
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
