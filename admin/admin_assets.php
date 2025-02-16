<?php
session_start();
if(empty($_SESSION["username"])){
    header("location:../login.php");
}
else {
    include("admin_header.php");

    $con=new connec();
    $sql="SELECT t.type, s.supplier, a.model, a.serial, dept.department, d.division
    FROM assets a
    INNER JOIN asset_type t ON a.type_id = t.id
    INNER JOIN supplier s ON a.supplier_id = s.id
    INNER JOIN department dept ON a.department_id = dept.id
    INNER JOIN division d ON a.division_id = d.id";
    $result=$con->select_by_query($sql);
    $assets = array();
    while ($row = $result->fetch_assoc()) {
        $model = $row["model"];
        $type = $row["type"];
        $supplier = $row["supplier"];
        $dept = $row["department"];
        $division = $row["division"];
        $key = $model . "_" . $type . "_" . $supplier . "_" . $dept . "_" . $division;
        if (!isset($assets[$key])) {
            $assets[$key] = array(
                "model" => $model,
                "amount" => 1,
                "type" => $type,
                "supplier" => $supplier,
                "dept" => $dept,
                "division" => $division
            );
        } else {
            $assets[$key]["amount"]++;
        }
    }
    ?> 
 
    <head>
        <title>Assets</title>
        <link rel="stylesheet" href="../assets.css">
    </head>
    <main>
    <div class="container-fluid px-4">
        <h2 class="mt-4">Assets</h2>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                                    <a class="btn btn-primary" href="add_assets.php" role="button">Add</a>
                                </div>

                                <div class="card-body">

                                <div class="table-responsive">
 
                                <table class="table " id="dataAssetTable" width="100%" cellspacing="0">
                                    <thead class="table-blue">
                                        <tr>
                                            <th scope="col">Type</th>
                                            <th scope="col">Supplier</th>
                                            <th scope="col">Asset Model</th>
                                            <th scope="col">Department</th>
                                            <th scope="col">Division</th>
                                            <th scope="col">Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach ($assets as $asset){
                                            ?>
                                            <tr>
                                                <td><?php echo $asset["type"]; ?></td>
                                                <td><?php echo $asset["supplier"]; ?></td>
                                                <td><?php echo $asset["model"]; ?></td>
                                                <td><?php echo $asset["dept"]; ?></td>
                                                <td><?php echo $asset["division"]; ?></td>
                                                <td><?php echo $asset["amount"]; ?></td>
                                                <td>
                                                <a class='btn btn-primary btn-sm' href='edit_assets.php?model=<?php echo $asset["model"]; ?>&type=<?php echo $asset["type"]; ?>&supplier=<?php echo $asset["supplier"]; ?>&dept=<?php echo $asset["dept"]; ?>&division=<?php echo $asset["division"]; ?>'>Edit</a>
                                                <a class='btn btn-sm btn-danger' href='delete_assets.php?model=<?php echo $asset["model"]; ?>&type=<?php echo $asset["type"]; ?>&supplier=<?php echo $asset["supplier"]; ?>&dept=<?php echo $asset["dept"]; ?>&division=<?php echo $asset["division"]; ?>'>Delete</a>
                                                </td>
                                            </tr>
                                            <?php
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