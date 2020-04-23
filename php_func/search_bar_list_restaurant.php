<?php

require_once('database_stub.php');
require_once('check_variable_format_stub.php');


$district = $_POST['district'];

//verify district format is valid
if (!php_check_resv_district_format($district)) {
    return;
}

//prevent SQL injection
php_database_connect();

//Found the restaurant is matched with the district
$safe_num_district = GetSQLValueString($district, 'int');

//Use 'group by' in the SQL statement, because some restaurants are located on different district but the names are same.
if ($safe_num_district == 10) {
    $query = sprintf("SELECT fullname FROM restaurant where district >= 11 and district <= 19 GROUP BY fullname ORDER BY fullname");
} else if ($safe_num_district == 20) {
    $query = sprintf("SELECT fullname FROM restaurant where district >= 21 and district <= 29 GROUP BY fullname ORDER BY fullname");
} else if ($safe_num_district == 30) {
    $query = sprintf("SELECT fullname FROM restaurant where district >= 31 and district <= 39 GROUP BY fullname ORDER BY fullname");
} else {
    $query = sprintf("SELECT fullname FROM restaurant where district = %s GROUP BY fullname ORDER BY fullname", $safe_num_district);
}

$restaurantList = "";

$rs = php_database_query($query);
while ($restInfo = mysql_fetch_array($rs)) {
    $restaurantList = $restaurantList . $restInfo['fullname'] . ';';
}

//In order to minimize the transmission overhead, the results are concatenate using ; character
//DUMMY DATA: $response = "ADA Steak House;Ching Sushi;Columbia Cafe";
//return $response;
//remove the last ';' character
echo rtrim($restaurantList, ';');

?>