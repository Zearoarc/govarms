<?php
session_start();

$name_edit="";
$email_edit="";
$password_edit="";
$dept_edit="";
$division_edit="";
$contact_edit="";
$user_role_edit="";

if(isset($_POST["btn_delete"])){
    include("../conn.php");
    $id=$_GET['id'];
    $table="users";
    $con=new connec();

    $con->delete($table,$id);
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
        $tbl="users";
        $result=$con->select($tbl, $id);

        if($result->num_rows>0){
            $row=$result->fetch_assoc();
            $name_edit=$row["name"];
            $email_edit=$row["email"];
            $password_edit=password_hash($row["password"], PASSWORD_DEFAULT);
            $dept_edit=$row["dept"];
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
                            <h5 class="text-center mt-2">Delete User Info</h5>

                            <form method="post">
                                <div class="container">

                                    <label for="name_new"><b>Name</b></label>
						            <input type="text" name="name_new" id="name_new" class="form-control" value="<?php echo $name_edit ?>" readonly required><br>
                                    
                                    <label for="email_new"><b>Email</b></label>
						            <input type="email" name="email_new" id="email_new" class="form-control" value="<?php echo $email_edit ?>" readonly required><br>

                                    <label for="psw_new"><b>Password</b></label>
						            <input type="text" name="psw_new" id="psw_new" class="form-control" value="<?php echo $password_edit ?>" readonly required><br>

                                    <label for="dept_new"><b>Department</b></label>
						            <input type="text" name="dept_new" id="dept_new" class="form-control" value="<?php echo $dept_edit ?>" readonly required><br>

                                    <label for="division_new"><b>Division</b></label>
                                    <input type="text" name="division_new" id="division_new" class="form-control" value="<?php echo $division_edit ?>" readonly required><br>

                                    <label for="contact_new"><b>Contact</b></label>
                                    <input type="text" name="contact_new" id="contact_new" class="form-control" value="<?php echo $contact_edit ?>" readonly required><br>

                                    <label for="user_role"><b>Role</b></label>
                                    <input type="text" name="user_role_new" id="user_role_new" class="form-control" value="<?php echo $user_role_edit ?>" readonly required><br>


                                    <a href="admin_manage.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
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
