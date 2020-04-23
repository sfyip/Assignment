<?php
require_once('php_func/session_stub.php');
php_session_start();
php_auth_restaurant_only();
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
        <link type="text/css" rel="stylesheet" href="./css/manager_resv_list.css" >
        <link type="text/css" rel="stylesheet" href="./css/message_remainder.css" >
        <link type="text/css" rel="stylesheet" href="./css/msgbox.css" >

        <script type="text/javascript" src="./js/utility.js"></script>

        <script type="text/javascript" src="./js/login_panel_lib.js"></script>

        <script type="text/javascript" src="./js/ajax_manager_resv_list.js"></script>

        <!-- Include the calender control-->
        <link type="text/css" rel="stylesheet" href="./css/calander_ctrl.css">
        <script type="text/javascript" src="./js/calander_ctrl.js"></script>

        <script type="text/javascript">

            function showGenReportWindow() {
                var month = parseInt(selectedMonth) + 1;
                var link = 'manager_resv_gen_report.php?date='+selectedDate+'%2F'+month+'%2F'+selectedYear;
                window.open(link, 'Reservation Report');
            }

            function showTodayResvList() {
                var today = new Date();
                
                //selectedYear, selectedMonth and selectedDate is global variable.
                selectedYear = today.getFullYear();
                selectedMonth = today.getMonth();
                selectedDate = today.getDate();

                //alert("D:" + selectedDate + "M:" + selectedMonth + "Y:" + selectedYear);

                ajax_showRestResvList(selectedDate, selectedMonth, selectedYear);
            }

            function Init()
            {
                calanderCtrl_init();
                showTodayResvList();

                document.getElementById("customerInfoMsgBox_OKBtn").addEventListener("click", customerInfoMsgBox_OKBtnHandler, false);

                document.getElementById("resvResponseMsgBox_acceptBtn").addEventListener("click", resvResponseMsgBox_AcceptBtnHandler, false);
                document.getElementById("resvResponseMsgBox_declineBtn").addEventListener("click", resvResponseMsgBox_DeclineBtnHandler, false);
                document.getElementById("resvResponseMsgBox_cancelBtn").addEventListener("click", resvResponseMsgBox_CancelBtnHandler, false);

                document.getElementById("messageRemainder_viewBtn").addEventListener("click", messageRemainder_showExtentedPanel, false);

                //Select today button, when click, it will display today reservation list
                document.getElementById("selTodayBtn").addEventListener("click", showTodayResvList, false);

                //Select the date in calander, when click, it will display reservation list on that day
                for (var i = 0; i <= 41; i++)
                {
                    document.getElementById("calanderCtrl_date_id_" + i).addEventListener("click", function() {

                        if (this.innerHTML === "")
                        {
                            return;
                        }

                        //selectedYear, selectedMonth and selectedDate is global variable.
                        selectedYear = calanderCtrl_getSelectedYear();
                        selectedMonth = calanderCtrl_getSelectedMonth();
                        selectedDate = parseInt(this.innerHTML);

                        //alert("D:" + selectedDate + "M:" + selectedMonth + "Y:" + selectedYear);

                        ajax_showRestResvList(selectedDate, selectedMonth, selectedYear);
                        
                    }, false);
                }

                document.getElementById("genReportBtn").addEventListener("click", showGenReportWindow, false);

                //Get the message now and continue retrieve the message every 5 seconds.
                ajax_getMessage();
                setInterval(ajax_getMessage, 5000);
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

        <!--Customer Info message box begin, it will show a message box when the invisible_style is removed -->
        <div id="customerInfoMsgBox" class="panel msgbox invisible_style">
            <div class="panel_title">Customer Information</div>
            <div class="panel_content">
                <img class="msgbox_icon" src="./img/msgbox/icon_question.png" alt="info" />
                <div class="msgbox_text">
                    <table>
                        <tbody>
                            <tr>
                                <td>Name:&nbsp;</td>
                                <td id="customer_info_msgbox_name"></td>
                            </tr>        
                            <tr>
                                <td>Email:&nbsp;</td>
                                <td id="customer_info_msgbox_email"></td>
                            </tr>
                            <tr>
                                <td>Contact No.:&nbsp;</td>
                                <td id="customer_info_msgbox_tel"></td>
                            </tr>   
                        </tbody>
                    </table>
                </div>
                <div class="msgbox_btn_place_holder">
                    <input type="button" id="customerInfoMsgBox_OKBtn" value="OK" class="button msgbox_btn1"/>
                </div>
            </div>
        </div>
        <!--Customer Info message box end-->

        <!--ResvResponse message box begin, it will show a message box when the invisible_style is removed -->
        <div id="resvResponseMsgBox" class="panel msgbox invisible_style">
            <div class="panel_title">Reservation Response</div>
            <div class="panel_content">
                <img class="msgbox_icon" src="./img/msgbox/icon_question.png" alt="info" />
                <div class="msgbox_text">
                    Accept the reservation request?
                    <p>Once it is accepted, the state cannot be changed anymore.</p>
                </div>
                <div class="msgbox_btn_place_holder">
                    <input type="button" id="resvResponseMsgBox_acceptBtn" value="Accept" class="button msgbox_btn3" />
                    <input type="button" id="resvResponseMsgBox_declineBtn" value="Decline" class="button msgbox_btn2" />
                    <input type="button" id="resvResponseMsgBox_cancelBtn" value="Cancel" class="button msgbox_btn1" />
                </div>
            </div>
        </div>
        <!--ResvResponse message box end-->

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
            <table>
                <tbody>
                    <tr>
                        <td>
                            <div class="message_remainder">
                                <!--
                                DUMMY MESSAGE SAMPLE
                                <b>You need to confirm 3 reservation requests.</b>
                                -->
                                <div id="messageRemainder_brief"></div>
                                <input type="button" id="messageRemainder_viewBtn" class="button" name="view_msg" value="View >>" />

                                <div class="message_remainder_close"> 
                                    <div class="message_remainder_detail_view invisible_style" id="msgPanel">
                                        <div id="messageRemainder_msg"></div>
                                        <!--
                                        DUMMY MESSAGE SAMPLE
                                        <p>14, Jul, 2014 7:30pm, (2 person)<input type="button" class="button" name="check" value="Check" /></p>
                                        <p>15, Jul, 2014 8:30pm, (3 person)<input type="button" class="button" name="check" value="Check" /></p>
                                        <p>17, Jul, 2014 9:15pm, (4 person)<input type="button" class="button" name="check" value="Check" /></p>
                                        -->

                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>

                        </td></tr>
                </tbody>
            </table>




            <div class="panel">
                <div class="panel_title">Reservation List</div>
                <div class="panel_content">
                    <table class="manager_resv_category">
                        <tbody>
                            <tr>
                                <td class="manager_resv_list_pickup_date_panel"  style="vertical-align:top">
                                    <!--Date pick up control Begin-->
                                    <Input type="button" id="genReportBtn" class="button" name="gen_report" value="Generate Report"/>
                                    <br/>
                                    <Input type="button" id="selTodayBtn" class="button" name="today" value="View Today Now"/>
                                    <br/>

                                    <div class="calanderCtrl" id="cal">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th id="calanderCtrl_prev_year" onclick="calanderCtrl_selected_prev_year();">&lt;&lt;</th>
                                                    <th id="calanderCtrl_prev_month" onclick="calanderCtrl_selected_prev_month();">&lt;</th>
                                                    <th id="calanderCtrl_date_month" colspan="3"></th>
                                                    <th id="calanderCtrl_next_month" onclick="calanderCtrl_selected_next_month();">&gt;</th>
                                                    <th id="calanderCtrl_next_year" onclick="calanderCtrl_selected_next_year();">&gt;&gt;</th>
                                                </tr>
                                                <tr>
                                                    <th class="calanderCtrl_week">Su</th>
                                                    <th class="calanderCtrl_week">Mo</th>
                                                    <th class="calanderCtrl_week">Tu</th>
                                                    <th class="calanderCtrl_week">We</th>
                                                    <th class="calanderCtrl_week">Th</th>
                                                    <th class="calanderCtrl_week">Fr</th>
                                                    <th class="calanderCtrl_week">Sa</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td id="calanderCtrl_date_id_0"></td>
                                                    <td id="calanderCtrl_date_id_1"></td>
                                                    <td id="calanderCtrl_date_id_2"></td>
                                                    <td id="calanderCtrl_date_id_3"></td>
                                                    <td id="calanderCtrl_date_id_4"></td>
                                                    <td id="calanderCtrl_date_id_5"></td>
                                                    <td id="calanderCtrl_date_id_6"></td>
                                                </tr>
                                                <tr>
                                                    <td id="calanderCtrl_date_id_7"></td>
                                                    <td id="calanderCtrl_date_id_8"></td>
                                                    <td id="calanderCtrl_date_id_9"></td>
                                                    <td id="calanderCtrl_date_id_10"></td>
                                                    <td id="calanderCtrl_date_id_11"></td>
                                                    <td id="calanderCtrl_date_id_12"></td>
                                                    <td id="calanderCtrl_date_id_13"></td>
                                                </tr>
                                                <tr>
                                                    <td id="calanderCtrl_date_id_14"></td>
                                                    <td id="calanderCtrl_date_id_15"></td>
                                                    <td id="calanderCtrl_date_id_16"></td>
                                                    <td id="calanderCtrl_date_id_17"></td>
                                                    <td id="calanderCtrl_date_id_18"></td>
                                                    <td id="calanderCtrl_date_id_19"></td>
                                                    <td id="calanderCtrl_date_id_20"></td>
                                                </tr>
                                                <tr>
                                                    <td id="calanderCtrl_date_id_21"></td>
                                                    <td id="calanderCtrl_date_id_22"></td>
                                                    <td id="calanderCtrl_date_id_23"></td>
                                                    <td id="calanderCtrl_date_id_24"></td>
                                                    <td id="calanderCtrl_date_id_25"></td>
                                                    <td id="calanderCtrl_date_id_26"></td>
                                                    <td id="calanderCtrl_date_id_27"></td>
                                                </tr>
                                                <tr>
                                                    <td id="calanderCtrl_date_id_28"></td>
                                                    <td id="calanderCtrl_date_id_29"></td>
                                                    <td id="calanderCtrl_date_id_30"></td>
                                                    <td id="calanderCtrl_date_id_31"></td>
                                                    <td id="calanderCtrl_date_id_32"></td>
                                                    <td id="calanderCtrl_date_id_33"></td>
                                                    <td id="calanderCtrl_date_id_34"></td>
                                                </tr>
                                                <tr>
                                                    <td id="calanderCtrl_date_id_35"></td>
                                                    <td id="calanderCtrl_date_id_36"></td>
                                                    <td id="calanderCtrl_date_id_37"></td>
                                                    <td id="calanderCtrl_date_id_38"></td>
                                                    <td id="calanderCtrl_date_id_39"></td>
                                                    <td id="calanderCtrl_date_id_40"></td>
                                                    <td id="calanderCtrl_date_id_41"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>


                                    <!--Date pick up control End-->
                                </td>
                                <td style="vertical-align:top">
                                    <!--Panel Begin-->
                                    <div class ="panel">
                                        <div class="panel_title manager_resv_panel_day" id="resv_list_day"></div>
                                        <div class="panel_content">
                                            <table class="resv_list">
                                                <thead>
                                                    <tr>
                                                        <th>Time</th>
                                                        <th>Person</th>
                                                        <th colspan="2">Customer Info</th>
                                                        <th>Special Request from Customer</th>
                                                        <th>Ref. ID</th>
                                                        <th colspan="2">Response</th>
                                                    </tr>
                                                </thead>
                                                <tbody id = "resv_list_tbody">
                                                    <!-- The server side dynamic generated content will be placed here in table format-->
                                                    <!--reservation item begin-->
                                                    <!--
                                                    <tr>
                                                        <td class="resv_item_time">
                                                            1:00pm
                                                        </td>
                                                        <td class="resv_item_person">
                                                            4
                                                        </td>
                                                        <td class="resv_item_customer_name">
                                                            Tom Chan
                                                        </td>
                                                        <td class="resv_item_customer_detail">
                                                            <input type="button" class="button" value="Detail" onclick="showCustomerInfoMsgBoxBtnHandler('1234-001');"/>
                                                        </td>
                                                        <td class="resv_item_msg">
                                                        </td>
                                                        <td class="resv_item_id">
                                                            1234-001
                                                        </td>
                                                        <td id="1234-001_response" class="resv_item_response" >
                                                            <img src="./img/accept.png" alt="accept" />
                                                        </td>
                                                        <td id="1234-001_change" class="resv_item_change">
                                                            <input type="button" class="button" name="change_action" value="Change" onclick="showResvResponseMsgBoxBtnHandler();" /> 
                                                        </td>
                                                    </tr>
                                                    -->
                                                    <!--reservation item end-->

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!--Panel End-->
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
