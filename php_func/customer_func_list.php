<?php

require_once('session_stub.php');
php_session_start();
php_auth_customer_only();

require_once('database_stub.php');
require_once('check_variable_format_stub.php');

function php_show_first_upcoming_reservation() {
    //get the output query from the table
    $uid = php_get_uid();

    php_database_connect();

    $safe_uid = GetSQLValueString($uid, 'int');

    $query = sprintf("SELECT rest_id, DATE(timeslot) AS resv_date from resv WHERE cust_id = %s AND HEX(response) = 2 AND timeslot >= NOW() ORDER BY timeslot ASC LIMIT 1", $safe_uid);
    $rs = php_database_query($query);
    if (mysql_num_rows($rs) == 0) {
        return 'You have no upcoming reservation being accepted.';
    }
    $resvInfo = mysql_fetch_array($rs);

    $safe_restId = GetSQLValueString($resvInfo['rest_id'], 'int');
    //Only look up the restaurant name of the first record, 
    $query = sprintf("SELECT fullname FROM restaurant WHERE id = %s", $safe_restId);
    $rs = php_database_query($query);
    $restInfo = mysql_fetch_array($rs);
    if (empty($restInfo)) {
        return 'Cannot lookup the restaurant.';
    }

    return 'This is just a friendly reminder that you need to go to ' . $restInfo['fullname'] . ' on ' . $resvInfo['resv_date'] . '.';
}

function php_show_reservation() {
    $uid = php_get_uid();

    $mergeHtmlResponse = '';

    php_database_connect();
    $safe_uid = GetSQLValueString($uid, 'int');

    $queryFuture = sprintf("SELECT id, rest_id, DATE_FORMAT(timeslot, '%%d, %%M, %%Y %%h:%%i%%p') AS 'datetime_timeslot', person, special_request, HEX(response) AS hex_response, HEX(receive_email) AS 'hex_receive_email' from resv WHERE cust_id = %s AND timeslot >= NOW() ORDER BY timeslot DESC", $safe_uid);
    $mergeHtmlResponse .= _php_show_reservation_stub($queryFuture, true);

    $queryPast = sprintf("SELECT id, rest_id, DATE_FORMAT(timeslot, '%%d, %%M, %%Y %%h:%%i%%p') AS 'datetime_timeslot', person, special_request, HEX(response) AS hex_response, HEX(receive_email) AS 'hex_receive_email' from resv WHERE cust_id = %s AND timeslot < NOW() ORDER BY timeslot DESC LIMIT 5", $safe_uid);
    $mergeHtmlResponse .= _php_show_reservation_stub($queryPast, false);

    return $mergeHtmlResponse;
}

function _php_show_reservation_stub($query, $isFuture) {
    /*
      <tr><td class="resv_item_time">
      16, Jul, 2014 1:30pm
      </td>
      <td class="resv_item_person">
      4
      </td>
      <td class="resv_item_restaurant_name">
      Michael American Food
      </td>
      <td class="resv_item_restaurant_detail">
      <input type="button" class="button" value="Detail" onclick="showRestaurantInfoMsgBoxBtnHandler('1236');" />
      </td>
      <td class="resv_item_msg">
      </td>
      <td class="resv_item_id">
      1236-50
      </td>
      <td class = "resv_item_email">
      <img src="./img/accept.png" alt="accept" />
      </td>
      <td class="resv_item_response">
      <img src="./img/accept.png" alt="accept" />
      </td>
      </tr>
     */
    $rs = php_database_query($query);
    if (mysql_num_rows($rs) == 0) {
        if ($isFuture) {
            return '<tr colspan="8" class="resv_item_is_upcoming"><td>You have no upcoming reservation.</td></tr>';
        } else {
            return '<tr colspan="8" class="resv_item_is_past"><td>History: empty</td></tr>';
        }
    }
    if ($isFuture) {
        $html1 = '<tr class="resv_item_is_upcoming"><td class="resv_item_time">';
    } else {
        $html1 = '<tr class="resv_item_is_past"><td class="resv_item_time">';
    }
    $html2 = '</td><td class="resv_item_person">';
    $html3 = '</td><td class="resv_item_restaurant_name">';
    $html4 = '</td><td class="resv_item_restaurant_detail"><input type="button" class="button" value="Detail" onclick="showRestaurantInfoMsgBoxBtnHandler(\'';
    $html5 = '\');" /></td><td class="resv_item_msg">';
    $html6 = '</td><td class="resv_item_id">';
    $html7 = '</td><td class="resv_item_email"><img src="./img/';
    $html8 = '.png" alt="respond" /></td><td class="resv_item_response"><img src="./img/';
    $html9 = '.png" alt="respond" /></td></tr>';

    while ($resvInfo = mysql_fetch_array($rs)) {
        //Only look up the restaurant name of the first record, 
        $safe_restId = GetSQLValueString($resvInfo['rest_id'], int);
        $query = sprintf("SELECT fullname FROM restaurant WHERE id = %s", $safe_restId);
        $restRs = php_database_query($query);
        $restInfo = mysql_fetch_array($restRs);
        if (empty($restInfo)) {
            return '<tr colspan="8"><td>Cannot lookup the restaurant name</td></tr>';
        }

        switch (intval($resvInfo['hex_response'])) {
            case 0:
                $str_response = 'pending';
                break;
            case 1:
                $str_response = 'decline';
                break;
            case 2:
                $str_response = 'accept';
                break;
            default:
                return '<tr colspan="8"><td>Error when parsing response field</td></tr>';
        }

        if (intval($resvInfo['hex_receive_email'])) {
            $str_receive_email = 'accept';
        } else {
            $str_receive_email = 'decline';
        }

        $resvHtmlItem = $html1 . $resvInfo['datetime_timeslot'] . $html2 . $resvInfo['person'] . $html3 . $restInfo['fullname'] . $html4 . $resvInfo['id'] . $html5 . $resvInfo['special_request'] . $html6 . $resvInfo['id'] . $html7 . $str_receive_email . $html8 . $str_response . $html9;

        $mergeHtmlResponse .= $resvHtmlItem;
    }
    return $mergeHtmlResponse;
}

function php_show_restaurant_info($resvId) {

    $returnStatus['status'] = 'undefined';

    if (!php_check_resv_id_format($resvId)) {
        $returnStatus['status'] = 'Resveration ID format incorrect';
        return $returnStatus;
    }

    $uid = php_get_uid();
    php_database_connect();

    //Use resvId to look up the table
    //prevent spoofing ( customer ID spoofing )

    $safe_resvId = GetSQLValueString($resvId, 'int');
    $safe_uid = GetSQLValueString($uid, 'int');

    $query = sprintf("SELECT rest_id FROM resv WHERE id = %s AND cust_id = %s", $safe_resvId, $safe_uid);
    $rs = php_database_query($query);
    $resvInfo = mysql_fetch_array($rs);
    if (empty($resvInfo)) {
        $returnStatus['status'] = 'Cannot get the reservation info';
        return $returnStatus;
    }

    #######################################################################
    $safe_restId = GetSQLValueString($resvInfo['rest_id'], 'int');
    $query = sprintf("SELECT fullname, address, tel FROM restaurant WHERE id = %s", $safe_restId);
    $rs = php_database_query($query);
    $restInfo = mysql_fetch_array($rs);
    if (empty($restInfo)) {
        $returnStatus['status'] = 'Cannot get the restaurant info';
        return $returnStatus;
    }

    $xmlElement['name'] = $restInfo['fullname'];
    $xmlElement['address'] = $restInfo['address'];
    $xmlElement['tel'] = $restInfo['tel'];

    $responseXML = "<restaurant><name>" . $xmlElement['name'] . "</name><address>" . $xmlElement['address'] . "</address><tel>" . $xmlElement['tel'] . "</tel></restaurant>";

    echo $responseXML;
    $returnStatus['status'] = 'success';

    return $returnStatus;
}

function php_reserve_table($restaurantID, $person, $date, $time, $specialRequest, $receiveEmail) {
    //verify the session is valid
    //verify the restaurant ID is valid


    $returnResult['status'] = "undefined";
    $returnResult['id'] = "";
    $returnResult['datetime'] = $date . ' ' . $time;
    $returnResult['rest_name'] = "(Abort)";
    $returnResult['rest_addr'] = "(Abort)";
    $returnResult['rest_tel'] = "(Abort)";
    $returnResult['username'] = "(Abort)";
    $returnResult['person'] = intval($person);
    $returnResult['special_request'] = (strlen($specialRequest) > 0) ? ($specialRequest) : ('(none)');
    $returnResult['receive_email'] = isset($receiveEmail) ? ('YES') : ('NO');


    if (!php_check_rest_id_format($restaurantID)) {
        $returnResult['status'] = 'Restaurant ID format incorrect';
        return $returnResult;
    }

    //verify the person format is valid
    if (!php_check_resv_person_format($person)) {
        $returnResult['status'] = 'Person format incorrect';
        return $returnResult;
    }

    //verify the datetime format is valid
    if (!php_check_convert_resv_date($date, $d, $m, $y)) {
        $returnResult['status'] = 'Date format incorrect';
        return $returnResult;
    }

    if (!php_check_resv_time_format($time)) {
        $returnResult['status'] = 'Time format incorrect';
        return $returnResult;
    }

    //verify the special request format is valid
    if (!php_check_resv_special_request_format($specialRequest)) {
        $returnResult['status'] = 'Special request format incorrect';
        return $returnResult;
    }

    $uid = php_get_uid();

    php_database_connect();

    $safe_uid = GetSQLValueString($uid, 'int');
    $safe_restaurantID = GetSQLValueString($restaurantID, 'int');
    $safe_person = GetSQLValueString($person, 'int');
    $safe_specialRequest = GetSQLValueString($specialRequest, 'text');
    $safe_receiveEmail = GetSQLValueString(isset($receiveEmail) ? '1' : '0', 'bit');
    $safe_timeslot = GetSQLValueString($y . '-' . $m . '-' . $d . ' ' . $time . ':00', 'date');

    $query = sprintf("SELECT NOW() < %s AS date_is_match", $safe_timeslot);
    $rs = php_database_query($query);
    $dateInfo = mysql_fetch_array($rs);
    if (empty($dateInfo['date_is_match'])) {
        $returnResult['status'] = 'The datetime is earlier than database server one.';
        return $returnResult;
    }

    ########################################################################################################
    //Lookup the restaurant name
    $query = sprintf("SELECT fullname, address, tel FROM restaurant WHERE id = %s", $safe_restaurantID);
    $rs = php_database_query($query);
    $restInfo = mysql_fetch_array($rs);
    if (empty($restInfo)) {
        $returnResult['status'] = "Cannot find the restaurant name";
        return $returnResult;
    }
    $returnResult['rest_name'] = $restInfo['fullname'];
    $returnResult['rest_addr'] = $restInfo['address'];
    $returnResult['rest_tel'] = $restInfo['tel'];

    #######################################################################################################
    //Lookup the person name

    $query = sprintf("SELECT fullname FROM customer WHERE id = %s", $safe_uid);
    $rs = php_database_query($query);
    $userInfo = mysql_fetch_array($rs);
    if (empty($userInfo)) {
        $returnResult['status'] = "Cannot find the customer name";
        return $returnResult;
    }
    $returnResult['username'] = $userInfo['fullname'];

    #######################################################################################################
    # PREVENT DUPLICATE RESERVATE THE SAME RESTAURANT ON THAT DAY IF THAT RECORD STATE IS ACCEPTED OR PENDING.
    # IN OTHER WORDS, IF THE RESPONSE OF THE PREVIOUS RECORD IS DECLINED, THE CUSTOMER STILL CAN REQUEST TO RESERVE THE TABLE.
    $query = sprintf("SELECT id FROM resv WHERE (HEX(response)=0 OR HEX(response)=2) AND cust_id = %s AND rest_id = %s AND DATE(timeslot) = DATE(%s)", $safe_uid, $safe_restaurantID, $safe_timeslot);
    $rs = php_database_query($query);
    if (mysql_num_rows($rs) > 0) {
        $returnResult['status'] = "Cannot reserve the same restaurant on the same day";
        return $returnResult;
    }

    #######################################################################################################
    //Insert the record
    $query = sprintf("INSERT INTO resv (rest_id, cust_id, timeslot, person, special_request, receive_email, response) VALUES (%s, %s, %s, %s, %s, %s, b'00')", $safe_restaurantID, $safe_uid, $safe_timeslot, $safe_person, $safe_specialRequest, $safe_receiveEmail);
    $rs = php_database_query($query);
    $returnResult['id'] = mysql_insert_id();

    $returnResult['status'] = "The reservation request has been submitted successfully";
    return $returnResult;
}

function php_change_profile($oldPassword, $newPassword, $fullname, $email, $tel) {
    //verify the fullname is valid
    //verify the password is correct
    //verify the email format is correct
    //verify the tel format is correct
    //update the table

    $uid = php_get_uid();
    $username = php_get_username();

    $changePassword = empty($newPassword) ? (false) : (true);

    if (!php_check_password_format($oldPassword)) {
        return 'Old password format incorrect';
    }

    if ($changePassword) {
        if (!php_check_password_format($newPassword)) {
            return 'New password format incorrect';
        }
    }

    if (!php_check_fullname_format($fullname)) {
        return 'Fullname format incorrect';
    }

    if (!php_check_email_format($email)) {
        return 'Email format incorrect';
    }

    if (!php_check_tel_format($tel)) {
        return 'Tel format incorrect';
    }

    php_database_connect();

    $hashOldPassword = hash('sha256', $username . $oldPassword . CUSTOMER_PASSWD_SALT);

    $safe_uid = GetSQLValueString($uid, 'int');
    $safe_fullname = GetSQLValueString($fullname, 'text');
    $safe_email = GetSQLValueString($email, 'text');
    $safe_tel = GetSQLValueString($tel, 'text');
    $safe_hashOldPassword = GetSQLValueString($hashOldPassword, 'text');

    $query = sprintf("SELECT id FROM customer WHERE id = %s AND password = %s", $safe_uid, $safe_hashOldPassword);
    $rs = php_database_query($query);
    if (mysql_num_rows($rs) == 0) {
        return 'Password not matched';
    }

    if ($changePassword) {
        $hashNewPassword = hash('sha256', $username . $newPassword . CUSTOMER_PASSWD_SALT);
        $safe_hashNewPassword = GetSQLValueString($hashNewPassword, 'text');
        $query = sprintf("UPDATE customer SET password = %s, fullname = %s, email = %s, tel = %s WHERE id = %s", $safe_hashNewPassword, $safe_fullname, $safe_email, $safe_tel, $safe_uid);
    } else {
        $query = sprintf("UPDATE customer SET fullname = %s, email = %s, tel = %s WHERE id = %s", $safe_fullname, $safe_email, $safe_tel, $safe_uid);
    }
    $rs = php_database_query($query);
    if (empty($rs)) {
        return 'Cannot update the user profile';
    }

    return 'success';
}

function php_get_profile_info() {

    $uid = php_get_uid();

    $returnResult['status'] = 'undefined';
    $returnResult['fullname'] = '';
    $returnResult['email'] = '';
    $returnResult['tel'] = '';

    php_database_connect();

    $safe_uid = GetSQLValueString($uid, 'int');

    $query = sprintf("SELECT fullname, email, tel FROM customer WHERE id = %s", $safe_uid);
    $rs = php_database_query($query);
    $userInfo = mysql_fetch_array($rs);
    if (empty($userInfo)) {
        $returnResult['status'] = 'Userinfo not found';
        return $returnResult;
    }

    $returnResult['fullname'] = $userInfo['fullname'];
    $returnResult['email'] = $userInfo['email'];
    $returnResult['tel'] = $userInfo['tel'];

    $returnResult['status'] = 'success';
    return $returnResult;
}

switch ($_POST['func_name']) {
    case "change_profile":
        $result = php_change_profile($_POST['oldPassword'], $_POST['newPassword'], $_POST['fullname'], $_POST['email'], $_POST['tel']);
        if ($result == 'success') {
            header('Location:' . URL . 'operation_success.php');
        } else {
            header('Location:' . URL . 'operation_failed.php?reason=' . htmlentities($result));
        }
        return;

    case "show_restaurant_info":
        return php_show_restaurant_info($_POST['resv_id']);
        
    default:
        break;
}
?>