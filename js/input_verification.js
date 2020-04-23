function js_checkTel(str)
{
    if (str.length > 15)
    {
        return "Phone number exceeds maximum length (15).<br/>";
    }

    var digitNo = 0;
    var i = (str[i] == '+')?(1):(0);
    for (; i < str.length; i++)
    {
        var c = str.charAt(i);
        if (c >= '0' && c <= '9')
        {
            digitNo++;
        }
        else if (c === '-')
        {
        }
        else
        {
            return "Phone number should only contain numbers or -.<br/>";
        }
    }
    if (digitNo < 8)
    {
        return "The length of phone number should be greater than 7.<br/>";
    }
    return "";
}

function js_checkEmail(str)
{
    if (str.length < 6)
    {
        return "The length of email should be greater than 5.<br/>";
    }

    if (str.length > 50)
    {
        return "The length of email exceeds maximum length (50).<br/>";
    }
    var pos;
    pos = str.indexOf('@');
    if (pos <= 0) {
        return "Email format is incorrect.<br/>";	//'@' not exist or in first position
    }
    if (pos === str.length - 1) {

        return "Email format is incorrect.<br/>";	//'@' at last position
    }
    if (str.indexOf('@') !== str.lastIndexOf('@')){
        return "Email format is incorrect.<br/>";	//multiple '@'
    }
    return "";
}

function js_checkUsername(str)
{
    if (str.length < 6)
    {
        return "The length of username should be greater than 5.<br/>";
    }
    if (str.length > 30)
    {
        return "Username exceeds maximum length (30).<br/>";
    }

    for (var i = 0; i < str.length; i++)
    {
        var c = str.charAt(i);
        if ((c >= 'A' && c <= 'Z') || (c >= 'a' && c <= 'z'))
        {

        }
        else if ((c >= '0' && c <= '9'))
        {

        }
        else if (c === '.' || c === ' ' || c === '_' || c === '-')
        {

        }
        else
        {
            return "Username should contain [a-z][A-Z][0-9][.][ ][-][_] only.<br/>";
        }
    }

    return "";
}

function js_checkFullname(str)
{
    if (str.length < 6)
    {
        return "The length of full name should be greater than 5.<br/>";
    }
    if (str.length > 50)
    {
        return "Full name exceeds maximum length (50).<br/>";
    }

    for (var i = 0; i < str.length; i++)
    {
        var c = str.charAt(i);
        if ((c >= 'A' && c <= 'Z') || (c >= 'a' && c <= 'z'))
        {

        }
        else if ((c >= '0' && c <= '9'))
        {

        }
        else if (c === '.' || c === ' ' || c === '_' || c === '-')
        {

        }
        else
        {
            return "Username should contain [a-z][A-Z][0-9][.][ ][-][_] only.<br/>";
        }
    }

    return "";
}

function js_checkPassword2(passwd1, passwd2)
{
    if (passwd1 === "")
    {
        return "Password cannot be empty.<br/>";
    }
    else if (passwd2 === "")
    {
        return "Password cannot be empty.<br/>";
    }
    else if (passwd1 !== passwd2)
    {
        return "Password and retype password are not matched.<br/>";
    }
    else if (passwd2.length < 6)
    {
        return "The length of password should be greater than 5.<br/>";
    }
    return "";
}

function js_checkPassword3(oldPasswd, newPasswd1, newPasswd2)
{
    if (oldPasswd === "")
    {
        return "Password cannot be empty.<br/>";
    }
    else if (oldPasswd.length < 6)
    {
        return "The length of password should be greater than 5.<br/>";
    }

    //User do not want to change new password, so we can ignore it.
    if((newPasswd1.length === 0) && (newPasswd1.length === 0))
    {
        return ""; 
    }

    return js_checkPassword2(newPasswd1, newPasswd2);
}

function js_checkAddress(address)
{
    if(address.length < 10)
    {
        return "The length of address should be greater than 9.<br/>";
    }
    else if (address.length > 100)
    {
        return "Full name exceeds maximum length (100)..<br/>";
    }
    return "";
}

function js_checkDescription(description)
{
    if (description.length > 1000)
    {
        return "Full name exceeds maximum length (1000)..<br/>";
    }
    return "";
}

//txtDate should be in the format of DD/MM/YYYY
function js_checkResvDateTime(txtDate, txtTime)
{
    //Create date from input value
    var dateParts = txtDate.split("/");
    var timeParts = txtTime.split(":");

    //check exactly a number, use Number function, if it is not a number, it will return NaN 
    var day = Number(dateParts[0]);
    var month = Number(dateParts[1]);
    var year = Number(dateParts[2]);

    if (isNaN(day) || isNaN(month) || isNaN(year))
    {
        return "Date format is incorrect (It should be DD/MM/YYYY)<br/>";
    }

    if (month < 1 || month > 12) { // check month range
        return "Month must be between 1 and 12.<br/>";
    }

    if (day < 1 || day > 31) {
        return "Day must be between 1 and 31.<br/>";
    }

    if ((month === 4 || month === 6 || month === 9 || month === 11) && day === 31) {
        return "Month " + month + " doesn't have 31 days.<br/>";
    }

    if (month === 2) { // check for february 29th
        var isleap = (year % 4 === 0 && (year % 100 !== 0 || year % 400 === 0));
        if (day > 29 || (day === 29 && !isleap)) {
            return "February " + year + " doesn't have " + day + " days.<br/>";
        }
    }

    //Get today's date
    var today = new Date();

    var checkDate = new Date(dateParts[2], (dateParts[1] - 1), dateParts[0], timeParts[0], timeParts[1], 59);

    return (checkDate > today) ? ("") : ("The selected date time should be later than now.<br/>");
}

function js_checkResvPerson(person)
{
    //check exactly a number, use Number function, if it is not a number, it will return NaN 
    var p = Number(person);
    if (isNaN(p))
    {
        return "Person format incorrect.<br/>";
    }

    if (p <= 0)
    {
        return "The person number should be greater than 0.<br/>";
    }

    if (p > 50)
    {
        return "The maximum person number should be 50.<br/>";
    }
    return "";
}

function js_checkRestaurantName(name)
{
    return "";
}

function js_checkRank(rank)
{
    //check exactly a number, use Number function, if it is not a number, it will return NaN 
    var r = Number(rank);
    if (isNaN(r))
    {
        return "Rank format incorrect.<br/>";
    }

    if (r <= 0)
    {
        return "The rank number should be greater than 0.<br/>";
    }

    if (r > 5)
    {
        return "The maximum rank number should be 5.<br/>";
    }
    return "";
}