<?php
require_once('php_func/session_stub.php');
php_session_start();
php_auth_customer_only();

require_once('php_func/customer_func_list.php');
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
        <link type="text/css" rel="stylesheet" href="./css/resv_process.css" >

        <script type="text/javascript" src="./js/utility.js"></script>

        <script type="text/javascript" src="./js/login_panel_lib.js"></script>

        <script type="text/javascript">
            function Init()
            {
                document.getElementById("goToHomeBtn").addEventListener("click", utility_goToMainPage, false);
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
                <input type="hidden" name="func_name" value = "login">
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
                <div class="panel_title">Reservation Request</div>
                <div class="panel_content">
                    <div id="resv_process_status">
                        <p><?php
                        $result = php_reserve_table($_POST['rest_id'], $_POST['person'], $_POST['date'], $_POST['time'], $_POST['special_request'], $_POST['receive_email']);
                        echo $result['status'];
                        ?></p>
                    </div>
                    <div class="resv_process_info">
                        <table>
                            <tbody>
                                <tr>
                                    <td>ID:</td>
                                    <td><div id="resv_id"><?php echo $result['id']; ?></div></td>
                                </tr>
                                <tr>
                                    <td>Date Time:</td>
                                    <td><div id="resv_datetime"><?php echo $result['datetime']; ?></div></td>
                                </tr>
                                <tr>
                                    <td>Restaurant:</td>
                                    <td><div id="resv_rest"><?php echo $result['rest_name']; ?></div></td>
                                </tr>
                                <tr>
                                    <td>Restaurant Location:</td>
                                    <td><div id="resv_rest_location"><?php echo $result['rest_addr']; ?></div></td>
                                </tr>
                                <tr>
                                    <td>Restaurant Tel:</td>
                                    <td><div id="resv_rest_tel"><?php echo $result['rest_tel']; ?></div></td>
                                </tr>
                                <tr>
                                    <td>Under the name:</td>
                                    <td><div id="resv_customer_name"><?php echo $result['username']; ?></div></td>
                                </tr>
                                <tr>
                                    <td>Person:</td>
                                    <td><div id="resv_person"><?php echo $result['person']; ?></div></td>
                                </tr>
                                <tr>
                                    <td>Special Request:</td>
                                    <td><div id="resv_special_request"></div><?php echo $result['special_request']; ?></td>
                                </tr>
                                <tr>
                                    <td>Receive email:</td>
                                    <td><div id="resv_receive_email"></div><?php echo $result['receive_email']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <input type="button" id="goToHomeBtn" class="button button_large_style" value="Go to main page" />
                </div>
            </div>
        </div>
    </body>
</html>
