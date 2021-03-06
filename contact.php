<?php
require_once('php_func/session_stub.php');
php_session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Restaurant Reservation System</title>
        <link href="img/icon.ico" rel="icon" type="image/x-icon"/>

        <link type="text/css" rel="stylesheet" href="./css/general.css" >
        <link type="text/css" rel="stylesheet" href="./css/button.css" >   
        <link type="text/css" rel="stylesheet" href="./css/navbar.css" >
        <link type="text/css" rel="stylesheet" href="./css/login_panel.css" >
        <link type="text/css" rel="stylesheet" href="./css/panel.css" >

        <script type="text/javascript" src="./js/utility.js"></script>
        <script type="text/javascript" src="./js/login_panel_lib.js"></script>

        <script type="text/javascript">
            function Init()
            {
                sesson_initNavBar(9999);
            }

            //When the page is start, call init()
            window.addEventListener("load", Init, false);
        </script>
    </head>

    <body>
        <div class="navbar">
            <?php
                php_gen_navbar();
            ?>
        </div>

        <!--login_panel begin-->
        <div id="login_panel" class="login_panel invisible_style">
            <form action="auth.php" method="POST">
                <input type="hidden" name="func_name" value = "login" />
                <table align = "right">
                    <tbody>
                        <tr>
                            <td>
                                <input type="radio" name="user_type" value="customer" checked="checked" />Customer&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="user_type" value="restaurant" />Restaurant&nbsp;&nbsp;<br/>
                                Username:&nbsp;<input type="text" name="username" />&nbsp;&nbsp;
                                Password:&nbsp;<input type="password" name="password" />
                            </td>
                            <td>
                                <input type="submit" class="button" name="login" value="Login" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
        <!--login_panel end-->

        <div class="container">
            <div class="panel">
                <div class="panel_title">General Questions</div>
                <div class="panel_content">
                    <p>Please e-mail us with any questions or feedback about our website.</p>
                    <p><a href="mailto:cs1@restaurant_reservate_system.com">cs1@restaurant_reservate_system.com</a></p>
                </div>
            </div>
            <div class="panel">
                <div class="panel_title">Voice of Customer</div>
                <div class="panel_content">
                    <p>Our customer service team is always willing to answer your proposal concerning Samsung Service.</p>
                    <p>Your message will be promptly handled under the direct supervision of our executive management.</p>
                    <p>(852)-12345678</p>
                </div>
            </div>
        </div>
    </body>
</html>
