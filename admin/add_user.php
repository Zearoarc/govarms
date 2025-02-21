<?php
session_start();
if (isset($_POST["btn_insert"])) {
    include("../conn.php");
    $name = $_POST["name_new"];
    $email = $_POST["email_new"];
    $password = $_POST["psw_new"];
    $office = $_POST["office_new"];
    $contact = $_POST["contact_new"];
    $user_role = $_POST["user_role_new"];

    $con = new connec();
    $sql = "INSERT INTO users VALUES(0, '$name', '$email', '$password', '$office', '$contact', '$user_role')";
    $con->insert($sql, "Data Inserted Successfully");
    header("location:admin_manage.php");
}

if (empty($_SESSION["username"])) {
    header("location:admin_index.php");
}

else {
    include("admin_header.php");

    $con=new connec();
    ?>
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10">
                    <h5 class="text-center mt-2">Add New User</h5>

                    <form method="post">
                        <div class="container">

                            <label for="name_new"><b>Name</b></label>
                            <input type="text" name="name_new" id="name_new" class="form-control" required><br>

                            <label for="email_new"><b>Email</b></label>
                            <input type="email" name="email_new" id="email_new" class="form-control" required><br>

                            <label for="psw_new"><b>Password</b></label>
                            <input type="password" name="psw_new" id="psw_new" class="form-control" required><br>

                            <label for="office_new"><b>Office</b></label>
                            <select name="office_new" id="office_new" class="form-control" required>
                                <option value="" disabled selected>Select Department First</option>
                            </select><br>

                            <label for="contact_new"><b>Contact</b></label>
                            <input type="text" name="contact_new" id="contact_new" class="form-control" required><br>

                            <label for="user_role"><b>Role</b></label>
                            <select name="user_role_new" id="user_role_new" class="form-control" required>
                                <option value="Admin">Admin</option>
                                <option value="Office">Office</option>
                                <option value="Employee">Employee</option>
                            </select><br>

                            <a href="admin_manage.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
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