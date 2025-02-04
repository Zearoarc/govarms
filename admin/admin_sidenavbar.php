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
                <i class='bx bxs-dashboard' style='color:#ffffff'></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="admin_assets.php">
                <i class='bx bx-desktop' style='color:#ffffff'></i>
                <span>Assets</span>
            </a>
        </li>
        <li>
            <a href="admin_manage.php">
                <i class='bx bxs-user' style='color:#ffffff'></i>
                <span>Manage</span>
            </a>
        </li>
        <li>
            <button onclick=toggleSubMenu(this) class="dropdown-btn">
                <i class='bx bx-user-voice' style='color:#ffffff'></i>
                <span>Requests</span>
                <i class='bx bx-chevron-down' style='color:#ffffff'></i>
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