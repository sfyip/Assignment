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
    </head>

    <body>
        <div class="navbar">
            <?php
            php_gen_navbar_webadmin_page();
            ?>
        </div>

        <!--login_panel begin-->
        <div id="login_panel" class="login_panel invisible_style">
            <form action="auth.php" method="POST">
                <input type="hidden" name="func_name" value = "login">
                <table align = "right">
                    <tbody>
                        <tr>
                            <td>
                                <input type="hidden" name="user_type" value="webadmin" />
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
                <div class="panel_title">Information</div>
                <div class="panel_content">
                   <h2>Please login first.</h2>
                </div>
            </div>
        </div>
    </body>
</html>
