//Must include the utility.js file in HTML first


function ajax_showRestResvList(selectedDate, selectedMonth, selectedYear)
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

    xmlhttp.open("POST", "./php_func/manager_func_list.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");


    //Change the resv_list_day to deticated date format
    var months = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
    document.getElementById("resv_list_day").innerHTML = selectedDate + ", " + months[selectedMonth] + ", " + selectedYear;

    //Show "retrieve content" message in the reservation list
    document.getElementById('resv_list_tbody').innerHTML = 'Retrieving...';

    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
        {
            var responseXML = xmlhttp.responseText;
            var resvListHTML = "";

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


            var resvNodes = xmlDoc.getElementsByTagName("resv");
            if(resvNodes.length === 0)
            {
                resvListHTML = "<td colspan=\"7\">No reservation.</td>";
            }
            else
            {
            for (var i = 0; i < resvNodes.length; i++)
            {
                var resvObj = resvNodes[i];
                var time, person, customer_name, msg, resv_id, response;

                for (var j = 0; j < resvObj.childNodes.length; j++)
                {
                    switch (resvObj.childNodes[j].nodeName)
                    {
                        case "time":
                            time = resvObj.childNodes[j].textContent;
                            break;
                        case "person":
                            person = resvObj.childNodes[j].textContent;
                            break;
                        case "customer_name":
                            customer_name = resvObj.childNodes[j].textContent;
                            break;
                        case "msg":
                            msg = resvObj.childNodes[j].textContent;
                            break;
                        case "resv_id":
                            resv_id = resvObj.childNodes[j].textContent;
                            break;
                        case "response":
                            response = resvObj.childNodes[j].textContent;
                            break;
                        default:
                            break;
                    }
                }

                var html1 = '<tr><td class="resv_item_time">';

                var html2 = '</td><td class="resv_item_person">';

                var html3 = '</td><td class="resv_item_customer_name">';

                var html4 = '</td><td class="resv_item_customer_detail">';
                var html5 = '<input type="button" class="button" value="Detail" onclick="showCustomerInfoMsgBoxBtnHandler(';

                var html6 = ');" /></td><td class="resv_item_msg">';
                var html7 = '</td><td class="resv_item_id">';

                var htmlResponseAccept_1 = '</td><td id="';
                var htmlResponseAccept_2 = '_response" class="resv_item_response"><img src="./img/accept.png" alt="accept" /></td><td class="resv_item_change"></td></tr>';

                var htmlResponseDecline_1 = '</td><td id="';
                var htmlResponseDecline_2 = '_response" class="resv_item_response"><img src="./img/decline.png" alt="decline" /></td><td id="';
                var htmlResponseDecline_3 = '_change" class="resv_item_change"><input type="button" class="button" value="Change" onclick="showResvResponseMsgBoxBtnHandler(this);" /></td></tr>';

                var htmlResponsePending_1 = '</td><td id="';
                var htmlResponsePending_2 = '_response" class="resv_item_response"><img src="./img/pending.png" alt="pending" /></td><td id="';
                var htmlResponsePending_3 = '_change" class="resv_item_change"><input type="button" class="button" value="Change" onclick="showResvResponseMsgBoxBtnHandler(this);" /></td></tr>';

                var resvItemHTML = html1 + time + html2 + person + html3 + customer_name + html4 + html5 + resv_id + html6 + msg +html7 + resv_id;

                if (response === "accept")
                {
                    resvItemHTML += (htmlResponseAccept_1 + resv_id + htmlResponseAccept_2);
                }
                else if (response === "decline")
                {
                    resvItemHTML += (htmlResponseDecline_1 + resv_id + htmlResponseDecline_2 + resv_id + htmlResponseDecline_3);
                }
                else if (response === "pending")
                {
                    resvItemHTML += (htmlResponsePending_1 + resv_id + htmlResponsePending_2 + resv_id + htmlResponsePending_3);
                }
                else
                {
                    return;
                }

                resvListHTML += resvItemHTML;
            }
            }
            document.getElementById('resv_list_tbody').innerHTML = resvListHTML;
        }
    };
    xmlhttp.send("func_name=show_reservation_list&date=" + selectedDate + "%2F" + (selectedMonth+1) + "%2F" + selectedYear);
}

function ajax_showCustomerInfo(resvId)
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

    xmlhttp.open("POST", "./php_func/manager_func_list.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    //Clear the datafield content first
    document.getElementById('customer_info_msgbox_name').innerHTML = '';
    document.getElementById('customer_info_msgbox_email').innerHTML = '';
    document.getElementById('customer_info_msgbox_tel').innerHTML = '';

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

            var customerNodes = xmlDoc.getElementsByTagName("customer");

            if (customerNodes.length !== 1)
            {
                return;
            }

            var customerObj = customerNodes[0];
            var customer_name, customer_email, customer_tel;

            for (var j = 0; j < customerObj.childNodes.length; j++)
            {
                switch (customerObj.childNodes[j].nodeName)
                {
                    case "name":
                        customer_name = customerObj.childNodes[j].textContent;
                        break;
                    case "email":
                        customer_email = customerObj.childNodes[j].textContent;
                        break;
                    case "tel":
                        customer_tel = customerObj.childNodes[j].textContent;
                        break;
                    default:
                        break;
                }
            }

            document.getElementById('customer_info_msgbox_name').innerHTML = customer_name;
            document.getElementById('customer_info_msgbox_email').innerHTML = customer_email;
            document.getElementById('customer_info_msgbox_tel').innerHTML = customer_tel;
        }
    };
    xmlhttp.send("func_name=show_customer_info&resv_id=" + resvId);
}


function ajax_getMessage()
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

    xmlhttp.open("POST", "./php_func/manager_func_list.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

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

            var briefNodes = xmlDoc.getElementsByTagName("brief");

            //Invalid messageRemainder format
            if (briefNodes.length !== 1)
            {
                return;
            }

            //Setup brief field content
            document.getElementById("messageRemainder_brief").innerHTML = '<b>'+briefNodes[0].textContent+'</b>';

            //Clear msg content
            document.getElementById('messageRemainder_msg').innerHTML = "";

            //Setup msg content
            var msgNodes = xmlDoc.getElementsByTagName("msg");

            for (var i = 0; i < msgNodes.length; i++)
            {
                var msgObj = msgNodes[i];
                var msg_text, msg_date;
                for (var j = 0; j < msgObj.childNodes.length; j++)
                {
                    switch (msgObj.childNodes[j].nodeName)
                    {
                        case "text":
                            msg_text = msgObj.childNodes[j].textContent;
                            break;
                        case "date":
                            msg_date = msgObj.childNodes[j].textContent;
                            break;
                        default:
                            break;
                    }
                }
                //dummy content
                //<p>14, Jul, 2014 7:30pm, (2 person)<input type="button" class="button" name="check" value="Check" /></p>

                document.getElementById('messageRemainder_msg').innerHTML += '<p>' + msg_text + '<input type="button" onclick="ajax_showRestResvList(' + msg_date + ')" class="button" name="check" value="check"></p>';


            }

        }
    };
    xmlhttp.send("func_name=get_message");
}

function ajax_resvResponse(resvId, responseStr)
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

    xmlhttp.open("POST", "./php_func/manager_func_list.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
        {
            var responseStr = xmlhttp.responseText;
            if(responseStr === "accept")
            {
                //change the 'response' data field image
                //change the "change data field" to disable the change button.
                document.getElementById(resvId+"_response").innerHTML = '<img src="./img/accept.png" alt="accept" />';
                document.getElementById(resvId+"_change").innerHTML = "";
            }
            else if(responseStr === "decline")
            {
                ////change the 'response' data field image
                //keep change button on, no need to change the "change data field"
                document.getElementById(resvId+"_response").innerHTML = '<img src="./img/decline.png" alt="decline" />';
                document.getElementById(resvId+"_response").innerHTML = '<img src="./img/decline.png" alt="decline" />';             
            }
            else
            {
                return;
            }
        }
    };
    
    xmlhttp.send("func_name=table_confirmation_response&resv_id=" + resvId + "&response="+responseStr);
}
//=============================================================================================

function messageRemainder_showExtentedPanel()
{
    utility_divAddInvisibleStyle("messageRemainder_viewBtn");
    utility_divRemoveInvisibleStyle("msgPanel");
}

//=============================================================================================

function showCustomerInfoMsgBoxBtnHandler(resvId)
{
    ajax_showCustomerInfo(resvId);
    customerInfoMsgBox_Show();
}

function showResvResponseMsgBoxBtnHandler(currentNode)
{
    var parentNodeId = currentNode.parentNode.id;
    
    selectedResvId = parentNodeId.replace("_change", "");
    resvResponseMsgBox_Show();
}

//=============================================================================================

function customerInfoMsgBox_Show()
{
    utility_divRemoveInvisibleStyle("mask");
    utility_divRemoveInvisibleStyle("customerInfoMsgBox");

    utility_divCenterWindow("customerInfoMsgBox");
}

function resvResponseMsgBox_Show()
{
    utility_divRemoveInvisibleStyle("mask");
    utility_divRemoveInvisibleStyle("resvResponseMsgBox");

    utility_divCenterWindow("resvResponseMsgBox");
}

//=============================================================================================

function customerInfoMsgBox_OKBtnHandler()
{
    utility_divAddInvisibleStyle("mask");
    utility_divAddInvisibleStyle("customerInfoMsgBox");
}

function resvResponseMsgBox_AcceptBtnHandler()
{
    ajax_resvResponse(selectedResvId, 'accept');
        
    utility_divAddInvisibleStyle("mask");
    utility_divAddInvisibleStyle("resvResponseMsgBox");
}

function resvResponseMsgBox_DeclineBtnHandler()
{
    ajax_resvResponse(selectedResvId, 'decline');
        
    utility_divAddInvisibleStyle("mask");
    utility_divAddInvisibleStyle("resvResponseMsgBox");
}

function resvResponseMsgBox_CancelBtnHandler()
{       
    utility_divAddInvisibleStyle("mask");
    utility_divAddInvisibleStyle("resvResponseMsgBox");
}