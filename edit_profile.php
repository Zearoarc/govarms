<?php
session_start();
include("header.php");
$con = new connec();

if (empty($_SESSION["username"])) {
    header("location:login.php");
} else {
    $id = $_SESSION["employee_id"];
    $sql = "SELECT * FROM users WHERE id = '$id'";
    $result = $con->select_by_query($sql);
    $row = $result->fetch_assoc();

    if (isset($_POST["update"])) {
        $name = $_POST["name"];
        $email = $_POST["email"];
        $contact = $_POST["contact"];

        $sql = "UPDATE users SET name = '$name', email = '$email', contact = '$contact' WHERE id = '$id'";
        $con->update($sql, "Data Updated Successfully");

        header("location:user_profile.php");
    }
}
?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-4">Edit Profile</h2>
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="" method="post">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $row["name"]; ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $row["email"]; ?>">
                    </div>
                    <div class="form-group">
                        <label for="contact">Contact:</label>
                        <input type="text" class="form-control" id="contact" name="contact" value="<?php echo $row["contact"]; ?>">
                    </div>
                    <button type="submit" name="update" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php
include("footer.php");
?>