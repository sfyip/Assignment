<?php

define("VARCHK_USERNAME_MINLEN", 6);
define("VARCHK_USERNAME_MAXLEN", 30);

define("VARCHK_PASSWORD_MINLEN", 6);

define("VARCHK_EMAIL_MINLEN", 6);
define("VARCHK_EMAIL_MAXLEN", 50);

define("VARCHK_TEL_MINDIGIT", 8);
define("VARCHK_TEL_MAXLEN", 15);

define("VARCHK_FULLNAME_MINLEN", 6);
define("VARCHK_FULLNAME_MAXLEN", 50);

define("VARCHK_ADDRESS_MINLEN", 10);
define("VARCHK_ADDRESS_MAXLEN", 100);

define("VARCHK_DESCRIPTION_MAXLEN", 1000);

define("VARCHK_SPECIALREQUEST_MAXLEN", 500);

define("VARCHK_PERSON_MAXNUM", 50);

define("VARCHK_RANK_MINNUM", 1);
define("VARCHK_RANK_MAXNUM", 5);

//Only permit [a-z][A-Z][0-9]._[\s]- character
function php_str_check_letter_digit_dot_space_line($str) {
    for ($i = 0; $i < strlen($str); $i++) {
        $c = $str[$i];
        if ($c >= 'a' && $c <= 'z') {
            
        } else if ($c >= 'A' && $c <= 'Z') {
            
        } else if ($c >= '0' && $c <= '9') {
            
        } else if ($c == '.' || $c == '_' || $c == ' ' || $c == '-') {
            
        } else {
            return false;
        }
    }
    return true;
}

function php_check_username_format($username) {
    if (strlen($username) < VARCHK_USERNAME_MINLEN) {
        return false;
    }

    if (strlen($username) > VARCHK_USERNAME_MAXLEN) {
        return false;
    }

    if (!php_str_check_letter_digit_dot_space_line($username)) {
        return false;
    }

    return true;
}
function php_check_password_format($password) {
    if (strlen($password) < VARCHK_PASSWORD_MINLEN) {
        return false;
    }

    return true;
}


function php_check_rest_id_format($rest_id) {
    return ctype_digit($rest_id);
}

function php_check_resv_id_format($resv_id) {
    return ctype_digit($resv_id);
}

function php_check_email_format($email) {
    if (strlen($email) < VARCHK_EMAIL_MINLEN) {
        return false;
    }

    if (strlen($email) > VARCHK_EMAIL_MAXLEN) {
        return false;
    }

    //Need enhancement

    return true;
}

function php_check_tel_format($tel) {
    if (strlen($tel) > VARCHK_TEL_MAXLEN) {
        return false;
	}

    $i = ($tel[0] == '+') ? (1):(0);
    
    $digitNo = 0;
    for (; $i < strlen($tel); $i++) {
        $c = $tel[$i];
        if ($c >= '0' && $c <= '9') {
            $digitNo++;
        } else if ($c == '-') {
            
        } else {
            return false;
        }
    }
    if ($digitNo < VARCHK_TEL_MINDIGIT) {
        return false;
    }
    return true;
}

function php_check_fullname_format($fullname) {
    if (strlen($fullname) < VARCHK_FULLNAME_MINLEN) {
        return false;
    }

    if (strlen($fullname) > VARCHK_FULLNAME_MAXLEN) {
        return false;
    }

    if (!php_str_check_letter_digit_dot_space_line($fullname)) {
        return false;
    }

    return true;
}

function php_check_address_format($address)
{
    if (strlen($address) < VARCHK_ADDRESS_MINLEN) {
        return false;
    }

    if (strlen($address) > VARCHK_ADDRESS_MAXLEN) {
        return false;
    }
    
    return true;
}

function php_check_description_format($description)
{
    //description can be empty.

    if (strlen($description) > VARCHK_DESCRIPTION_MAXLEN) {
        return false;
    }
    
    return true;
}

//Make sure the district format used by changing profile is correct
function php_check_district_format($district) {
    
    if(!ctype_digit($district))
    {
        return false;
    }
 
    $num_district = intval($district);
    if($num_district >= 11 && $num_district <=14)
    {
        return true;
    }
    else if($num_district >= 21 && $num_district <=25)
    {
        return true;
    }
    else if($num_district >= 31 && $num_district <=39)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function php_check_resv_district_format($district) {
    
      #<option value="10">=== Hong Kong Island ===</option>
      #<option value="11">Central and Western</option>
      #<option value="12">Eastern</option>
      #<option value="13">Southern</option>
      #<option value="14">Wan Chai</option>
      #<option value="20">=== Kowloon ===</option>
      #<option value="21">Sham Shui Po</option>
      #<option value="22">Kowloon City</option>
      #<option value="23">Kwun Tong</option>
      #<option value="24">Wong Tai Sin</option>
      #<option value="25">Yau Tsim Mong</option>
      #<option value="30">=== New Territories ===</option>
      #<option value="31">Islands</option>
      #<option value="32">Kwai Tsing</option>
      #<option value="33">North</option>
      #<option value="34">Sai Kung</option>
      #<option value="35">Sha Tin</option>
      #<option value="36">Tai Po</option>
      #<option value="37">Tsuen Wan</option>
      #<option value="38">Tuen Mun</option>
      #<option value="39">Yuen Long</option>
    
    if(!ctype_digit($district))
    {
        return false;
    }
 
    $num_district = intval($district);
    if($num_district >= 10 && $num_district <=14)
    {
        return true;
    }
    else if($num_district >= 20 && $num_district <=25)
    {
        return true;
    }
    else if($num_district >= 30 && $num_district <=39)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function php_convert_district_id_to_name($districtId) {
    /*
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
     */
    switch ($districtId) {
        case "10":
            return "Hong Kong Island";
        case "11":
            return "Central and Western";
        case "12":
            return "Eastern";
        case "13":
            return "Southern";
        case "14":
            return "Wan Chai";

        case "20":
            return "Kowloon";
        case "21":
            return "Sham Shui Po";
        case "22":
            return "Kowloon City";
        case "23":
            return "Kwun Tong";
        case "24":
            return "Wong Tai Sin";
        case "25":
            return "Yau Tsim Mong";

        case "30":
            return "New Territories";
        case "31":
            return "Islands";
        case "32":
            return "Kwai Tsing";
        case "33":
            return "North";
        case "34":
            return "Sai Kung";
        case "35":
            return "Sha Tin";
        case "36":
            return "Tai Po";
        case "37":
            return "Tsuen Wan";
        case "38":
            return "Tuen Mun";
        case "39":
            return "Yuen Long";
        default:
            return "";
    }
}

function php_check_resv_restaurant_format($restaurant)
{
    return true;
}

function php_check_resv_date_format($date) {
   
    $d = explode('/',$date);
    return checkdate($d[1], $d[0], $d[2]);
}

function php_check_convert_resv_date($date, &$pd, &$pm, &$py)
{
    $d = explode('/',$date);
    if(checkdate($d[1], $d[0], $d[2]))
    {
        $pd = $d[0];
        $pm = $d[1];
        $py = $d[2];
        return true;
    }
    
    return false;
}

function php_check_resv_time_format($time) {
    
    $t = explode(':', $time);
    return ($t[0]>=11 && $t[0]<=22 && ($t[1]=='00' || $t[1]=='15' || $t[1]=='30' || $t[1]=='45'));
}

function php_check_resv_date_time_format($datetime) {
    $dt = explode(' ', $datetime);
    return php_check_resv_date_format($dt[0]) && php_check_resv_time_format($dt[1]);
}

function php_check_resv_person_format($person) {    
    if(!ctype_digit($person))
    {
        return false;
    }
    
    $num_person = intval($person);
    if(!($num_person >= 1 && $num_person <= VARCHK_PERSON_MAXNUM))
    {
        return false;
    }
    
    return true;
}

function php_check_resv_special_request_format($msg) {
    if(strlen($msg) > VARCHK_SPECIALREQUEST_MAXLEN)
    {
        return false;
    }
    return true;
}

function php_check_rank_format($rank)
{
    if(!ctype_digit($rank))
    {
        return false;
    }
    
    $num_rank = intval($rank);
    if(!($num_rank >= VARCHK_RANK_MINNUM && $num_rank <= VARCHK_RANK_MAXNUM))
    {
        return false;
    }
    
    return true;
}

?>