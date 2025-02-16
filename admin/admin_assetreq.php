<?php
session_start();
if(empty($_SESSION["username"])){
    header("location:../login.php");
}
else {
    include("admin_header.php");

    $con=new connec();
    $sql="SELECT r.req_type, r.order_id, u.name, a.model, a.serial, s.supplier, d.department, dv.division
    FROM req r
    JOIN users u ON r.user_id = u.id
    JOIN assets a ON r.asset_id = a.id
    JOIN supplier s ON a.supplier_id = s.id
    JOIN department d ON a.department_id = d.id
    JOIN division dv ON a.division_id = dv.id
    WHERE req_type='Asset';";
    $result=$con->select_by_query($sql);

    // Group orders by order ID
    $orders = array();
    while ($row = $result->fetch_assoc()) {
        $order_id = $row["order_id"];
        if (!isset($orders[$order_id])) {
            $orders[$order_id] = array(
                "user_name" => $row["name"],
                "order_data" => array()
            );
        }
        $orders[$order_id]["order_data"][] = array(
            "req_type" => $row["req_type"],
            "model" => $row["model"],
            "supplier" => $row["supplier"],
            "serial" => $row["serial"],
            "department" => $row["department"],
            "division" => $row["division"]
        );
    }
    ?>
    <head>
        <title>Asset Request</title>
    </head>
    <main>
        <div class="container-fluid px-4">
            <h2 class="mt-4">Asset Requests</h2>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <?php
                        foreach ($orders as $order_id => $order_data) {
                            ?>
                            <h4>Order ID: <?php echo $order_id; ?> (<?php echo $order_data["user_name"]; ?>)</h4>
                            <table class="table " id="dataAssetTable" width="100%" cellspacing="0">
                                <thead class="table-blue">
                                    <tr>
                                        <th>Supplier</th>
                                        <th>Asset Model</th>
                                        <th>Asset Serial</th>
                                        <th>Department</th>
                                        <th>Division</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($order_data["order_data"] as $row) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row["supplier"]; ?></td>
                                        <td><?php echo $row["model"]; ?></td>
                                        <td><?php echo $row["serial"]; ?></td>
                                        <td><?php echo $row["department"]; ?></td>
                                        <td><?php echo $row["division"]; ?></td>
                                        <td>
                                            <a class='btn btn-primary btn-sm' href='edit_assets.php?model=<?php echo $asset["model"]; ?>&type=<?php echo $asset["type"]; ?>&supplier=<?php echo $asset["supplier"]; ?>&dept=<?php echo $asset["dept"]; ?>&division=<?php echo $asset["division"]; ?>'>Edit</a>
                                            <a class='btn btn-sm btn-danger' href='delete_assets.php?model=<?php echo $asset["model"]; ?>&type=<?php echo $asset["type"]; ?>&supplier=<?php echo $asset["supplier"]; ?>&dept=<?php echo $asset["dept"]; ?>&division=<?php echo $asset["division"]; ?>'>Delete</a>
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
        </div>
    </main>
</body>
</html>


<?php
include("admin_footer.php");
}
?>