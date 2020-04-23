//Must include the utility.js file in HTML first


function ajax_showRestaurantInfo(resvId)
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

    xmlhttp.open("POST", "./php_func/customer_func_list.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    //Clear the datafield content first
    document.getElementById('restaurant_info_msgbox_name').innerHTML = '';
    document.getElementById('restaurant_info_msgbox_address').innerHTML = '';
    document.getElementById('restaurant_info_msgbox_tel').innerHTML = '';

    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
        {
            var responseXML = xmlhttp.responseText;

            if (window.DOMParser)
            {
                parser = new DOMParser();
                xmlDoc = parser.parseFromString(responseXML, "text/xml");
            }
            else // Internet Explorer
            {
                xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
                xmlDoc.async = false;
                xmlDoc.loadXML(responseXML);
            }

            var restaurantNodes = xmlDoc.getElementsByTagName("restaurant");

            if (restaurantNodes.length !== 1)
            {
                return;
            }

            var restaurantObj = restaurantNodes[0];
            var restaurant_name, restaurant_address, restaurant_tel;

            for (var j = 0; j < restaurantObj.childNodes.length; j++)
            {
                switch (restaurantObj.childNodes[j].nodeName)
                {
                    case "name":
                        restaurant_name = restaurantObj.childNodes[j].textContent;
                        break;
                    case "address":
                        restaurant_address = restaurantObj.childNodes[j].textContent;
                        break;
                    case "tel":
                        restaurant_tel = restaurantObj.childNodes[j].textContent;
                        break;
                    default:
                        break;
                }
            }

            document.getElementById('restaurant_info_msgbox_name').innerHTML = restaurant_name;
            document.getElementById('restaurant_info_msgbox_address').innerHTML = restaurant_address;
            document.getElementById('restaurant_info_msgbox_tel').innerHTML = restaurant_tel;
        }
    };
    xmlhttp.send("func_name=show_restaurant_info&resv_id=" + resvId);
}

//=============================================================================================

function showRestaurantInfoMsgBoxBtnHandler(resvId)
{
    ajax_showRestaurantInfo(resvId);
    restaurantInfoMsgBox_Show();
}

//=============================================================================================

function restaurantInfoMsgBox_Show()
{
    utility_divRemoveInvisibleStyle("mask");
    utility_divRemoveInvisibleStyle("restaurantInfoMsgBox");

    utility_divCenterWindow("restaurantInfoMsgBox");
}

//=============================================================================================

function restaurantInfoMsgBox_OKBtnHandler()
{
    utility_divAddInvisibleStyle("mask");
    utility_divAddInvisibleStyle("restaurantInfoMsgBox");
}
