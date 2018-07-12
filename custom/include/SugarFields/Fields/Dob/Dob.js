/*
*   DOB.js
*   Author: Hieu Nguyen
*   Purpose: Handle validating DOB fields
*/

var DOB = {};

// Init DOB field
DOB.init = function(key) {
    var day = $('#'+ key +'_day');
    var month = $('#'+ key +'_month');
    var year = $('#'+ key +'_year');
    var date = $('#'+ key +'_date');
    var form = date.closest('form');
    
    // When the fields are changed, validate the value, and then assign value to the hidden date field
    $('#'+ day.attr('id') +', #'+ month.attr('id') +', #'+ year.attr('id')).live('change', function(){
        DOB.validate(key);
        
        var dayVal = (day.val() == '') ? '00' : day.val();        
        var monthVal = (month.val() == '') ? '00' : month.val();        
        var yearVal = year.val().trim();
        var yearPattern = '0000';
        yearVal = (yearVal == '') ? yearPattern : yearPattern.substring(0, yearPattern.length - yearVal.length) + yearVal;        
        var value = cal_date_format;
        value = value.replace('%d', dayVal);
        value = value.replace('%m', monthVal);
        value = value.replace('%Y', yearVal);
        date.val(value);
        date.trigger('change');
    });    
}

DOB.validate = function(key) {
    var dayId = '#'+ key +'_day';
    var monthId = '#'+ key +'_month';
    var yearId = '#'+ key +'_year';
    var date = $('#'+ key +'_date');
    var formName = date.closest('form').attr('name');
    
    removeFromValidate(formName, date.attr('name'));
    if($(dayId).val() != '' || $(monthId).val() != '' || $(yearId).val().trim() != ''){
        addToValidate(formName, dayId, 'enum', true, SUGAR.language.get('app_strings', 'LBL_DAY'));
        addToValidate(formName, monthId, 'enum', true, SUGAR.language.get('app_strings', 'LBL_MONTH'));
        addToValidateRange(formName, yearId, 'int', false,SUGAR.language.get('app_strings', 'LBL_YEAR'), 1600, 3000);
    } else {
        removeFromValidate(formName, dayId);
        removeFromValidate(formName, monthId);
    }
}

function validateDate()
{
    var ddlDay = $("#ddlDay");
    var ddlMonth = $("#ddlMonth");
    var ddlYear = $("#ddlYear");
 
    // check date was selected
    if (ddlDay[0].selectedIndex == 0 ||
        ddlMonth[0].selectedIndex == 0 ||
        ddlYear[0].selectedIndex == 0)
    {
        alert("date is required!");
        return false;
    }
 
    // check date is valid
    var date = new Date();
    date.setFullYear(ddlYear.val(), ddlMonth.val() - 1, ddlDay.val());
    var inputDate = ddlYear.val() + "-" + (ddlMonth.val() - 1) + "-" + ddlDay.val();
    var parsedDate = date.getFullYear() + "-" + date.getMonth() + "-" + date.getDate();
    // the parsed date will only match the input date if the input date is valid
    if (inputDate != parsedDate)
    {
        alert("date is invalid!");
        return false;
    }
 
    // date is valid
    return true;
}