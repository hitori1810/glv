$(document).ready(function(){
    $('#start_schedule,#end_schedule').on('change',function(){
        dateValid = isInSchedule($(this).val());
        if(!dateValid) {
            $(this).val('').effect("highlight", {color: 'red'}, 1000);
            return ;
        }

        if(!checkDataLockDate($(this).attr('id'),true))
            return ;

        var rs1 = validateDateIsBetween($(this).val(), $('#start_date').text(), $('#end_date').text());
        if(!rs1) {
            $(this).val('').effect("highlight", {color: 'red'}, 1000);
            return ;
        }

        getWeekDay($('#start_schedule').val(),$('#end_schedule').val());
        clearTeacherList();
    });

    $('#btn_check_schedule').on('click',function(){
        ajaxCheckScheduleTecher();
    });

    $('#btn_reset_input').on('click',function(){
        resetScreen();
    });

    $('#btn_teacher_schedule_save').on('click',function(){
        saveSchedule();
    });

    $('#btn_teacher_schedule_close').live('click',function(){
        $(this).closest('#dialog_teacher').dialog('close');
    });
    $('#select_teaching_type').live('change',function(){
        if($(this).val() != 'main_teacher'){
            $('.change_reason_required').show();
        }else{
            $('.change_reason_required').hide();
        }
    });

    //    ajaxCheckScheduleTecher();


    $('.day_of_week').live('change',function(){
        if($(this).is(':checked')){
            var time_slot =  $.parseJSON($(this).attr('time_slot'));
            var html = '<select class="time_slot" size="1" title="Shift">';
            if(time_slot['timeslot'].length > 1){
                html += '<option value="select_all">'+SUGAR.language.get('J_Class', 'LBL_SELECT_ALL')+'</option>';
            }
            $.each( time_slot['timeslot'], function( key, value ) {
                html += '<option value="'+value+'">'+value+'</option>';
            });
            html += '</select>';
            $(this).closest('div').append(html);
        }else {
            $(".time_slot").prop("disabled",false);
            $(this).closest('div').find('.time_slot').remove();

        }
    });
});
function schedule_teacher(schedule){
    //Show dialog
    $('#dialog_teacher').attr('title','Schedule Teacher');
    $('#dialog_teacher').dialog({
        resizable: false,
        width:'1000px',
        modal: true,
        visible: false,
        position: ['center',50],
        beforeclose: function (event, ui) {
            $('#dialog_teacher').dialog("destroy");
        }
    });
    $('#dlg_class_schedule').html($("#main_schedule").html());
    $('#class_code_schedule').text($('#class_code').text());
    $('#class_name_schedule').text($('#name').text());
    $('#upgrade_schedule').text($('#j_class_j_class_1j_class_ida').text());
    $('#start_schedule').val($("#next_session_date").val());
    $('#end_schedule').val($('#end_date').text());
    $('#select_teaching_type').val("main_teacher");
    $('#change_teacher_reason').val("");

    getWeekDay($('#start_schedule').val(),$('#end_schedule').val());

    clearTeacherList();
}

//Get Day of Week have session in class
function getWeekDay(str_from_date,str_to_date){
    $.ajax({
        url: "index.php?module=J_Class&action=handleAjaxJclass&sugar_body_only=true",
        type: "POST",
        async: true,
        data:
        {
            type : "ajaxGetWeekDay",
            from_date: str_from_date,
            to_date: str_to_date,
            class_id : $("input[name='record']").val(),
        },
        dataType: "json",
        success: function(res){
            if(res.success == "1"){

                var array_date = $.parseJSON(res.array_date);
                $('input:checkbox').removeAttr('checked');
                $(".day_of_week").hide();
                $(".day_of_week").attr('time_slot','');
                $(".day_of_week").next("label").hide();
                $.each(array_date, function(key, value){
                    $("#"+key+".day_of_week").closest('div').find('.time_slot').remove();
                    $("#"+key+".day_of_week").show();
                    $("#"+key+".day_of_week").attr('time_slot',JSON.stringify(value));
                    $("#"+key+".day_of_week").next("label").show();

                    /*switch (key)
                    {
                    case 'Monday':
                    $('#Monday').prop('checked', true);
                    break;
                    case 'Tuesday':
                    $('#Tuesday').prop('checked', true);
                    break;
                    case 'Wednesday':
                    $('#Wednesday').prop('checked', true);
                    break;
                    case 'Thursday':
                    $('#Thursday').prop('checked', true);
                    break;
                    case 'Friday':
                    $('#Friday').prop('checked', true);
                    break;
                    case 'Saturday':
                    $('#Saturday').prop('checked', true);
                    break;
                    case 'Sunday':
                    $('#Sunday').prop('checked', true);
                    break;
                    }*/
                });

                /*for(var i=0;i < array_date.length; i++){

                } */
                /* $(".day_of_week").each(function() {
                if ($(this).prop('checked') == true){
                $(this).show();
                $(this).next("label").show();
                }
                else{
                $(this).hide();
                $(this).next("label").hide();
                }
                });
                $('input:checkbox').removeAttr('checked'); */
            }
        },
    });
}

function ajaxCheckScheduleTecher(){
    var day_of_week = [];
    $(".day_of_week").each(function() {
        if ($(this).prop('checked') == true){
            if($(this).closest('div').find('.time_slot').val() === 'select_all'){
                var slot = [];
                for (var i = 1; i < $(this).closest('div').find('option').length; i++){
                    slot.push($(this).closest('div').find('option')[i].innerHTML);
                }
                day_of_week.push($(this).attr("id") + ' | ' + slot.join(' & '));
            }else{
                day_of_week.push($(this).attr("id")+ ' | ' + $(this).closest('div').find('.time_slot').val());
            }
        }
    });
    var day_of_week_string = day_of_week.join(', ');
    if(day_of_week.length == 0 || $('#start_schedule').val()== '' || $('#end_schedule').val() == ''){
        alertify.error(SUGAR.language.get('J_Class', 'LBL_PLEASE_FILL_OUT_COMPLETELY'));
        $('#list_teacher tbody').html("");
        $('#btn_teacher_schedule_save').hide();
        return ;
    }
    $('#btn_teacher_schedule_save').hide();
    $("#teacher_schedule_loading_icon").show();

    $("#start_schedule,#end_schedule,.day_of_week,.time_slot").prop("disabled",true);
    $("#start_schedule_trigger,#end_schedule_trigger").hide();

    $.ajax({
        url: "index.php?module=J_Class&action=handleAjaxJclass&sugar_body_only=true",
        type: "POST",
        async: true,
        data:
        {
            type : "ajaxGetTeacherBySchedule",
            class_id    : $("[name='record']").val(),
            from_date   : $('#start_schedule').val(),
            to_date     : $('#end_schedule').val(),
            day_of_week : day_of_week,
        },
        success: function(data){
            $("#teacher_schedule_loading_icon").hide();
            $('#list_teacher tbody').html(data);
            $('#btn_teacher_schedule_save').show();
            $('[name="teacher_schedule_radio"]:first').prop("checked",true);
            // Remember this filter to alert when save
            $('#teacher_schedule_start_date').val($('#start_schedule').val());
            $('#teacher_schedule_end_date').val($('#end_schedule').val());
            $('#teacher_schedule_day_of_week').val(day_of_week_string);

            $("#start_schedule,#end_schedule,.day_of_week").prop("disabled",false);
            $("#start_schedule_trigger,#end_schedule_trigger").show();
        },
    });
}

function saveSchedule(){
    var teacher_id      = $('[name="teacher_schedule_radio"]:checked').val();
    var teacher_name    = $('[name="teacher_schedule_radio"]:checked').closest("tr").find(".schedule_contract_id").closest("td")[0].innerText;
    var teaching_type   = $('#select_teaching_type').val();
    var change_reason   = $('#change_teacher_reason').val();
    var end_date;
    var schedule_contract_until = $('[name="teacher_schedule_radio"]:checked').closest("tr").find(".schedule_contract_until").val();
    var schedule_end_date =  $('#teacher_schedule_end_date').val();
    if(schedule_contract_until == ""){
        end_date = schedule_end_date;
        teaching_type = "";
    }else if(Date.compare(SUGAR.util.DateUtils.parse(schedule_contract_until, cal_date_format), SUGAR.util.DateUtils.parse(schedule_end_date,cal_date_format))<0){
        end_date = schedule_contract_until;
    } else { end_date = schedule_end_date;}
    if(teacher_id === undefined){
        alertify.error(SUGAR.language.get('J_Class','LBL_SCHEDULE_NO_TEACHER'));
    }
    else if((teaching_type == 'cover' || teaching_type == 'take_over' || teaching_type == 'make_up') && change_reason == ''){
        alertify.error('Please, note change teacher reason.');
    }
    else{
        var alertString = SUGAR.language.get('J_Class', 'LBL_NAME')+": "+$('#name').text();
        alertString += "\n"+SUGAR.language.get('J_Class', 'LBL_START_DATE')+": "+$('#teacher_schedule_start_date').val();
        alertString += "\n"+SUGAR.language.get('J_Class', 'LBL_END_DATE')+": "+ end_date;
        alertString += "\n"+SUGAR.language.get('J_Class', 'LBL_DAY_OF_WEEK')+": "+$('#teacher_schedule_day_of_week').val();
        alertString += "\n"+SUGAR.language.get('J_Class', 'LBL_TEACHER')+": " + teacher_name;
        alertString += "\n"+SUGAR.language.get('J_Class', 'LBL_CONFIRM_SAVE_SCHEDULE');

        if (!confirm(alertString)){
            return;
        }
        $('#btn_teacher_schedule_save').hide();
        $('#btn_teacher_schedule_close').hide();
        $("#teacher_schedule_loading_icon").show();
        $.ajax({
            url: "index.php?module=J_Class&action=handleAjaxJclass&sugar_body_only=true",
            type: "POST",
            async: true,
            data:
            {
                type : "ajaxSaveTeacherSchedule",
                teacher_id      : teacher_id,
                contract_id     : $('[name="teacher_schedule_radio"]:checked').closest("tr").find(".schedule_contract_id").val(),
                class_id        : $('input[name=record]').val(),
                from_date       : $('#teacher_schedule_start_date').val(),
                to_date         : end_date,
                day_of_week     : $('#teacher_schedule_day_of_week').val(),
                teaching_type   : teaching_type,
                change_reason   : $('#change_teacher_reason').val(),
            },
            success: function(data){
                $('#btn_teacher_schedule_save').show();
                $('#btn_teacher_schedule_close').show();
                $("#teacher_schedule_loading_icon").hide();
                $('#dialog_teacher').dialog('close');
                showSubPanel('j_class_meetings', null, true);
            },
        });
    }
}

function clearTeacherList(){
    $("#list_teacher tbody").html("");
}

function resetScreen(){
    getWeekDay($('#start_schedule').val(),$('#end_schedule').val());
    $('#start_schedule').val($('#start_date').text());
    $('#end_schedule').val($('#end_date').text());
    getWeekDay($('#start_schedule').val(),$('#end_schedule').val());

    clearTeacherList();
}