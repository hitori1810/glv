$(document).ready(function(){
    $('#btn_add_demo').live('click',function(){
        addDemo('create','');	
    });
    showDialogDemo();
    $('#dm_lesson_date').on('change',function(){
        calSession($(this).val(), $(this).val());	
    });
    $('.btn_delete_demo').live('click',function(){
        var situa_id = $(this).attr('situa_id');
        alertify.confirm(SUGAR.language.get('J_Class', 'LBL_CONFIRM_DELETE_DEMO'), function (e) {
            if (e) addDemo('delete',situa_id);	
        });
    });
});

//Mở popup chọn học viên
function open_popup_demo(thisButton){ 
    if( (current_status.value == 'Closed' || current_status.value == 'Finish') && is_admin != '1' ){
        alertify.alert("<span class=\"ui-icon ui-icon-alert\" style=\"float:left;\"></span> "+SUGAR.language.get('J_Class', 'LBL_NOT_ACTION_OPERATION')+current_status.value+" !!");
        return ; 
    } 
    var module = thisButton.parent().find('select#parent_demo').val();
    open_popup(module, 600, 400, "", true, false, {"call_back_function":"set_return_lead_student","form_name":"EditView","field_to_name_array":{"id":"student_id","name":"student_name"}}, "single", true);
};

//Trả về pop-up và Show dialog
function set_return_lead_student(popup_reply_data, filter) {
    var form_name = popup_reply_data.form_name;
    var name_to_value_array = popup_reply_data.name_to_value_array;
    $('#dm_student_id').val(name_to_value_array.student_id);
    $('#dm_student_name').text(name_to_value_array.student_name);
    $('#dm_type_student').val($('#parent_demo').val());
    showDialogDemo();
}

//Show dialog Demo 
function showDialogDemo(){
    var dm_student_id 	= $('#dm_student_id').val();
    var dm_lesson_date 	= $('#dm_lesson_date').val();
    var dm_type_student = $('#dm_type_student').val();
    if(dm_student_id == '' || dm_lesson_date == '' || dm_type_student == '') return false;
    $("body").css({ overflow: 'hidden' });
    $('#diaglog_demo').dialog({
        resizable: false,
        width:'auto',
        height:'auto',
        modal: true,
        visible: true,
        position: ['center',50],
        beforeClose: function(event, ui) {
            $("body").css({ overflow: 'inherit' });
        },
    });
}

function addDemo(action_demo,situa_id){
    var dm_student_id 	= $('#dm_student_id').val();
    var dm_lesson_date 	= $('#dm_lesson_date').val();
    var dm_type_student = $('#dm_type_student').val();
    var dm_class_id 	= $('input[name=record]').val();

    if(action_demo == 'create'){
        var res = calSession(dm_lesson_date, dm_lesson_date);
        if(res['total_hours'] == 0 || dm_student_id == '' || dm_lesson_date == '' || dm_type_student == '') return false;

        $('#btn_add_demo').hide();
        $("#add_demo_loading").show();	
    }
    ajaxStatus.showStatus('Saving <img src="custom/include/images/loader32.gif" align="absmiddle" width="32">');
    $.ajax({
        type: "POST",
        url: "index.php?module=J_Class&action=handleAjaxJclass&sugar_body_only=true",
        data:  
        {
            dm_student_id          	: dm_student_id,
            dm_lesson_date         	: dm_lesson_date,
            dm_type_student        	: dm_type_student,
            dm_class_id           	: dm_class_id,
            action_demo            	: action_demo,
            situa_id            	: situa_id,
            type                   	: "addDemo",
        },
        dataType: "json",
        success:function(data){
            if(data.success == "1") {
                handle_filter();
                $('#diaglog_demo').dialog("close");
                alertify.success(data.notify);
                //				showSubPanel('j_class_studentsituations', null, true);  
            }else{
                alertify.error(data.error);
            }
            ajaxStatus.hideStatus();
            $('#btn_add_demo').show();	
            $("#add_demo_loading").hide();
        },
    });   
}

//Set up calendar
Calendar.setup ({
    inputField : "dm_lesson_date",
    daFormat : cal_date_format,
    button : "dm_lesson_date_trigger",
    singleClick : true,
    dateStr : "",
    step : 1,
    weekNumbers:false
    }
);
