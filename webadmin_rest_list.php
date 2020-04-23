<?php
require_once('php_func/session_stub.php');
php_session_start();
php_auth_webadmin_only();

require_once ('php_func/search_rest_func.php');
$returnResult = php_search_table($_GET["district"], $_GET["restaurant"], $_GET["page"], 1);
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
        <link type="text/css" rel="stylesheet" href="./css/rest_list.css" >
        <link type="text/css" rel="stylesheet" href="./css/msgbox.css" >

        <script type="text/javascript" src="./js/utility.js"></script>
        <script type="text/javascript" src="./js/input_verification.js"></script>
        <script type="text/javascript" src="./js/login_panel_lib.js"></script>
        <script type="text/javascript" src="./js/search_panel.js"></script>

        <script type="text/javascript" src="./js/ajax_update_restaurant_list.js"></script>
        <script type="text/javascript" src="./js/ajax_webadmin_edit_restaurant.js"></script>

        <script type="text/javascript">
            function Init()
            {
                //Set the content of search FORM element to deticated GET variable
                document.getElementById("district").value = '<?php echo php_url_get_district_var(); ?>';
                document.getElementById("restaurant_txt").value = '<?php echo php_url_get_restaurant_var(); ?>';

                ajax_updateRestaurantList();

                //When the restaurant list is selected, the selected list content will be copied to text box.
                document.getElementById("restaurant_list").addEventListener("change", SearchBar_updateRestaurantTextBox, false);

                //When the district is selected, it will update the restaurant select list using ajax.
                document.getElementById("district").addEventListener("change", ajax_updateRestaurantList, false);

                document.getElementById("editMsgBox_OKBtn").addEventListener("click", editMsgBox_OKBtnHandler, false);
                document.getElementById("editMsgBox_CancelBtn").addEventListener("click", editMsgBox_CancelBtnHandler, false);

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
        <div id="editMsgBox" class="panel msgbox invisible_style">
            <div class="panel_title">Edit Restaurant</div>
            <div class="panel_content">
                <img class="msgbox_icon" src="./img/msgbox/icon_information.png" alt="info" />
                Rank (1-5): &nbsp; <input type="number" id="restaurant_info_rank" min="0" max="5"/>
                <div class="msgbox_btn_place_holder">
                    <input type="button" id="editMsgBox_OKBtn" value="OK" class="button msgbox_btn2" />
                    <input type="button" id="editMsgBox_CancelBtn" value="Cancel" class="button msgbox_btn1" />
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
                <div class="panel_title">Find Restaurant</div>
                <div class="panel_content">

                    <form class ="rest_search_bar" name="searchForm" action="webadmin_rest_list.php" method="get">
                        <table>
                            <tbody>
                                <tr>
                                    <td>
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <img src="./img/icon_location.png" alt="location"/>
                                                        District:
                                                    </td>
                                                    <td>
                                                        <select name="district" id="district">
                                                            <option value="10">=== Hong Kong Island ===</option>
                                                            <option value="11">Central and Western</option>
                                                            <option value="12">Eastern</option>
                                                            <option value="13">Southern</option>
                                                            <option value="14">Wan Chai</option>
                                                            <option value="20">=== Kowloon ===</option>
                                                            <option value="21">Sham Shui Po</option>
                                                            <option value="22">Kowloon City</option>
                                                            <option value="23">Kwun Tong</option>
                                                            <option value="24">Wong Tai Sin</option>
                                                            <option value="25">Yau Tsim Mong</option>
                                                            <option value="30">=== New Territories ===</option>
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
                                                    <td>
                                                        Restaurant:
                                                    </td>
                                                    <td>
                                                        <div class="select-editable"> 
                                                            <input type="text" id="restaurant_txt" name="restaurant" value="" />                                                            
                                                            <select id="restaurant_list">
                                                                <option value=""></option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td>
                                        <input class = "button button_large_style" type="submit" value="Search" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </form>
                </div>
            </div>
            <div class="panel">
                <div class="panel_title">Search Result</div>
                <div class="panel_content">
                    <div class="rest_list">
                        <!--
                        DUMMY SEARCH RESULT SAMPLE!
                        <div class="rest_list_item">
                            <a href="rest_detail.php?rest_id=1">
                                <img src="./img/restaurant/1/1.jpg" class="rest_list_item_thumbnail" alt="thumbnail" />
                                <div class="rest_list_item_name">ADA Steak House</div>
                                <div class="rest_list_item_district">Causeway Bay</div>
                                <div class="rest_list_item_rank"><img src="./img/rank/rank_5.png" alt="rank" /> </div>
                            </a>
                        </div>
                        -->
                        <?php
                        echo $returnResult['resultHtml'];
                        ?>
                    </div>
                    <br/>
                    <?php echo $returnResult['prevPageHtml']; ?>&nbsp;&nbsp;
                    <?php echo $returnResult['nextPageHtml']; ?>
                </div>
            </div>
        </div>
    </body>
</html>
