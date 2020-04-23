function ajax_updateRestaurantList(updateRestaurantTxt)
{
    if (typeof (updateRestaurantTxt) === 'undefined')
    {
        updateRestaurantTxt = true;
    }

    //Get the district value from select control
    var districtCtrl = document.getElementById("district");
    var restaurantListCtrl = document.getElementById('restaurant_list');
    var restaurantTxtCtrl = document.getElementById('restaurant_txt');

    var districtValue = districtCtrl.options[(districtCtrl.selectedIndex===-1)?(0):(districtCtrl.selectedIndex)].value;

    //clear the restaurant select control
    restaurantListCtrl.options.length = 0;
    if (updateRestaurantTxt)
    {
        restaurantTxtCtrl.value = "Retrieving restaurants...";
    }

    var xmlhttp;
    if (window.XMLHttpRequest)
    {
        xmlhttp = new XMLHttpRequest();
    }
    else
    {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.open("POST", "./php_func/search_bar_list_restaurant.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
        {
            var responseStr = xmlhttp.responseText;

            if (responseStr.length === 0)
            {
                var opt = document.createElement('option');
                opt.value = "none";
                opt.innerHTML = "none";
                restaurantListCtrl.appendChild(opt);
                
                if (updateRestaurantTxt)
                {
                    restaurantTxtCtrl.value = "none";
                }
            }
            else
            {
                var restaurantList = responseStr.split(";");
                for (var i = 0; i < restaurantList.length; i++)
                {
                    var opt = document.createElement('option');
                    opt.value = restaurantList[i];
                    opt.innerHTML = restaurantList[i];
                    restaurantListCtrl.appendChild(opt);
                }
                if (updateRestaurantTxt)
                {
                    restaurantTxtCtrl.value = restaurantList[0];
                }
            }

        }
    };

    xmlhttp.send('district=' + districtValue);
}

function SearchBar_updateRestaurantTextBox()
{
    document.getElementById('restaurant_txt').value = document.getElementById('restaurant_list').value;
}
