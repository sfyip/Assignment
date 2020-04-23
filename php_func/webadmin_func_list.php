<?php

require_once('session_stub.php');
php_session_start();
php_auth_webadmin_only();

require_once('database_stub.php');
require_once('check_variable_format_stub.php');

function php_edit_restaurant($restId, $rank) {

    //Lookup current status in the table.
    //If it is confirmed HEX(response) = 2, return immediately.
    //It it is decline/pending, update the table

    if (!php_check_rank_format($rank)) {
        return 'Rank format incorrect';
    }

    php_database_connect();

    //Use resvId to look up the table, only the same restaurant id in the reservation table can further continue.. 
    //prevent spoofing ( customer ID spoofing )
    $safe_restId = GetSQLValueString($restId, 'int');
    $safe_rank = GetSQLValueString($rank, 'int');

    $query = sprintf("UPDATE restaurant SET rank = %s WHERE id = %s", $safe_rank, $safe_restId);
    $rs = php_database_query($query);
    if (empty($rs)) {
        return 'Failed to update the value';
    }

    return 'success';
}

function php_change_profile($oldPassword, $newPassword) {

    //verify the password is correct
    //update the table

    $uid = php_get_uid();
    $username = php_get_username();

//    $changePassword = empty($newPassword) ? (false) : (true);

    if (!php_check_password_format($oldPassword)) {
        return 'Old password format incorrect';
    }

 //   if ($changePassword) {
        if (!php_check_password_format($newPassword)) {
            return 'New password format incorrect';
        }
//    }

    php_database_connect();

    $hashOldPassword = hash('sha256', $username . $oldPassword . WEBADMIN_PASSWD_SALT);

    $safe_uid = GetSQLValueString($uid, 'int');
    $safe_hashOldPassword = GetSQLValueString($hashOldPassword, 'text');

    $query = sprintf("SELECT id FROM webadmin WHERE id = %s AND password = %s", $safe_uid, $safe_hashOldPassword);
    $rs = php_database_query($query);
    if (mysql_num_rows($rs) == 0) {
        return 'Password not matched';
    }

 //   if ($changePassword) {
        $hashNewPassword = hash('sha256', $username . $newPassword . WEBADMIN_PASSWD_SALT);
        $safe_hashNewPassword = GetSQLValueString($hashNewPassword, 'text');
        $query = sprintf("UPDATE webadmin SET password = %s WHERE id = %s", $safe_hashNewPassword, $safe_uid);
//    } else {
//        $query = sprintf("UPDATE webadmin SET ??? WHERE id = %s", ???, $safe_uid);
//    }
    $rs = php_database_query($query);
    if (empty($rs)) {
        return 'Cannot update the user profile';
    }

    return 'success';
}

function php_get_profile_info() {
    
    //Because web admin do not have any additional information except password saved in database.
    //In this case, just test the database connection only.
    $returnResult['id'] = php_get_uid();
    $returnResult['status'] = 'success';
    return $returnResult;
    
    /*
    $uid = php_get_uid();

    $returnResult['status'] = 'undefined';

    php_database_connect();

    $safe_uid = GetSQLValueString($uid, 'int');

    $query = sprintf("SELECT id FROM webadmin WHERE id = %s", $safe_uid);
    $rs = php_database_query($query);
    $userInfo = mysql_fetch_array($rs);
    if (empty($userInfo)) {
        $returnResult['status'] = 'Userinfo not found';
        return $returnResult;
    }
    
    $returnResult['id'] = $userInfo['id'];
    
    $returnResult['status'] = 'success';
    return $returnResult;
    */
    
}

switch ($_POST['func_name']) {
    case "change_profile":
        $result = php_change_profile($_POST['oldPassword'], $_POST['newPassword']);
        if ($result == 'success') {
            header('Location:'.URL.'operation_success.php');
        } else {
            header('Location:'.URL.'operation_failed.php?reason=' . htmlentities($result));
        }
        return;
    case "edit_restaurant":
        echo php_edit_restaurant($_POST['rest_id'], $_POST['rank']);
        return;
        
    default:
        break;
}

?>