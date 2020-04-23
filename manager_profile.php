<?php
require_once('php_func/session_stub.php');
php_session_start();
php_auth_restaurant_only();

require_once ('php_func/manager_func_list.php');
$returnResult = php_get_profile_info();
if ($returnResult['status'] != 'success') {
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

                errMsg += js_checkPassword3(document.profileForm.oldPassword.value, document.profileForm.newPassword.value, document.profileForm.newPassword2.value);
                errMsg += js_checkEmail(document.profileForm.email.value);
                errMsg += js_checkFullname(document.profileForm.fullname.value);
                errMsg += js_checkTel(document.profileForm.tel.value);
                errMsg += js_checkAddress(document.profileForm.address.value);
                errMsg += js_checkDescription(document.profileForm.description.value);

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
                document.profileForm.district.value = "<?php echo $returnResult['district']; ?>";
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
            php_gen_navbar();
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
                <div class="panel_title">User Profile</div>
                <div class="panel_content">
                    <form class="profile_form" name="profileForm" action="php_func/manager_func_list.php" method="POST">
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
                                        <tr>
                                            <td>Email:</td>
                                            <td><input type="email" name="email" value="<?php echo $returnResult['email']; ?>" /></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="panel">
                            <div class="panel_title profile_form_category_theme">Restaurant Details</div>
                            <div class="panel_content">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Name:</td>
                                            <td><input type="text" name="fullname" value="<?php echo $returnResult['fullname']; ?>" /></td>
                                        </tr>
                                        <tr>
                                            <td>Tel:</td>
                                            <td><input type="text" name="tel" value="<?php echo $returnResult['tel']; ?>" /></td>
                                        </tr>
                                        <tr>
                                            <td>District:</td>
                                            <td>
                                                <select name="district">
                                                    <option value="11">Central and Western</option>
                                                    <option value="12">Eastern</option>
                                                    <option value="13">Southern</option>
                                                    <option value="14">Wan Chai</option>
                                                    <option value="21">Sham Shui Po</option>
                                                    <option value="22">Kowloon City</option>
                                                    <option value="23">Kwun Tong</option>
                                                    <option value="24">Wong Tai Sin</option>
                                                    <option value="25">Yau Tsim Mong</option>
                                                    <option value="31">Islands</option>
                                                    <option value="32">Kwai Tsing</option>
                                                    <option value="33">North</option>
                                                    <option value="34">Sai Kung</option>
                                                    <option value="35">Sha Tin</option>
                                                    <option value="36">Tai Po</option>
                                                    <option value="37">Tsuen Wan</option>
                                                    <option value="38">Tuen Mun</option>
                                                    <option value="39">Yuen Long</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Address:
                                            </td>
                                            <td>
                                                <textarea rows="4" cols="50" maxlength="100" name="address"><?php echo $returnResult['address']; ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Description:
                                            </td>
                                            <td>
                                                <textarea rows="8" cols="50" maxlength="1000" name="description"><?php echo $returnResult['description']; ?></textarea>
                                            </td>
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

            <div class="panel">
                <div class="panel_title profile_form_category_theme">Thumbnail</div>
                <div class="panel_content">
                    <table>
                        <tbody>
                            <tr>
                                <td>
                                    Image File:
                                </td>
                                <td>
                                    <img src="img/restaurant/<?php echo empty($returnResult['id']) ? ('0') : $returnResult['id']; ?>/1.jpg" alt="preview"/>
                                    <br/>
                                    <form action="php_func/upload_file.php" method="post"
                                          enctype="multipart/form-data">
                                        <label for="file">Filename:</label>
                                        <input type="file" name="file">
                                        <input type="submit" value="Upload">
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
