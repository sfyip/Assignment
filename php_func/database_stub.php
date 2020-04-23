<?php

  //For CITYU mysql
  define("MYSQL_HOSTNAME", "courses");
  define("MYSQL_USER", "sfyip2");
  define("MYSQL_PASSWORD", "sfyip2abcd");
  define("MYSQL_DATABASE", "db_sfyip2");


/*
//For HOME mysql
define("MYSQL_HOSTNAME", "192.168.1.101");
define("MYSQL_USER", "yip");
define("MYSQL_PASSWORD", "q12we34rtt");
define("MYSQL_DATABASE", "db_sfyip2");
*/
//=================================================================

define("CUSTOMER_PASSWD_SALT", 'IamSalt1234!@#$%^&*()');
define("RESTAURANT_PASSWD_SALT", 'IamSalt2468!@#$%^&*()');
define("WEBADMIN_PASSWD_SALT", 'IamSalt9876!@#$%^&*()');

function php_database_connect() {
    if (empty($GLOBALS['dbConnectionStatus'])) {
        $bd = mysql_connect(MYSQL_HOSTNAME, MYSQL_USER, MYSQL_PASSWORD) or trigger_error(mysql_error(), E_USER_ERROR);
        mysql_select_db(MYSQL_DATABASE, $bd) or trigger_error(mysql_error(), E_USER_ERROR);
        $GLOBALS['dbConnectionStatus'] = true;
    }
}

//it is not necessary to call this function, because it will close the database when the PHP script ends.
function _php_database_close() {
    mysql_close();
    unset($GLOBALS['dbConnectionStatus']);
}

/*
  NO RECORD DELETION!!!
 */

function php_database_query($queryString) {
    //echo $queryString;
    
    $result = mysql_query($queryString);
    if(!result)
    {
        die('Cannot query the database');
    }
    //var_dump($result);
    return $result;
}

function GetSQLValueString($value, $type) {
    // Stripslashes
    if (get_magic_quotes_gpc()) {
        $value = stripslashes($value);
    }
    // Quote if not a number
    if (!is_numeric($value)) {
        $value = "'" . mysql_real_escape_string($value) . "'";
    }
    return $value;
}

?>