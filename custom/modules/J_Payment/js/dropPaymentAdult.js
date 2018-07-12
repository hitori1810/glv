var payment_type = $('#payment_type').val();
$(document).ready(function(){
    $('#dl_date').on('change',function(){
        $('#dl_drop_amount').text(0);
        $('#dl_used_amount').text(0);
        $('#dl_total_amount').text(0);
        $('#drop_amount_raw').val(0);
        $('#dl_drop_day').text(0);
        $('#drop_day_raw').val(0);

        //Validate Data Lock
        var rs3 = checkDataLockDate($(this).attr('id'));
        caculateDropPayment();

        var rs1 = validateDateIsBetween($(this).val(), $('#payment_date').text(),'');
        if(!rs1) {
            $(this).val('').effect("highlight", {color: 'red'}, 1000);
            return ;
        }
    });


    $('#edf_return_date').on('change',function(){
        //Validate Data Lock
        var rs3 = checkDataLockDate($(this).attr('id'));
        caculateDropPayment();

        var rs1 = validateDateIsBetween($(this).val(), $('#payment_date').text(),'');
        if(!rs1) {
            $(this).val('').effect("highlight", {color: 'red'}, 1000);
            return ;
        }
    });

    $('#btn_delay_payment').live('click', function(){
        showDialogDropPayment();
        caculateDropPayment();
    });

    $('#btn_enable_delay_fee').live('click', function(){
        showDialogDelayFee();
    });
});

//Show dialog Delay
function showDialogDropPayment(){
    $('#drop_payment_dialog').dialog({
        resizable: false,
        width: 700,
        modal: true,
        buttons: {
            "Delay Payment":{
                click:function() {
                    createDrop('drop_to_delay');
                },
                class	: 'button primary btn_drop_to_delay',
                text	: 'Delay Payment',
            },
            "Drop to Revenue":{
                click:function() {
                    createDrop('drop_to_revenue');
                },
                class    : 'button primary btn_drop_to_revenue',
                text    : 'Drop to Revenue',
            },
            "Cancel":{
                click:function() {
                    $(this).dialog('close');
                },
                class	: 'button btn_cancel_drop',
                text	: 'Cancel',
            },
        }
    });
}

//Show dialog Enable Delay Fee
function showDialogDelayFee(){
    $('#enable_delay_fee').dialog({
        resizable: false,
        modal: true,
        width: 350,
        buttons: {
            "Submit":{
                click:function() {
                    enable_delay_fee();
                },
                class    : 'button primary submit_enable_delay_fee',
                text    : 'Submit',
            },
            "Cancel":{
                click:function() {
                    $(this).dialog('close');
                },
                class    : 'button cancel_enable_delay_fee',
                text    : 'Cancel',
            },
        }
    });
}

function caculateDropPayment(){
    var dl_date             = $('#dl_date').val();
    var remain_amount       = Numeric.parse($('#remain_amount').text());
    var drop_amount_raw     = Numeric.parse($('#drop_amount_raw').val());
    var today 	            = SUGAR.util.DateUtils.formatDate(new Date());
    if(dl_date == ''){
        $('#dl_drop_amount').text(0);
        $('#dl_used_amount').text(0);
        $('#dl_total_amount').text(0);
        $('#dl_drop_day').text(0);
        $('#drop_amount_raw').val(0);
        $('#drop_day_raw').val(0);
        return ;
    }
    $('.btn_drop_to_delay, .btn_drop_to_revenue, .btn_cancel_drop').hide();
    $('#save_drop_loading').show();
    $.ajax({
        url: "index.php?module=J_Payment&action=handleAjaxPayment&sugar_body_only=true",
        type: "POST",
        async: true,
        data:
        {
            payment_id      : $('input[name=record]').val(),
            dl_date	        : dl_date,
            type 			: "caculateDropPaymentAdult",
        },
        dataType: "json",
        success: function(res){
            if(res.success == "1"){
                $('#dl_total_amount').text(res.total_amount).effect("highlight", {color: '#3594FF'}, 2000);
                $('#dl_used_amount').text(res.used_amount).effect("highlight", {color: '#3594FF'}, 2000);
                $('#dl_drop_amount').text(res.drop_amount).effect("highlight", {color: '#3594FF'}, 2000);
                $('#drop_amount_raw').val(res.drop_amount);
                $('#dl_drop_day').text(res.drop_day).effect("highlight", {color: '#3594FF'}, 2000);
                $('#drop_day_raw').val(res.drop_day);
            }else{
                alertify.alert('An error occurred. Please, Try again!');
                location.reload();
            }
            $('.btn_drop_to_delay, .btn_cancel_drop, .btn_drop_to_revenue').show();
            if(res.hide_drop_delay == '1')
                $('.btn_drop_to_delay').hide();
            if(res.hide_drop_revenue == '1')
                $('.btn_drop_to_revenue').hide();
            $('#save_drop_loading').hide();
            ajaxStatus.hideStatus();
        },
    });
}

function createDrop(drop_type){
    var drop_amount = Numeric.parse($('#drop_amount_raw').val());
    var drop_day    = Numeric.parse($('#drop_day_raw').val());
    var dl_date     = $('#dl_date').val();
    var dl_reason   = $('#dl_reason').val();
    if(drop_amount < 0){
        alertify.error('The Drop Amount is too less! Can not drop !');
        return ;
    }
    if( dl_reason == '' || dl_date == ''){
        alertify.error('Please, fill out completely !');
        return ;
    }

    $('.btn_drop_to_delay, .btn_cancel_drop, .btn_drop_to_revenue').hide();
    $('#save_drop_loading').show();
    $.ajax({
        url: "index.php?module=J_Payment&action=handleAjaxPayment&sugar_body_only=true",
        type: "POST",
        async: true,
        data:
        {
            drop_amount :   drop_amount,
            drop_day    :   drop_day,
            payment_id  :   $('input[name=record]').val(),
            dl_date     :   dl_date,
            dl_reason   :   dl_reason,
            drop_type   :   drop_type,
            type        :   "createDropPaymentAdult",
        },
        dataType: "json",
        success: function(res){
            if(res.success == '1'){
                $('#drop_payment_dialog').dialog("close");
                $('#btn_drop_payment').hide();
                alertify.success('Saved completely ! Waiting for reloading');
                location.reload();
                ajaxStatus.showStatus('Reloading...');
            }else{
                alertify.alert('An error occurred. Please, Try again!');
            }
            $('.btn_drop_to_delay, .btn_cancel_drop, .btn_drop_to_revenue').show();
            $('#save_drop_loading').hide();
        },
    });
}

function enable_delay_fee(){
    var edf_return_date     = $('#edf_return_date').val();

    if( edf_return_date == ''){
        alertify.error('Please, fill out completely !');
        return ;
    }

    $('.submit_enable_delay_fee, .cancel_enable_delay_fee').hide();
    $('#save_edf_loading').show();
    $.ajax({
        url: "index.php?module=J_Payment&action=handleAjaxPayment&sugar_body_only=true",
        type: "POST",
        async: true,
        data:
        {
            edf_return_date :   edf_return_date,
            payment_id  :   $('input[name=record]').val(),
            type        :   "enable_delay_fee",
        },
        dataType: "json",
        success: function(res){
            if(res.success == '1'){
                $('#enable_delay_fee').dialog("close");
                ajaxStatus.showStatus('Redirecting...');
                window.location.replace("index.php?module=J_Payment&action=DetailView&record="+res.cashholder_id);
            }else{
                alertify.alert('An error occurred. Please, Try again!');
            }
            $('.submit_enable_delay_fee, .cancel_enable_delay_fee').show();
            $('#save_edf_loading').hide();
        },
    });
}

Calendar.setup ({
    inputField : "dl_date",
    daFormat : cal_date_format,
    button : "dl_date_trigger",
    singleClick : true,
    dateStr : "",
    step : 1,
    weekNumbers:false
});

Calendar.setup ({
    inputField : "edf_return_date",
    daFormat : cal_date_format,
    button : "edf_return_date_trigger",
    singleClick : true,
    dateStr : "",
    step : 1,
    weekNumbers:false
});