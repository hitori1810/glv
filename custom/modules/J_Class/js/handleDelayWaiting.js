$(document).ready(function(){
    $('#dl_from_date').on('change',function(){
        //Validate Data Lock
        var rs3 = checkDataLockDate($(this).attr('id'));
    });

    $('.btn_delay_waiting').live('click', function(){
        var payment_status= this.getAttribute('payment_status');
        if(payment_status == 'Paid'){
            //Prepare dialog
            var student_id 	= this.getAttribute('student_id');
            var student_name= this.getAttribute('student_name');
            var situa_id     = this.getAttribute('situa_id');
            var class_code 	= $('#class_code').text();
            var total_hour 	= this.getAttribute('total_hour');
            var total_amount= this.getAttribute('total_amount');


            $('#dl_situation_id').val(situa_id);
            $('#dl_student_id').val(student_id);
            $('#dl_student_name').text(student_name);
            $('#dl_class_code').text(class_code);

            $('.dl_total_hour').text(total_hour).val(total_hour);
            $('.dl_total_amount').text(total_amount).val(total_amount);

            $('#dl_hour').attr('max',Numeric.parse(total_hour));
            $('#dl_hour').val(total_hour);

            showDialogDelay();
            $('#dl_hour').trigger('change').bootstrapNumber();
        }else if(payment_status == 'Unpaid'){
            alertify.alert(SUGAR.language.get('J_Class', 'LBL_STUDENT_NOT_PAID_IN_FULL'));
        }
    });
    $('#dl_hour').live('change', function(){
        var min             = parseFloat($(this).attr('min'));
        var max             = parseFloat($(this).attr('max'));
        var value           = Numeric.parse($(this).val());
        var total_hour      = Numeric.parse($('#dl_total_hour').val());
        var total_amount    = Numeric.parse($('#dl_total_amount').val());
        var delay_hour      = 0;
        var delay_amount    = 0;

        if(value > max || value < min){
            value = 1;
            $(this).val(value).effect("highlight", {color: 'red'}, 2000);
            alertify.error('Invalid Input, Total delay hour should be between '+min+' - '+max+' hours');
        }
        delay_hour      = Numeric.toFloat(value,2,2);
        delay_amount    = Numeric.toFloat(value * (total_amount / total_hour));
        $('.dl_delay_hour').text(delay_hour).val(delay_hour).effect("highlight", {color: '#3594FF'}, 500);
        $('.dl_delay_amount').text(delay_amount).val(delay_amount).effect("highlight", {color: '#3594FF'}, 500);
    });

});

//Show dialog Delay
function showDialogDelay(){
    $('#delay_class_waiting').dialog({
        resizable: false,
        width: 700,
        modal: true,
        buttons: {
            "Create Delay":{
                click:function() {
                    handleCreateDelayWaiting();
                },
                class	: 'button primary btn_create_delay',
                text    : SUGAR.language.get('J_Class', 'LBL_SAVE'),
            },
            "Cancel":{
                click:function() {
                    $(this).dialog('close');
                },
                class	: 'button btn_cancel_delay',
                text    : SUGAR.language.get('J_Class', 'BTN_CANCEL'),
            },
        }
    });
}

function handleCreateDelayWaiting(){
    var dl_delay_hour 	= Numeric.parse($('#dl_delay_hour').text());
    var dl_delay_amount = Numeric.parse($('#dl_delay_amount').text());
    var dl_reason       = $('#dl_reason').val();
    var dl_situation_id = $('#dl_situation_id').val();
    var dl_from_date    = $('#dl_from_date').val();

    if( dl_delay_hour <= 0 || dl_reason == ''|| dl_from_date == ''){
        alertify.error(SUGAR.language.get('J_Class', 'LBL_PLEASE_FILL_OUT_COMPLETELY'),);
        return ;
    }
    $('.btn_create_delay, .btn_cancel_delay').hide();
    $('#delay_save_loading').show();
    $.ajax({
        url: "index.php?module=J_Class&action=handleAjaxJclass&sugar_body_only=true",
        type: "POST",
        async: true,
        data:
        {
            dl_situation_id		:	 dl_situation_id,
            dl_reason           :    dl_reason,
            dl_delay_hour       :    dl_delay_hour,
            dl_delay_amount     :    dl_delay_amount,
            dl_from_date		: 	 dl_from_date,
            type 				: 	"handleCreateDelayWaiting",
        },
        dataType: "json",
        success: function(res){
            if(res.success == '1'){
                showSubPanel('j_class_studentsituations', null, true);
                alertify.success(SUGAR.language.get('J_Class', 'LBL_DELAY_COMPLETELY'));
            }else
                alertify.alert(SUGAR.language.get('J_Class', 'LBL_ERROR_DELAY_3'));
            $('.btn_create_delay, .btn_cancel_delay').show();
            $('#delay_save_loading').hide();
            $('#delay_class_waiting').dialog('close');

        },
    });
}
Calendar.setup ({
    inputField : "dl_from_date",
    daFormat : cal_date_format,
    button : "dl_from_date_trigger",
    singleClick : true,
    dateStr : "",
    step : 1,
    weekNumbers:false
});


function undoDelay(situation_delay_id){
    if(situation_delay_id == ''){
        alertify.error(SUGAR.language.get('J_Class', 'LBL_CAN_NOT_UNDO'));
        return ;
    }
    alertify.confirm(SUGAR.language.get('J_Class', 'LBL_CONFIRM_UNDO'), function (e) {
        if (e) {
            ajaxStatus.showStatus('Undying <img src="custom/include/images/loader32.gif" align="absmiddle" width="32">');
            $.ajax({
                url: "index.php?module=J_Class&action=handleAjaxJclass&sugar_body_only=true",
                type: "POST",
                async: true,
                data:
                {
                    situation_delay_id	:	situation_delay_id,
                    type 				: 	"undoDelay",
                },
                dataType: "json",
                success: function(res){
                    if(res.success == '1'){
//                        $('#filter_date').val(res.start_study);
                        handle_filter();
                        $('#delay_class_dialog').dialog("close");
                        alertify.success('Undo completely !');
                    }else
                        alertify.alert(SUGAR.language.get('J_Class', 'LBL_ERROR_DELAY_3'));

                    ajaxStatus.hideStatus();
                },
            });
        }
    });

}

