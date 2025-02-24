<?php
session_start();
if(empty($_SESSION["username"])){
    header("location:../login.php");
}
else {
    include("office_header.php");
    $office = $_SESSION["office_id"];

    $con=new connec();
    $sql="SELECT s.id, t.type, t.category, s.quantity, o.office, s.price
    FROM supplies s
    INNER JOIN supply_type t ON s.type_id = t.id
    INNER JOIN office o ON s.office_id = o.id
    WHERE s.office_id = $office";
    $result=$con->select_by_query($sql);

    ?> 
 
    <head>
        <title>Suplies</title>
    </head>
    <main>
    <div class="container-fluid px-4">
        <h2 class="mt-4">Supplies</h2>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <a class="btn btn-primary" href="add_supplies.php?id=<?php echo $_SESSION["employee_id"]; ?>" role="button">Add</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table " id="dataAssetTable" width="100%" cellspacing="0">
                        <thead class="table-blue">
                            <tr>
                                <th>Supply Type</th>
                                <th>Supply Category</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                                    if($result->num_rows>0){
                                        while($row=$result->fetch_assoc()){
                                            ?>
                                            <tr>
                                                <td><?php echo $row["type"]; ?></td>
                                                <td><?php echo $row["category"]; ?></td>
                                                <td>₱ <?php echo number_format($row["price"]); ?></td>
                                                <td><?php echo $row["quantity"]; ?></td>
                                                <td style="width: 150px;">
                                                <a class='btn btn-primary btn-sm' href='edit_supplies.php?id=<?php echo $row["id"]; ?>'>Edit</a>
                                                <a class='btn btn-sm btn-danger' href='delete_supplies.php?id=<?php echo $row["id"]; ?>'>Delete</a>
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
    include("office_footer.php");
    }
    ?>