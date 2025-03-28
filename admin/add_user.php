<?php
session_start();
if (isset($_POST["btn_insert"])) {
    include("../conn.php");
    $name = $_POST["name_new"];
    $email = $_POST["email_new"];
    $password = $_POST["psw_new"];
    $confirm_password = $_POST["confirm_psw_new"];
    $office = $_POST["office_new"];
    $contact = $_POST["contact_new"];
    $user_role = $_POST["user_role_new"];
    $date_add = date("Y-m-d");
    $con = new connec();

    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/", $password)) {
        $error = "Password must be at least 8 characters, contain at least one number, one uppercase and lowercase letter";
    } elseif ($password != $confirm_password) {
        $error = "Passwords do not match";
    } else {
        $sql = "INSERT INTO users VALUES(0, '$name', '$email', '$password', '$office', '$contact', '$user_role', '$date_add')";
        $con->insert($sql, "Data Inserted Successfully");
        header("location:admin_manageusers.php");
    }
}

if (empty($_SESSION["username"])) {
    header("location:office_index.php");
}

else {
    include("admin_header.php");

    if(isset($_GET['id'])){
        $id=$_GET['id'];

        $con=new connec();
        $sql_user="SELECT u.id, u.office_id, o.office
        FROM users u
        INNER JOIN office o ON u.office_id = o.id
        WHERE u.id='$id'";
        $result_user=$con->select_by_query($sql_user);

        if($result_user->num_rows>0){
            $row_user=$result_user->fetch_assoc();
            $office_id=$row_user['office_id'];
            $office_name=$row_user['office'];
        }
    }
    ?>
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10">
                    <h5 class="text-center mt-2">Add New User</h5>

                    <form method="post">
                        <div class="container">

                            <label for="name_new"><b>Name</b></label>
                            <input type="text" name="name_new" id="name_new" class="form-control" required value="<?php echo (isset($_POST["name_new"])) ? $_POST["name_new"] : ""; ?>"><br>

                            <label for="email_new"><b>Email</b></label>
                            <input type="email" name="email_new" id="email_new" class="form-control" required value="<?php echo (isset($_POST["email_new"])) ? $_POST["email_new"] : ""; ?>"><br>

                            <label for="psw_new"><b>Password</b></label>
                            <input type="password" name="psw_new" id="psw_new" class="form-control" required><br>

                            <label for="confirm_psw_new"><b>Confirm Password</b></label>
                            <input type="password" name="confirm_psw_new" id="confirm_psw_new" class="form-control" required><br>

                            <label for="office_new"><b>Office</b></label>
                            <select name="office_new" id="office_new" class="form-control" required>
                                <option value="" disabled selected>Select Type</option>
                                <?php
                                    // Retrieve type data from the database
                                    $sql_office = "SELECT id, office FROM office";
                                    $result_office = $con->select_by_query($sql_office);
                                    if($result_office->num_rows > 0){
                                        while($row_office = $result_office->fetch_assoc()){
                                            ?>
                                            <option value="<?php echo $row_office["id"]; ?>"<?php echo (isset($_POST["office_new"]) && $_POST["office_new"] == $row_office["id"]) ? "selected" : ""; ?>><?php echo $row_office["office"]; ?></option>
                                            <?php
                                        }
                                    }
                                ?>
                            </select><br>

                            <label for="contact_new"><b>Contact</b></label>
                            <input type="text" name="contact_new" id="contact_new" class="form-control" required value="<?php echo (isset($_POST["contact_new"])) ? $_POST["contact_new"] : ""; ?>"><br>

                            <label for="user_role"><b>Role</b></label>
                            <select name="user_role_new" id="user_role_new" class="form-control" required>
                                <option value="" disabled selected>Select Type</option>
                                <option value="Office Supplier">Office Supplier</option>
                                <option value="Admin">Admin</option>
                            </select><br>

                            <script>
                                document.getElementById('user_role_new').addEventListener('change', function() {
                                    var officeSelect = document.getElementById('office_new');
                                    if (this.value === 'Admin') {
                                        officeSelect.value = 1;
                                        officeSelect.setAttribute('readonly', 'readonly');
                                        officeSelect.style.pointerEvents = 'none';
                                        officeSelect.style.opacity = '0.5';
                                    } else {
                                        officeSelect.removeAttribute('readonly');
                                        officeSelect.style.pointerEvents = 'auto';
                                        officeSelect.style.opacity = '1';
                                    }
                                });
                            </script>

                            <a href="admin_manageusers.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
                            <button type="submit" class="btn" name="btn_insert" style="background-color:#3741c9; color:white">Insert</button><br><br><br>

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