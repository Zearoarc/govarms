<?php
session_start();
if(empty($_SESSION["username"])){
    header("location:login.php");
}
else {
    include("header.php");

    $con=new connec();
    $sql="SELECT a.id, a.type, a.category, u.unit_name, u.description, a.date_expected
    FROM asset_type a
    JOIN unit u ON a.unit_id = u.id";
    $result=$con->select_by_query($sql);
    
    ?> 
 
    <head>
        <title>Asset Request</title>
    </head>
    <main>
        <div class="container-fluid px-4">
            <h2 class="mt-4">Asset Request</h2>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table " id="assetreq" width="100%" cellspacing="0">
                            <thead class="table-blue">
                                <tr>
                                    <th><input type="checkbox" id="selectAll" /></th>
                                    <th>Asset Type</th>
                                    <th>Asset Category</th>
                                    <th>Unit of Issue</th>
                                    <th>Expected Delivery Date</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                <?php
                                    if($result->num_rows>0){
                                        while($row=$result->fetch_assoc()){
                                            ?>
                                            <tr>
                                                <td><input type="checkbox" class="checkbox" value="<?php echo $row["type"]; ?>" /></td>
                                                <td><?php echo $row["type"]; ?></td>
                                                <td><?php echo $row["category"]; ?></td>
                                                <td><?php echo $row["unit_name"]; ?></td>
                                                <td>
                                                    <?php echo date('Y-m-d', strtotime('+' . $row["date_expected"] . ' days')); ?>
                                                    <input type="hidden" id="date-expected-<?php echo $row["type"]; ?>" value="<?php echo $row["date_expected"]; ?>">
                                                </td>
                                                <td style="width: 150px;">
                                                    <input type="hidden" id="id-<?php echo $row["type"]; ?>" value="<?php echo $row["id"]; ?>">
                                                    <div class="input-group">
                                                        <input type="number" class="form-control form-control-sm" value="1" id="amount-<?php echo $row["type"]; ?>" data-max-quantity="3" max="3">
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
                                        <a class='btn btn-primary' id='request-btn' href='#'>Request</a>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        <script>
                            new DataTable('#assetreq', {
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