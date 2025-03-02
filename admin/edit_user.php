<?php
session_start();
if (isset($_POST["btn_update"])) {
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
        $sql="UPDATE users SET name='$name', email='$email', office_id='$office', contact='$contact', user_role='$user_role', password='$password' WHERE id='$id'";
        $con->update($sql, "Data Updated Successfully");
        header("location:admin_manageusers.php");
    }
}

if (empty($_SESSION["username"])) {
    header("location:../login.php");
}

else {
    include("admin_header.php");

    if(isset($_GET['id'])){
        $id=$_GET['id'];
        $office_id=$_SESSION['office_id'];
        error_log($_SESSION['office_id']);

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

                                    <label for="office_new<?php echo $row["id"]; ?>"><b>Office</b></label>
                                    <select name="office_new" id="office_new" class="form-control" required>
                                        <option value="" disabled selected>Select Type</option>
                                        <?php
                                            // Retrieve type data from the database
                                            $sql_office = "SELECT id, office FROM office";
                                            $result_office = $con->select_by_query($sql_office);
                                            if($result_office->num_rows > 0){
                                                while($row_office = $result_office->fetch_assoc()){
                                                    ?>
                                                    <option value="<?php echo $row_office["id"]; ?>" <?php if($row["office_id"] == $row_office["id"]) echo 'selected'; ?>><?php echo $row_office["office"]; ?></option>
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </select><br>

                                    <label for="contact_new"><b>Contact</b></label>
                                    <input type="text" name="contact_new" id="contact_new" class="form-control" value="<?php echo $contact_edit ?>" required><br>

                                    <label for="user_role"><b>Role</b></label>
                                    <input type="text" name="user_role_new" id="user_role_new" class="form-control" value="Office Supplier" readonly required><br>

                                    <a href="admin_manageusers.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
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
    include("admin_footer.php");
}
?>