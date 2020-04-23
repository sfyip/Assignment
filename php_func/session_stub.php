<?php

require_once('check_variable_format_stub.php');
require_once('database_stub.php');

//For CITYU
define("URL", 'http://personal.cs.cityu.edu.hk/~sfyip2/');

//For HOME
//define("URL", 'http://192.168.1.101/');


function php_session_start()
{
    session_start();
}

function php_session_destroy() {
    session_destroy();
}

function php_session_customer_login($username, $password) {

    //Server side verification, prevent sql injection attack
    if (!php_check_username_format($username)) {
        return 'Username format incorrect';
    }

    if (!php_check_password_format($password)) {
        return 'Password format incorrect.';
    }

    php_database_connect();

    //check the username and password field is matched in the table
    $safe_username = GetSQLValueString($username, 'text');

    $query = sprintf("SELECT username FROM customer WHERE username=%s", $safe_username);
    $rs = php_database_query($query);
    if (mysql_num_rows($rs) != 1) {
        return 'Username is incorrect';
    }

    $hashPassword = hash('sha256', $username . $password . CUSTOMER_PASSWD_SALT);
    $safe_hashPassword = GetSQLValueString($hashPassword, 'text');

    $query = sprintf("SELECT id FROM customer WHERE username=%s AND password=%s", $safe_username, $safe_hashPassword);
    $rs = php_database_query($query);
    if (mysql_num_rows($rs) != 1) {
        return 'Password is incorrect.';
    }

    $userInfo = mysql_fetch_array($rs);

    //perform login
    $_SESSION['uid'] = $userInfo['id'];
    $_SESSION['username'] = $username;
    $_SESSION['group'] = "customer";
    return 'success';
}

function php_session_restaurant_login($username, $password) {

    //Server side verification, prevent sql injection attack
    if (!php_check_username_format($username)) {
        return 'Username format incorrect';
    }

    if (!php_check_password_format($password)) {
        return 'Password format incorrect.';
    }

    php_database_connect();

    //check the username and password field is matched in the table
    $safe_username = GetSQLValueString($username, 'text');

    $query = sprintf("SELECT username FROM restaurant WHERE username=%s", $safe_username);
    $rs = php_database_query($query);
    if (mysql_num_rows($rs) != 1) {
        return 'Username is incorrect';
    }

    $hashPassword = hash('sha256', $username . $password . RESTAURANT_PASSWD_SALT);
    $safe_hashPassword = GetSQLValueString($hashPassword, 'text');

    $query = sprintf("SELECT id FROM restaurant WHERE username=%s AND password=%s", $safe_username, $safe_hashPassword);
    $rs = php_database_query($query);
    if (mysql_num_rows($rs) != 1) {
        return 'Password is incorrect.';
    }

    $userInfo = mysql_fetch_array($rs);

    //perform login
    $_SESSION['uid'] = $userInfo['id'];
    $_SESSION['username'] = $username;
    $_SESSION['group'] = "restaurant";
    return 'success';
}

function php_session_webadmin_login($username, $password) {

    //Server side verification, prevent sql injection attack
    if (!php_check_username_format($username)) {
        return 'Username format incorrect';
    }

    if (!php_check_password_format($password)) {
        return 'Password format incorrect.';
    }

    php_database_connect();

    //check the username and password field is matched in the table
    $safe_username = GetSQLValueString($username, 'text');

    $query = sprintf("SELECT username FROM webadmin WHERE username=%s", $safe_username);
    $rs = php_database_query($query);
    if (mysql_num_rows($rs) != 1) {
        return 'Username is incorrect';
    }

    $hashPassword = hash('sha256', $username . $password . WEBADMIN_PASSWD_SALT);
    $safe_hashPassword = GetSQLValueString($hashPassword, 'text');

    $query = sprintf("SELECT id FROM webadmin WHERE username=%s AND password=%s", $safe_username, $safe_hashPassword);
    $rs = php_database_query($query);
    if (mysql_num_rows($rs) != 1) {
        return 'Password is incorrect.';
    }

    $userInfo = mysql_fetch_array($rs);

    //perform login
    $_SESSION['uid'] = $userInfo['id'];
    $_SESSION['username'] = $username;
    $_SESSION['group'] = "webadmin";
    return 'success';
}

function php_session_logout() {
    //perform logout
    // Unset all session values 
    //$_SESSION = array();
    $_SESSION['group'] = NULL;
    unset($_SESSION['group']);
    session_unset();

    session_destroy();
    session_regenerate_id(true);

    return 'success';
}

function php_verify_user_match_customer() {
    return ($_SESSION['group'] === "customer");
}

function php_verify_user_match_restaurant() {
    return ($_SESSION['group'] === "restaurant");
}

function php_verify_user_match_webadmin() {
    return ($_SESSION['group'] === "webadmin");
}

function php_auth_customer_only() {
    //check the session id is valid
    if($_SESSION['group'] !== "customer")
    {
         header('Location:'.URL.'auth_failed.php?reason='.htmlentities('Only for authorized people'));
    }
}

function php_auth_restaurant_only() {
    //check the session id is valid
    if($_SESSION['group'] !== "restaurant")
    {
         header('Location:'.URL.'auth_failed.php?reason='.htmlentities('Only for authorized people'));
    }
}

function php_auth_webadmin_only() {
    //check the session id is valid
    if($_SESSION['group'] !== "webadmin")
    {
         header('Location:'.URL.'auth_failed.php?reason='.htmlentities('Only for authorized people'));
    }
}

function php_get_username() {
    return $_SESSION['username'];
}

function php_get_uid() {
    return $_SESSION['uid'];
}

function php_gen_navbar() {
    //echo "s:group".$_SESSION['group'];
    if (php_verify_user_match_restaurant()) {

        echo '<div class = "navbar_item nav_item_my_brand"><a href="index.php"><img src="./img/brand.png" alt="brand"/>Restaurant Reservation System</a></div>
            
            <div class = "navbar_item"><a href="manager_resv_list.php"><img src="./img/icon_restaurant.png" alt="manager_resv_list"/>Reservation List</a></div>

            <div class = "navbar_item"><a href="contact.php"><img src="./img/icon_contact.png" alt="contact us"/>Contact us</a></div>
            <div class = "navbar_item"><a href="manager_profile.php"><img src="./img/icon_profile.png" alt="profile"/>Profile</a></div>
            <div class = "navbar_item"><a href="auth.php?func_name=logout"><img src="./img/icon_logout.png" alt="logout"/>('.php_get_username().') Logout</a></div>';
    } else if (php_verify_user_match_customer()) {
        echo '<div class = "navbar_item nav_item_my_brand"><a href="index.php"><img src="./img/brand.png" alt="brand"/>Restaurant Reservation System</a></div>
        
            <div class = "navbar_item""><a href="customer_resv_list.php"><img src="./img/icon_restaurant.png" alt="customer_resv_list"/>Reservation List</a></div>
            
            <div class = "navbar_item"><a href="contact.php"><img src="./img/icon_contact.png" alt="contact us"/>Contact us</a></div>
            <div class = "navbar_item"><a href="customer_profile.php"><img src="./img/icon_profile.png" alt="profile"/>Profile</a></div>
            <div class = "navbar_item"><a href="auth.php?func_name=logout"><img src="./img/icon_logout.png" alt="logout"/>('.php_get_username().') Logout</a></div>';
    }  else {    //guest
        echo '<div class = "navbar_item nav_item_my_brand"><a href="index.php"><img src="./img/brand.png" alt="brand"/>Restaurant Reservation System</a></div>

            <div class = "navbar_item"><a href="contact.php"><img src="./img/icon_contact.png" alt="contact us"/>Contact us</a></div>

            <div class = "navbar_item" id="navbar_login"><a href="#" onclick="loginPanel_toogle();"><img src="./img/icon_login.png" alt="login"/>Login</a></div>
            <div class = "navbar_item" id="navbar_register"><a href="register.php"><img src="./img/icon_register.png" alt="register"/>Register</a></div>';
    }
}

function php_gen_navbar_webadmin_page()
{
    if (php_verify_user_match_webadmin()) {
        echo '<div class = "navbar_item nav_item_my_brand"><a href="index.php"><img src="./img/brand.png" alt="brand"/>Restaurant Reservation System</a></div>
        
            <div class = "navbar_item"><a href="webadmin_rest_list.php"><img src="./img/icon_restaurant.png" alt="webadmin_rest_list"/>Restaurant List</a></div>
         
            <div class = "navbar_item"><a href="contact.php"><img src="./img/icon_contact.png" alt="contact us"/>Contact us</a></div>
            <div class = "navbar_item"><a href="webadmin_profile.php"><img src="./img/icon_profile.png" alt="profile"/>Profile</a></div>
            <div class = "navbar_item"><a href="auth.php?func_name=logout"><img src="./img/icon_logout.png" alt="logout"/>('.php_get_username().') Logout</a></div>';
    }  else {    //guest
        echo '<div class = "navbar_item nav_item_my_brand"><a href="index.php"><img src="./img/brand.png" alt="brand"/>Restaurant Reservation System</a></div>

            <div class = "navbar_item"><a href="contact.php"><img src="./img/icon_contact.png" alt="contact us"/>Contact us</a></div>

            <div class = "navbar_item" id="navbar_login"><a href="#" onclick="loginPanel_toogle();"><img src="./img/icon_login.png" alt="login"/>Login</a></div>';
    }
}

?>