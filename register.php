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
        <link type="text/css" rel="stylesheet" href="./css/register_form.css" >
        <link type="text/css" rel="stylesheet" href="./css/msgbox.css" >

        <script type="text/javascript" src="./js/utility.js"></script>
        <script type="text/javascript" src="./js/input_verification.js"></script>
        <script type="text/javascript" src="./js/login_panel_lib.js"></script>
        <script type="text/javascript" src="./js/ajax_register.js"></script>

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

            function isSelCustomer()
            {
                var selObj = document.regForm.user_type;
                return value = selObj[0].checked;
            }

            function switchForm(e)
            {
                if (isSelCustomer())
                {
                    utility_divAddInvisibleStyle("restaurant_detail");
                    utility_divRemoveInvisibleStyle("customer_detail");
                }
                else
                {
                    utility_divAddInvisibleStyle("customer_detail");
                    utility_divRemoveInvisibleStyle("restaurant_detail");
                }
            }

            function formVerification(e)
            {
                e.preventDefault();

                var isCustomer = isSelCustomer();

                var errMsg = "";

                errMsg += js_checkUsername(document.regForm.username.value);
                errMsg += js_checkPassword2(document.regForm.password.value, document.regForm.password2.value);
                errMsg += js_checkEmail(document.regForm.email.value);

                if (isCustomer)
                {
                    errMsg += js_checkFullname(document.regForm.cust_fullname.value);
                    errMsg += js_checkTel(document.regForm.cust_tel.value);
                } else
                {
                    errMsg += js_checkFullname(document.regForm.rest_fullname.value);
                    errMsg += js_checkTel(document.regForm.rest_tel.value);
                    errMsg += js_checkAddress(document.regForm.rest_address.value);
                    errMsg += js_checkDescription(document.regForm.rest_description.value);
                }

                if (errMsg !== "")
                {
                    showErrMsgBox(errMsg);
                    return false;
                }
                else if (isCustomer)
                {
                    ajax_customer_register();
                    return false;
                }
                else
                {
                    ajax_restaurant_register();
                    return false;
                }
            }

            function Init()
            {
                document.regForm.addEventListener("submit", formVerification, false);

                document.getElementById("sel_customer").addEventListener("click", switchForm, false);
                document.getElementById("sel_restaurant").addEventListener("click", switchForm, false);

                document.getElementById("registerStatusMsgBox_OKBtn").addEventListener("click", registerStatusMsgBox_OKBtnHandler, false);
                document.getElementById("errMsgBox_OKBtn").addEventListener("click", errMsgBox_OKBtnHandler, false);

                switchForm();
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

        <!--registerStatus message box begin, it will show a message box when the invisible_style is removed -->
        <div id="registerStatusMsgBox" class="panel msgbox invisible_style">
            <div class="panel_title">Information</div>
            <div class="panel_content">
                <img class="msgbox_icon" src="./img/msgbox/icon_information.png" alt="info" />
                <div class="msgbox_text"> <div id="registerStatusText">Waiting response...</div></div>
                <div class="msgbox_btn_place_holder">
                    <input type="button" id="registerStatusMsgBox_OKBtn" value="OK" class="button msgbox_btn1" />
                </div>
            </div>
        </div>
        <!--registerStatus message box end-->              

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
                <div class="panel_title">User Registration</div>
                <div class="panel_content">
                    <form class="register_form" name="regForm" action="#"><!--use AJAX technology--><!-form--->

                        <h4>Register as:&nbsp;&nbsp;<input type="radio" id="sel_customer" name="user_type" value="customer" checked="checked"/> customer&nbsp;&nbsp;
                            <input type="radio" id="sel_restaurant" name="user_type" value="restaurant" /> restaurant&nbsp;&nbsp;<br/></h4>
                        <br/>
                        <div class="panel">
                            <div class="panel_title general_regform_category_theme">Account Information</div>
                            <div class="panel_content">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Login username:</td>
                                            <td><input type="text" name="username" /></td>
                                        </tr>
                                        <tr>
                                            <td>New Password:</td>
                                            <td><input type="password" name="password" /></td>
                                        </tr>
                                        <tr>
                                            <td>Retype New Password:</td>
                                            <td><input type="password" name="password2" /></td>
                                        </tr>
                                        <tr>
                                            <td>Email:</td>
                                            <td><input type="email" name="email" /></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!--Customer Detail Begin-->
                        <div class="panel" id="customer_detail">
                            <div class="panel_title customer_regform_category_theme">User Details</div>
                            <div class="panel_content">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Name:</td>
                                            <td><input type="text" name="cust_fullname" /></td>
                                        </tr>
                                        <tr>
                                            <td>Mobile:</td>
                                            <td><input type="text" name="cust_tel" /></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--Customer Detail End-->

                        <!--Restaurant Detail Begin-->
                        <div class="panel" id="restaurant_detail">
                            <div class="panel_title restaurant_regform_category_theme">User Details</div>
                            <div class="panel_content">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Restaurant Name:</td>
                                            <td><input type="text" name="rest_fullname" /></td>
                                        </tr>
                                        <tr>
                                            <td>Tel:</td>
                                            <td><input type="text" name="rest_tel" /></td>
                                        </tr>
                                        <tr>
                                            <td>District:</td>
                                            <td>
                                                <select name="rest_district">
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
                                                <textarea rows="4" cols="50" maxlength="100" name="rest_address"><?php echo $returnResult['address']; ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Description:
                                            </td>
                                            <td>
                                                <textarea rows="8" cols="50" maxlength="1000" name="rest_description"><?php echo $returnResult['description']; ?></textarea>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--Restaurant Detail End-->

                        <input type="submit" class="button" value="Register" />
                        <input type="reset" class="button" value="Reset" />
                    </form><!--</form>-->
                </div>
            </div>
        </div>
    </body>
</html>
