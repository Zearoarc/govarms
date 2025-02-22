<?php
include("conn.php");
$con = new connec();

if (!isset($_SESSION)) {
    session_start();
}

// Redirect to Login
if (empty($_SESSION["username"])) {
    // Get the current script name
    $current_page = basename($_SERVER['PHP_SELF']);

    // If the current page is NOT the login page, redirect to the login page
    if ($current_page !== 'login.php') {
        header("Location: login.php");
        exit; // Ensure no further code is executed after the redirect
    }
}

// Login
if (isset($_POST["btn_login"])) {
    $email_id = $_POST["email_log"];
    $psw_log = $_POST["psw_log"];

    $result = $con->select_login("users", $email_id);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Check if the email and password match one of the items in the users database
        if ($row["email"] == $email_id && $row["password"] == $psw_log) {
            $_SESSION["username"] = $row["name"];
            $_SESSION["employee_id"] = $row["id"];
            $_SESSION["user_role"] = $row["user_role"];
            $_SESSION["office_id"] = $row["office_id"];

            error_log("User role: " . $_SESSION["user_role"]);

            $_SESSION["ul"] = '

            <nav class="govblue">
                <ul class="side">
                    <li onclick=toggleNavSidebar()><a href="#"><i class="bx bx-x" style="color:#ffffff; font-size: 1.75rem"></i></a></li>
                    <li><a href="index.php">Asset Request and Reservation System</a></li>
                    <li><a href="login.php?action=logout">Logout</a></li>
                </ul>
                <ul>
                    <li><a href="index.php"><img src="images/logocity.webp" alt="GovLogo" style="width:50px; margin:5px; margin-right:15px;"><span class="hideOnMobile">Asset Request and Reservation System</span></a></li>
                    <li class="hideOnMobile"><a href="login.php?action=logout">Logout</a></li>
                    <li class="menu-button" onclick=toggleNavSidebar()><a href="#"><i class="bx bx-menu" style="color:#ffffff; font-size: 1.75rem"></i></a></li>
                </ul>
            </nav>';
            if ($_SESSION["user_role"] === 'Admin') {
                header("Location: admin/admin_index.php");
            } else if ($_SESSION["user_role"] === 'Office Supplier') {
                header("Location: office-s/office_index.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            header("Location: login.php?error=Invalid credentials");
            exit();
        }
    } else {
        header("Location: login.php?error=Invalid credentials");
        exit();
    }
}

// Logout
if (isset($_GET["action"])) {
    if ($_GET["action"] == "logout") {
        $_SESSION["username"] = NULL;
        $_SESSION["employee_id"] = NULL;
        $_SESSION["user_role"] = NULL;
    }
}

// Change Navbar on Login
if (empty($_SESSION["username"])) {
    $_SESSION["ul"] = '';
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
    <?php if (!empty($_SESSION["username"])) { include("sidenavbar.php"); } ?>
    </ul>
    </div>
    </nav>