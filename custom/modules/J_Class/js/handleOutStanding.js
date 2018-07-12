$(document).ready(function(){
    $('#start_outstanding, #end_outstanding').on('change',function(){
        //Validate Is Between and check valid input
        var rs1 = validateDateIsBetween($(this).val(), $('#start_date').text(), $('#end_date').text());
        if(!rs1) {
            $(this).val('').effect("highlight", {color: 'red'}, 1000);
            return ;
        }
        //Validate Data Lock
//        if(!checkDataLockDate($(this).attr('id'),true))
//            return ;

        var res = calSession($('#start_outstanding').val(), $('#end_outstanding').val());
        setValueToField(res.total_sessions, res.total_hours);
    });
    //==========================Handle ADD OutStanding ==========================//
    $('#add_out_btn').live('click', function(){
        $('#ot_add_type').val('Create');
        $('#start_outstanding').val($("#next_session_date").val());
        $('#end_outstanding').val($('#end_date').text());

    });

    //==========================Handle Edit OutStanding ==========================//

    $('.btn_edit_outstd').live('click', function(){
        $('#ot_add_type').val('Edit');
        $('#start_outstanding').val($(this).attr('start_study'));
        $('#end_outstanding').val($(this).attr('end_study'));
        $('#ot_situation_id').val($(this).attr('situa_id'));

        var popup_reply_data = {};
        popup_reply_data['name_to_value_array'] = {};
        popup_reply_data['name_to_value_array']['ot_student_name']	 	= $(this).attr('student_name');
        popup_reply_data['name_to_value_array']['ot_student_id'] 		= $(this).attr('student_id');
        //SHOW DIALOG
        set_ot_return_student(popup_reply_data);
    });

    //==========================Handle DELETE OutStanding ==========================//
    $('.btn_delete_outstd').live('click', function(){
        $('#ot_add_type').val('Delete');
        $('#ot_situation_id').val($(this).attr('situa_id'));
        $('#ot_student_id').val($(this).attr('student_id'));
        if (confirm('Are you sure you want to delete this Outstanding!'))
            ajaxAddOutstanding();
    });
    showDialogOutstanding();
});

//Mở Pop Up chọn học viên  - Rất đơn giản
function open_popup_add_outstanding(){
    if( (current_status.value == 'Closed' || current_status.value == 'Finish') && is_admin != '1' ){
        alertify.alert("<span class=\"ui-icon ui-icon-alert\" style=\"float:left;\"></span> "+SUGAR.language.get('J_Class', 'LBL_NOT_ACTION_OPERATION')+current_status.value+" !!");
        return ;
    }
    open_popup("Contacts", 600, 400, "", true, false, {"call_back_function":"set_ot_return_student","form_name":"DetailView","field_to_name_array":{"id":"ot_student_id","name":"ot_student_name"}}, "single", true);
}

//Trả về pop-up và Show dialog - Rất đơn giản
function set_ot_return_student(popup_reply_data){
    var form_name = popup_reply_data.form_name;
    var name_to_value_array = popup_reply_data.name_to_value_array;
    $('#ot_student_name').text(name_to_value_array.ot_student_name);
    $('#ot_student_id').val(name_to_value_array.ot_student_id);
    showDialogOutstanding();
}

//Show dialog Demo
function showDialogOutstanding(){
    var ot_student_id 		= $('#ot_student_id').val();
    var ot_student_name 	= $('#ot_student_name').text();
    if(dm_student_id == '' || ot_student_name == '') return false;

    $("body").css({ overflow: 'hidden' });
    $('#diaglog_outstanding').dialog({
        resizable	: false,
        width		: 900,
        height		:'auto',
        modal		: true,
        visible		: true,
        position	: ['center',130],
        beforeClose: function(event, ui) {
            $("body").css({ overflow: 'inherit' });
        },
        buttons: {
            "Add Outstanding":{
                click:function() {
                    ajaxAddOutstanding();
                },
                class	: 'button primary btn_add_outstanding',
                text    : SUGAR.language.get('J_Class', 'LBL_SAVE'),
            },
            "Cancel":{
                click:function() {
                    $(this).dialog('close');
                },
                class	: 'button btn_cancel_dialog_outstanding',
                text    : SUGAR.language.get('J_Class', 'BTN_CANCEL'),
            },
        },
    });
    var res = calSession($('#start_outstanding').val(), $('#end_outstanding').val());
    setValueToField(res.total_sessions, res.total_hours);
}

function ajaxAddOutstanding(){                                                                                              
    ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_AJAX_PLEASE_WAIT'));
    $('.btn_add_outstanding, .btn_cancel_dialog_outstanding').prop('disabled',true);
    $.ajax({
        type: "POST",
        url: "index.php?module=J_Class&action=handleAjaxJclass&sugar_body_only=true",
        data:  {
            student_id          : $('#ot_student_id').val(),
            class_id            : $('input[name=record]').val(),
            start_outstanding   : $('#start_outstanding').val(),
            end_outstanding     : $('#end_outstanding').val(),
            total_hours      	: $('#ot_total_hours').val(),
            type               	: "ajaxAddOutstanding",
            add_type         	: $('#ot_add_type').val(),  //Create, Edit, Delete
            situation_id        : $('#ot_situation_id').val(),
        },
        dataType: "json",
        success:function(data){
            if (data.success == "1") {
                handle_filter();
                $('#diaglog_outstanding').dialog("close");
                //				showSubPanel('j_class_studentsituations', null, true);
                alertify.success(data.notify);
            }else
                alertify.error(data.error);
            ajaxStatus.hideStatus();
            $('.btn_add_outstanding, .btn_cancel_dialog_outstanding').prop('disabled',false);
        },
    });
}

function setValueToField(total_sessions, total_hours){
    $('#ot_total_sessions').val(total_sessions);
    $('#ot_total_hours').val(total_hours);
    $('#ot_total_sessions_text').text(total_sessions).effect("highlight", {color: 'red'}, 1000);
    $('#ot_total_hours_text').text(formatNumber(total_hours,num_grp_sep,dec_sep,2,2)).effect("highlight", {color: 'red'}, 1000);
}

function calSession(start_study, end_study){
    var rs = new Object();
    var count_ss 		= 0
    var total_hours 	= 0;

    var json_sessions 	= $("#json_sessions").val();
    if(start_study != '' && end_study != '' && json_sessions != ''){
        var start_study = SUGAR.util.DateUtils.parse(start_study,cal_date_format);
        var end_study   = SUGAR.util.DateUtils.parse(end_study,cal_date_format);

        obj = JSON.parse(json_sessions);
        var flag_start 	= false;
        var flag_end 	= false;
        $.each(obj, function( key, value ) {
            var key_date = Date.parse(key);
            if(start_study.toDateString() == key_date.toDateString())
                flag_start = true;

            if(end_study.toDateString() == key_date.toDateString())
                flag_end = true;

            if( (key_date <= end_study) && (key_date >= start_study) ){
                count_ss++;
                total_hours = total_hours + parseFloat(value);
            }
        });

        if( !flag_start || !flag_end ){
            if(!flag_start) alertify.error(SUGAR.language.get('J_Class', 'LBL_DATE_START_NOT_IN_SCHEDULE'));
            else if(!flag_end) alertify.error(SUGAR.language.get('J_Class', 'LBL_DATE_END_NOT_IN_SCHEDULE'));
            count_ss = '0';
            total_hours = '0';
        }
    }
    rs['total_sessions'] 	= count_ss;
    rs['total_hours'] 		= total_hours;
    return rs;
}

function validateDateIsBetween(check_date, from, to){
    if(check_date == '') return true;
    check_date 	= SUGAR.util.DateUtils.parse(check_date, cal_date_format);
    alertify.set({ delay: 10000 });
    if(from != '' && to != ''){
        from_check           = SUGAR.util.DateUtils.parse(from, cal_date_format).getTime();
        to_check             = SUGAR.util.DateUtils.parse(to, cal_date_format).getTime();
        if(check_date == false){
            alertify.error(SUGAR.language.get('J_Class', 'LBL_INVALID_DATE_RANGE_DATE_MUST_BETWEEN')+from+SUGAR.language.get('J_Class', 'LBL_AND')+to+'.');
            return false;
        }else{
            check_date = check_date.getTime();
            if(check_date < from_check || check_date > to_check){
                alertify.error(SUGAR.language.get('J_Class', 'LBL_INVALID_DATE_RANGE_DATE_MUST_BETWEEN')+from+SUGAR.language.get('J_Class', 'LBL_AND')+to+'.');
                return false;
            }
        }
    }else if(from != ''){
        from_check           = SUGAR.util.DateUtils.parse(from, cal_date_format).getTime();

        if(check_date < from_check){
            alertify.error(SUGAR.language.get('J_Class', 'LBL_INVALID_DATE_RANGE_DATE_MUST_AFTER')+from+'.');
            return false;
        }
    } else{
        to_check             = SUGAR.util.DateUtils.parse(to, cal_date_format).getTime();
        if(check_date > to_check){
            alertify.error(SUGAR.language.get('J_Class', 'LBL_INVALID_DATE_RANGE_DATE_MUST_BEFORE')+to+'.');
            return false;
        }
    }
    return true;
}
Calendar.setup ({
    inputField : "start_outstanding",
    daFormat : cal_date_format,
    button : "start_outstanding_trigger",
    singleClick : true,
    dateStr : "",
    step : 1,
    weekNumbers:false
});
Calendar.setup ({
    inputField : "end_outstanding",
    daFormat : cal_date_format,
    button : "end_outstanding_trigger",
    singleClick : true,
    dateStr : "",
    step : 1,
    weekNumbers:false
});