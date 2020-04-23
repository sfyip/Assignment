<?php

require_once('check_variable_format_stub.php');
require_once('database_stub.php');

function php_query_restaurant_detail($restId) {
    $returnResult['search_result_rest_id'] = "0";
    $returnResult['search_result_rest_name'] = "RESTAURANT NOT FOUND";
    $returnResult['search_result_rest_address'] = "";
    $returnResult['search_result_rest_tel'] = "";
    $returnResult['search_result_rest_rank'] = "0";
    $returnResult['search_result_rest_description'] = "Restaurant not found. Please ensure the restaurant id is correct.";

    if (!php_check_rest_id_format($restId)) {
        return $returnResult;
    }

    php_database_connect();

    //prevent SQL injection
    $safe_restId = GetSQLValueString($restId, 'int');

    $query = sprintf("SELECT id, fullname, address, tel, rank, description FROM restaurant WHERE id=%s", $safe_restId);
    $rs = php_database_query($query);

    $record = mysql_fetch_array($rs);
    if (empty($record)) {
        return $returnResult;
    }

    $id = file_exists('./img/restaurant/' . $record['id'] . '/1.jpg') ? ($record['id']) : ('0');
    $returnResult['search_result_rest_id'] = $id;
    $returnResult['search_result_rest_name'] = $record['fullname'];
    $returnResult['search_result_rest_address'] = $record['address'];
    $returnResult['search_result_rest_tel'] = $record['tel'];
    $returnResult['search_result_rest_rank'] = $record['rank'];
    $returnResult['search_result_rest_description'] = $record['description'];

    return $returnResult;
}

?>