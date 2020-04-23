//Must include utility.js in the HTML file first

function reserveRestHandler1(e) {

    e.preventDefault();

    if (!loginAsCustomer)
    {
        loginPromptMsgBox_Show();
        return;
    }

    //input verification for form data field  
    var errMsg = "";

    errMsg += js_checkResvDateTime(document.resvForm1.date.value, document.resvForm1.time.value);
    errMsg += js_checkResvPerson(document.resvForm1.person.value);

    if (errMsg !== "")
    {
        showErrMsgBox(errMsg);
        return;
    }

    //Login sesson is verified.
    //Display the msgbox to let user change setting.
    reserveConfigMsgBox_Show();
}

function reserveRestHandler2()
{
    document.resvForm2.person.value = document.getElementById("person").value;
    document.resvForm2.date.value = document.getElementById("date_txt").value;
    document.resvForm2.time.value = document.getElementById("time").value;
}

//=============================================================================================

function loginPromptMsgBox_Show()
{
    utility_divRemoveInvisibleStyle("mask");
    utility_divRemoveInvisibleStyle("loginPromptMsgBox");

    utility_divCenterWindow("loginPromptMsgBox");
}

function reserveConfigMsgBox_Show()
{
    utility_divRemoveInvisibleStyle("mask");
    utility_divRemoveInvisibleStyle("reserveConfigMsgBox");

    utility_divCenterWindow("reserveConfigMsgBox");
}

//=============================================================================================

function loginPromptMsgBox_OKBtnHandler()
{
    utility_divAddInvisibleStyle("mask");
    utility_divAddInvisibleStyle("loginPromptMsgBox");
}

function reserveConfigMsgBox_OKBtnHandler()
{
    utility_divAddInvisibleStyle("mask");
    utility_divAddInvisibleStyle("reserveConfigMsgBox");
}

function reserveConfigMsgBox_CancelBtnHandler()
{
    utility_divAddInvisibleStyle("mask");
    utility_divAddInvisibleStyle("reserveConfigMsgBox");
}

//=============================================================================================

