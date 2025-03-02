<?php
session_start();
include('../log.php');
$name_edit="";
$email_edit="";
$password_edit="";
$office_edit="";
$contact_edit="";
$user_role_edit="";

if(isset($_POST["btn_update"])){
    include("../conn.php");
    $name = $_POST["name_new"];
    $email = $_POST["email_new"];
    $password = $_POST["psw_new"];
    $confirm_password = $_POST["confirm_psw_new"];
    $office = $_POST["office_new"];
    $contact = $_POST["contact_new"];
    $user_role = $_POST["user_role_new"];
    $id=$_GET['id'];
    $con=new connec();

    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/", $password)) {
        $error = "Password must be at least 8 characters, contain at least one number, one uppercase and lowercase letter, and one special character";
    } elseif ($password != $confirm_password) {
        $error = "Passwords do not match";
    } else {
        $sql="SELECT u.name, u.email, u.office_id, u.contact, u.user_role, u.password
        FROM users u
        WHERE u.id='$id'";
        $result=$con->select_by_query($sql);
        $row=$result->fetch_assoc();
        $old_name=$row["name"];
        $old_email=$row["email"];
        $old_office=$row["office_id"];
        $old_contact=$row["contact"];
        $old_user_role=$row["user_role"];
        $old_password=$row["password"];

        $changes = array();
        if($old_name != $name) $changes[] = "Name: $old_name -> $name";
        if($old_email != $email) $changes[] = "Email: $old_email -> $email";
        if($old_office != $office) $changes[] = "Office: $old_office -> $office";
        if($old_contact != $contact) $changes[] = "Contact: $old_contact -> $contact";
        if($old_user_role != $user_role) $changes[] = "Role: $old_user_role -> $user_role";
        if($old_password != $password) $changes[] = "Password: Changed";

        $sql="UPDATE users SET name='$name', email='$email', office_id='$office', contact='$contact', user_role='$user_role', password='$password' WHERE id='$id'";
        $con->update($sql, "Data Updated Successfully");

        if(count($changes) > 0) {
            $changes_str = implode(", ", $changes);
            log_edituser($id, $office, $changes_str);
        }

        header("location:office_manage.php");
    }
}

if(empty($_SESSION["username"])){
    header("location:office_index.php");
}

else{
    include("office_header.php");
    
    
    if(isset($_GET['id'])){
        $id=$_GET['id'];

        $con=new connec();
        $sql="SELECT u.id, u.name, u.email, u.password, u.contact, u.date_add, u.user_role, u.office_id, o.office
        FROM users u
        INNER JOIN office o ON u.office_id = o.id
        WHERE u.id='$id'";
        $result=$con->select_by_query($sql);

        if($result->num_rows>0){
            $row=$result->fetch_assoc();
            $name_edit=$row["name"];
            $email_edit=$row["email"];
            $password_edit=$row["password"];
            $office_edit=$row["office"];
            $office_id_edit=$row["office_id"];
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
						            <input type="password" name="psw_new" id="psw_new" class="form-control" value="<?php echo $password_edit ?>" required><br>

                                    <label for="confirm_psw_new"><b>Confirm Password</b></label>
						            <input type="password" name="confirm_psw_new" id="confirm_psw_new" class="form-control" required><br>

                                    <label for="office_display"><b>Office</b></label>
                                    <input type="text" name="office_display" id="office_display" class="form-control" value="<?php echo $office_edit ?>" readonly required><br>
                                    <input type="hidden" name="office_new" id="office_new" class="form-control" value="<?php echo $office_id_edit ?>" readonly required>

                                    <label for="contact_new"><b>Contact</b></label>
                                    <input type="text" name="contact_new" id="contact_new" class="form-control" value="<?php echo $contact_edit ?>" required><br>

                                    <label for="user_role"><b>Role</b></label>
                                    <input type="text" name="user_role_new" id="user_role_new" class="form-control" value="Employee" readonly required><br>

                                    <a href="office_manage.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
                                    <button type="submit" class="btn" name="btn_update" style="background-color:#3741c9; color:white">Update</button><br><br><br>

                                    <?php if (isset($error)) { ?>
                                        <script>
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error',
                                                text: '<?php echo $error; ?>',
                                            });
                                        </script>
                                    <?php } ?>
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
