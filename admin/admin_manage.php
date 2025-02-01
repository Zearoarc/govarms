<?php
session_start();
include("admin_header.php");
include("admin_sidenavbar.php");
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"> MANAGE EMPLOYEES
            </h6>
        </div>
       
    </div>
<div class="modal fade" id="addemployee" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<h1 class="modal-title fs-5" id="staticBackdropLabel">Employee Information</h1>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>
<form action="manage.php" method="POST">
<div class="form-group">
    <label>ID</label>
    <input type="text" name="id" class="form-control" placeholder="Enter ID"> 
</div>
<div class="form-group">
    <label>Name</label>
    <input type="text" name="name" class="form-control" placeholder="Enter Name"> 
</div>
<div class="form-group">
    <label>Contact</label>
    <input type="text" name="contact" class="form-control" placeholder="Enter Contact"> 
</div>
<div class="form-group">
    <label>Email</label>
    <input type="email" name="email" class="form-control" placeholder="Enter Email"> 
</div>
<div class="form-group">
    <label>Department</label>
    <input type="text" name="department" class="form-control" placeholder="Enter Department"> 
</div>
<div class="form-group">
    <label>Date Added</label>
    <input type="date" name="date" class="form-control" placeholder="Enter Date"> 
</div>

</div>
<div class="modalfooter">
    <button type="button" class="btn btn-secondary" data-dismiss="model">Close</button>
    <button type="submit" name="managebtn" class="btn btn-primary">Save</button>
</div>
</form>

</div>
</div>
</div>


<div class="container-fluid">

<div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"> EMPLOYEES
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addemployee"> Add</button>
            </h6>
        </div>
<div class="card-body">



    <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>





</div>
        
</div>

<?php
include("admin_footer.php");
?>