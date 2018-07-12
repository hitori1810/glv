/*********************************************************************************
 * By installing or using this file, you are confirming on behalf of the entity
 * subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
 * the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
 * http://www.sugarcrm.com/master-subscription-agreement
 *
 * If Company is not bound by the MSA, then by installing or using this file
 * you are agreeing unconditionally that Company will be bound by the MSA and
 * certifying that you have authority to bind Company accordingly.
 *
 * Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
 ********************************************************************************/

 
var CAL = {};

CAL.fillRepeatForm = function(data) {
    if(typeof data.repeat_parent_id != "undefined"){
        $("#cal-repeat-block").css('display', "none");
        $("#edit_all_recurrences_block").css('display', "");
        $("#edit_all_recurrences").val("");
        $("#repeat_parent_id").val(data.repeat_parent_id);
        return;
    }        
        
    $("#cal-repeat-block").css('display', "");
        
    var repeatType = "";
    var setDefaultRepeatUntil = true;
    if (typeof data.repeat_type != "undefined") {
        repeatType = data.repeat_type;
        
        document.forms['CalendarRepeatForm'].repeat_type.value = data.repeat_type;
        document.forms['CalendarRepeatForm'].repeat_interval.value = data.repeat_interval;
        if (data.repeat_count != '' && data.repeat_count != 0) {
            document.forms['CalendarRepeatForm'].repeat_count.value = data.repeat_count;
            $("#repeat_count_radio").prop('checked', true);
            $("#repeat_until_radio").prop('checked', false);
        } else {
            document.forms['CalendarRepeatForm'].repeat_until.value = data.repeat_until;
            $("#repeat_until_radio").prop('checked', true);
            $("#repeat_count_radio").prop('checked', false);
            setDefaultRepeatUntil = false;
        }
        if (data.repeat_type == "Weekly") {
            var arr = data.repeat_dow.split("");
            $.each(arr, function(i,d){
                $("#repeat_dow_" + d).prop('checked', true);
            });
        }
        
        $("#cal-repeat-block").css('display', "");
        $("#edit_all_recurrences_block").css('display', "none");
        toggle_repeat_type();
    }
    
    $("#edit_all_recurrences").val("true");
    
    if(typeof data.current_dow != "undefined" && repeatType != "Weekly") {
        $("#repeat_dow_" + data.current_dow).prop('checked', true);
    }
    
    if(typeof data.default_repeat_until != "undefined" && setDefaultRepeatUntil) {
        $("#repeat_until_input").val(data.default_repeat_until);
    }
}

CAL.editAllRecurrences = function() {
    disableOnUnloadEditView();
    document.forms['EditView'].elements['action'].value = 'editAllRecurrences';
    document.forms['EditView'].submit();
}

CAL.removeAllRecurrences = function() {
    if (confirm(SUGAR.language.get(document.forms['EditView'].elements['module'].value, 'LBL_CONFIRM_REMOVE_ALL_RECURRENCES'))) {
        disableOnUnloadEditView();
        document.forms['EditView'].elements['action'].value = 'removeAllRecurrences';
        document.forms['EditView'].submit();
    }
}

CAL.fillRepeatData = function() {
    document.forms['EditView'].repeat_type.value = '';
    if (repeatType = document.forms['CalendarRepeatForm'].repeat_type.value) {
        document.forms['EditView'].repeat_type.value = repeatType;
        document.forms['EditView'].repeat_interval.value = document.forms['CalendarRepeatForm'].repeat_interval.value;
        if (document.getElementById("repeat_count_radio").checked) {
            document.forms['EditView'].repeat_count.value = document.forms['CalendarRepeatForm'].repeat_count.value;
            document.forms['EditView'].repeat_until.value = "";
        } else {
            document.forms['EditView'].repeat_until.value = document.forms['CalendarRepeatForm'].repeat_until.value;
            document.forms['EditView'].repeat_count.value = "";
        }
        if (repeatType == 'Weekly') {
            var repeatDow = "";
            for (var i = 0; i < 7; i++) {
                if ($("#repeat_dow_" + i).prop('checked')) {
                    repeatDow += i.toString();
                }
            }
            $("#repeat_dow").val(repeatDow);
        }
    }
}

CAL.checkRecurrenceForm = function() {
    lastSubmitTime = lastSubmitTime - 2001;
    return check_form('CalendarRepeatForm');
}
