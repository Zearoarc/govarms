<?php
session_start();
if(empty($_SESSION["username"])){
    header("location:../login.php");
}
else {
    include("admin_header.php");

    $con=new connec();
    $sql="SELECT t.type, s.supplier, a.model, a.cost, a.status, o.office
    FROM assets a
    INNER JOIN asset_type t ON a.type_id = t.id
    INNER JOIN supplier s ON a.supplier_id = s.id
    INNER JOIN office o ON a.office_id = o.id
    WHERE a.status='Available'";
    $result=$con->select_by_query($sql);
    $assets = array();
    while ($row = $result->fetch_assoc()) {
        $model = $row["model"];
        $cost = $row["cost"];
        $type = $row["type"];
        $supplier = $row["supplier"];
        $status = $row["status"];
        $office = $row["office"];
        $key = $model . "_" . $type . "_" . $supplier . "_" . $office;
        if (!isset($assets[$key])) {
            $assets[$key] = array(
                "model" => $model,
                "cost" => $cost,
                "amount" => 1,
                "type" => $type,
                "supplier" => $supplier,
                "status" => $status,
                "office" => $office
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
                                            <th>Type</th>
                                            <th>Supplier</th>
                                            <th>Asset Model</th>
                                            <th>Office</th>
                                            <th>Cost</th>
                                            <th>Amount</th>
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
                                                <td><?php echo $asset["office"]; ?></td>
                                                <td>₱ <?php echo number_format($asset["cost"]); ?></td>
                                                <td><?php echo $asset["amount"]; ?></td>
                                                <td>
                                                <a class='btn btn-primary btn-sm' href='edit_assets.php?model=<?php echo $asset["model"]; ?>&type=<?php echo $asset["type"]; ?>&supplier=<?php echo $asset["supplier"]; ?>&office=<?php echo $asset["office"]; ?>'>Edit</a>
                                                <a class='btn btn-sm btn-danger' href='delete_assets.php?model=<?php echo $asset["model"]; ?>&type=<?php echo $asset["type"]; ?>&supplier=<?php echo $asset["supplier"]; ?>?>&office=<?php echo $asset["office"]; ?>'>Delete</a>
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