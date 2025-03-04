<?php
session_start();
include('../log.php');
$name_edit="";
$email_edit="";
$office_edit="";
$contact_edit="";
$user_role_edit="";

if(isset($_POST["delete_clicked"]) && $_POST["delete_clicked"] == "1"){
    include("../conn.php");
    $id=$_GET['id'];
    $con=new connec();
    $sql="SELECT u.name, u.office_id
    FROM users u
    WHERE u.id='$id'";
    $result=$con->select_by_query($sql);
    $row=$result->fetch_assoc();
    $name=$row["name"];
    $office_id=$row["office_id"];

    $con->delete("users",$id);
    log_deleteuser($id, $office_id, $name);

    header("location:office_manage.php");
}

if(empty($_SESSION["username"])){
    header("location:office_index.php");
}

else{
    include("office_header.php");
    
    
    if(isset($_GET['id'])){
        $id=$_GET['id'];    

        $con=new connec();
        $sql="SELECT u.id, u.name, u.email, u.contact, u.date_add, u.user_role, o.office
        FROM users u
        INNER JOIN office o ON u.office_id = o.id
        WHERE u.id='$id'";
        $result=$con->select_by_query($sql);

        if($result->num_rows>0){
            $row=$result->fetch_assoc();
            $name_edit=$row["name"];
            $email_edit=$row["email"];
            $office_edit=$row["office"];
            $contact_edit=$row["contact"];
            $user_role_edit=$row["user_role"];
        }
    }

    ?>
        <section>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-10">
                            <h5 class="text-center mt-2">Delete Employee Info</h5>

                            <form method="post">
                                <div class="container">

                                    <label for="name_new"><b>Name</b></label>
						            <input type="text" name="name_new" id="name_new" class="form-control" value="<?php echo $name_edit ?>" readonly required><br>
                                    
                                    <label for="email_new"><b>Email</b></label>
						            <input type="email" name="email_new" id="email_new" class="form-control" value="<?php echo $email_edit ?>" readonly required><br>

                                    <!-- <label for="office_new"><b>Office</b></label> -->
                                    <input type="hidden" name="office_new" id="office_new" class="form-control" value="<?php echo $office_edit ?>" readonly required>

                                    <label for="contact_new"><b>Contact</b></label>
                                    <input type="text" name="contact_new" id="contact_new" class="form-control" value="<?php echo $contact_edit ?>" readonly required><br>

                                    <!-- <label for="user_role"><b>Role</b></label> -->
                                    <input type="hidden" name="user_role_new" id="user_role_new" class="form-control" value="<?php echo $user_role_edit ?>" readonly required>

                                    <input type="hidden" name="delete_clicked" value="0">
                                    <a href="office_manage.php" class="btn" name="btn_cancel" style="background-color:#3741c9; color:white">Cancel</a>
                                    <button type="submit" class="btn" name="btn_delete" style="background-color:#3741c9; color:white">Delete</button><br><br><br>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        </section>
        <script>
            $(document).ready(function() {
                $('button[name="btn_delete"]').on('click', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'You will not be able to recover this user data!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'No, cancel!',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('input[name="delete_clicked"]').val('1');
                            $('form').submit();
                        }
                    });
                });
            });
        </script>
        <?php
    include("office_footer.php");
}
?>
