<?php
session_start();
include("office_header.php");
// Include the database connection file

// Create a connection to the database
$con = new connec();

$office_id = $_SESSION["office_id"];

// Query the logs table
$sql = "SELECT * FROM logs
WHERE office_id = $office_id
ORDER BY log_date DESC";
$result = $con->select_by_query($sql);

$sql_return="SELECT i.model, i.serial, f.repair, f.feedback, f.created_at
    FROM feedback f
    JOIN items i ON f.asset_id = i.id
    WHERE f.action = 'return'";
    $result_return=$con->select_by_query($sql_return);

// Display the logs
?>
    <head>
        <title>Logs</title>
    </head>
    <main>
        <div class="container-fluid px-4">
            <h2 class="mt-4">Logs</h2>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table " id="logs" width="100%" cellspacing="0">
                            <thead class="table-blue">
                                <tr>
                                    <th>Log Date</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                <?php
                                while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td style = "width:180px;"><?php echo $row["log_date"]; ?></td>
                                        <td><?php echo $row["notes"]; ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        <script>
                            new DataTable('#logs', {
                                "paging": true,
                                "lengthChange": true,
                                "searching": true,
                                "ordering": true,
                                "info": false,
                                "autoWidth": false,
                                "order": [[ 0, "desc" ]]
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
</main>
<?php
include("office_footer.php");
?>