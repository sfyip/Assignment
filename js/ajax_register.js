function ajax_restaurant_register()
{
    var username = document.regForm.username.value;
    var password = document.regForm.password.value;
    var email = document.regForm.email.value;
    var fullname = document.regForm.rest_fullname.value;
    var tel = document.regForm.rest_tel.value;
    var district = document.regForm.rest_district.value;
    var address = document.regForm.rest_address.value;
    var description = document.regForm.rest_description.value;

    var xmlhttp;
    if (window.XMLHttpRequest)
    {
        xmlhttp = new XMLHttpRequest();
    }
    else
    {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.open("POST", "./php_func/restaurant_register.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
        {
            registerStatusMsgBox_Show();
            document.getElementById("registerStatusText").innerHTML = xmlhttp.responseText;
        }
    };

    xmlhttp.send('username=' + username +
            '&password=' + password +
            '&email=' + email +
            '&fullname=' + fullname +
            '&tel=' + tel +
            '&district=' + district +
            '&address=' + address +
            '&description=' + description);
}

function ajax_customer_register()
{
    var username = document.regForm.username.value;
    var password = document.regForm.password.value;
    var email = document.regForm.email.value;
    var fullname = document.regForm.cust_fullname.value;
    var tel = document.regForm.cust_tel.value;

    var xmlhttp;
    if (window.XMLHttpRequest)
    {
        xmlhttp = new XMLHttpRequest();
    }
    else
    {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.open("POST", "./php_func/customer_register.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
        {
            registerStatusMsgBox_Show();
            document.getElementById("registerStatusText").innerHTML = xmlhttp.responseText;
        }
    };

    xmlhttp.send('username=' + username +
            '&password=' + password +
            '&email=' + email +
            '&fullname=' + fullname +
            '&tel=' + tel);
}

//=============================================================================================

function registerStatusMsgBox_Show()
{
    utility_divRemoveInvisibleStyle("mask");
    utility_divRemoveInvisibleStyle("registerStatusMsgBox");

    utility_divCenterWindow("registerStatusMsgBox");
}

//=============================================================================================

function registerStatusMsgBox_OKBtnHandler()
{
    utility_divAddInvisibleStyle("mask");
    utility_divAddInvisibleStyle("registerStatusMsgBox");
}
