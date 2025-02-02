<?php

include("../conn.php");
?>

<!doctype html>
<html lang="en">

<head>
  <title>Admin Dashboard</title>
  <!-- Required meta tags -->
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
  <link rel="stylesheet" href="../styles.css">
</head>

<body>
  <nav class="govblue">
    <ul class="side">
      <li onclick=toggleNavSidebar()><a href="#"><i class='bx bx-x' style='color:#ffffff; font-size: 1.75rem'></i></a></li>
      <li><a href="admin_header.php">Admin Asset Request and Reservation System</a></li>
      <li><a href="../login.php?action=logout">Logout</a></li>
    </ul>
    <ul>
      <li><a href="admin_header.php"><img src="..//images/logocity.webp" alt="GovLogo" style="width:50px; margin:5px; margin-right:15px;"><span class="hideOnMobile">Admin Asset Request and Reservation System</span></a></li>
      <li class="hideOnMobile"><a href="../login.php?action=logout">Logout</a></li>
      <li class="menu-button" onclick=toggleNavSidebar()><a href="#"><i class='bx bx-menu' style='color:#ffffff; font-size: 1.75rem'></i></a></li>
    </ul>
  </nav>

  <?php include('admin_sidenavbar.php'); ?>