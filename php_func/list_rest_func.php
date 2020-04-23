<?php

require_once('database_stub.php');
require_once('check_variable_format_stub.php');

function php_url_get_district_var() {
    return isset($_GET['district']) ? ($_GET['district']) : ('10');
}

function php_url_get_restaurant_var() {
    return isset($_GET['restaurant']) ? (addslashes($_GET['restaurant'])) : ('');
}

#Page starts from 1, not 0.

function php_list_best_restaurant_result($page) {

    $returnResult['status'] = 'undefined';
    $returnResult['page_count'] = 0;
    $returnResult['resultHtml'] = '';
    $returnResult['prevPageHtml'] = '';
    $returnResult['nextPageHtml'] = '';
    $page_num = intval($page);
    $returnResult['current_page'] = ($page_num == 0) ? (1) : $page_num;

    php_database_connect();
    
    //prevent SQL injection
    $safe_num_page =  empty($page) ? (1) : intval($page);

    //generate the query from these input parameter

    $query1 = sprintf("SELECT COUNT(id) AS count_num FROM restaurant", ($safe_num_page - 1) * 20);

    $query2 = sprintf("SELECT id, fullname, district, rank FROM restaurant ORDER BY rank DESC LIMIT %s, 20", ($safe_num_page - 1) * 20);


    $rs = php_database_query($query1);
    $pageInfo = mysql_fetch_array($rs);
    if (empty($pageInfo) || empty($pageInfo['count_num'])) {
        $returnResult['resultHtml'] = "<h2>Result not found</h2>";
        $returnResult['status'] = 'success';
        return $returnResult;
    }
    $returnResult['page_count'] = (intval($pageInfo['count_num'] / 20)) + (($pageInfo['count_num'] % 20) ? (1) : (0));

    //query and get the result from the database
    $responseMergeHTML = "";

    /*
      <div class="rest_list_item">
      <a href="rest_detail.php?rest_id=1>
      <img src="./img/restaurant/1/1.jpg" class="rest_list_item_thumbnail" alt="thumbnail" />
      <div class="rest_list_item_name">ADA Steak House</div>
      <div class="rest_list_item_district">Causeway Bay</div>
      <div class="rest_list_item_rank"><img src="./img/rank/rank_5.png" alt="rank" /> </div>
      </a>
      </div>
     */

    $html1 = '<div class="rest_list_item"><a href="rest_detail.php?rest_id=';
    $html2 = '"><img src="./img/restaurant/';
    $html3 = '/1.jpg" class="rest_list_item_thumbnail" alt="thumbnail" /><div class="rest_list_item_name">';
    $html4 = '</div><div class="rest_list_item_district">';
    $html5 = '</div><div class="rest_list_item_rank"><img src="./img/rank/rank_';
    $html6 = '.png" alt="rank" /> </div></a></div>';

	$rs = php_database_query($query2);
    while ($restInfo = mysql_fetch_array($rs)) {

        $restInfo_district = php_convert_district_id_to_name($restInfo['district']);
        $restInfo_restId = $restInfo['id'];
        #check if the img file exist.
        $imgExistId = file_exists('./img/restaurant/' . $restInfo_restId . '/1.jpg') ? ($restInfo_restId) : ('0');
        $restInfo_restName = $restInfo['fullname'];
        $restInfo_rank = $restInfo['rank'];

        //refactoring the format by add restaurant image link, restaurant name, restaurant location, restaurant rank.
        $responseItemHTML = $html1 . $restInfo_restId . $html2 . $imgExistId . $html3 . $restInfo_restName . $html4 . $restInfo_district . $html5 . $restInfo_rank . $html6;

        $responseMergeHTML = $responseMergeHTML . $responseItemHTML;
    }

    $returnResult['resultHtml'] = $responseMergeHTML;
    
###################################################################################
    if ( ($returnResult['page_count'] > 1) && ($returnResult['current_page'] > 1) ) {
        $returnResult['prevPageHtml'] = '<a id="prev_page" href="index.php?&page='.(intval($returnResult['current_page']) - 1).'">Prev</a>';
    }
    
    if ((($returnResult['current_page'] < $returnResult['page_count']) && $returnResult['page_count']>1)) {
        $returnResult['nextPageHtml'] = '<a id="next_page" href="index.php?&page='.(intval($returnResult['current_page']) + 1).'">Next</a>';
    }
    //echo "Debug: currentPage:".$returnResult['current_page'];
    //echo "Debug: count_num:".$pageInfo['count_num'];
    //echo "Debug: pageCount:".$returnResult['page_count'];
    $returnResult['status'] = 'success';
    return $returnResult;
}


?>