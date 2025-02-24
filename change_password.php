<?php
session_start();
include("header.php");
$con = new connec();

if (empty($_SESSION["username"])) {
    header("location:login.php");
} else {
    $id = $_SESSION["employee_id"];

    if (isset($_POST["change_password"])) {
        $old_password = $_POST["old_password"];
        $new_password = $_POST["new_password"];
        $confirm_password = $_POST["confirm_password"];

        $sql = "SELECT password FROM users WHERE id = '$id'";
        $result = $con->select_by_query($sql);
        $row = $result->fetch_assoc();

        if (password_verify($old_password, $row["password"])) {
            if ($new_password == $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET password = '$hashed_password' WHERE id = '$id'";
                $con->update($sql, "Data Updated Successfully");

                echo "<script>alert('Password changed successfully!');</script>";
            } else {
                echo "<script>alert('New password and confirm password do not match!');</script>";
            }
        } else {
            echo "<script>alert('Old password is incorrect!');</script>";
        }
    }
}
?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-4">Change Password</h2>
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="" method="post">
                    <div class="form-group">
                        <label for="old_password">Old Password:</label>
                        <input type="password" class="form-control" id="old_password" name="old_password">
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password:</label>
                        <input type="password" class="form-control" id="new_password" name="new_password">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    </div>
                    <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php
include("footer.php");
?>