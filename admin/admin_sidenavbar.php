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
        if (strpos($url, 'manage') !== false) {
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
        <li >
            <button onclick=toggleSubMenu(this) class="dropdown-btn 
            <?php
            if (strpos($url, 'req') !== false) {
                echo 'active"';
            } else {
                echo '';
            }
            ?>
            ">
                <i class='bx bx-user-voice'></i>
                <span>Requests</span>
                <i class='bx bx-chevron-down'></i>
            </button>
            <ul class="sub-menu">
                <div>
                    <li><a href="admin_assetreq.php">Asset Requests</a></li>
                    <li><a href="admin_maintainancereq.php">Maintainance Requests</a></li>
                    <li><a href="admin_disposalreq.php">Disposal Requests</a></li>
                </div>
            </ul>
        </li>
        <li>
            <a href="admin_reports.php">
                <i class='bx bxs-report' style='color:#ffffff'></i>
                <span>Generate Reports</span>
            </a>
        </li>
    </ul>
</aside>