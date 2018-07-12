$(document).ready(function(){
    $('#rfc_from_date, #rfc_to_date').on('change',function(){
        $('#rfc_studied_hour').text(0);
        $('#rfc_studied_session').text(0);
        $('#rfc_remove_hour').text(0);
        $('#rfc_remove_session').text(0);

        //Validate Is Between and check valid input
        var rs1 = validateDateIsBetween($(this).val(), $('#rfc_start').text(), $('#rfc_end').text());
        if(!rs1) {
            $(this).val('').effect("highlight", {color: 'red'}, 1000);
            return ;	
        }
        //Kiểm tra ngày được chọn có nằm trong lịch không
        rs2 = isInSchedule($(this).val());
        if(!rs2) {
            $(this).val('').effect("highlight", {color: 'red'}, 1000);
            return ;
        }

        //Validate Data Lock
        if(!checkDataLockDate($(this).attr('id'),true))
            return ;

        caculateFreeBalanceAdult();

    });

    $('#rfc_payment_date').on('change',function(){
        //Validate Is Between and check valid input
        var rs1 = validateDateIsBetween($(this).val(), $('#rfc_payment_date_date').val(), $('#rfc_from_date').val());
        if(!rs1) {
            $(this).val('').effect("highlight", {color: 'red'}, 1000);
            return ;    
        }

        //Validate Data Lock
        if(!checkDataLockDate($(this).attr('id'),true))
            return ;

    });


    $('.btn_remove_from_class').live('click', function(){
        if( (current_status.value == 'Closed' || current_status.value == 'Finish') && is_admin != '1' ){
            alertify.alert("<span class=\"ui-icon ui-icon-alert\" style=\"float:left;\"></span> Could not perform this operation because the class was "+current_status.value+" !!");
            return ; 
        }
        //Prepare dialog           
        var situa_id 	= this.getAttribute('situa_id');
        var student_name = this.getAttribute('student_name');
        var start_study = this.getAttribute('start_study');
        var end_study     = this.getAttribute('end_study');
        var total_hour 	= this.getAttribute('total_hour');

        $('#rfc_situation_id').val(situa_id);
        $('#rfc_student_name').text(student_name);
        $('#rfc_start').text(start_study);
        $('#rfc_end').text(end_study);
        $('#rfc_from_date').val(start_study);
        $('#rfc_to_date').val(end_study);
        $('#rfc_total_hour').text(total_hour);

        showDialogRemoveClass();
        $('#rfc_from_date').trigger('change');    
    });
});

//Show dialog Delay 
function showDialogRemoveClass(){
    $('#remove_from_class_dialog').dialog({
        resizable: false,
        width: 650,
        modal: true,
        buttons: {
            "Submit":{
                click:function() {
                    handleRemoveFromClassAdult();
                },
                class	: 'button primary btn_submit_rfc',
                text    : SUGAR.language.get('J_Class', 'LBL_SUBMIT'),
            },
            "Cancel":{
                click:function() {
                    $(this).dialog('close');
                },
                class	: 'button btn_cancel_rfc',
                text    : SUGAR.language.get('J_Class', 'BTN_CANCEL'),
            },
        }

    });
}

function caculateFreeBalanceAdult(){
    var rfc_from_date 	    = $('#rfc_from_date').val();
    var rfc_to_date 		= $('#rfc_to_date').val();
    var rfc_situation_id    = $('#rfc_situation_id').val();
    if(rfc_from_date == '' || rfc_to_date == '' ||rfc_situation_id == ''){
        $('#rfc_studied_hour').text(0);
        $('#rfc_studied_session').text(0);
        $('#rfc_remove_hour').text(0);
        $('#rfc_remove_session').text(0);
        return ;
    }
    $('.btn_submit_rfc, .btn_cancel_rfc').hide();
    $('.btn_submit_rfc').parent().append('<span id="rfc_saving" >'+SUGAR.language.get('J_Class', 'LBL_LOADING')+'.. <img src="custom/include/images/loader.gif" align="absmiddle" width="16"></span>');
    $.ajax({
        url: "index.php?module=J_Class&action=handleAjaxJclass&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  
        {
            rfc_situation_id	: rfc_situation_id,
            rfc_from_date		: rfc_from_date,
            rfc_to_date			: rfc_to_date,
            type 			    : "caculateFreeBalanceAdult",
        },
        dataType: "json", 
        success: function(res){
            if(res.success == "1"){
                $('#rfc_studied_hour').text(res.studied_hour).effect("highlight", {color: '#3594FF'}, 2000);
                $('#rfc_studied_session').text(res.studied_session).effect("highlight", {color: '#3594FF'}, 2000);
                $('#rfc_remove_hour').text(res.delay_hour).effect("highlight", {color: '#3594FF'}, 2000);
                $('#rfc_remove_session').text(res.delay_session).effect("highlight", {color: '#3594FF'}, 2000);
            }else
                alertify.alert('An error occurred. Please, Try again!'); 
            $('.btn_submit_rfc, .btn_cancel_rfc').show();
            $('#rfc_saving').remove(); 
        },       
    });  
}

function handleRemoveFromClassAdult(){
    var rfc_from_date 	    = $('#rfc_from_date').val();
    var rfc_to_date 		= $('#rfc_to_date').val();
    var rfc_situation_id    = $('#rfc_situation_id').val();
    var rfc_remove_hour 	    = Numeric.parse($('#rfc_remove_hour').text());
    var rfc_remove_session      = Numeric.parse($('#rfc_remove_session').text());

    if(rfc_from_date == '' || rfc_to_date == '' || rfc_situation_id == '' || rfc_remove_hour <= 0  || rfc_remove_session <= 0){
        alertify.error('Please fill out fields completely !');
        return ;
    }
    $('.btn_submit_rfc, .btn_cancel_rfc').hide();
    $('.btn_submit_rfc').parent().append('<span id="rfc_saving" >'+SUGAR.language.get('J_Class', 'LBL_LOADING')+'.. <img src="custom/include/images/loader.gif" align="absmiddle" width="16"></span>');

    ajaxStatus.showStatus(SUGAR.language.get('J_Class', 'LBL_SAVING')+' <img src="custom/include/images/loader32.gif" align="absmiddle" width="32">');
    $.ajax({
        url: "index.php?module=J_Class&action=handleAjaxJclass&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  
        {
            rfc_from_date		:	rfc_from_date,
            rfc_to_date			:	rfc_to_date,
            rfc_situation_id    :   rfc_situation_id,
            rfc_class_id	    :	$('input[name=record]').val(),
            type 				: 	"handleRemoveFromClassAdult",
        },
        dataType: "json",
        success: function(res){
            if(res.success == '1'){
                $('#remove_from_class_dialog').dialog("close");  
                alertify.success('Saved completely !');
                handle_filter();
                ajaxStatus.hideStatus();
            }else
                alertify.alert(SUGAR.language.get('J_Class', 'LBL_ERROR_DELAY_3'));

            $('.btn_submit_rfc, .btn_cancel_rfc').show();   
            $('#rfc_saving').remove();
        },       
    });  
}

Calendar.setup ({
    inputField : "rfc_from_date",
    daFormat : cal_date_format,
    button : "rfc_from_date_trigger",
    singleClick : true,
    dateStr : "",
    step : 1,
    weekNumbers:false
});

Calendar.setup ({
    inputField : "rfc_to_date",
    daFormat : cal_date_format,
    button : "rfc_to_date_trigger",
    singleClick : true,
    dateStr : "",
    step : 1,
    weekNumbers:false
});