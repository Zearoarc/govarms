<?php
session_start();
$connection = mysqli_connect("localhost","root","","arms_db");

if(isset($_POST['managebtn']))
{
    $id = $_POST['id'];
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $date = $_POST['date'];


        $query = "INSERT INTO manage (id,name,contact,email,department,date) VALUES ('$id','$name','$contact','$email','$department','$date')";
        $query_run = mysqli_query($connection, $query);
    
        if($query_run)
        {
            $_SESSION['success'] = "Employee Added";
            header('Location: admin_manage.php');
        }
        else
        {
            $_SESSION['status'] = "Employee Not Added";
            header('Location: admin_manage.php');
        }
 

    }
    
    
    ?>