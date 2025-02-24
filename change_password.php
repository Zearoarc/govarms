<?php
session_start();
include("header.php");

if (empty($_SESSION["username"])) {
    header("location:login.php");
} else {
    $id = $_SESSION["employee_id"];
    $con = new connec();
    $sql = "SELECT * FROM users WHERE id = '$id'";
    $result = $con->select_by_query($sql);
    $row = $result->fetch_assoc();
    ?>
    <main>
        <div class="container-fluid px-4">
            <h2 class="mt-4">Change Password</h2>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form action="change_password.php" method="post">
                        <div class="form-group">
                            <label for="current_password">Current Password:</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="form-group">
                            <label for="new_password">New Password:</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password:</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $current_password = $_POST["current_password"];
        $new_password = $_POST["new_password"];
        $confirm_password = $_POST["confirm_password"];
        $hashed_password = password_hash($row["password"], PASSWORD_DEFAULT);
        error_log($current_password);
        error_log($row["password"]);

        if ($current_password == $row["password"]) {
            if ($new_password == $confirm_password) {
                $sql = "UPDATE users SET password = '$new_password' WHERE id = '$id'";
                $con->update($sql, "Data Updated Successfully");
                echo "<script>alert('Password changed successfully!');</script>";
                echo "<script>window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('New password and confirm password do not match!');</script>";
            }
        } else {
            echo "<script>alert('Current password is incorrect!');</script>";
        }
    }
    ?>
    <?php
    include("footer.php");
}
?>