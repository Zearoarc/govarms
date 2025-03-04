<?php

include_once("../conn.php");

if (empty($_SESSION["username"])) {
  // If the current page is NOT the login page, redirect to the login page
  if ($current_page !== '../login.php') {
      header("Location: ../login.php");
      exit; // Ensure no further code is executed after the redirect
  }
}

if ($_SESSION['user_role'] == 'Office Supplier') {
  header('Location: ../office-s/office_index.php');
  exit;
} elseif ($_SESSION['user_role'] == 'Employee') {
  header('Location: ../index.php');
  exit;
} 
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>


  <link rel="icon" href="../images/logocity.webp">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
  <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/autofill/2.7.0/css/autoFill.dataTables.min.css">
  <script src="https://cdn.datatables.net/autofill/2.7.0/js/dataTables.autoFill.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.2/css/buttons.dataTables.min.css">
  <script src="https://cdn.datatables.net/buttons/3.2.2/js/dataTables.buttons.min.js"></script>
  <link rel="stylesheet" href="../styles.css">
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <nav class="govblue">
    <ul class="side">
      <li onclick=toggleNavSidebar()><a href="#"><i class='bx bx-x' style='color:#ffffff; font-size: 1.75rem'></i></a></li>
      <li><a href="admin_index.php">Asset Request and Management System</a></li>
      <li><a href="admin_index.php">Admin</a></li>
      <li><a href="../login.php?action=logout">Logout</a></li>
    </ul>
    <ul>
      <li><a href="admin_index.php"><img src="..//images/logocity.webp" alt="GovLogo" style="width:50px; margin:5px; margin-right:15px;"><span class="hideOnMobile">Asset Request and Management System</span></a></li>
      <li class="hideOnMobile"><a href="admin_index.php">Admin</a></li>
      <li class="hideOnMobile"><a href="../login.php?action=logout">Logout</a></li>
      <li class="menu-button" onclick=toggleNavSidebar()><a href="#"><i class='bx bx-menu' style='color:#ffffff; font-size: 1.75rem'></i></a></li>
    </ul>
  </nav>

  <?php include('admin_sidenavbar.php'); ?>