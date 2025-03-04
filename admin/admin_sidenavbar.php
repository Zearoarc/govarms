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
        if (strpos($url, 'manage') !== false || strpos($url, 'user') !== false || strpos($url, 'office') !== false) {
            echo '<li class="active">';
        } else {
            echo '<li>';
        }
        ?>
            <button onclick=toggleSubMenu(this) class="dropdown-btn">
                <i class='bx bxs-user'></i>
                <span>Manage</span>
                <i class='bx bx-chevron-down'></i>
            </button>
            <ul class="sub-menu">
                <div>
                    <li><a href="admin_manageusers.php"><span
                    <?php
                    if (strpos($url, 'user') !== false) {
                        echo '';
                    } else {
                        echo 'style="color: #ffffff; text-decoration: none;"';
                    }
                    ?>
                    >Users</span></a></li>
                    <li><a href="admin_manageoffices.php"><span
                    <?php
                    if (strpos($url, 'offices') !== false) {
                        echo '';
                    } else {
                        echo 'style="color: #ffffff; text-decoration: none;"';
                    }
                    ?>
                    >Offices</span></a></li>
                </div>
            </ul>
        </li>
    </ul>
</aside>