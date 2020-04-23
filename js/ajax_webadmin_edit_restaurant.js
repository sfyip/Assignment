//Must include the utility.js file in HTML first


function ajax_editRestaurant()
{
    var xmlhttp;
    if (window.XMLHttpRequest)
    {
        xmlhttp = new XMLHttpRequest();
    }
    else
    {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.open("POST", "./php_func/webadmin_func_list.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    var selectedRank = document.getElementById('restaurant_info_rank').value;

    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
        {
            var responseText = xmlhttp.responseText;

            if (responseText == "success")
            {
                html = "<img src=\"./img/rank/rank_" + selectedRank + ".png\" alt=\"rank\">";
                document.getElementById('rest' + selectedRestId + '_rank').innerHTML = html;
            }
        }
    };
    xmlhttp.send("func_name=edit_restaurant&rest_id=" + selectedRestId + "&rank=" + selectedRank);
}

//=============================================================================================

function showEditMsgBox(restId)
{
    selectedRestId = restId;
    
    var rankHtml = document.getElementById('rest' + selectedRestId + '_rank').innerHTML;
    var rank = parseInt(rankHtml[rankHtml.indexOf("rank_")+5]);
    
    document.getElementById("restaurant_info_rank").value = rank;
    
    utility_divRemoveInvisibleStyle("mask");
    utility_divRemoveInvisibleStyle("editMsgBox");
    utility_divCenterWindow("editMsgBox");
}
//=============================================================================================

function editMsgBox_OKBtnHandler()
{
    var selectedRank = document.getElementById('restaurant_info_rank').value;

    var errMsg = js_checkRank(selectedRank);
    if (errMsg != "")
    {
        //alert(errMsg);
        return;
    }

    ajax_editRestaurant();
    utility_divAddInvisibleStyle("mask");
    utility_divAddInvisibleStyle("editMsgBox");

}


function editMsgBox_CancelBtnHandler()
{
    utility_divAddInvisibleStyle("mask");
    utility_divAddInvisibleStyle("editMsgBox");
}

