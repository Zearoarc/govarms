<?php

session_start();
include("header.php");

?>
<main>
    <div class="loginpage">
        <div class="wrapper govblue">
            <form method="post">
                <div class="box">
                    <img src="images/logocity.webp" unselectable="on" draggable="false" class="image" />
                    <div class="text">
                        Asset Request and<br> Management System
                    </div>
                </div>
                <div class="input-box">
                    <i class='bx bxs-user'></i>
                    <input type="text" name="email_log" id="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="input-box">
                    <i class='bx bxs-lock-alt'></i>
                    <input type="password" name="psw_log" id="psw" class="form-control" placeholder="Password" required>
                </div>

                <div class="remember-forgot">
                    <label><input type="checkbox"> Remember me</label>
                    <a href="#">Forgot password</a>
                </div>

                <button type="submit" class="btn" name="btn_login">Login</button>
            </form>
        </div>
    </div>
</main>
</body>

</html>