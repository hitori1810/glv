var record_id       = $('input[name=record]').val();
var duplicate_id    = $('input[name=duplicateId]').val();
var aims_id         = $('input[name=aims_id]').val();
if (typeof duplicate_id == 'undefined')
    duplicate_id = '';
var ug_koc = '';
var ug_level = '';
var ug_module = '';
$(document).ready(function(){
    //Fix bug Upgrade Class - Do not input this field
    $('#j_class_j_class_1_name').prop('readonly',true).attr('title','Please, select !!');
    // Set TimePicker
    setTimePickerAll();
    // case Duplicate
    if(duplicate_id != "" ){
        ug_koc      = $('#kind_of_course').val();
        ug_level    = $('#level').val();
        ug_module   = $('#modules').val();
        generateSchedule($('input#content').val(), $('#j_class_j_class_1j_class_ida').val());
    }

    toggleTimeslots();
    $('input[name=week_date]').change(function(){
        toggleTimeslots();
        ajaxMakeJsonSession();
    });
    $('#start_date').live('change',function(){
        ajaxMakeJsonSession();
    });

    //Handle Button Clear Schedule
    $('#btn_clr_time').click(function(){
        clrSelection();
    });
    //Handle Create / Edit
    generateOption();
    if(record_id == ''){
        addToValidate('EditView', 'validate_weekday', 'varchar', true, 'Weekdays' );
        getClassHours();
        $("#change_date_from").closest("tr").hide();
        $("#btn_edit_schedule").closest("tr").hide();
    }
    else{
        //Validate Data Lock
        $('#change_date_from').live('change',function(){
            if(!checkDataLockDate('change_date_from', false))
                return ;
        });
        $('#validate_weekday, #timeframe_panel').closest('tr').hide();
        $('#btn_edit_schedule, #btn_edit_startdate').show();
        //    if(aims_id == '' || is_admin == '1'){  //AIMS CODE
        $('#start_date_trigger').hide();
        $('#hours, #start_date').prop('readonly',true).addClass('input_readonly');
        if(is_admin != '1')
            $('#kind_of_course, #level, #modules').prop('disabled',true).addClass('input_readonly');
        //Lock Team
        $('#EditView_team_name_table').find('input').prop('readonly',true).addClass('input_readonly');
        //    }
    }
    //Lock Team
    $('#teamSelect, #teamAdd, #remove_team_name_collection_0').prop('disabled',true);
    $("#change_reason_label").closest("tr").hide();


    $('#kind_of_course, #level').live('change',function(){
        $('#koc_id').val($('#kind_of_course option:selected').attr('koc_id'));
        $('#short_course_name').val($('#kind_of_course option:selected').attr('short_course_name'));
        generateOption();
        if(record_id == '')
            ajaxMakeJsonSession();
    });

    $('#class_type_adult').on('change',function(){
        if($(this).val() != 'Practice')
            $('#level').prop("multiple", true).attr('name','level[]');
        else $('#level').prop("multiple", false).attr('name','level');

        generateOption();
    });

    $('#modules').on('change',function(){
        getClassHours();
    });
    $('.revenue_hour').on('change',function(){
        ajaxMakeJsonSession();
    });
    $('#hours').on('blur',function(){
        ajaxMakeJsonSession();
    });

    //Handle change startdate
    $('#btn_edit_startdate').on('click',function(){
        $(this).hide();
        $('#btn_edit_schedule').hide();
        $('#start_date').prop('readonly',false).removeClass('input_readonly');
        $('#start_date_trigger').show();

        $('#change_reason_label').closest('tr').show();
        $('#validate_weekday, #timeframe_panel').closest('tr').show();
        $('#class_case').val('change_startdate');
        if(is_admin != '1' && current_user_name != 'crm.bot5')
            addToValidate('EditView', 'change_reason', 'text', true,'Change Reason' );
        addToValidate('EditView', 'validate_weekday', 'varchar', true, 'Weekdays' );
    });
    //Handle edit schedule
    $('#btn_edit_schedule').on('click',function(){
        $(this).hide();
        $('#btn_edit_startdate').hide();
        $('#change_date_from_label, #change_date_to_label, #change_date_from_span, #change_date_to_span').show();
        $('#change_reason_label').closest('tr').show();
        $('#validate_weekday, #timeframe_panel').closest('tr').show();
        $('#class_case').val('change_schedule');
        $('#change_date_to').val($('#end_date').val());
        addToValidate('EditView', 'change_date_from', 'date', true,'Change From Date' );
        addToValidate('EditView', 'change_date_to', 'date', true,'Change To Date' );
        if(is_admin != '1' && current_user_name != 'crm.bot5')
            addToValidate('EditView', 'change_reason', 'text', true,'Change Reason' );
        addToValidate('EditView', 'validate_weekday', 'varchar', true, 'Weekdays' );
    });
    $('#change_date_from_trigger_div_t tbody, #container_change_date_from_trigger #callnav_today').live('click',function(){
        validateChangeFrom();
    });

    $('#change_date_to_trigger_div_t tbody, #container_change_date_to_trigger #callnav_today').live('click',function(){
        validateChangeTo();
    });


    $('#change_date_from').on('change',validateChangeFrom);
    $('#change_date_to').on('change',validateChangeTo);



    $('#btn_clr_j_class_j_class_1_name').on('click',function(){
        $('#upgrade_class_info').remove();
    });

    //Remove SQS
    sqs_objects['EditView_j_class_j_class_1_name'] = {};

    var crm_bot = [ 'crm.bot1','crm.bot2','crm.bot3','crm.bot4','crm.bot5'];
    if(aims_id != '' && $('#main_schedule').val() != '' && is_admin != '1' && jQuery.inArray( current_user_name, crm_bot ) > -1){
        $('#hours').prop('readonly',false).removeClass('input_readonly');
        $('#btn_edit_schedule').trigger('click');
        removeFromValidate('EditView','change_reason');
    }  //AIMS CODE
    //Validate Data Lock
    if(!checkDataLockDate('start_date', false, false))
        $('#btn_edit_startdate').hide();


    $('.addTimeSlot').live('click',function(){
        addTimeSlot($(this));
        ajaxMakeJsonSession();
    });
    $('.removeTimeSlot').live('click',function(){
        var sc_index = $(this).closest('tr').index();
        $(this).closest('table').closest('tr').find('td.hour_item').find('tbody tr').eq(sc_index).remove();
        $(this).closest('tr').remove();
        ajaxMakeJsonSession();
    });
});

function addTimeSlot(_this){
    var sc_html = $('<tr><td><p class="timeOnly"><input class="time start" type="text" style="width: 70px; text-align: center;"> - to - <input class="time end" type="text" style="width: 70px; text-align: center;"></p></td><td><span class="id-ff multiple ownline"><button type="button" style="margin-bottom: 0px;" class="button removeTimeSlot" value="Remove" title="Remove Time Slot"><img src="themes/default/images/id-ff-remove-nobg.png" alt="Remove Time Slot"></button></span></td></tr>');
    var dr_html = $('<tr><td><input type="text" class="duration_hour input_readonly" readonly style="width: 70px; text-align: center;"></td><td style="display: none;"><input type="text" class="revenue_hour" style="width: 70px; text-align: center;"></td><td style="display: none;"><input type="text" class="teaching_hour" style="width: 70px; text-align: center;"></td></tr>');
    _this.closest('tbody').append(sc_html);
    _this.closest('table').closest('tr').find('td.hour_item').find('tbody').append(dr_html);
    //Set DatePair
    var timeOnly = sc_html.find('.timeOnly');
    setTimePicker(timeOnly);
}
// Show/Hide Timeslot, Mapping weekday and Timeslot and Clear Input
function toggleTimeslots(){
    var count_selected = 0;
    $('input[name=week_date]').each(function () {
        var week_row = "#TS_"+$(this).val();
        if($(this).is(":checked")){
            $(week_row).show();
            count_selected++
        }else{
            $(week_row).hide();
            $(week_row+' :input').val('');
        }
    });
    //Catch validate Weekday
    if(count_selected == 0 )
        $('#validate_weekday').val('');
    else
        $('#validate_weekday').val('1');
}

function getClassHours(){
    if(record_id != ''){
        return false;
    }else{
        var class_type_adult       = $('#class_type_adult').val();
        var arr_type               = [ "Skill", "Connect Club", "Connect Event"];
        if(aims_id == ''){
            var kind_of_course = $('#kind_of_course').val();
            var level_selected = $('#level').val();
            var objs =  $.parseJSON($('#kind_of_course :selected').attr('json'));

            if ( (record_id == '') && (kind_of_course == "" || (($('#level option').size() == 1) && kind_of_course == ""))){
                $('#hours').val("");
                return false;
            }
            $.each( objs, function( key, koc ) {
                //Fix issue Adult
                if((class_type_adult !='') && (typeof class_type_adult !== 'undefined') &&  ($.inArray(class_type_adult, arr_type) != -1)){
                    $('#hours').prop('readonly',false).removeClass('input_readonly');
                }else if(koc.levels == level_selected){
                    $('#hours').val(koc.hours);

                    if(koc.is_set_hour == '1' || typeof koc.is_set_hour == 'undefined')
                        $('#hours').prop('readonly',true).addClass('input_readonly');
                    else
                        $('#hours').prop('readonly',false).removeClass('input_readonly');
                }
            });
        }
    }
}

//showing Kind Of Course
function generateOption(){
    var kind_of_course = $('#kind_of_course').val();
    var level_selected = $('#level').val();
    var module_selected = $('#modules').val();
    var objs =  $.parseJSON($('#kind_of_course :selected').attr('json'));

    //Adult variable
    var team_type              = $('#team_type').val();
    //Generate options level
    if(kind_of_course != '' && kind_of_course != null && aims_id == ''){
        $('#hours').prop('readonly',true).addClass('input_readonly');
        $('#level').prop('disabled',false).removeClass('input_readonly');

        // Clear all select list items except first one
        $('#level').find('option:gt(0)').remove();
        $.each( objs, function( key, obj ) {
            if(obj.levels != '')
                $('#level').append('<option label="'+obj.levels+'" value="'+obj.levels+'">'+obj.levels+'</option>');
        });
        if ($('#level option').size() == 1){
            $('#level').prop('disabled',true).addClass('input_readonly');
            $('#modules').prop('disabled',true).addClass('input_readonly');
        }
        else {
            $('#level').prop('disabled',false).removeClass('input_readonly');
        }
        $('#level').val(level_selected);

        //Generate options module
        $('#modules').find('option:gt(0)').remove();
        if(level_selected != '' && level_selected != null){
            $.each( objs, function( key, koc ) {
                if(koc.levels == level_selected){
                    $.each( koc.modules, function( key, module ) {
                        if(module != "")
                            $('#modules').append('<option label="'+module+'" value="'+module+'">'+module+'</option>');
                    });
                    //If level do not have module
                    if ($('#modules option').size() == 1)
                        $('#modules').prop('disabled',true).addClass('input_readonly');
                    else
                        $('#modules').prop('disabled',false).removeClass('input_readonly');
                }
            });
            $('#modules').val(module_selected);
        }else
            $('#modules').prop('disabled',true).addClass('input_readonly');

    }
    getClassHours();
}

//Ajax Cal Ending Date
function ajaxMakeJsonSession(){
    var schedule            = {};
    var start_date          = $('#start_date').val();
    var flag_ajax           = 0;
    var class_case          = $('#class_case').val();
    var change_date_from    = $('#change_date_from').val();
    var change_date_to      = $('#change_date_to').val();
    var team_type           = $('#team_type').val();

    $('input[name=week_date]').each(function(){
        if($(this).is(':checked')){
            schedule[$(this).val()]= new Array();
            var rvn_hours       = $("#TS_"+$(this).val()).find(".revenue_hour");
            var tea_hours       = $("#TS_"+$(this).val()).find(".teaching_hour");
            var duration_hours  = $("#TS_"+$(this).val()).find(".duration_hour");
            var start_times     = $("#TS_"+$(this).val()).find(".start");
            var end_times       = $("#TS_"+$(this).val()).find(".end");
            if( (rvn_hours.length == tea_hours.length)  && (tea_hours.length == duration_hours.length) && (duration_hours.length == start_times.length) && (start_times.length == end_times.length)){
                for (ind = 0; ind < rvn_hours.length; ind++) {
                    var rvn_hour        = rvn_hours[ind].value;
                    var tea_hour        = tea_hours[ind].value;
                    var duration_hour   = duration_hours[ind].value;
                    var start_time      = start_times[ind].value;
                    var end_time        = end_times[ind].value;

                    if(start_time == '' || end_time == '' || duration_hour <= 0 || duration_hour == ''){
                        flag_ajax += 1;
                    }else{
                        schedule[$(this).val()][ind]                     = {};
                        schedule[$(this).val()][ind]['start_time']       = start_time;
                        schedule[$(this).val()][ind]['end_time']         = end_time;
                        schedule[$(this).val()][ind]['duration_hour']    = duration_hour;
                        schedule[$(this).val()][ind]['revenue_hour']     = rvn_hour;
                        schedule[$(this).val()][ind]['teaching_hour']    = tea_hour;
                    }
                }
            }else
                flag_ajax += 1;

        }
    });
    if(record_id == "" || $('#class_case').val() == 'change_startdate'){
        $('#main_schedule').val(JSON.stringify(schedule));
    }
    if($('#class_case').val() == 'change_schedule' && $('#change_date_from').val() == '') {
        flag_ajax += 1;
    }
    if($('#class_case').val() == 'change_schedule') {
        $('#history_temp').val(JSON.stringify(schedule));
    }

    //check start date
    $('#notification_weekdate').remove();
    if($('input[name=week_date]:checked').length != 0)
        checkStartDate();

    //checking run validate ajax
    if(flag_ajax > 0 || $('input[name=week_date]:checked').length == 0 || $('#hours').val() == '')
        return false;
    else
        $('#end_date').val(''); // Run Ajax

    //get hour test of Kind of Course
    var level_selected = $('#level').val();
    var objs =  $.parseJSON($('#kind_of_course :selected').attr('json'));
    $('#accept_schedule_change').val('0') //Update change lich
    if(start_date != '' && schedule != ''){     
        ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_AJAX_PLEASE_WAIT'));
        $.ajax({
            url: "index.php?module=J_Class&action=handleAjaxJclass&sugar_body_only=true",
            type: "POST",
            async: true,
            data:
            {
                type            : 'ajaxMakeJsonSession',
                class_id        : record_id,
                class_case      : class_case,
                change_date_from: change_date_from,
                change_date_to  : change_date_to,
                start_date      : start_date,
                schedule        : schedule,
                team_type       : team_type,
                total_hours     : $('input#hours').val(),
                change_reason   : $('#change_reason').val(),
            },
            dataType: "json",
            success: function(res){
                ajaxStatus.hideStatus();
                if(res.success == "1"){
                    $('input#content').val(res.content);
                    var json =  jQuery.parseJSON(res.content);
                    var end_date = new Date(json.end_date);

                    var end_date_format = SUGAR.util.DateUtils.formatDate(end_date);
                    $('#end_date').val(end_date_format);
                    $('#end_date').effect("highlight", {color: '#ff9933'}, 2000);

                    if(json.new_start_Date != "" && typeof json.new_start_Date != 'undefined' && typeof json.holidays != 'undefined'){
                        var holiday_alert = "The Holidays in this duration:\n";
                        $.each(json.holidays, function( index, value ) {
                            holiday_alert += "- "+index+": "+value+"\n";
                        });
                        holiday_alert += "\nStart date will change to: " + json.new_start_Date;
                        alertify.alert(holiday_alert);
                        $("#start_date").val(json.new_start_Date);
                    }
                    if(json.html_situation != '' && typeof json.html_situation != 'undefined'){
                        $('#sn_table_error').html(json.html_situation);

                        $('#count_unpaid').val(json.count_unpaid);
                        show_dialog_change_schedule();

                    }else
                        $('#sn_table_error').html('');

                }
                else
                    $('#end_date').val('');
            },
        });
    }else{
        $('#end_date').val('');
        return ;
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
                    $(this).dialog('close');
                },
            },
            "Cancel":{
                class    : 'button primary btn_unaccept_change', 
                text    : SUGAR.language.get('J_Class','LBL_CONFIRM_CHANGE_SCHEDULE_2'),
                click:function() {
                    $(this).dialog('close');
                },
            },
        },
    });
    if(count_unpaid > 0)
        $('.btn_accept_change').hide();
    else $('.btn_accept_change').show();
}
//Validate Change To
function validateChangeTo(){
    $from   = SUGAR.util.DateUtils.parse($('#change_date_from').val(),cal_date_format).getTime();
    $to     = SUGAR.util.DateUtils.parse($('#root_end_date').val(),cal_date_format).getTime();
    //get date change
    $date_change = SUGAR.util.DateUtils.parse($('#change_date_to').val(),cal_date_format);
    if($date_change==false){
        alertify.error(SUGAR.language.get('J_Class','LBL_INVALID_DATE_RANGE')); 
        $('#change_date_to').val('');
    }else{
        $change = $date_change.getTime();
        if($change < $from || $change > $to){                                     
            alertify.error(SUGAR.language.get('J_Class','LBL_INVALID_DATE_RANGE')); 
            $('#change_date_to').val('');
        }else
            ajaxMakeJsonSession();
    }
}

//Validate Change From
function validateChangeFrom(){
    $from   = SUGAR.util.DateUtils.parse($('#root_start_date').val(),cal_date_format).getTime();
    $to     = SUGAR.util.DateUtils.parse($('#root_end_date').val(),cal_date_format).getTime();
    //get date change
    $date_change = SUGAR.util.DateUtils.parse($('#change_date_from').val(),cal_date_format);
    if($date_change==false){                                                   
        alertify.error(SUGAR.language.get('J_Class','LBL_INVALID_DATE_RANGE')); 
        $('#change_date_from').val('');
    }else{
        $change = $date_change.getTime();
        if($change < $from || $change > $to){                                   
            alertify.error(SUGAR.language.get('J_Class','LBL_INVALID_DATE_RANGE')); 
            $('#change_date_from').val('');
        }else
            ajaxMakeJsonSession();
    }
}

//Overwrite function set_return: Handle upgrade class
function set_class_return(popup_reply_data){
    $('#upgrade_class_info').remove();
    var form_name = popup_reply_data.form_name;
    var name_to_value_array = popup_reply_data.name_to_value_array;
    if(name_to_value_array.isupgrade != 0){
        label = SUGAR.language.get('J_Class','LBL_ERROR_CLASS_IS_UPGRADED');
        label = label.replace("class_name", name_to_value_array.j_class_j_class_1_name);
        alertify.alert(label);
        return false;
    }
    if(name_to_value_array.class_type == 'Waiting Class'){
        label = SUGAR.language.get('J_Class','LBL_ERROR_CLASS_IS_WAITING_CLASS');
        label = label.replace("class_name", name_to_value_array.j_class_j_class_1_name);                                                   
        alertify.alert(label);
        return false;
    }
    for (var the_key in name_to_value_array) {
        if (the_key == 'toJSON') {
            continue;
        } else {
            var val = name_to_value_array[the_key].replace(/&amp;/gi, '&').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&#039;/gi, '\'').replace(/&quot;/gi, '"');
            switch (the_key)
            {
                case 'j_class_j_class_1j_class_ida':
                    $('[name="j_class_j_class_1j_class_ida"]').val(val); // Edit by Tung Nguyen 13-6-2018
                    //$('#j_class_j_class_1j_class_ida').val(val);
                    var upgrade_id = val;
                    break;
                case 'j_class_j_class_1_name':
                    $('#j_class_j_class_1_name').val(val);
                    var upgrade_name = val;
                    break;
                case 'c_teachers_j_class_1c_teachers_ida':
                    if(record_id == '')
                        $('#c_teachers_j_class_1c_teachers_ida').val(val);
                    break;
                case 'c_teachers_j_class_1_name':
                    if(record_id == ''){
                        $('#c_teachers_j_class_1_name').val(val);
                        $('#c_teachers_j_class_1_name').effect("highlight", {color: '#ff9933'}, 3000);
                    }
                    break;
                case 'kind_of_course':
                    if(record_id == ''){
                        $('#kind_of_course').val(val);
                        $('#kind_of_course').effect("highlight", {color: '#ff9933'}, 3000);
                        ug_koc = val;
                    }
                    break;
                case 'level':
                    if(record_id == ''){
                        $('#level').val(val);
                        $('#level').effect("highlight", {color: '#ff9933'}, 3000);
                        ug_level = val;
                    }
                    break;
                case 'modules':
                    if(record_id == ''){
                        $('#modules').val(val);
                        $('#modules').effect("highlight", {color: '#ff9933'}, 3000);
                        ug_module = val;
                    }
                    break;
                case 'content':
                    if(record_id == '')
                        var content = val;
                    break;
            }
        }
    }
    //Append btn More Info
    generateSchedule(content, upgrade_id);

}

// check the Start date of course
function checkStartDate() {
    var class_case = $('#class_case').val();
    var start_date = $('#start_date').val();
    $('#notification_weekdate').remove();
    if($('input[name=week_date]:checked').length == 0) return true;
    if( class_case=='change_startdate' || class_case=='create'){
        var daysOfWeek = new Array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
        var weekdate_checking =  daysOfWeek[SUGAR.util.DateUtils.parse(start_date,cal_date_format).getDay()];

        var count = 0;
        $('input[name=week_date]:checked').each(function () {
            if($(this).val() == weekdate_checking)
                count ++;
        });
        if(count == 0){
            $('input[name=week_date]').closest('td').append("<div id='notification_weekdate' class='required validation-message'>"+SUGAR.language.get('J_Class','LBL_CHECK_DATE_ERROR')+"</div>");
            return false;
        }
        else return true;
    }
    else
        return true;
}

// check Duplicate class name
function checkDuplicate(){
    var cr_koc      = $('#kind_of_course').val();
    var cr_level    = $('#level').val();
    var cr_module   = $('#modules').val();
    var upgrade_id  = $('#j_class_j_class_1j_class_ida').val();
    if( (upgrade_id != '') && (typeof upgrade_id != 'undefined') && (ug_koc == cr_koc) && (ug_level == cr_level) && (ug_module == cr_module)){
        alertify.error(SUGAR.language.get('J_Class','LBL_ERROR_UPDATE_CLASS_WITH_SAME_LEVEL'));
        return false;
    }else
        return true;
}

//Overwrite check_form to validate
function check_form(formname) {
    var accept_sche = $('#accept_schedule_change').val() == '1' ? true : false;
    var sn_table_error = $('#sn_table_error').html();
    if(!accept_sche && sn_table_error != '')
        show_dialog_change_schedule();
    else
        accept_sche = true;

    //Validate timepicker
    var validate_days = true;
    $('input[name=week_date]').each(function () {
        var week_row = "#TS_"+$(this).val();
        if($(this).is(":checked")){
            $(week_row+' :input:not(:checkbox):not(:button)').each(function () {
                if($(this).val() == ''){
                    validate_days = false;
                    $(this).effect("highlight", {color: '#FF0000'}, 2000);
                }
            });
        }
    });
    if($('#class_case').val() == 'change_schedule'){
        var history                 = {};
        var schedule                = jQuery.parseJSON($('#history_temp').val());
        history['change_from']      =  $('#change_date_from').val();
        history['change_to']        =  $('#change_date_to').val();
        history['schedule']         =  schedule;
        history['change_reason']    =  $('#change_reason').val();
        $('#history').val(JSON.stringify(history));
    }

    if(validate_form(formname, '') && validate_days && checkStartDate() && checkDuplicate() && alertUpgradeClass() && accept_sche)
        return true;
    else return false;
}

//Generate suggest selected schedule
function generateSchedule(content, upgrade_id){
    if(record_id == ''){
        clrSelection();
        // Generate schedule from class upgrade
        var day_schedule = [];

        var json =  jQuery.parseJSON(content);
        if(json != null && typeof json !== "undefined"){
            $.each( json.schedule , function( key, obj ){
                $('input[id='+key+']').prop('checked',true).trigger('change');
                for (ind = 0; ind < obj.length; ind++){
                    if(ind > 0)
                        addTimeSlot($('#TS_'+key).find('.addTimeSlot'));
                    var sss = new Date("2015-03-25 "+obj[ind].start_time);
                    var eee = new Date("2015-03-25 "+obj[ind].end_time);
                    $('#TS_'+key).find('.start').eq(ind).timepicker('setTime', sss);
                    $('#TS_'+key).find('.end').eq(ind).timepicker('setTime', eee);
                    $('#TS_'+key).find('.duration_hour').eq(ind).val(formatNumber(obj[ind].duration_hour,num_grp_sep,dec_sep,2,2));
                    $('#TS_'+key).find('.revenue_hour').eq(ind).val(formatNumber(obj[ind].revenue_hour,num_grp_sep,dec_sep,2,2));
                    $('#TS_'+key).find('.teaching_hour').eq(ind).val(formatNumber(obj[ind].teaching_hour,num_grp_sep,dec_sep,2,2));
                }
                day_schedule.push(key);
            });
            // set TimePicker
            //setTimePickerAll();

            // set start date of class upgrade
            var end_date = new Date(json.end_date);
            var end_date_next = new Date(end_date.setDate(end_date.getDate() + 1));
            var daysOfWeek = new Array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
            var end_date_checking =  daysOfWeek[SUGAR.util.DateUtils.parse(end_date_next,cal_date_format).getDay()];

            while(day_schedule.indexOf(end_date_checking) == -1) {
                end_date_next = new Date(end_date.setDate(end_date_next.getDate() + 1));
                end_date_checking =  daysOfWeek[SUGAR.util.DateUtils.parse(end_date_next,cal_date_format).getDay()];
            }
            $('#start_date').val(SUGAR.util.DateUtils.formatDate(end_date_next));
            checkStartDate();
        }

        // suggest kind of course, level, module
        generateOption();
        if($('#modules option:selected').index() != $('#modules option').length-1){
            $('#modules').val($('#modules option:selected').next().val());
        }
        else if($('#level option:selected').index() != $('#level option').length-1){
            $('#level').val($('#level option:selected').next().val());
            $('#modules').val($('#modules option:nth-child(2)').val());
        }
        else if($('#kind_of_course option:selected').index() != $('#kind_of_course option').length-1){
            $('#kind_of_course').val($('#kind_of_course option:selected').next().val());
            var objs =  $.parseJSON($('#kind_of_course :selected').attr('json'));
            $.each( objs, function( key, koc ) {
                $('#level').val($('#level option:eq('+(key+1)+')').val());
                var next_level = $('#level').val();
                if(koc.levels == next_level){
                    if(koc.is_upgrade == '1' || typeof koc.is_upgrade == 'undefined'){
                        return false;
                    }
                }
            });
            $('#modules').val($('#modules option:nth-child(2)').val());
        }
        // link more info
        $('#btn_j_class_j_class_1_name').closest('.multiple').after( "<a target='_blank' id='upgrade_class_info' style='text-decoration: none; font-style: italic;' href='index.php?module=J_Class&offset=1&stamp=1439367238038569100&return_module=J_Class&action=DetailView&record="+upgrade_id+"'>&nbsp;&nbsp;More info >></a>" );

        $('#koc_id').val($('#kind_of_course option:selected').attr('koc_id'));
        $('#short_course_name').val($('#kind_of_course option:selected').attr('short_course_name'));
        generateOption();
        ajaxMakeJsonSession();
    }
}

// Set Timepicker
function setTimePickerAll(){
    $('.timeOnly').each(function () {
        setTimePicker($(this));
    });
}
function setTimePicker(slot_time){
    slot_time.find(".time").eq(0).timepicker({
        'minTime': '6:00am',
        'maxTime': '9:30pm',
        'showDuration': false,
        'timeFormat': SUGAR.expressions.userPrefs.timef,
        'step': 15
    });
    slot_time.find(".time").eq(1).timepicker({
        'showDuration': true,
        'timeFormat': SUGAR.expressions.userPrefs.timef,
        'step': 15
    });
    var timeOnlyDatepair = new Datepair(slot_time[0], {
        'defaultTimeDelta': defaultTimeDelta * 3600000 // milliseconds
    });
    slot_time.on('rangeSelected', function(){
        var sc_index = $(this).closest('tr').index();
        var js_start    = SUGAR.util.DateUtils.parse($(this).find(".start").val(),SUGAR.expressions.userPrefs.timef);
        var js_end      = SUGAR.util.DateUtils.parse($(this).find(".end").val(),SUGAR.expressions.userPrefs.timef);
        var duration    = formatNumber(((js_end - js_start)/3600000),num_grp_sep,dec_sep,2,2);
        $(this).closest('table').closest('tr').find('td.hour_item').find('tbody tr').eq(sc_index).find(".duration_hour").val(duration);
        $(this).closest('table').closest('tr').find('td.hour_item').find('tbody tr').eq(sc_index).find(".revenue_hour").val(duration);
        $(this).closest('table').closest('tr').find('td.hour_item').find('tbody tr').eq(sc_index).find(".teaching_hour").val(duration);
        ajaxMakeJsonSession();
    });
}

//Clear all selection && Input
function clrSelection(){
    $('.start, .end, .revenue_hour, .teaching_hour, .duration_hour, #end_date').val('');
    $('input[name=week_date]').prop('checked',false).trigger('change');
}

function alertUpgradeClass(){
    var upgrade_to_id     = $('#upgrade_to_id').val();
    var upgrade_to_name = $('#upgrade_to_name').val();
    var class_name         = $('#name').val();
    var class_case         = $('#class_case').val();
    if(upgrade_to_id != '' && upgrade_to_name != ''){
        alertify.set({ labels: {
            ok     : "Let's me check & Save",
            cancel : "Skip & Save"
        } });


        alertify.confirm('<b>'+class_name+"</b> is upgraded to class <b>"+upgrade_to_name+'</b>. Want you to update the upgrade\'s Schedule ?', function (e) {
            if (e) {
                window.open("index.php?module=J_Class&action=EditView&return_module=J_Class&return_action=DetailView&record="+upgrade_to_id, '_blank');
                ajaxStatus.showStatus('Saving...');
                var _form = document.getElementById('EditView');
                _form.action.value='Save';
                SUGAR.ajaxUI.submitForm(_form);
                return false;
            } else {
                ajaxStatus.showStatus('Saving...');
                var _form = document.getElementById('EditView');
                _form.action.value='Save';
                SUGAR.ajaxUI.submitForm(_form);
                return false;
            }
        });
    }else return true;
}


