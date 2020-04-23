function searchPanel_loadSettingFromCookie(searchPanelObj)
{
    //Ensure the cookie value is not correct first (not modified by 3rd part, safe to deal with the cookie.).

    var cookiePerson = utility_getCookie("person");
    if (js_checkResvPerson(cookiePerson) === "")
    {
        searchPanelObj.person.value = cookiePerson;
    }
    else
    {
        searchPanelObj.person.value = "2";
    }

    var cookieDate = utility_getCookie("date");
    var cookieTime = utility_getCookie("time");

    if (js_checkResvDateTime(cookieDate, cookieTime) === "")
    {
        searchPanelObj.date.value = cookieDate;
        searchPanelObj.time.value = cookieTime;
    }
    else
    {
        var today = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);   //Tomorrow
        var dd = today.getDate();
        var mm = today.getMonth() + 1; //January is 0.
        var yyyy = today.getFullYear();

        if (dd < 10) {
            dd = '0' + dd;
        }

        if (mm < 10) {
            mm = '0' + mm;
        }

        today = dd + '/' + mm + '/' + yyyy;

        searchPanelObj.date.value = today;
        searchPanelObj.time.value = "19:00";
    }
}

function searchPanel_SaveSettingToCookie(searchPanelObj)
{
    //Ensure the cookie value is input correctly first.
    //If not, write default value to the cookie.

    var personValue = searchPanelObj.person.value;
    if (js_checkResvPerson(personValue) === "")
    {
        utility_setCookie("person", personValue);
    }

    var dateValue = searchPanelObj.date.value;
    var timeValue = searchPanelObj.time.value;
    if (js_checkResvDateTime(dateValue, timeValue) === "")
    {
        utility_setCookie("date", dateValue);
        utility_setCookie("time", timeValue);
    }
}
