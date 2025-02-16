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
            <a href="admin_index.php">
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
            <a href="admin_assets.php">
                <i class='bx bx-desktop'></i>
                <span>Assets</span>
            </a>
        </li>
        <?php
        if (strpos($url, 'manage') !== false || strpos($url, 'user') !== false) {
            echo '<li class="active">';
        } else {
            echo '<li>';
        }
        ?>
            <a href="admin_manage.php">
                <i class='bx bxs-user'></i>
                <span>Manage</span>
            </a>
        </li>
        <?php
        if (strpos($url, 'req') !== false) {
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
                    <li><a href="admin_assetreq.php"
                    <?php
                    if (strpos($url, 'assetreq') !== false) {
                        echo '';
                    } else {
                        echo 'style="color: #ffffff"';
                    }
                    ?>
                    >Asset Requests</a></li>
                    <li><a href="admin_maintainancereq.php"
                    <?php
                    if (strpos($url, 'maintainancereq') !== false) {
                        echo '';
                    } else {
                        echo 'style="color: #ffffff"';
                    }
                    ?>
                    >Maintainance Requests</a></li>
                </div>
            </ul>
        </li>
        <?php
        if (strpos($url, 'admin_reports.php') !== false) {
            echo '<li class="active">';
        } else {
            echo '<li>';
        }
        ?>
            <a href="admin_reports.php">
                <i class='bx bxs-report'></i>
                <span>Generate Reports</span>
            </a>
        </li>
    </ul>
</aside>