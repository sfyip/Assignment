
function utility_getCookie(cname)
{
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)===' ') c = c.substring(1);
        if (c.indexOf(name) !== -1)
        {
            return c.substring(name.length,c.length);
        }
    }
    return "";
}

function utility_setCookie(cname, cvalue) {
    var d = new Date();
    d.setTime(d.getTime() + (1*24*60*60*1000));
    var expires = "expires="+d.toGMTString();

    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function utility_goToMainPage()
{
    window.location.href = "index.php";
}

function utility_divCenterWindow(divID)
{
    var obj = document.getElementById(divID);

    var offsetX = (self.innerWidth - obj.offsetWidth) / 2;
    var offsetY = (self.innerHeight - obj.offsetHeight) / 2;

    obj.style.position = "fixed";
    obj.style.top = offsetY + 'px';
    obj.style.left = offsetX + 'px';
}

//=============================================================================================

function utility_divRemoveInvisibleStyle(divID)
{
    var obj = document.getElementById(divID);
    obj.className = obj.className.replace("invisible_style", "");
}

function utility_divAddInvisibleStyle(divID)
{
    var obj = document.getElementById(divID);
    obj.className = obj.className.replace("invisible_style", "");
    
    obj.className+=" invisible_style";
}