<?php

session_start();
if(empty($_SESSION["username"])){
    header("location:../login.php");
}
else {
    include("admin_header.php");

    $con=new connec();
    $sql="SELECT u.name, u.email, u.office_id, o.office, u.contact, u.user_role
    FROM users u
    JOIN office o  ON u.office_id = o.id
    ORDER BY 
        CASE u.user_role
            WHEN 'Admin' THEN 1
            WHEN 'Office Supplier' THEN 2
            WHEN 'Employee' THEN 3
            ELSE 4
        END";
    $result=$con->select_by_query($sql);
    $offices = array();
    while($row = $result->fetch_assoc()) {
        $office_id = $row['office_id'];
        if(!isset($offices[$office_id])) {
            $offices[$office_id] = array(
                'office' => $row['office'],
                "office_data" => array()
            );
        }
        $offices[$office_id]["office_data"][] = array(
            "name" => $row['name'],
            "email" => $row['email'],
            "contact" => $row['contact'],
            "user_role" => $row['user_role']
        );
    }
    ?>
    <head>
        <title>Admin Dashboard</title>
    </head>
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Dashboard</h1>
            <?php
            foreach ($offices as $office_id => $office_data) {
                ?>
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <h4><?php echo $office_data["office"] ?> Users</h4>
                            <table class="display" id="office_users<?php echo $office_id; ?>" width="100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Contact</th>
                                        <th>User Role</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($office_data["office_data"] as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row["name"] ?></td>
                                            <td><?php echo $row["email"] ?></td>
                                            <td><?php echo $row["contact"] ?></td>
                                            <td><?php echo $row["user_role"] ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <script>
                                new DataTable('#office_users<?php echo $office_id; ?>', {
                                    "paging": false,
                                    "lengthChange": true,
                                    "searching": true,
                                    "ordering": true,
                                    "info": false,
                                    "autoWidth": false,
                                    "order": [[3, 'desc']]
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </main>
<?php
include("admin_footer.php");
}
?>