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
                    <li><a href="admin_manageusers.php"
                    <?php
                    if (strpos($url, 'user') !== false) {
                        echo '';
                    } else {
                        echo 'style="color: #ffffff"';
                    }
                    ?>
                    >Users</a></li>
                    <li><a href="admin_manageoffices.php"
                    <?php
                    if (strpos($url, 'offices') !== false) {
                        echo '';
                    } else {
                        echo 'style="color: #ffffff"';
                    }
                    ?>
                    >Offices</a></li>
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
                    if (strpos($url, 'suppplytypes') !== false) {
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
    </ul>
</aside>