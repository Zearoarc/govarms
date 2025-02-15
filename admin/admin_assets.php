<?php
session_start();
if(empty($_SESSION["username"])){
    header("location:../login.php");
}
else {
    include("admin_header.php");

    $con=new connec();
    $tbl="assets";
    $result=$con->select_all($tbl);
    ?> 
 
    <head>
        <title>Assets</title>
        <link rel="stylesheet" href="../assets.css">
    </head>
    <main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Assets</h1>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                                    <a class="btn btn-primary" href="add_assets.php" role="button">Add</a>
                                </div>

                                <div class="card-body">

                                <div class="table-responsive">
 
                                <table class="table " id="dataAssetTable" width="100%" cellspacing="0">
                                    <thead class="table-blue">
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Supplier</th>
                                            <th scope="col">Asset Model</th>
                                            <th scope="col">Division</th>
                                            <th scope="col">Department</th>
                                            <th scope="col">Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if($result->num_rows>0){
                                                while($row=$result->fetch_assoc()){
                                                ?>
                                                <tr>
                                                    <td><?php echo $row["id"]; ?></td>
                                                    <td><?php echo $row["type"]; ?></td>
                                                    <td><?php echo $row["supplier"]; ?></td>
                                                    <td><?php echo $row["model"]; ?></td>
                                                    <td><?php echo $row["division"]; ?></td>
                                                    <td><?php echo $row["dept"]; ?></td>
                                                    <td><?php echo $row["status"]; ?></td>
                                                    <td>
                                                    <a class='btn btn-primary btn-sm' href='editasset.php?id=<?php echo $row["id"]; ?>'>Edit</a>
                                                    <a class='btn btn-sm btn-danger' href='deleteasset.php?id=<?php echo $row["id"]; ?>'>Delete</a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                                </div>
        </div>
                            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
                            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                            </main>
 
    <?php
    include("admin_footer.php");
    }
    ?>