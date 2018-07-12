$(document).ready(function() {
    $('#btn_sel_atc').live('click',function(){
        var student_list = $('#contacts_checked_str').val();
        if (typeof(student_list) == "undefined" || student_list == ''){
            alertify.alert("<span class=\"ui-icon ui-icon-alert\" style=\"float:left;\"></span> Chọn ít nhất 1 học viên để đưa vào lớp !!");
            return ;
        }
        open_popup("J_Class", 600, 400, "", true, false, {"call_back_function":"set_atc_return","form_name":"DetailView","field_to_name_array":{"id":"atc_class_id"}}, "single", true);
    });


    $('#atc_start_study, #atc_end_study').on('change',function(){
        //Validate Is Between and check valid input
        var rs1 = validateDateIsBetween($(this).val(), $('#atc_start_date').text(), $('#atc_end_date').text());
        if(!rs1) {
            $(this).val('').effect("highlight", {color: 'red'}, 1000);
            return ;
        }
        //Validate Data Lock
        if(!checkDataLockDate($(this).attr('id'),true))
            return ;
        var res = calSession($('#atc_start_study').val(), $('#atc_end_study').val());

    });

    $('.btn_submit_add_to_class').live( 'click', submitAddToClass);

    $('.btn_refresh_page').live( 'click', function(){
        $('.btn_refresh_page').hide();
        $('.btn_refresh_page').parent().append('<span id="atc_saving" >Reloading.. <img src="custom/include/images/loader.gif" align="absmiddle" width="16"></span>');
    });


    var contract_status = $('#status').val();
    if(contract_status != 'signed'){
        $('#contracts_contacts_create_button, #import_student, #contracts_contacts_select_button, #btn_sel_atc').hide();
        $('#contracts_contacts_create_button').closest('div').append('<span style="color:red;">Change Status = Signed before you handle it.</span>');
    }
});
//Function Adult
function set_atc_return(popup_reply_data){
    var form_name = popup_reply_data.form_name;
    var name_to_value_array = popup_reply_data.name_to_value_array;
    $('#atc_class_id').val(name_to_value_array.atc_class_id);
    get_class_info_adult();
}

function get_class_info_adult(){
    ajaxStatus.showStatus('Loading <img src="custom/include/images/loader32.gif" align="absmiddle" width="32">');
    $.ajax({
        type:"POST",
        url: "index.php?module=J_Payment&action=handleAjaxPayment&sugar_body_only=true",
        data:{
            type            : "get_class_info_adult",
            class_id        : $('#atc_class_id').val(),
            contract_id     : $('input[name=record]').val(),
        },
        dataType: "json",
        success:function(data){
            ajaxStatus.hideStatus();
            if (data.success == "1"){
                //Show Add To Class Dialog
                $('#atc_class_code').text(data.class_code);
                $('#atc_class_name').text(data.class_name);
                $('#atc_class_type').text(data.class_type);
                $('#atc_start_date').text(data.start_date);
                $('#atc_end_date').text(data.end_date);
                $('#atc_koc').text(data.koc);
                $('#atc_level').text(data.level);
                $('#atc_schedule').html(data.schedule);

                $('#atc_sessionList').val(data.session_list);
                $('#atc_remain_practice').val(data.remain_practice);
                $('#atc_remain_skill').val(data.remain_skill);
                $('#atc_remain_connect').val(data.remain_connect);
                $('#atc_start_study').val(data.start_study);
                $('#atc_end_study').val(data.end_study);
                calSession($('#atc_start_study').val(), $('#atc_end_study').val());

                //Show Dialog
                $('#add_to_class_dialog').dialog({
                    resizable    : false,
                    width        :'auto',
                    height       :'auto',
                    modal        : true,
                    position     : ['middle',40],
                    visible      : true,
                    beforeClose: function(event, ui) {
                        clearClassInfo();
                    },
                    buttons: {
                        "Submit":{
                            click:function() {
                            },
                            class    : 'button primary btn_submit_add_to_class',
                            text    : 'Submit',
                        },
                        "Cancel":{
                            click:function() {
                                $(this).dialog('close');
                            },
                            class    : 'button btn_cancel_add_to_class',
                            text    : 'Cancel',
                        },
                    },
                });
            }else{
                alertify.alert(data.notify);
            }
        },
        error: function(xhr, textStatus, errorThrown){
            ajaxStatus.hideStatus();
        }
    });
}

function submitAddToClass(){
    var start_study = $('#atc_start_study').val();
    var end_study   = $('#atc_end_study').val();
    var class_id    = $('#atc_class_id').val();
    var cal_session = $('#atc_session').val();
    var cal_session_remain = $('#atc_session_remain').val();
    if(start_study == '' || end_study == '' || class_id == '' || cal_session == 0){
        alertify.error('Please fill out fields completely !');
        return ;
    }
    if( cal_session_remain < 0){
        alertify.error('Sessions remaining are not enough !');
        return ;
    }
    $('.btn_submit_add_to_class, .btn_cancel_add_to_class').hide();
    $('.btn_submit_add_to_class').parent().append('<span id="atc_saving" >Saving.. <img src="custom/include/images/loader.gif" align="absmiddle" width="16"></span>');
    $.ajax({
        type: "POST",
        url: "index.php?module=J_Payment&action=handleAjaxPayment&sugar_body_only=true",
        data:  {
            type        : "submitAddToClass",
            start_study : start_study,
            end_study   : end_study,
            contract_id : $('input[name=record]').val(),
            student_list: $('#contacts_checked_str').val(),
            class_id    : class_id,
        },
        dataType: "json",
        success:function(data){
            if (data.success == "1"){
                $('#add_to_class_dialog').dialog('close');
                var notify = '';
                var count = 1;
                $.each(data.result, function( index, value ) {
                    var stu_res = $.parseJSON(value.result);
                    notify += count+' - '+value.student_name + ': '+
                    ((stu_res.success == '1') ?
                        '<span style="color: #169400; font-weight: bold;">Thành công</span>' :
                        ('<span style="color: #d14;  font-weight: bold;">Thất bại</span>' + '<br><span style="color: #d14;"> Lỗi: '+stu_res.notify+'</span>'));
                    notify += '<br><br>';
                    count++;

                });
                $('#add_to_class_notify').html(notify);
                $('#add_to_class_notify').dialog({
                    resizable    : false,
                    width        :'auto',
                    height       :'auto',
                    modal        : true,
                    visible      : true,
                    position     : ['middle',30],
                    beforeClose: function(event, ui) {
                        location.reload();
                        ajaxStatus.showStatus('Reloading <img src="custom/include/images/loader32.gif" align="absmiddle" width="32">');
                    },
                    buttons: {
                        "Refresh":{
                            click:function() {
                                location.reload();
                            },
                            class    : 'button primary btn_refresh_page',
                            text    : 'Refresh Page',
                        },
                    },
                });
            }
        },
    });
}
function calSession(start_study, end_study){
    var rs = new Object();
    var count_ss            = 0
    var total_hours         = 0;
    var remain_practice     = $('#atc_remain_practice').val();

    var json_sessions     = $("#atc_sessionList").val();
    if(start_study == '' || end_study == '' || json_sessions == ''){
        return ;
    }
    var start_study = SUGAR.util.DateUtils.parse(start_study,cal_date_format);
    var end_study   = SUGAR.util.DateUtils.parse(end_study,cal_date_format);

    obj = JSON.parse(json_sessions);
    var flag_start  = false;
    var flag_end    = false;

    $.each(obj, function( key, value){
        var key_date = Date.parse(value['date']);
        if(start_study.toDateString() == key_date.toDateString())
            flag_start = true;

        if(end_study.toDateString() == key_date.toDateString())
            flag_end = true;

        if( (key_date <= end_study) && (key_date >= start_study) ){
            count_ss++;
            total_hours = total_hours + Numeric.parse(value['hour']);
        }
    });

    if( !flag_start || !flag_end ){
        if(!flag_start){
            alertify.error('Start Study not in class schedule !');
            $('#atc_start_study').val('').effect("highlight", {color: 'red'}, 1000);
        }
        else if(!flag_end){
            alertify.error('Start Study not in class schedule !');
            $('#atc_end_study').val('').effect("highlight", {color: 'red'}, 1000);
        }
        count_ss    = '0';
        total_hours = '0';
    }
    $('#atc_cal_session').text(count_ss).effect("highlight", {color: 'blue'}, 1000);
    $('#atc_session').val(count_ss);
    if(remain_practice == 0){
        $('#atc_cal_session_remain').text('-unlimited-').effect("highlight", {color: 'blue'}, 1000);
        $('#atc_session_remain').val(0);
    }else{
        $('#atc_cal_session_remain').text(remain_practice - count_ss).effect("highlight", {color: 'blue'}, 1000);
        $('#atc_session_remain').val(remain_practice - count_ss);
    }
    $('#atc_cal_total_hours').text(Numeric.toFloat(total_hours,2,2)).effect("highlight", {color: 'blue'}, 1000);
}

function validateDateIsBetween(check_date, from, to){
    if(check_date == '') return true;
    check_date     = SUGAR.util.DateUtils.parse(check_date, cal_date_format);
    alertify.set({ delay: 10000 });
    if(from != '' && to != ''){
        from_check           = SUGAR.util.DateUtils.parse(from, cal_date_format).getTime();
        to_check             = SUGAR.util.DateUtils.parse(to, cal_date_format).getTime();
        if(check_date == false){
            alertify.error('Invalid date range. Date must be between '+from+' and '+to+'.');
            return false;
        }else{
            check_date = check_date.getTime();
            if(check_date < from_check || check_date > to_check){
                alertify.error('Invalid date range. Date must be between '+from+' and '+to+'.');
                return false;
            }
        }
    }else if(from != ''){
        from_check           = SUGAR.util.DateUtils.parse(from, cal_date_format).getTime();

        if(check_date < from_check){
            alertify.error('Invalid date range. Date must after '+from+'.');
            return false;
        }
    } else{
        to_check             = SUGAR.util.DateUtils.parse(to, cal_date_format).getTime();
        if(check_date > to_check){
            alertify.error('Invalid date range. Date must before '+to+'.');
            return false;
        }
    }
    return true;
}

function clearClassInfo(){
    $('#atc_class_code').text('');
    $('#atc_class_name').text('');
    $('#atc_class_type').text('');
    $('#atc_start_date').text('');
    $('#atc_end_date').text('');
    $('#atc_koc').text('');
    $('#atc_level').text('');
    $('#atc_cal_session').text('');
    $('#atc_cal_session_remain').text('');
    $('#atc_schedule').html('');
    $('#atc_cal_total_hours').text('');

    $('#atc_sessionList').val('');
    $('#atc_remain_practice').val('');
    $('#atc_remain_skill').val('');
    $('#atc_remain_connect').val('');
    $('#atc_start_study').val('');
    $('#atc_end_study').val('');
    $('#atc_session').val('');
    $('#atc_session_remain').val('');
}


Calendar.setup ({
    inputField : "atc_start_study",
    daFormat : cal_date_format,
    button : "atc_start_study_trigger",
    singleClick : true,
    dateStr : "",
    step : 1,
    weekNumbers:false
});
Calendar.setup ({
    inputField : "atc_end_study",
    daFormat : cal_date_format,
    button : "atc_end_study_trigger",
    singleClick : true,
    dateStr : "",
    step : 1,
    weekNumbers:false
});
