<?php
require_once('php_func/session_stub.php');
php_session_start();

require_once 'php_func/search_rest_func.php';
require_once 'php_func/rest_detail_func.php';
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
        <link type="text/css" rel="stylesheet" href="./css/rest_search_bar.css" >
        <link type="text/css" rel="stylesheet" href="./css/rest_detail.css" >
        <link type="text/css" rel="stylesheet" href="./css/msgbox.css" >

        <script type="text/javascript" src="./js/utility.js"></script>
        <script type="text/javascript" src="./js/input_verification.js"></script>
        <script type="text/javascript" src="./js/login_panel_lib.js"></script>
        <script type="text/javascript" src="./js/search_panel.js"></script>

        <script type="text/javascript" src="./js/reserve_rest_handler.js"></script>

        <link type="text/css" rel="stylesheet" href="./css/calander_ctrl.css">
        <script type="text/javascript" src="./js/calander_ctrl.js"></script>

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

            function Init()
            {
                loginAsCustomer = <?php echo (php_verify_user_match_customer())? "true": "false"; ?>;
                
                calanderCtrl_init();

                //When the "..."(calander pop up button) is clicked, it will pop up the calander ctrl
                document.getElementById("dateBtn").addEventListener("click", function() {
                    calanderCtrl_show('cal');
                }, false);

                document.getElementById("loginPromptMsgBox_OKBtn").addEventListener("click", loginPromptMsgBox_OKBtnHandler, false);
                document.getElementById("reserveConfigMsgBox_OKBtn").addEventListener("click", reserveConfigMsgBox_OKBtnHandler, false);
                document.getElementById("reserveConfigMsgBox_CancelBtn").addEventListener("click", reserveConfigMsgBox_CancelBtnHandler, false);

                searchPanel_loadSettingFromCookie(document.resvForm1);
                document.resvForm1.addEventListener("submit", function(){searchPanel_SaveSettingToCookie(document.resvForm1);}, false);
                document.resvForm1.addEventListener("submit", reserveRestHandler1, false);
                document.resvForm2.addEventListener("submit", reserveRestHandler2, false);
                document.getElementById("errMsgBox_OKBtn").addEventListener("click", errMsgBox_OKBtnHandler, false);

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

                        if ((!selectedDate) || (selectedDate.length === 0))
                        {
                            calanderCtrl_hide("cal");
                            return;
                        }

                        //setup dedicated input text field. 
                        var fmt_calanderCtrl_selectedMonth = ((selectedMonth + 1) < 10) ? ("0" + (selectedMonth + 1)) : ((selectedMonth + 1));
                        var fmt_selectedDate = (selectedDate < 10) ? ("0" + selectedDate) : (selectedDate);

                        //The text field format is DD/MM/YYYY
                        document.getElementById("date_txt").value = fmt_selectedDate + "/" + fmt_calanderCtrl_selectedMonth + "/" + selectedYear;

                        calanderCtrl_hide("cal");

                    }, false);
                }
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

        <!--Login prompt message box begin, it will show a message box when the invisible_style is removed -->
        <div id="loginPromptMsgBox" class="panel msgbox invisible_style">
            <div class="panel_title">Information</div>
            <div class="panel_content">
                <img class="msgbox_icon" src="./img/msgbox/icon_information.png" alt="info" />
                <div class="msgbox_text">You must login / register first.</div>
                <div class="msgbox_btn_place_holder">
                    <input type="button" id="loginPromptMsgBox_OKBtn" value="OK" class="button msgbox_btn1" />
                </div>
            </div>
        </div>
        <!--login prompt message box end-->

        <!--Reserve restaurant message box begin, it will show a message box when the invisible_style is removed -->
        <div id="reserveConfigMsgBox" class="panel msgbox invisible_style">
            <div class="panel_title">Reservation</div>
            <div class="panel_content">
                <form name="resvForm2" action="resv_process_result.php" method="POST">
                    <input type="hidden" name="date" />
                    <input type="hidden" name="time" />
                    <input type="hidden" name="person" />
                    <input type="hidden" name="rest_id" <?php echo (ctype_digit($_GET['rest_id'])?('value="'.$_GET['rest_id'].'"'):''); ?> />

                    <img class="msgbox_icon" src="./img/msgbox/icon_question.png" alt="info" />
                    <div class="msgbox_text">
                        <p><input type="checkbox" name="receive_email" checked="checked">I want to receive email when the result is acknowledged.</p>
                        <p>Special Request:</p>
                        <textarea name="special_request" cols="40" rows="5"></textarea>
                    </div>
                    <div class="msgbox_btn_place_holder">
                        <input type="submit" id="reserveConfigMsgBox_OKBtn" value="Submit" class="button msgbox_btn2" />
                        <input type="button" id="reserveConfigMsgBox_CancelBtn" value="Cancel" class="button msgbox_btn1" />
                    </div>
                </form>
            </div>
        </div>
        <!--login prompt message box end-->

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
                <div class="panel_title">Table Reservation</div>
                <div class="panel_content">
                    <!--Just pop up reservation dialog instead of submit the form-->
                    <form name="resvForm1" class ="rest_search_bar"  action="#" method="POST">
                        <table>
                            <tbody>
                                <tr>
                                    <td>
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <img src="./img/icon_datetime.png" alt="datetime"/>
                                                        Date (DD/MM/YYYY):
                                                    </td>
                                                    <td>
                                                        <div class="calanderCtrl_popup_style_placement">
                                                            <input type="text" name="date" id="date_txt" />
                                                            <input type="button" class="button" id="dateBtn" value="..." />

                                                            <div class="calanderCtrl calanderCtrl_popup_style invisible_style" id="cal">
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
                                                                            <td id="calanderCtrl_date_id_0" ></td>
                                                                            <td id="calanderCtrl_date_id_1" ></td>
                                                                            <td id="calanderCtrl_date_id_2" ></td>
                                                                            <td id="calanderCtrl_date_id_3" ></td>
                                                                            <td id="calanderCtrl_date_id_4" ></td>
                                                                            <td id="calanderCtrl_date_id_5" ></td>
                                                                            <td id="calanderCtrl_date_id_6" ></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td id="calanderCtrl_date_id_7" ></td>
                                                                            <td id="calanderCtrl_date_id_8" ></td>
                                                                            <td id="calanderCtrl_date_id_9" ></td>
                                                                            <td id="calanderCtrl_date_id_10" ></td>
                                                                            <td id="calanderCtrl_date_id_11" ></td>
                                                                            <td id="calanderCtrl_date_id_12" ></td>
                                                                            <td id="calanderCtrl_date_id_13" ></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td id="calanderCtrl_date_id_14" ></td>
                                                                            <td id="calanderCtrl_date_id_15" ></td>
                                                                            <td id="calanderCtrl_date_id_16" ></td>
                                                                            <td id="calanderCtrl_date_id_17" ></td>
                                                                            <td id="calanderCtrl_date_id_18" ></td>
                                                                            <td id="calanderCtrl_date_id_19" ></td>
                                                                            <td id="calanderCtrl_date_id_20" ></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td id="calanderCtrl_date_id_21" ></td>
                                                                            <td id="calanderCtrl_date_id_22" ></td>
                                                                            <td id="calanderCtrl_date_id_23" ></td>
                                                                            <td id="calanderCtrl_date_id_24" ></td>
                                                                            <td id="calanderCtrl_date_id_25" ></td>
                                                                            <td id="calanderCtrl_date_id_26" ></td>
                                                                            <td id="calanderCtrl_date_id_27" ></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td id="calanderCtrl_date_id_28" ></td>
                                                                            <td id="calanderCtrl_date_id_29" ></td>
                                                                            <td id="calanderCtrl_date_id_30" ></td>
                                                                            <td id="calanderCtrl_date_id_31" ></td>
                                                                            <td id="calanderCtrl_date_id_32" ></td>
                                                                            <td id="calanderCtrl_date_id_33" ></td>
                                                                            <td id="calanderCtrl_date_id_34" ></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td id="calanderCtrl_date_id_35" ></td>
                                                                            <td id="calanderCtrl_date_id_36" ></td>
                                                                            <td id="calanderCtrl_date_id_37" ></td>
                                                                            <td id="calanderCtrl_date_id_38" ></td>
                                                                            <td id="calanderCtrl_date_id_39" ></td>
                                                                            <td id="calanderCtrl_date_id_40" ></td>
                                                                            <td id="calanderCtrl_date_id_41" ></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>

                                                    </td>
                                                    <td>Time:</td>
                                                    <td><select name="time" id="time">
                                                            <option value="11:00">11:00am</option>
                                                            <option value="11:15">11:15am</option>
                                                            <option value="11:30">11:30am</option>
                                                            <option value="11:45">11:45am</option>
                                                            <option value="12:00">12:00pm</option>
                                                            <option value="12:15">12:15pm</option>
                                                            <option value="12:30">12:30pm</option>
                                                            <option value="12:45">12:45pm</option>
                                                            <option value="13:00">1:00pm</option>
                                                            <option value="13:15">1:15pm</option>
                                                            <option value="13:30">1:30pm</option>
                                                            <option value="13:45">1:45pm</option>
                                                            <option value="14:00">2:00pm</option>
                                                            <option value="14:15">2:15pm</option>
                                                            <option value="14:30">2:30pm</option>
                                                            <option value="14:45">2:45pm</option>
                                                            <option value="15:00">3:00pm</option>
                                                            <option value="15:15">3:15pm</option>
                                                            <option value="15:30">3:30pm</option>
                                                            <option value="15:45">3:45pm</option>
                                                            <option value="16:00">4:00pm</option>
                                                            <option value="16:15">4:15pm</option>
                                                            <option value="16:30">4:30pm</option>
                                                            <option value="16:45">4:45pm</option>
                                                            <option value="17:00">5:00pm</option>
                                                            <option value="17:15">5:15pm</option>
                                                            <option value="17:30">5:30pm</option>
                                                            <option value="17:45">5:45pm</option>
                                                            <option value="18:00">6:00pm</option>
                                                            <option value="18:15">6:15pm</option>
                                                            <option value="18:30">6:30pm</option>
                                                            <option value="18:45">6:45pm</option>
                                                            <option value="19:00">7:00pm</option>
                                                            <option value="19:15">7:15pm</option>
                                                            <option value="19:30">7:30pm</option>
                                                            <option value="19:45">7:45pm</option>
                                                            <option value="20:00">8:00pm</option>
                                                            <option value="20:15">8:15pm</option>
                                                            <option value="20:30">8:30pm</option>
                                                            <option value="20:45">8:45pm</option>
                                                            <option value="21:00">9:00pm</option>
                                                            <option value="21:15">9:15pm</option>
                                                            <option value="21:30">9:30pm</option>
                                                            <option value="21:45">9:45pm</option>
                                                            <option value="22:00">10:00pm</option>
                                                            <option value="22:15">10:15pm</option>
                                                            <option value="22:30">10:30pm</option>
                                                            <option value="22:45">10:45pm</option>
                                                        </select>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        <img src="./img/icon_person.png" alt="person"/>
                                                        Number of person:
                                                    </td>
                                                    <td colspan="3">
                                                        <input type="number" id="person" min="1" max="50"  value="2" name="person" id="person" />
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td>
                                        <input class = "button button_large_style" type="submit" value="Reserve" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>

                </div>
            </div>
            <div class="panel">
                <div class="panel_title">Information</div>
                <div class="panel_content">
                    <div class="rest_detail">
                        <?php
                        $result = php_query_restaurant_detail($_GET['rest_id']);
                        ?>
                        <img src="./img/restaurant/<?php echo $result['search_result_rest_id']; ?>/1.jpg" class="rest_detail_thumbnail" alt="thumbnail"/>
                        <div class="rest_detail_name">
                            <?php
                            echo $result['search_result_rest_name'];
                            ?>
                        </div>
                        <div class="rest_detail_tel"><img src="img/icon_tel.png" alt="tel"/>
                            <?php
                            echo $result['search_result_rest_tel'];
                            ?>                        
                        </div>
                        <div class="rest_detail_address"><img src="img/icon_location.png" alt="location"/>

                            <?php
                            echo $result['search_result_rest_address'];
                            ?>     

                        </div>
                        <div class="rest_detail_rank"><img src="img/icon_rank.png" alt="rank"/>
                            <img src="./img/rank/rank_<?php echo $result['search_result_rest_rank']; ?>.png" alt="rank"/>
                        </div>
                        <div class="rest_detail_desc">
                            <h4>Description:</h4>
                            <p>
                                <?php
                                echo $result['search_result_rest_description'];
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
