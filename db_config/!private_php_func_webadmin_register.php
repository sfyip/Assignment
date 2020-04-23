<?php

require_once('database_stub.php');
require_once('check_variable_format_stub.php');

function _php_is_user_exist($safe_username) {
    //The caller function should unsure the function parameter is safe and trusted.
    //check the username to ensure that it is not exist. 
    php_database_connect();
    
    $query = sprintf("SELECT username FROM webadmin WHERE username= %s", $safe_username);
    $rs = php_database_query($query);
    if (mysql_num_rows($rs) > 0) {
        return true;
    }

    return false;
}

/*
  $username = "webadminid1";
  $password = "123456";
 */

 $_POST['username'] = "webadminid1";
 $_POST['password'] = "123456";
 
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


//Perform password hashing
//Sine the username is unique, the hash result is different even the password for the users are the same,
$hashPassword = hash('sha256', $_POST['username'] . $_POST['password'] . WEBADMIN_PASSWD_SALT);

php_database_connect();

$safe_username = GetSQLValueString($_POST['username'], 'text');
$safe_hashPassword = GetSQLValueString($hashPassword, 'text');

//Check if the user is alreay exist.
if (_php_is_user_exist($safe_username)) {
    echo 'The username is already in use.';
    return false;
}

//Insert into table
$query = sprintf("INSERT INTO webadmin (username, password, activate) VALUES (%s, %s, b'1')", $safe_username, $safe_hashPassword);
$rs = php_database_query($query);
if (empty($rs)) {
    echo 'Cannot insert the record';
    return false;
}

echo 'Register successfully';
return true;
?>