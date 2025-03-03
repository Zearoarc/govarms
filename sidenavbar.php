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
            <a href="index.php">
                <i class='bx bxs-user'></i>
                <span>Profile</span>
            </a>
        </li>
        <?php
        if (strpos($url, 'assets') !== false) {
            echo '<li class="active">';
        } else {
            echo '<li>';
        }
        ?>
            <a href="assets.php">
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
            <a href="supplies.php">
                <i class='bx bx-cart' ></i>
                <span>Supplies</span>
            </a>
        </li>
        <?php
        if (strpos($url, 'feedbacks') !== false) {
            echo '<li class="active">';
        } else {
            echo '<li>';
        }
        ?>
            <a href="feedbacks.php">
                <i class='bx bx-file' ></i>
                <span>Feedbacks</span>
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
                <span>Request</span>
                <i class='bx bx-chevron-down'></i>
            </button>
            <ul class="sub-menu">
                <div>
                    <li><a href="assetreq.php"><span <?php
                    if (strpos($url, 'assetreq') !== false) {
                        echo '';
                    } else {
                        echo 'style="color: #ffffff; text-decoration: none;"';
                    }
                    ?>>Asset Request</span></a></li>
                    <li><a href="assetres.php"><span <?php
                    if (strpos($url, 'assetres') !== false) {
                        echo '';
                    } else {
                        echo 'style="color: #ffffff; text-decoration: none;"';
                    }
                    ?>>Asset Reservation</span></a></li>
                    <li><a href="supplyreq.php"><span <?php
                    if (strpos($url, 'supplyreq') !== false) {
                        echo '';
                    } else {
                        echo 'style="color: #ffffff; text-decoration: none;"';
                    }
                    ?>>Supply Request</span></a></li>
                </div>
            </ul>
        </li>
    </ul>
</aside>