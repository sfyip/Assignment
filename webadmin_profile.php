<?php
require_once('php_func/session_stub.php');
php_session_start();
php_auth_webadmin_only();

require_once ('php_func/webadmin_func_list.php');
$returnResult = php_get_profile_info();
if($returnResult['status'] != 'success')
{
    header('Location:'.URL.'operation_failed.php?reason=' . htmlentities($returnResult['status']));
}
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
        <link type="text/css" rel="stylesheet" href="./css/profile_form.css" >
        <link type="text/css" rel="stylesheet" href="./css/msgbox.css" >

        <script type="text/javascript" src="./js/utility.js"></script>
        <script type="text/javascript" src="./js/input_verification.js"></script>
        <script type="text/javascript" src="./js/login_panel_lib.js"></script>

        <script type="text/javascript">
            function errMsgBox_OKBtnHandler()
            {
                utility_divAddInvisibleStyle("mask");
                utility_divAddInvisibleStyle("errMsgBox");
            }

            function showErrMsgBox(msg)
            {
                utility_divRemoveInvisibleStyle("mask");
                utility_divRemoveInvisibleStyle("errMsgBox");
                document.getElementById("errMsg").innerHTML = msg;
                utility_divCenterWindow("errMsgBox");
            }

            function formVerification(e)
            {
                var errMsg = "";

                errMsg += js_checkPassword2(document.profileForm.newPassword.value, document.profileForm.newPassword2.value);
                errMsg += js_checkPassword3(document.profileForm.oldPassword.value, document.profileForm.newPassword.value, document.profileForm.newPassword2.value);

                if (errMsg !== "")
                {
                    showErrMsgBox(errMsg);
                    e.preventDefault();
                    return false;
                }
                else
                {
                    return true;
                }
            }

            function Init()
            {
                document.profileForm.addEventListener("submit", formVerification, false);
                document.getElementById("errMsgBox_OKBtn").addEventListener("click", errMsgBox_OKBtnHandler, false);
            }

            //When the page is start, call init()
            window.addEventListener("load", Init, false);
        </script>
    </head>

    <body>

        <div class="navbar">
            <?php
                php_gen_navbar_webadmin_page();
            ?>
        </div>

        <!--mask begin, it will show a mask when the invisible_style is removed-->
        <div id="mask" class="mask invisible_style">
        </div>
        <!--mask end-->

        <!--Error message box begin, it will show a message box when the invisible_style is removed -->
        <div id="errMsgBox" class="panel msgbox invisible_style">
            <div class="panel_title">Information</div>
            <div class="panel_content">
                <img class="msgbox_icon" src="./img/msgbox/icon_error.png" alt="info" />
                <div class="msgbox_text" id="errMsg"></div>
                <div class="msgbox_btn_place_holder">
                    <input type="button" id="errMsgBox_OKBtn" value="OK" class="button msgbox_btn1" />
                </div>
            </div>
        </div>
        <!--Error message box message box end-->  

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
                <div class="panel_title">User Profile</div>
                <div class="panel_content">
                    <form class="profile_form" name="profileForm" action="php_func/webadmin_func_list.php" method="POST">
                        <input type="hidden" name="func_name" value="change_profile" />
                        <br/>
                        <div class="panel">
                            <div class="panel_title profile_form_category_theme">Account Information</div>
                            <div class="panel_content">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Old Password:</td>
                                            <td><input type="password" name="oldPassword" /></td>
                                        </tr>
                                        <tr>
                                            <td>New Password:</td>
                                            <td><input type="password" name="newPassword" /></td>
                                        </tr>
                                        <tr>
                                            <td>Retype New Password:</td>
                                            <td><input type="password" name="newPassword2" /></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <input type="submit" class="button button_large_style" value="Change" />
                        <input type="reset" class="button button_large_style" value="Reset" />
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
