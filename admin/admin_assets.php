<?php
include("admin_header.php");

    $username="root";
    $password="";
    $server_name="localhost";
    $db_name="arms_db";

    $conn = new mysqli($server_name, $username, $password, $db_name);
        if($conn->connect_error)
        {
            die("Connection Failed");}
    // Fetch id, type, asset_model, and department from database
    $sql = "SELECT * FROM assets";
    $result = $conn->query($sql);
?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"> ASSETS
            </h6>
        </div>
       
    </div>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assets</title>
    <link rel="stylesheet" href="../assets.css">

</head>
<body>
<div class="container-fluid">

<div class="card shadow mb-4">
        <div class="card-header py-3">

            
        </div>
    <table class="table table-bordered">
        <thead class="table-blue">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Type</th>
                <th scope="col">Supplier</th>
                <th scope="col">Asset Model</th>
                <th scope="col">Department</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $supplierLogo = "/images/{$row['supplier']}.jpg";
                        $status = "Available";
                        if($status == null){
                            $status = "Unavailable";
                        }
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['type']}</td>
                                <td>{$row['supplier']}</td>
                                <td>{$row['assetModel']}</td>
                                <td>{$row['department']}</td>
                                <td>{$status}</td>
                                <td>
    <button class ='btn btn-success'><a href='edit.php?updateid='..'' class='text-light'>Edit</a></button>
    <button class ='btn btn-danger'><a href='delete.php?deleteid='..'' class='text-light'>Cancel</a></button>
  </td>
                              </tr>";
                              
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>No records found</td></tr>";
                }
            ?>
        </tbody>
    </table>
    
</div>
<div class="dropdown">
        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" 
    data-bs-display="static" aria-expanded="false">
    Add
</button>

  <form class="dropdown-menu dropdown-menu-end p-4" style="position: absolute; z-index: 1050; width: 300px;">
    <div class="mb-3">
      <label for="type" class="form-label">Type</label>
      <input type="email" class="form-control" id="exampleDropdownFormEmail2" placeholder="email@example.com">
    </div>
    <div class="mb-3">
      <label for="exampleDropdownFormPassword2" class="form-label">Password</label>
      <input type="password" class="form-control" id="exampleDropdownFormPassword2" placeholder="Password">
    </div>
    <div class="mb-3">
      <div class="form-check">
        <input type="checkbox" class="form-check-input" id="dropdownCheck2">
        <label class="form-check-label" for="dropdownCheck2">
          Remember me
        </label>
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Sign in</button>
  </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
    include("admin_footer.php");
?>