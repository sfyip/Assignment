//Must include the utility,js in HTML first

function loginPanel_show() {
    utility_divRemoveInvisibleStyle("login_panel");
}

function loginPanel_hide()
{
    utility_divAddInvisibleStyle("login_panel");
}

function loginPanel_toogle()
{
    //check the loginPanel has included "invisible_style" class or not.
    var obj = document.getElementById("login_panel");
    if (obj.className.match(/\binvisible_style\b/))
    {
        loginPanel_show();
    }
    else {
        loginPanel_hide();
    }
}