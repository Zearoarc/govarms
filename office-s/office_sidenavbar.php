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
        if (strpos($url, 'assets') !== false) {
            echo '<li class="active">';
        } else {
            echo '<li>';
        }
        ?>
            <a href="office_assets.php">
                <i class='bx bx-desktop'></i>
                <span>Assets</span>
            </a>
        </li>
        <?php
        if (strpos($url, 'supplies') !== false) {
            echo '<li class="active">';
        } else {
            echo '<li>';
        }
        ?>
            <a href="office_supplies.php">
                <i class='bx bx-cart' ></i>
                <span>Supplies</span>
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
                    <li><a href="office_assetreq.php"
                    <?php
                    if (strpos($url, 'assetreq') !== false) {
                        echo '';
                    } else {
                        echo 'style="color: #ffffff"';
                    }
                    ?>
                    >Asset Requests</a></li>
                    <li><a href="office_assetres.php"
                    <?php
                    if (strpos($url, 'assetres') !== false) {
                        echo '';
                    } else {
                        echo 'style="color: #ffffff"';
                    }
                    ?>
                    >Asset Reservations</a></li>
                    <li><a href="office_supplyreq.php"
                    <?php
                    if (strpos($url, 'supplyreq') !== false) {
                        echo '';
                    } else {
                        echo 'style="color: #ffffff"';
                    }
                    ?>
                    >Supply Requests</a></li>
                    <li><a href="office_maintenancereq.php"
                    <?php
                    if (strpos($url, 'maintenancereq') !== false) {
                        echo '';
                    } else {
                        echo 'style="color: #ffffff"';
                    }
                    ?>
                    >Maintenance</a></li>
                    <li><a href="office_completedreq.php"
                    <?php
                    if (strpos($url, 'completedreq') !== false) {
                        echo '';
                    } else {
                        echo 'style="color: #ffffff"';
                    }
                    ?>
                    >Completed</a></li>
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
                    <li><a href="admin_assettypes.php"
                    <?php
                    if (strpos($url, 'assettypes') !== false) {
                        echo '';
                    } else {
                        echo 'style="color: #ffffff"';
                    }
                    ?>
                    >Assets</a></li>
                    <li><a href="admin_supplytypes.php"
                    <?php
                    if (strpos($url, 'supplytypes') !== false) {
                        echo '';
                    } else {
                        echo 'style="color: #ffffff"';
                    }
                    ?>
                    >Supplies</a></li>
                    <li><a href="admin_brandtypes.php"
                    <?php
                    if (strpos($url, 'brandtypes') !== false) {
                        echo '';
                    } else {
                        echo 'style="color: #ffffff"';
                    }
                    ?>
                    >Brands</a></li>
                </div>
            </ul>
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