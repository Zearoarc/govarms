<?php
session_start();

$name_edit="";
$email_edit="";
$password_edit="";
$dept_edit="";
$division_edit="";
$contact_edit="";
$user_role_edit="";

if(isset($_POST["btn_update"])){
    include("../conn.php");
    $name=$_POST["name_new"];
    $email=$_POST["email_new"];
    $password=$_POST["psw_new"];
    $dept=$_POST["number_new"];
    $division=$_POST["division_new"];
    $contact=$_POST["contact_new"];
    $user_role=$_POST["user_role_new"];

    $id=$_GET['id'];
    $con=new connec();
    $sql="UPDATE users SET name='$name', email='$email', password='$password', dept='$dept', division='$division', contact='$contact', user_role='$user_role' WHERE id='$id'";
    $con->update($sql, "Data Updated Successfully");
    header("location:admin_manage.php");
}

if(empty($_SESSION["username"])){
    header("location:admin_index.php");
}

else{
    include("admin_header.php");
    
    
    if(isset($_GET['id'])){
        $id=$_GET['id'];

        $con=new connec();
        $sql="SELECT u.id, u.name, u.email, u.password, u.contact, u.date_add, u.user_role, dept.department, d.division
        FROM users u
        INNER JOIN department dept ON u.dept_id = dept.id
        INNER JOIN division d ON u.division_id = d.id
        WHERE u.id='$id'";
        $result=$con->select_by_query($sql);

        if($result->num_rows>0){
            $row=$result->fetch_assoc();
            $name_edit=$row["name"];
            $email_edit=$row["email"];
            if($_SESSION["username"] == $name_edit){
                $password_edit=$row["password"];
            } else {
                $password_edit=password_hash($row["password"], PASSWORD_DEFAULT);
            }
            $dept_edit=$row["department"];
            $division_edit=$row["division"];
            $contact_edit=$row["contact"];
            $user_role_edit=$row["user_role"];
        }
    }

    ?>
        <section>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-10">
                            <h5 class="text-center mt-2">Edit User Info</h5>

                            <form method="post">
                                <div class="container">

                                    <label for="name_new"><b>Name</b></label>
						            <input type="text" name="name_new" id="name_new" class="form-control" value="<?php echo $name_edit ?>"required><br>
                                    
                                    <label for="email_new"><b>Email</b></label>
						            <input type="email" name="email_new" id="email_new" class="form-control" value="<?php echo $email_edit ?>" required><br>

                                    <label for="psw_new"><b>Password</b></label>
						            <input type="text" name="psw_new" id="psw_new" class="form-control" value="<?php echo $password_edit ?>" required><br>

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

                                    <label for="contact_new"><b>Contact</b></label>
                                    <input type="text" name="contact_new" id="contact_new" class="form-control" value="<?php echo $contact_edit ?>" required><br>

                                    <label for="user_role"><b>Role</b></label>
                                    <select name="user_role" id="user_role" class="form-control" value="<?php echo $user_role_edit ?>" required>
                                        <option value="Admin">Admin</option>
                                        <option value="Dept Head">Dept Head</option>
                                        <option value="Division Head">Division Head</option>
                                        <option value="Employee">Employee</option>
                                    </select><br>


                                    <a href="admin_manage.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
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
