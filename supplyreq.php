<?php
session_start();
if(empty($_SESSION["username"])){
    header("location:login.php");
}
else {
    include("header.php");
    $office = $_SESSION["office_id"];

    $con=new connec();
    $sql="SELECT i.id, t.type, t.category, u.unit_name, t.date_expected, i.amount, i.price, i.date_update
    FROM items i
    JOIN supply_type t ON i.supply_type_id = t.id
    JOIN office o ON i.office_id = o.id
    JOIN unit u ON t.unit_id = u.id
    WHERE i.office_id = $office";
    $result=$con->select_by_query($sql);
    
    ?> 
 
    <head>
        <title>Supply Request</title>
    </head>
    <main>
        <div class="container-fluid px-4">
            <h2 class="mt-4">Supply Request</h2>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table " id="supplyreq" width="100%" cellspacing="0">
                            <thead class="table-blue">
                                <tr>
                                    <th><input type="checkbox" id="selectAll" /></th>
                                    <th>Supply Type</th>
                                    <th>Supply Category</th>
                                    <th>Unit of Issue</th>
                                    <th>Price</th>
                                    <th>Amount</th>
                                    <th>Last Updated</th>
                                    <th>Expected Delivery Date</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                <?php
                                    if($result->num_rows>0){
                                        while($row=$result->fetch_assoc()){
                                            $type_id = str_replace(' ', '_', $row["type"]); // Replace spaces with underscores
                                            ?>
                                            <tr>
                                                <td><input type="checkbox" class="checkbox" value="<?php echo $type_id; ?>" /></td>
                                                <td><?php echo $row["type"]; ?></td>
                                                <td><?php echo $row["category"]; ?></td>
                                                <td><?php echo $row["unit_name"]; ?></td>
                                                <td>₱ <?php echo $row["price"]; ?></td>
                                                <td><?php echo $row["amount"]; ?></td>
                                                <td><?php echo $row["date_update"]; ?></td>
                                                <td>
                                                    <?php echo date('Y-m-d', strtotime('+' . $row["date_expected"] . ' days')); ?>
                                                    <input type="hidden" id="date-expected-<?php echo $type_id; ?>" value="<?php echo $row["date_expected"]; ?>">
                                                </td>
                                                <td style="width: 150px;">
                                                    <input type="hidden" id="id-<?php echo $type_id; ?>" value="<?php echo $row["id"]; ?>">
                                                    <input type="hidden" id="quantity-<?php echo $type_id; ?>" value="<?php echo $row["amount"]; ?>">
                                                    <div class="input-group">
                                                        <input type="number" class="form-control form-control-sm" value="1" id="amount-<?php echo $type_id; ?>" data-max-quantity="<?php echo $row['amount'] ?>" max="<?php echo $row["amount"]; ?>">
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>
                                        <a class='btn btn-primary' id='supply-btn' href='#'>Request</a>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        <script>
                            new DataTable('#supplyreq', {
                                "paging": false,
                                "lengthChange": true,
                                "searching": true,
                                "ordering": true,
                                "info": false,
                                "autoWidth": false,
                                "columnDefs": [
                                    {
                                        "targets": 0, // specify the column index (0-based)
                                        "orderable": false // disable ordering for this column
                                    }
                                ]
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </main>
 
    <?php
    include("footer.php");
    }
    ?>