var is_generate = false;
$(document).ready(function(){

    //Không tạo ra Bug cho mình
    //Thì làm ơn đừng tạo ra bug cho người khác
    //Chú ý các đặt tên các thẻ ID của các input - Trung bug
    addToValidate('CancelView', 'cs_cancel_reason', 'text', true, 'Reason Cancel' );
    addToValidate('CancelView', 'cs_date_makeup', 'date', true, 'Date Make-up' );
    addToValidate('CancelView', 'cs_start', 'text', true, 'Start time' );
    $('.btn_check_cancel_session').live('click',function(){
        var _this = $(this);
        var date =  $('#cs_date_makeup').val();
        var start = $('input[name=cs_start]').val();
        var end =   $('input[name=cs_end]').val();
        $('#accept_schedule_change').val('0');
        $(this).parent().append('<span id = "cs_teacher_loading" >'+SUGAR.language.get('J_Class', 'LBL_LOADING')+'.. <img src="custom/include/images/loader.gif" align="absmiddle" width="16"></span>');
        $(this).hide();
        ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_AJAX_PLEASE_WAIT'));
        jQuery.ajax({
            url: "index.php?module=J_Class&sugar_body_only=true&action=handleAjaxJclass",
            type: "POST",
            data:{
                type    : "getTeacherandRoom",
                date    : date,
                start   : start,
                end     : end,
                class_id: $('input[name=record]').val(),
            },
            dataType: "json",
            success: function(data){
                ajaxStatus.hideStatus();
                $('#cs_teacher').append(data.teacher_options);
                $('#cs_room').html(data.room_options);
                $("#cs_teacher_loading").remove();
                $('.btn_check_cancel_session').show();
            },
            error: function(){
                ajaxStatus.hideStatus();
                alertify.error(SUGAR.language.get('app_strings','LBL_AJAX_ERROR'));
            },
        });
    });
    //end
    /* CHỈ CÓ TRUNG-BUG MỚI HIỂU */
    $('#cs_date_makeup').live('change',function(){
        //Validate Data Lock
        if(!checkDataLockDate($(this).attr('id'),true))
            return ;
        if(is_admin != '1'){
            var rs1 = validateDateIsBetween($(this).val(), $('#before_ss').val(), $('#after_ss').val());
            if(!rs1) {
                $(this).val('').effect("highlight", {color: 'red'}, 1000);
                return ;
            }
        }
    });
    $('#cs_teaching_type').live('change',function(){
        if($(this).val() != 'main_teacher' && $(this).val() != ''){
            $('.cancel_change_required').show();
            addToValidate('CancelView', 'cs_change_teacher_reason', 'text', true, 'Change Teacher Reason' );
        }else{
            $('.cancel_change_required').hide();
            removeFromValidate('CancelView', 'cs_change_teacher_reason')
        }
    });


});

function cancelSession(this_session){
    //Show dialog
    var session_id =   this_session.attr("id");
    $('#cancel_session_id').val(session_id);
    var div_template_id = "#cancel_dialog";
    var div_template = $(div_template_id);

    //get data for cancel template
    ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_AJAX_PLEASE_WAIT'));
    jQuery.ajax({
        url: "index.php?module=J_Class&sugar_body_only=true&action=handleAjaxJclass",
        type: "POST",
        async: false,
        data:{
            type: "getDataForCancelSession",
            class_id: $('input[name=record]').val(),
            session_id: session_id,
        },
        dataType: "json",
        success: function(data){
            $('#cs_date_makeup').val(data.session_date).attr('oldval',data.session_date);
            $('#cs_cancel_reason').val('');
            $('#cs_change_teacher_reason').val('');

            $('#cs_cancel_by').val('Student request');
            $('#cs_this_schedule').attr('checked',true);
            $('input[name=cs_start]').val(data.start_time).attr('oldval',data.start_time);
            $('input[name=cs_end]').val(data.end_time).attr('oldval',data.end_time);
            $('#cs_duration_hour').val(data.hour);

            // $('#cs_teacher').html("<option value=''>--None--</option>");
            $('#cs_room').html("<option value=''>--None--</option>");
            $('#cs_cancel_date').html(data.this_session_date);
            $('#cs_cancel_time').html(data.this_session_time);
            div_template.find('#before_ss').val(data.before);
            div_template.find('#after_ss').val(data.after);
            //this_session.attr('id', data.id);
        },
        error: function(){                                           
            alertify.error(SUGAR.language.get('app_strings','LBL_AJAX_ERROR'));
        },
    });

    var hour = $('#cs_duration_hour').val() + 0;
    if(!is_generate) {
        $(div_template_id + ' .timeOnly').each(function () {
            $(this).find(".time").eq(0).timepicker({
                'minTime': '6:00am',
                'maxTime': '9:30pm',
                'showDuration': false,
                'timeFormat': SUGAR.expressions.userPrefs.timef,
                'step': 15
            });
            $(this).find(".time").eq(1).timepicker({
                'showDuration': true,
                'timeFormat': SUGAR.expressions.userPrefs.timef,
                'step': 15
            });
            var timeOnlyDatepair = new Datepair(this, {
                'defaultTimeDelta': hour * 3600000 // milliseconds
            });

            $(this).on('rangeSelected', function(){
                var js_start    = SUGAR.util.DateUtils.parse($(this).find(".start").val(),SUGAR.expressions.userPrefs.timef);
                var js_end      = SUGAR.util.DateUtils.parse($(this).find(".end").val(),SUGAR.expressions.userPrefs.timef);
                var duration    = formatNumber(((js_end - js_start)/3600000),num_grp_sep,dec_sep,2,2);
                $('#cs_duration_hour').val(duration);
            });
        });
        is_generate = true;
    }   else {
        $('input[name=cs_start]').timepicker('setTime', $('input[name=cs_start]').attr('oldval'));
        $('input[name=cs_end]').timepicker('setTime', $('input[name=cs_end]').attr('oldval')).trigger('change');
    }
    setReadonlyField(true,div_template);

    $('input[type=radio][name="cs_makeup_type"]').live('change',function() {
        if (this.value == 'this_schedule')
            setReadonlyField(true,div_template);
        else
            setReadonlyField(false,div_template);

    });
    ajaxStatus.hideStatus();
    $(div_template_id).dialog({
        title: SUGAR.language.get('J_Class','TITLE_CANCEL_SESSION'),
        width: "800px",
        resizable: false,
        modal: true,
        buttons: [
            {
                text: SUGAR.language.get('J_Class','BTN_SAVE'),
                class    : 'button primary btn_save_cancel',
                click: function() {
                    saveCancelSession();
                }
            },
            {                   
                text: SUGAR.language.get('J_Class','BTN_CANCEL'),
                class    : 'button btn_cancel_cancel',
                click: function() {
                    clear_all_errors();
                    $(this).dialog("close");
                }
            }
        ]
    });

    $(div_template_id).effect("highlight", {color: '#228ef1'}, 1000);
}

function saveCancelSession(){
    if(check_form("CancelView")){
        $('#cancel_dialog').dialog("close"); 
        ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_AJAX_PLEASE_WAIT'));
        jQuery.ajax({
            url: "index.php?module=J_Class&sugar_body_only=true&action=handleAjaxJclass",
            type: "POST",
            dataType: "json",
            data:{
                type        : "cancelSession",
                record      : $('input[name=record]').val(),
                session_id  : $('#cancel_session_id').val(),
                cancel_by   : $('#cs_cancel_by').val(),
                cancel_reason: $('#cs_cancel_reason').val(),
                makeup_type : $('input[name="cs_makeup_type"]:checked').val(),
                date        : $('#cs_date_makeup').val(),
                canceldate  : $('#cs_cancel_date').text(),
                start       : $('input[name=cs_start]').val(),
                end         : $('input[name=cs_end]').val(),
                teacher     : $('#cs_teacher').val(),
                room        : $('#cs_room').val(),
                accept_schedule_change        : $('#accept_schedule_change').val(),
                duration_hour               : $('#cs_duration_hour').val(),
                cancel_teaching_type        : $('#cs_teaching_type').val(),
                cancel_change_teacher_reason: $('#cs_change_teacher_reason').val(),
            },
            success: function(data){             
                if(data.success == '1'){
                    alertify.success(SUGAR.language.get('app_strings','LBL_AJAX_SAVE_SUCCESS'));                                   
                    ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_AJAX_PLEASE_WAIT'));
                    ajaxStatus.hideStatus();
                    location.reload();
                }else{
                    if(data.html_situation != '' && typeof data.html_situation != 'undefined'){
                        $('#save_cancel_loading').remove();
                        $('#sn_table_error').html(data.html_situation);

                        $('#count_unpaid').val(data.count_unpaid);
                        show_dialog_change_schedule();
                    }else{              
                        alertify.error(SUGAR.language.get('app_strings','LBL_AJAX_ERROR'));
                        $('#sn_table_error').html('');                                                                                 
                        ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_AJAX_PLEASE_WAIT'));
                        location.reload();
                    }
                }
            },
        });
    }
}

function show_dialog_change_schedule(){
    var count_unpaid = $('#count_unpaid').val();
    $('#situa_notify_dialog').dialog({
        resizable    : false,
        width        :"800px",
        height       :'auto',
        modal        : true,
        visible      : true,
        position     : ['middle',30],
        closeOnEscape: false,
        open: function(event, ui) {
            $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
        },
        buttons: {
            "OK":{
                class    : 'button btn_accept_change',  
                text    : SUGAR.language.get('J_Class','LBL_CONFIRM_CHANGE_SCHEDULE_1'),
                click:function() {
                    $('#accept_schedule_change').val('1');
                    saveCancelSession();
                    $(this).dialog('close');
                },
            },
            "Cancel":{
                class    : 'button primary btn_unaccept_change',           
                text    : SUGAR.language.get('J_Class','LBL_CONFIRM_CHANGE_SCHEDULE_2'),
                click:function() {
                    $('#accept_schedule_change').val('0');
                    clear_all_errors();
                    $(this).dialog('close');
                },
            },
        },
    });
    if(count_unpaid > 0)
        $('.btn_accept_change').hide();
    else $('.btn_accept_change').show();
}

function setReadonlyField(is_readonly, div_template) {
    if (is_readonly) {
        $('input[name=cs_start]').attr('readonly','readonly').addClass('input_readonly');

        $('#cs_date_makeup').val($('#cs_date_makeup').attr('oldval')).addClass('input_readonly').prop('readonly',true);
        $('#cs_date_makeup_trigger').closest('span').hide();

        div_template.find('.start').timepicker('setTime', div_template.find('.start').attr('oldval'));
        div_template.find('.end').timepicker('setTime', div_template.find('.end').attr('oldval')).trigger('change');
    } else {
        $('input[name=cs_start]').removeAttr('readonly').removeClass('input_readonly');

        $('#cs_date_makeup').val($('#cs_cancel_date').text()).removeClass('input_readonly').prop('readonly',false);
        $('#cs_date_makeup_trigger').closest('span').show();

        div_template.find('.end').trigger('change');
    }
}

function deleteSession(_this) {
    alertify.confirm(SUGAR.language.get('J_Class', 'LBL_CONFIRM_DELETE_SESSION'),
        function(e){
            //console.log(e);
            if (e) {                                   
                ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_AJAX_PLEASE_WAIT'));
                jQuery.ajax({
                    url: "index.php?module=J_Class&sugar_body_only=true&action=handleAjaxJclass",
                    type: "POST",
                    dataType: "json",
                    data:{
                        type: "deleteSession",
                        record: $('input[name=record]').val(),
                        session_id: _this.attr('id'),
                    },
                    success: function(data){
                        ajaxStatus.hideStatus();                  
                        alertify.success(SUGAR.language.get('J_Class','LBL_LOAD_SUCCESSFULL'));
                        window.onbeforeunload = null;
                        showSubPanel('j_class_meetings', null, true);
                    },
                    error: function(){
                        ajaxStatus.hideStatus();                                    
                        alertify.error(SUGAR.language.get('app_strings','LBL_AJAX_ERROR'));
                        window.onbeforeunload = null;
                        showSubPanel('j_class_meetings', null, true);
                    },
                });

            } else {
                //alertify.error('Cancel');
            }
        }
    );
}

