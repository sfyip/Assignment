<?php

require_once('session_stub.php');
php_session_start();
php_auth_restaurant_only();

require_once('database_stub.php');
require_once('check_variable_format_stub.php');

function php_show_reservation_list($date) {
    //check the session ID is valid

    $returnStatus['status'] = 'undefined';

    $uid = php_get_uid();

    //check the date format is valid
    if (!php_check_convert_resv_date($date, $d, $m, $y)) {
        $returnResult['status'] = 'Date format incorrect';
        return $returnResult;
    }

    php_database_connect();

    //Lookup restaurant ID to get the query string from the table
    $safe_uid = GetSQLValueString($uid, 'int');
    $safe_timeslot = GetSQLValueString($y . '-' . $m . '-' . $d, 'date');

    $query = sprintf("SELECT id, cust_id, TIME(timeslot) AS time_timeslot, person, special_request, HEX(response) AS hex_response FROM resv WHERE rest_id = %s AND DATE(timeslot) = %s", $safe_uid, $safe_timeslot);
    $rs = php_database_query($query);

    $responseXML = "";
    while ($resvInfo = mysql_fetch_array($rs)) {
        $safe_custId = GetSQLValueString($resvInfo['cust_id']);
        $query = sprintf("SELECT fullname FROM customer WHERE id = %s", $safe_custId);
        $cust_rs = php_database_query($query);

        $custInfo = mysql_fetch_array($cust_rs);
        $xmlElement['time'] = $resvInfo['time_timeslot'];
        $xmlElement['person'] = $resvInfo['person'];
        $xmlElement['customer_name'] = $custInfo['fullname'];
        $xmlElement['msg'] = $resvInfo['special_request'];
        $xmlElement['resv_id'] = $resvInfo['id'];
        $xmlElement['response'] = "undefined";

        switch (intval($resvInfo['hex_response'])) {
            case 0:      //pending
                $xmlElement['response'] = 'pending';
                break;
            case 1:      //decline
                $xmlElement['response'] = 'decline';
                break;
            case 2:      //accept
                $xmlElement['response'] = 'accept';
                break;
            default:
                $xmlElement['response'] = 'undefined';
        }
        
        ##  <resv>
        ##  <time>1:00pm</time>
        ##  <person>4</person>
        ##  <customer_name>Tom Chan</customer_name>
        ##  <msg></msg>
        ##  <resv_id>1234-001</resv_id>
        ##  <response>accept</response>
        ##  </resv>
         
        $responseXMLItem = sprintf("<resv><time>%s</time><person>%s</person><customer_name>%s</customer_name><msg>%s</msg><resv_id>%s</resv_id><response>%s</response></resv>", $xmlElement['time'], $xmlElement['person'], $xmlElement['customer_name'], $xmlElement['msg'], $xmlElement['resv_id'], $xmlElement['response']);
        $responseXML = $responseXML . $responseXMLItem;
    }

    //Re-factoring the query  string
    echo $responseXML = '<resv_list>' . $responseXML . '</resv_list>';

    $returnStatus['status'] = 'success';
    return $returnStatus;
}

function php_table_confirmation_response($resvId, $response) {

    $uid = php_get_uid();

    $returnStatus['status'] = 'undefined';

    //Lookup current status in the table.
    //If it is confirmed HEX(response) = 2, return immediately.
    //It it is decline/pending, update the table

    if (!php_check_resv_id_format($resvId)) {
        $returnStatus['status'] = 'Resveration ID format incorrect';
        return $returnStatus;
    }

    php_database_connect();

    //Use resvId to look up the table, only the same restaurant id in the reservation table can further continue.. 
    //prevent spoofing ( customer ID spoofing )
    $safe_resvId = GetSQLValueString($resvId, 'int');
    $safe_uid = GetSQLValueString($uid, 'int');

    $response = strtolower($response);
    $safe_bit_response = '00';
    if ($response == 'accept') {
        $safe_bit_response = '10';
    } else if ($response == 'decline') {
        $safe_bit_response = '01';
    } else {
        $returnStatus['status'] = 'status format incorrect';
        return $returnStatus;
    }

    $query = sprintf("SELECT id FROM resv WHERE HEX(response)='2' AND id = %s AND rest_id = %s", $safe_resvId, $safe_uid);
    $rs = php_database_query($query);
    if (mysql_num_rows($rs) > 0) {
        $returnStatus['status'] = 'Cannot change to decline because it has been set to accept before';
        return $returnStatus;
    }

    $query = sprintf("UPDATE resv SET response = b'%s' WHERE id = %s", $safe_bit_response, $safe_resvId);
    $rs = php_database_query($query);
    if (empty($rs)) {
        $returnStatus['status'] = 'Failed to update the value';
        return $returnStatus;
    }

    #If the response is accept or decline, 
    #Lookup the field to see whether client want to receive email or not,
    #If it is yes, send the email to customer.
    if ($response == 'accept' || $response == 'decline') {
        $query = sprintf("SELECT rest_id, cust_id, DATE_FORMAT(timeslot, '%%d, %%M, %%Y %%h:%%i%%p') AS 'datetime_timeslot', HEX(receive_email) AS 'hex_receive_email' FROM resv WHERE id = %s", $safe_resvId);
        $rs = php_database_query($query);
        $resvInfo = mysql_fetch_array($rs);
        if (empty($resvInfo)) {
            $returnStatus['status'] = 'Cannot get receive_email state';
            return $returnStatus;
        }
        if (intval($resvInfo['hex_receive_email']) === 1) {
            $safe_customerId = GetSQLValueString($resvInfo['cust_id'], 'int');
            $query = sprintf("SELECT email FROM customer WHERE id = %s", $safe_customerId);
            $rs = php_database_query($query);
            $custInfo = mysql_fetch_array($rs);
            if (empty($custInfo)) {
                $returnStatus['status'] = 'Cannot get customer email address';
                return $returnStatus;
            }

            $safe_restaurantId = GetSQLValueString($resvInfo['rest_id'], 'int');
            $query = sprintf("SELECT fullname, tel, address FROM restaurant WHERE id = %s", $safe_restaurantId);
            $rs = php_database_query($query);
            $restInfo = mysql_fetch_array($rs);
            if (empty($restInfo)) {
                $returnStatus['status'] = 'Cannot get the restaurant info';
                return $returnStatus;
            }

            #########################################################

            require_once('email.php');

            $emailAddr = $custInfo['email'];
            if ($response == 'accept') {
                $emailContent1 = '<h2>The restaurant has accepted your reservation request</h2>';
            } else {
                $emailContent1 = '<h2>The restaurant has rejected your reservation request</h2>';
            }
            $emailContent2 = '<p>Reservation ID:' . $resvId . '</p>';
            $emailContent3 = '<p>DateTime:'.$resvInfo['datetime_timeslot']. '</p>';
            $emailContent4 = '<p>Restaurant Name: ' . $restInfo['fullname'] . '</p>';
            $emailContent5 = '<p>Restaurant Address: ' . $restInfo['address'] . '</p>';
            $emailContent6 = '<p>Restaurant Tel: ' . $restInfo['tel'] . '</p>';
            $emailContent7 = 'Thanks<br/><br/>BestRegards,<br/>Restaurant Reservation System<br/>';

            $emailContent = $emailContent1 . $emailContent2 . $emailContent3 . $emailContent4 . $emailContent5 . $emailContent6. $emailContent7 ;

            php_send_email($emailAddr, '[Restaurant Reservation Request] New Message', $emailContent);
        }
    }

    echo $response;

    $returnStatus['status'] = 'success';
    return $returnStatus;
}

function php_show_customer_info($resvId) {

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

    $query = sprintf("SELECT cust_id FROM resv WHERE id = %s AND rest_id = %s", $safe_resvId, $safe_uid);
    $rs = php_database_query($query);
    $resvInfo = mysql_fetch_array($rs);
    if (empty($resvInfo)) {
        $returnStatus['status'] = 'Cannot get the reservation info';
        return $returnStatus;
    }

    #######################################################################
    $safe_custId = GetSQLValueString($resvInfo['cust_id'], 'int');
    $query = sprintf("SELECT fullname, email, tel FROM customer WHERE id = %s", $safe_custId);
    $rs = php_database_query($query);
    $custInfo = mysql_fetch_array($rs);
    if (empty($custInfo)) {
        $returnStatus['status'] = 'Cannot get the customer info';
        return $returnStatus;
    }

    $xmlElement['name'] = $custInfo['fullname'];
    $xmlElement['email'] = $custInfo['email'];
    $xmlElement['tel'] = $custInfo['tel'];

    $responseXML = "<customer><name>" . $xmlElement['name'] . "</name><email>" . $xmlElement['email'] . "</email><tel>" . $xmlElement['tel'] . "</tel></customer>";

    echo $responseXML;
    $returnStatus['status'] = 'success';

    return $returnStatus;
}

function php_get_message() {
    //check the session ID is valid

    $uid = php_get_uid();
    $returnStatus['status'] = 'undefined';

    php_database_connect();

    $safe_uid = GetSQLValueString($uid, 'int');

    //Lookup restaurant ID to get the query string from the table
    $query = sprintf("SELECT id, person, DATE_FORMAT(timeslot, '%%d,%%m,%%Y') AS date_timeslot, DATE_FORMAT(timeslot, '%%d, %%M, %%Y %%l:%%i%%p') AS datetime_timeslot FROM resv WHERE HEX(response) = '0' AND rest_id = %s", $safe_uid);
    $rs = php_database_query($query);
    if (mysql_num_rows($rs) == 0) {
        echo '<msg_list><brief>You do not have upcoming reservation request.</brief></msg_list>';
        $returnResult['status'] = 'success';
        return $returnResult;
    }

    $responseXML = '';
    $num_message = 0;

    while ($resvInfo = mysql_fetch_array($rs)) {
        #for javascript, the month is start from 0, so that we should -1
        $arrayDate = explode(',', $resvInfo['date_timeslot']);
        $jsMonth = (int) $arrayDate[1] - 1;

        $responseXMLItem = sprintf("<msg><text>%s (%s person)</text><date>%s,%s,%s</date></msg>", $resvInfo['datetime_timeslot'], $resvInfo['person'], $arrayDate[0], $jsMonth, $arrayDate[2]);
        $responseXML = $responseXML . $responseXMLItem;
        $num_message++;
    }

    //Re-factoring the query  string
    //Example: $msgList = '<msg_list><brief>You need to confirm 3 reservation requests.</brief><msg><text>14, Jul, 2014 7:30pm, (2 person)</text><date>14,6,2014</date></msg><msg><text>18, Jul, 2014 8:30pm, (3 person)</text><date>18,6,2014</date></msg></msg_list>';
    echo $responseXML = '<msg_list><brief>You need to confirm ' . $num_message . ' reservation request.</brief>' . $responseXML . '</msg_list>';

    $returnStatus['status'] = 'success';
    return $returnStatus;
}

function php_gen_report($date) {
    //check the sessionID is valid

    $uid = php_get_uid();
    $returnStatus['status'] = 'undefined';

    php_database_connect();

    $safe_uid = GetSQLValueString($uid, 'int');

    if (!php_check_convert_resv_date($date, $d, $m, $y)) {
        $returnStatus['status'] = 'Date format is incorrect';
        return $returnStatus;
    }

    //Get the date from the MySQL server.
    $query = "SELECT DATE_FORMAT(NOW(),'%d, %M, %Y %h:%i%p') AS 'current_time'";
    $rs = php_database_query($query);
    $timeInfo = mysql_fetch_array($rs);
    if (empty($timeInfo)) {
        $returnStatus['status'] = 'Cannot get the database server time';
        return $returnStatus;
    }

    $arrMonth = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
    $strM = $arrMonth[$m];

    $header = '<h2>Reservation Report</h2>';
    $header = $header . '<h4>Current Date Time:' . $timeInfo['current_time'] . '</h4>';
    $header = $header . '<h4>Selected from:' . $d . ', ' . $strM . ', ' . $y . ' (Maximum 200 records will be shown)</h4>';

    //lookup table to query the output from restID, date, person, special request
    #and then lookup the customer name.
    $safe_timeslot = GetSQLValueString($y . '-' . $m . '-' . $d, 'date');

    $query = sprintf("SELECT id, cust_id, DATE_FORMAT(timeslot, '%%d, %%M, %%Y %%h:%%i%%p') AS datetime_timeslot, person, special_request, HEX(response) AS hex_response FROM resv WHERE rest_id = %s AND DATE(timeslot) >= DATE(%s) ORDER BY timeslot ASC LIMIT 200", $safe_uid, $safe_timeslot);
    $rs = php_database_query($query);
    $body = '';

    while ($resvInfo = mysql_fetch_array($rs)) {
        $query = sprintf("SELECT fullname FROM customer WHERE id = %s", $resvInfo['cust_id']);
        $custResult = php_database_query($query);
        $custInfo = mysql_fetch_array($custResult);
        if (empty($custInfo)) {
            $returnStatus['status'] = 'Cannot get the customer info';
            return $returnStatus;
        }

        $htmlElement['time'] = $resvInfo['datetime_timeslot'];
        $htmlElement['person'] = $resvInfo['person'];
        $htmlElement['customer_name'] = $custInfo['fullname'];
        $htmlElement['msg'] = $resvInfo['special_request'];
        $htmlElement['resv_id'] = $resvInfo['id'];
        $htmlElement['response'] = "undefined";

        switch (intval($resvInfo['hex_response'])) {
            case 0:      //pending
                $htmlElement['response'] = 'pending';
                break;
            case 1:      //decline
                $htmlElement['response'] = 'decline';
                break;
            case 2:      //accept
                $htmlElement['response'] = 'accept';
                break;
            default:
                $htmlElement['response'] = 'undefined';
        }

        $body = $body . "<tr><td>" . $htmlElement['time'] . "</td><td>" . $htmlElement['customer_name'] . "</td><td>" . $htmlElement['person'] . "</td><td>" . $htmlElement['msg'] . "</td><td>" . $htmlElement['resv_id'] . "</td><td>" . $htmlElement['response'] . "</td></tr>\n";
    }

    $body = "<table><thead><tr><th>Date Time</th><th>Customer Name</th><th>Person</th><th>Special Request</th><th>Resv ID</th><th>Response</th></tr></thead>\n<tfoot><tr><td colspan=\"6\">The end</td></tr></tfoot>\n<tbody>" . $body . "</tbody></table>";
    //re-factoring the query string

    $footer = "";

    $reportTxt = $header . $body . $footer;
    echo $reportTxt;

    $returnStatus['status'] = 'success';
    return $returnStatus;
}

function php_change_profile($oldPassword, $newPassword, $fullname, $email, $tel, $district, $address, $description) {
    //verify the fullname is valid
    //verify the password is correct
    //verify the email format is correct
    //verify the tel format is correct
    //verify the district format is correct
    //verify the address format is correct
    //verify the description is correct
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

    if (!php_check_district_format($district)) {
        return 'District format incorrect';
    }

    if (!php_check_address_format($address)) {
        return 'Address format incorrect';
    }

    if (!php_check_description_format($description)) {
        return 'Description format incorrect';
    }

    php_database_connect();

    $hashOldPassword = hash('sha256', $username . $oldPassword . RESTAURANT_PASSWD_SALT);

    $safe_uid = GetSQLValueString($uid, 'int');
    $safe_fullname = GetSQLValueString($fullname, 'text');
    $safe_email = GetSQLValueString($email, 'text');
    $safe_tel = GetSQLValueString($tel, 'text');
    $safe_hashOldPassword = GetSQLValueString($hashOldPassword, 'text');
    $safe_district = GetSQLValueString($district, 'int');
    $safe_address = GetSQLValueString($address, 'text');
    $safe_description = GetSQLValueString($description, 'text');

    $query = sprintf("SELECT id FROM restaurant WHERE id = %s AND password = %s", $safe_uid, $safe_hashOldPassword);
    $rs = php_database_query($query);
    if (mysql_num_rows($rs) == 0) {
        return 'Password not matched';
    }

    if ($changePassword) {
        $hashNewPassword = hash('sha256', $username . $newPassword . RESTAURANT_PASSWD_SALT);
        $safe_hashNewPassword = GetSQLValueString($hashNewPassword, 'text');
        $query = sprintf("UPDATE restaurant SET password = %s, fullname = %s, email = %s, tel = %s, district = %s, address = %s, description = %s WHERE id = %s", $safe_hashNewPassword, $safe_fullname, $safe_email, $safe_tel, $safe_district, $safe_address, $safe_description, $safe_uid);
    } else {
        $query = sprintf("UPDATE restaurant SET fullname = %s, email = %s, tel = %s, district = %s, address = %s, description = %s WHERE id = %s", $safe_fullname, $safe_email, $safe_tel, $safe_district, $safe_address, $safe_description, $safe_uid);
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
    $returnResult['district'] = '';
    $returnResult['address'] = '';
    $returnResult['description'] = '';

    php_database_connect();

    $safe_uid = GetSQLValueString($uid, 'int');

    $query = sprintf("SELECT id, fullname, email, tel, district, address, description FROM restaurant WHERE id = %s", $safe_uid);
    $rs = php_database_query($query);
    $userInfo = mysql_fetch_array($rs);
    if (empty($userInfo)) {
        $returnResult['status'] = 'Userinfo not found';
        return $returnResult;
    }
    
    $returnResult['id'] = $userInfo['id'];
    $returnResult['fullname'] = $userInfo['fullname'];
    $returnResult['email'] = $userInfo['email'];
    $returnResult['tel'] = $userInfo['tel'];
    $returnResult['district'] = $userInfo['district'];
    $returnResult['address'] = $userInfo['address'];
    $returnResult['description'] = $userInfo['description'];
    
    $returnResult['status'] = 'success';
    return $returnResult;
}

switch ($_POST['func_name']) {
    case "change_profile":
        $result = php_change_profile($_POST['oldPassword'], $_POST['newPassword'], $_POST['fullname'], $_POST['email'], $_POST['tel'], $_POST['district'], $_POST['address'], $_POST['description']);
        if ($result == 'success') {
            header('Location:'.URL.'operation_success.php');
        } else {
            header('Location:'.URL.'operation_failed.php?reason=' . htmlentities($result));
        }
        return;
    case "show_reservation_list":
        return php_show_reservation_list($_POST['date']);

    case "show_customer_info":
        return php_show_customer_info($_POST['resv_id']);

    case "table_confirmation_response":
        return php_table_confirmation_response($_POST['resv_id'], $_POST['response']);

    case "get_message":
        return php_get_message();

    default:
        break;
}

?>