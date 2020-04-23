<?php

require_once('database_stub.php');
require_once('check_variable_format_stub.php');

function _php_is_user_exist($safe_username, $safe_email) {
    //The caller function should unsure the function parameter is safe and trusted.
    //check the username and email address to ensure that it is not exist. 
    php_database_connect();

    $query = sprintf("SELECT username FROM restaurant WHERE username= %s", $safe_username);
    $rs = php_database_query($query);
    if (mysql_num_rows($rs) > 0) {
        return true;
    }
    $query = sprintf("SELECT email FROM restaurant WHERE email= %s", $safe_email);
    $rs = php_database_query($query);
    if (mysql_num_rows($rs) > 0) {
        return true;
    }

    return false;
}

/*
$_POST['username'] = "restid1";
$_POST['password'] = "123456";
$_POST['email'] = "restid1@gmail.com";
$_POST['tel'] = "23345678";
$_POST['fullname'] = "ADA Steak House";
$_POST['district'] = "11";
$_POST['address'] = "17 Plaza, Causeway Bay";
$_POST['description'] = "Steak House";
*/

//The variable will be passed by POST method,
//After finish the registration process,
//It will return the message to the client
//(Even the registration result is failed...).

//Server side verification, prevent sql injection attack
if (!php_check_username_format($_POST['username'])) {
    echo 'Username format incorrect';
    return false;
}

if (!php_check_password_format($_POST['password'])) {
    echo 'Password format incorrect.';
    return false;
}

if (!php_check_email_format($_POST['email'])) {
    echo 'Email format incorrect.';
    return false;
}

if (!php_check_tel_format($_POST['tel'])) {
    echo 'Tel format incorrect.';
    return false;
}

if (!php_check_fullname_format($_POST['fullname'])) {
    echo 'Fullname format incorrect.';
    return false;
}

//check district
if(!php_check_district_format($_POST['district'])) {
    echo 'District format incorrect.';
    return false;
}

//Check address
if(!php_check_address_format($_POST['address'])) {
    echo 'Address format incorrect.';
    return false;
}

//Check description
if(!php_check_description_format($_POST['description'])) {
    echo 'Description format incorrect.';
    return false;
}


//Perform password hashing
//Sine the username is unique, the hash result is diffent even the password for the users are the same,
$hashPassword = hash('sha256', $_POST['username'] . $_POST['password'] . RESTAURANT_PASSWD_SALT);

php_database_connect();

$safe_username = GetSQLValueString($_POST['username'], 'text');
$safe_fullname = GetSQLValueString($_POST['fullname'], 'text');
$safe_hashPassword = GetSQLValueString($hashPassword, 'text');
$safe_email = GetSQLValueString($_POST['email'], 'text');
$safe_tel = GetSQLValueString($_POST['tel'], 'text');
$safe_num_district = GetSQLValueString($_POST['district'], 'int');
$safe_address = GetSQLValueString($_POST['address'], 'text');
$safe_description = GetSQLValueString($_POST['description'], 'text');

//Check if the user is alreay exist.
if (_php_is_user_exist($safe_username, $safe_email)) {
    echo 'The username / email is already in use.';
    return false;
}

//Insert into table
$query = sprintf("INSERT INTO restaurant (username, password, district, address, fullname, email, tel, description, rank, activate) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, 5 , b'1')", $safe_username, $safe_hashPassword, $safe_num_district, $safe_address, $safe_fullname, $safe_email, $safe_tel, $safe_description);
$rs = php_database_query($query);
if (empty($rs)) {
    echo 'Cannot insert the record';
    return false;
}

echo 'Register successfully';
return true;
?>