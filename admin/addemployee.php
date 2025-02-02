<?php
session_start();
include("admin_header.php");


$servername = "localhost";
$username = "root";
$password = "";
$database = "arms_db";

$connection = new mysqli($servername, $username, $password, $database);


$id = "";
$name = "";
$contact = "";
$email = "";
$department = "";
$date = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $date = $_POST['date'];

    do {
        if (empty($id) || empty($name) || empty($contact) || empty($email) || empty($department) || empty($date)) {
            $errorMessage = "All the fields are required";
            break;
        }

        $sql = "INSERT INTO manage (id, name, contact, email, department, date)" .
            "VALUES ('$id', '$name', '$contact', '$email', '$department', '$date')";
        $result = $connection->query($sql);

        if (!$result) {
            $errorMessage = "Error: " . $connection->error;
            break;
        }

        $id = "";
        $name = "";
        $contact = "";
        $email = "";
        $department = "";
        $date = "";

        $successMessage = "Employee added";


        header("Location: admin_manage.php");
        exit;
    } while (false);
}


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container my-5">
        <h2>New Employee</h2>

        <?php
        if (!empty($errorMessage)) {
            echo "<div class='alert alert-warning alert-dismissible fade show role='alert'>
            <strong>$errorMessage</strong>
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            
            </div>";
        }
        ?>
        <form method="post">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">ID</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="id" value="<?php echo $id; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Name</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Contact</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="contact" value="<?php echo $contact; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="email" value="<?php echo $email; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Department</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="department" value="<?php echo $department; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Date</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="date" value="<?php echo $date; ?>">
                </div>
            </div>

            <?php
            if (!empty($successMessage)) {
                echo "
                <div class='row mb-3'>
                <div class='offeset-sm-3 col-sm-6'>
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                <strong>$successMessage</strong>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'</button>
                </div>
                </div>
                </div>";
            }

            ?>

            <div class="row mb-3">
                <div class="offset-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary" href="admin_manage.php">Add</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="admin_manage.php" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>

</body>

</html>

<?php
include("admin_footer.php");

?>