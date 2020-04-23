<?php
require_once('php_func/session_stub.php');
php_session_start();
php_auth_restaurant_only();

require_once('php_func/manager_func_list.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Restaurant Reservation System</title>
        <link href="img/icon.ico" rel="icon" type="image/x-icon"/>

        <link type="text/css" rel="stylesheet" href="./css/general.css" >
        <link type="text/css" rel="stylesheet" href="./css/resv_report.css" >
    </head>

    <body>

        <?php
        $result = php_gen_report($_GET['date']);
        if (strcmp($result, 'success') != 0) {
            echo $result['status'];
        }
        ?>

    </body>
</html>