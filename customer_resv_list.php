<?php
require_once('php_func/session_stub.php');
php_session_start();
php_auth_customer_only();

require_once './php_func/customer_func_list.php';
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
        <link type="text/css" rel="stylesheet" href="./css/general_resv_list.css" >
        <link type="text/css" rel="stylesheet" href="./css/message_remainder.css" >
        <link type="text/css" rel="stylesheet" href="./css/msgbox.css" >

        <script type="text/javascript" src="./js/utility.js"></script>

        <script type="text/javascript" src="./js/login_panel_lib.js"></script>

        <script type="text/javascript" src="./js/ajax_customer_resv_list.js"></script>

        <script type="text/javascript">
            function Init()
            {
                document.getElementById("restaurantInfoMsgBox_OKBtn").addEventListener("click", restaurantInfoMsgBox_OKBtnHandler, false);
            }

            //When the page is start, call init()
            window.addEventListener("load", Init, false);
        </script>
    </head>

    <body>

        <!--mask begin, it will show a mask when the invisible_style is removed-->
        <div id="mask" class="mask invisible_style">
        </div>
        <!--mask end-->

        <!--Restaurant Info message box begin, it will show a message box when the invisible_style is removed -->
        <div id="restaurantInfoMsgBox" class="panel msgbox invisible_style">
            <div class="panel_title">Restaurant Information</div>
            <div class="panel_content">
                <img class="msgbox_icon" src="./img/msgbox/icon_question.png" alt="info" />
                <div class="msgbox_text">
                    <table>
                        <tbody>
                            <tr>
                                <td>Name:&nbsp;</td>
                                <td id="restaurant_info_msgbox_name"></td>
                            </tr>        
                            <tr>
                                <td>Address:&nbsp;</td>
                                <td id="restaurant_info_msgbox_address"></td>
                            </tr>
                            <tr>
                                <td>Contact No.:&nbsp;</td>
                                <td id="restaurant_info_msgbox_tel"></td>
                            </tr>   
                        </tbody>
                    </table>
                </div>
                <div class="msgbox_btn_place_holder">
                    <input type="button" id="restaurantInfoMsgBox_OKBtn" value="OK" class="button msgbox_btn1" />
                </div>
            </div>
        </div>
        <!--Restaurant Info message box end-->       

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

            <div class="message_remainder">
                <b><!--Remainder: This is just a friendly reminder that you need to go to ADA Steak House on 16,Jul,2014-->
                    <?php echo php_show_first_upcoming_reservation(); ?>
                </b>
                <div class="message_remainder_close" >
                </div>
            </div>


            <div class="panel">
                <div class="panel_title">Upcoming Reservation List</div>
                <div class="panel_content">
                    <table class="resv_list">
                        <thead>
                            <tr>
                                <th>Date, Time</th>
                                <th>Person</th>
                                <th colspan="2">Restaurant</th>
                                <th>Special Request Submitted</th>
                                <th>Ref. ID</th>
                                <th>by Email</th>
                                <th>Response</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--reservation item begin
                            <tr>
                                <td class="resv_item_time">
                                    16, Jul, 2014 9:00pm
                                </td>
                                <td class="resv_item_person">
                                    4
                                </td>
                                <td class="resv_item_restaurant_name">
                                    ADA Steak House
                                </td>
                                <td class="resv_item_restaurant_detail">
                                    <input type="button" class="button" value="Detail" onclick="showRestaurantInfoMsgBoxBtnHandler('1234');" />
                                </td>
                                <td class="resv_item_msg">
                                </td>
                                <td class="resv_item_id">
                                    1234-250
                                </td>
                               <td class="resv_item_email">
                                    <img src="./img/accept.png" alt="accept" />
                                </td>
                                <td class="resv_item_response">
                                    <img src="./img/accept.png" alt="accept" />
                                </td>
                            </tr>
                            --reservation item end-->

                            
                            <?php echo php_show_reservation(); ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
