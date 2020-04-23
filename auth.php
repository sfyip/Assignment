<?php
require_once('php_func/session_stub.php');
php_session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['func_name']) {
        case "login":
            $result == "undefined";
            $userType = $_POST['user_type'];
            if($userType === "customer")
            {
                $result = php_session_customer_login($_POST['username'], $_POST['password']);
            }
            else if($userType === "restaurant")
            {
                $result = php_session_restaurant_login($_POST['username'], $_POST['password']);
            }
           else if($userType === "webadmin")
            {
                $result = php_session_webadmin_login($_POST['username'], $_POST['password']);
            }
            else
            {
                 header('Location:'.URL.'auth_failed.php?reason=' . htmlentities("user type invalid"));
                 return;
            }
            
            if ($result == "success") {
                if($userType === "webadmin" )
                {
                    header('Location:'.URL.'webadmin_index.php');
                }
                else
                {
                    header('Location:'.URL.'index.php');
                }
            } else {
                header('Location:'.URL.'auth_failed.php?reason=' . htmlentities($result));
            }

            //echo $result;
            //echo "s:group".$_SESSION['group'];
            return;

        case "logout":
            php_session_logout();
            header('Location:'.URL.'index.php');
            //echo "s:group".$_SESSION['group'];
            return;

        default:
            return;
    }
} else {

    switch ($_GET['func_name']) {
        case "logout":
            php_session_logout();
            header('Location:'.URL.'index.php');
            //echo "s:group".$_SESSION['group'];
            return;
        default:
            return;
    }
}
?>