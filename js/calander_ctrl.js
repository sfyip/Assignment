function calanderCtrl_init()
{
    var today = new Date();
    var dd = today.getDate();
    var m = today.getMonth();
    var yyyy = today.getFullYear();
    var day = today.getDay();

    //get current dd, m, yyyy day
    //In javascript, m is 0 based ( 0 = Jan , 1= Feb and so on...)
    //alert("Current Date: " + dd + "," + m + "," + yyyy + "," +day);

    //global variable
    calanderCtrl_selectedMonth = m;
    calanderCtrl_selectedYear = yyyy;
    calanderCtrl_update();
}

//Update the calanderCtrl based on calanderCtrl_selectedMonth and calanderCtrl_selectedYear
function calanderCtrl_update()
{
    var mmms = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

    var first_day_of_month = new Date(calanderCtrl_selectedYear, calanderCtrl_selectedMonth, 1).getDay();
    //alert("first_day_of_month:" + first_day_of_month);

    //Get total days in selected month
    var days_in_month = new Date(calanderCtrl_selectedYear, calanderCtrl_selectedMonth + 1, 0).getDate();
    //alert("days_in_month:" + days_in_month);

    var i;
    for (i = 0; i <= 41; i++)
    {

        if (i >= first_day_of_month && i < (days_in_month + first_day_of_month))
        {
            document.getElementById("calanderCtrl_date_id_" + i).innerHTML = i - first_day_of_month + 1;

            if ((i % 7) === 0)	//sunday?
            {
                document.getElementById("calanderCtrl_date_id_" + i).className = "calanderCtrl_valid_day_sunday";
            }
            else
            {
                document.getElementById("calanderCtrl_date_id_" + i).className = "calanderCtrl_valid_day";
            }
        }
        else
        {
            document.getElementById("calanderCtrl_date_id_" + i).innerHTML = "";
            document.getElementById("calanderCtrl_date_id_" + i).className = "calanderCtrl_invalid_day";
        }
    }

    var fmt_mmm = mmms[calanderCtrl_selectedMonth];		//month change to english

    document.getElementById("calanderCtrl_date_month").innerHTML = fmt_mmm + ", " + calanderCtrl_selectedYear;
}

function calanderCtrl_selected_prev_year()
{
    if ((calanderCtrl_selectedYear > 2000) && (calanderCtrl_selectedYear < 2100))
    {
        calanderCtrl_selectedYear--;
    }

    //alert("selectedYear: " + calanderCtrl_selectedYear);
    calanderCtrl_update();
}

function calanderCtrl_selected_next_year()
{
    if ((calanderCtrl_selectedYear > 2000) && (calanderCtrl_selectedYear < 2100))
    {
        calanderCtrl_selectedYear++;
    }

    //alert("selectedYear: " + calanderCtrl_selectedYear);
    calanderCtrl_update();
}

function calanderCtrl_selected_prev_month()
{
    if (calanderCtrl_selectedMonth === 0)
    {
        calanderCtrl_selectedMonth = 11;
        calanderCtrl_selected_prev_year();
    }
    else
    {
        calanderCtrl_selectedMonth--;
    }

    //alert("selectedMonth: " + calanderCtrl_selectedMonth);
    calanderCtrl_update();
}

function calanderCtrl_selected_next_month()
{
    if (calanderCtrl_selectedMonth === 11)
    {
        calanderCtrl_selectedMonth = 0;
        calanderCtrl_selected_next_year();
    }
    else
    {
        calanderCtrl_selectedMonth++;
    }

    //alert("selectedMonth: " + calanderCtrl_selectedMonth);
    calanderCtrl_update();
}

function calanderCtrl_getSelectedMonth()
{
    return calanderCtrl_selectedMonth;
}

function calanderCtrl_getSelectedYear()
{
    return calanderCtrl_selectedYear;
}

function calanderCtrl_show(thisId)
{
    utility_divRemoveInvisibleStyle(thisId);
}

function calanderCtrl_hide(thisId)
{
    utility_divAddInvisibleStyle(thisId);
}