<?php
include("conn.php");
$con=new connec();

if(!isset($_SESSION)){
    session_start();
}

// Redirect to Login
if(empty($_SESSION["username"])){
    // Get the current script name
    $current_page = basename($_SERVER['PHP_SELF']);

    // If the current page is NOT the login page, redirect to the login page
    if ($current_page !== 'login.php') {
        header("Location: login.php");
        exit; // Ensure no further code is executed after the redirect
    }
}

// Login
if(isset($_POST["btn_login"])){
    $email_id=$_POST["email_log"];
    $psw_log=$_POST["psw_log"];

    $result=$con->select_login("users",$email_id);
    if($result->num_rows>0){
        $row=$result->fetch_assoc();
        
        // Check if the email and password match one of the items in the users database
        if($row["email"]==$email_id && $row["password"]==$psw_log){
            $_SESSION["username"]=$row["name"];
            $_SESSION["employee_id"]=$row["id"];
            $_SESSION["user_role"]=$row["role"];

            error_log("User role: " . $_SESSION["user_role"]);

            $_SESSION["ul"]='

            <nav class="navbar navbar-expand-md navbar-dark govblue">
                <a class="navbar-brand" href="index.php"><img src="images/logocity.webp" style="width:50px;"/></a>
                <a class="navbar-brand" href="index.php">Asset Request and Management System</a>
                <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#collapsibleNavId" aria-controls="collapsibleNavId"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="collapsibleNavId">
                    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    </ul>

                    <ul class="navbar-nav">
                        <li class="nav-item"> <a class="nav-link" href="edit_profile.php"> '. $_SESSION["username"].'</a></li><li class="nav-item"><a class="nav-link" href="login.php?action=logout">Logout</a></li>';
            if($_SESSION["user_role"]==='admin'){
                header("Location: admin/admin_index.php");
            }
            else {
                header("Location: index.php");
            }
            exit();
        }
        else{
            header("Location: login.php?error=Invalid credentials");
            exit();
        }
    }
    else{
        header("Location: login.php?error=Invalid credentials");
        exit();
    }
}

// Logout
if(isset($_GET["action"])){
    if($_GET["action"]== "logout"){
        $_SESSION["username"]=NULL;
        $_SESSION["employee_id"]=NULL;
        $_SESSION["user_role"]=NULL;
    }
}

// Change Navbar on Login
if(empty($_SESSION["username"])){
    $_SESSION["ul"]='';
}

?> 



<!doctype html>
<html lang="en">
<head>
    <title>Asset Request and Management System</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    <link rel="icon" href="images/logocity.webp">
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" 
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
                <?php echo $_SESSION["ul"]; ?>
            </ul>
        </div>
    </nav>