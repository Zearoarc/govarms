<aside id="sidebar" class="sidebar">
    <ul>
        <li>
            <button onclick=toggleSidebar() id="toggle-btn">
                <i class='bx bx-chevrons-left' style='color:#ffffff'></i>
            </button>
        </li>

        <!-- Active Page Check -->

        <?php
        // Change to Server Name on Deployment
        $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        if (strpos($url, 'index') !== false) {
            echo '<li class="active">';
        } else {
            echo '<li>';
        }
        ?>
            <a href="office_index.php">
                <i class='bx bxs-dashboard'></i>
                <span>Dashboard</span>
            </a>
        </li>
        <?php
        if (strpos($url, 'inventory') !== false) {
            echo '<li class="active">';
        } else {
            echo '<li>';
        }
        ?>
            <a href="office_inventory.php">
                <i class='bx bx-cart' ></i>
                <span>Inventory</span>
            </a>
        </li>
        <?php
        if (strpos($url, 'manage') !== false || strpos($url, 'user') !== false) {
            echo '<li class="active">';
        } else {
            echo '<li>';
        }
        ?>
            <a href="office_manage.php">
                <i class='bx bx-user'></i>
                <span>Manage</span>
            </a>
        </li>
        <?php
        if (strpos($url, 'req') !== false || strpos($url, 'res') !== false) {
            echo '<li class="active">';
        } else {
            echo '<li>';
        }
        ?>
            <button onclick=toggleSubMenu(this) class="dropdown-btn">
                <i class='bx bx-user-voice'></i>
                <span>Requests</span>
                <i class='bx bx-chevron-down'></i>
            </button>
            <ul class="sub-menu">
                <div>
                    <li><a href="office_assetreq.php"><span
                    <?php
                    if (strpos($url, 'assetreq') !== false) {
                        echo '';
                    } else {
                        echo 'style="color: #ffffff; text-decoration: none;"';
                    }
                    ?>
                    >Asset Requests</span></a></li>
                    <li><a href="office_assetres.php"><span
                    <?php
                    if (strpos($url, 'assetres') !== false) {
                        echo '';
                    } else {
                        echo 'style="color: #ffffff; text-decoration: none;"';
                    }
                    ?>
                    >Asset Reservations</span></a></li>
                    <li><a href="office_supplyreq.php"><span
                    <?php
                    if (strpos($url, 'supplyreq') !== false) {
                        echo '';
                    } else {
                        echo 'style="color: #ffffff; text-decoration: none;"';
                    }
                    ?>
                    >Supply Requests</span></a></li>
                    <li><a href="office_maintenancereq.php"><span
                    <?php
                    if (strpos($url, 'maintenancereq') !== false) {
                        echo '';
                    } else {
                        echo 'style="color: #ffffff; text-decoration: none;"';
                    }
                    ?>
                    >Maintenance</span></a></li>
                    <li><a href="office_completedreq.php"><span
                    <?php
                    if (strpos($url, 'completedreq') !== false) {
                        echo '';
                    } else {
                        echo 'style="color: #ffffff; text-decoration: none;"';
                    }
                    ?>
                    >Completed</span></a></li>
                </div>
            </ul>
        </li>
        <?php
        if (strpos($url, 'types') !== false) {
            echo '<li class="active">';
        } else {
            echo '<li>';
        }
        ?>
            <button onclick=toggleSubMenu(this) class="dropdown-btn">
                <i class='bx bx-purchase-tag' ></i>
                <span>Types</span>
                <i class='bx bx-chevron-down'></i>
            </button>
            <ul class="sub-menu">
                <div>
                    <li><a href="office_assettypes.php"><span
                    <?php
                    if (strpos($url, 'assettypes') !== false) {
                        echo '';
                    } else {
                        echo 'style="color: #ffffff; text-decoration: none;"';
                    }
                    ?>
                    >Assets</span></a></li>
                    <li><a href="office_supplytypes.php"><span
                    <?php
                    if (strpos($url, 'supplytypes') !== false) {
                        echo '';
                    } else {
                        echo 'style="color: #ffffff; text-decoration: none;"';
                    }
                    ?>
                    >Supplies</span></a></li>
                    <li><a href="office_brandtypes.php"><span
                    <?php
                    if (strpos($url, 'brandtypes') !== false) {
                        echo '';
                    } else {
                        echo 'style="color: #ffffff; text-decoration: none;"';
                    }
                    ?>
                    >Brands</span></a></li>
                </div>
            </ul>
        </li>
        <?php
        if (strpos($url, 'office_logs.php') !== false) {
            echo '<li class="active">';
        } else {
            echo '<li>';
        }
        ?>
            <a href="office_logs.php">
                <i class='bx bx-file' ></i>
                <span>View Logs</span>
            </a>
        </li>
        <?php
        if (strpos($url, 'office_reports.php') !== false) {
            echo '<li class="active">';
        } else {
            echo '<li>';
        }
        ?>
            <a href="office_reports.php">
                <i class='bx bxs-report'></i>
                <span>Generate Reports</span>
            </a>
        </li>
    </ul>
</aside>