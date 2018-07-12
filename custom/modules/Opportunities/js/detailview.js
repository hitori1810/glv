
//Ajax Cancel Enrollment
function ajax2(){
    oppid = $('input[name=opportunity_id]').val();
    data = "&oppid="+oppid;
    if(confirm('Are you sure you want to cancel this enrollment ?')){
        ajaxStatus.showStatus('Waiting...'); //Sugar alert
        $.ajax({
            url:'index.php?module=Opportunities&action=cancelEnrollments&sugar_body_only=true',
            type: "POST",
            data: data,
            dataType: "json",
            success: function(response){
                if(response.success == "1"){
                    ajaxStatus.showStatus('Done');
                    location.href='index.php?module=Opportunities&action=DetailView&record='+oppid;
                }else{
                    ajaxStatus.hideStatus(); //END:Sugar alert
                    alert("Unexpected error! Try again.");
                }
            },
            error: function(res){
                ajaxStatus.hideStatus(); //END:Sugar alert
                alert("Unexpected error! Try again.");
            }
        });
    }
}
//Toggle Company
function toggleCompany(){
    if($('#is_company').is(':checked')){
        $('#vat-info').closest('tr').show();
    }else{
        $('#vat-info').closest('tr').hide();
    }
}

//Show/hide payment method
function togglePaymentMethod(self){
    var payment = $(self).val();
    switch(payment) {
        case "BankTranfer":
            $('#credit_info').hide();
            $('#loan_info').hide();
            break;
        case "CreditDebitCard":
            $('#credit_info').slideDown('fast');
            $('#loan_info').hide();
            break;
        case "Cash":
            $('#credit_info').hide();
            $('#loan_info').hide();
            break;
        case "Loan":
            $('#loan_info').slideDown('fast');
            $('#credit_info').hide();
            break;
        default:
            $('#credit_info').hide();
            $('#loan_info').hide();
    }
    calculatedPaymentMethod();
}

//Calculated Payment Fee
function calculatedPaymentMethod(){
    var paymentMethod = $('input[name=payment_method]:checked').val();
    var payment_amount = unformatNumber($('#payment_amount').val(),num_grp_sep,dec_sep);
    var cardType = $('#card_type').val();
    var card_rate = 0.00;
    if(cardType != '')
        card_rate = parseFloat(SUGAR.language.languages.app_list_strings['card_rate'][cardType]);


    var bankName = $('#bank_name').val();
    var loanRate = 0.00;
    var bankRate = 0.00;
    if(bankName != '')
    {
        loanRate =  parseFloat(SUGAR.language.languages.app_list_strings['loan_rate'][bankName].split("|")[0]);
        bankRate =  parseFloat(SUGAR.language.languages.app_list_strings['loan_rate'][bankName].split("|")[1]);
    }
    if(paymentMethod=='CreditDebitCard'){

        $('#card_rate').val(card_rate);
        $('#card_amount').val(formatNumber((payment_amount * card_rate) / 100,num_grp_sep,dec_sep,2,2));
    }
    if(paymentMethod=='Loan'){
        $('#bank_fee_rate').val(bankRate);
        $('#loan_fee_rate').val(loanRate);
        $('#bank_fee_amount').val(formatNumber(bankRate*payment_amount/100,num_grp_sep,dec_sep,2,2));
        $('#loan_fee_amount').val(formatNumber(loanRate*payment_amount/100, num_grp_sep,dec_sep,2,2));
    }
}

$(document).ready(function(){
    //INVOICE
    toggleCompany();
    $('#is_company').change(toggleCompany);
    $('#bank_fee_rate').prop('readonly', true);
    $('#loan_fee_rate').prop('readonly', true);
    $("#save_invoice").click(function(){
        ajaxStatus.showStatus('Saving'); //Sugar alert
        $('#amount_in_words_invoice').val(DocTienBangChu(unformatNumber($('#total_in_invoice').text(),num_grp_sep,dec_sep)));
        $('#action_save').val('OppSaveInvoice');
        $('#record_use').val($('input[name=record]').val());
    });

    $("#gerenare_invoice").click(function(){
        $(".entry-form").fadeIn("fast");
    });

    //PAYMENT
    $("#card_type, #bank_fee_rate, #loan_fee_rate, #bank_name").change(calculatedPaymentMethod);

    $('#credit_info').hide();
    $('#loan_info').hide();
    $('input:radio[name="payment_method"]').click(function(){
        togglePaymentMethod(this) ;
    });

    $("#save_payment").click(function(){
        if(unformatNumber($('#payment_amount').val(),num_grp_sep,dec_sep) > 0){
            ajaxStatus.showStatus('Saving'); //Sugar alert
            $('#amount_in_words_payment').val(DocTienBangChu(unformatNumber($('#payment_amount').val(),num_grp_sep,dec_sep)));
            $('#action_save').val('OppSavePayment');
            $('#record_use').val($('input[name=record]').val());
        } else {
            alert("Please enter again !");
            $('#payment_amount').val("");
        }
    });


    $("a.payment-popup").click(function(){
        $(".entry-payment-form").fadeIn("fast");
        //Add payment Amount
        $('input[name=payment_amount]').val(formatNumber($(this).attr('payment-amount'),num_grp_sep,dec_sep,0,0));
        $('input[name=payment_id]').val($(this).attr('id'));
        calculatedPaymentMethod();
    });

    //Remove value deleted in option Status Payment Methods
    $("#status option[value='Deleted']").remove();

    //Cancel Enrollment
    $("#cancelEnrollment").click(function(){
        ajax2();
    });
    if($('input#sales_stage').val()=='Failure'){
        $('#cancelEnrollment').hide();
    }
    //POPUP

    $("#close_ct").click(function(){
        $(".entry-form").fadeOut("fast");
        $(".entry-payment-form").fadeOut("fast");
    });

    $("#cancel_ct").click(function(){
        $(".entry-form").fadeOut("fast");
        $(".entry-payment-form").fadeOut("fast");
    });

    //Confirm Delete 1 records

    $('#delete_button').removeAttr('onclick');
    $('#delete_button').click(function(){
        var _form = document.getElementById('formDetailView');
        _form.return_module.value='Opportunities';
        _form.return_action.value='ListView';
        _form.action.value='Delete';

        var confirm = prompt("Please enter your description to confirm delete.", "");

        if (confirm != null) {
            $('#descriptions').val(confirm);
            SUGAR.ajaxUI.submitForm(_form);
        }
    });
    //Them Field Date
    Calendar.setup ({
        inputField : "date_text",
        ifFormat : cal_date_format,
        daFormat : cal_date_format,
        button : "date_text_trigger",
        singleClick : true,
        dateStr : "",
        startWeekday: 0,
        step : 1,
        weekNumbers:false
        }
    );
    $('#div_date_2').hide();
    $("#edit_date").click(function(){
        $('#div_date_1').hide();
        $('#date_text_span').hide();
        $('#div_date_2').show();
    });
    $("#save_date").live('click',ajaxChangeDateField);

    $("#close_enrollment").live('click',function(){
        $( "#dialog-confirm_10" ).dialog({
            resizable: false,
            width: 600,
            modal: true,
            buttons: {
                "Drop Balance to Free Balance": function() {
                    if(check_form('Close_enrollment')){
                        $(this).dialog('close');
                        caculateClosedEnrollment('drop_to_free_balance');
                    }
                },
                "Drop Balance to Revenue": function() {
                    if(check_form('Close_enrollment')){
                        $(this).dialog('close');
                        caculateClosedEnrollment('drop_to_revenue');
                    }
                },
                "Cancel": function() {
                    $(this).dialog('close');
                },
            }
        });
        caculateFreeBalance();
        $('.ui-dialog-buttonset').find('button').eq(0).addClass("primary");
        $('.ui-dialog-buttonset').find('button').eq(1).addClass("primary");
    });

    $('#drop_date_trigger_div_t tbody').live('click',function(){
        $('#expire_date_text').text($('#drop_date').val()).effect("highlight", {color: 'red'}, 1000);
    });

    $('#expire_date_trigger_div_t tbody').live('click',function(){
        checkDataLockDate('expire_date');
        caculateFreeBalance();
    });

    $('.container-close').live('click',function(){
        $('#dialog-confirm_10').trigger( "click" );
    });


    $('#undo_button').live('click',function(){
        ajaxUndo();
    });

    //Convert

    $('#btn_convert_to_360').click(function(){
        $('#diaglog_convert_to_360').dialog({
            resizable: false,
            width: 500,
            height:'auto',
            modal: true,
            visible: true,
            position: ['center'],
            beforeClose: function(event, ui) {
                $("body").css({ overflow: 'inherit' });
            },

        });
    });

    $('#btn_submit_convert_360').live('click',function(){
        if( $('#ct_days').val() == 0){
            alertify.error('Hour must be greater than 0 !');
            return ;
        }
        //Submit class in progress
        ajaxStatus.showStatus('Processing...');
        $('#submit_convert_loading').show();
        $('#btn_submit_convert_360').hide();
        $.ajax({
            url: "index.php?module=Opportunities&action=ajax_convert_to_360&sugar_body_only=true",
            type: "POST",
            async: true,
            data:
            {
                enrollment_id  : $("input[name='record']").val(),
                remain_amount  : $("#ct_amount").val(),
                remain_days    : $("#ct_days").val(),
                payment_date   : $("#ct_date").val(),
                unpaid_amount  : $("#ct_unpaid_amount").val(),
            },
            dataType: "json",
            success: function(res){
                if(res.success == '1'){
                    location.reload();
                }else{
                    alertify.alert('An error occurred. <br><br>Please, check again!');
                    ajaxStatus.hideStatus();
                    $('#submit_convert_loading').hide();
                    $('#btn_submit_convert_360').show();
                    $('#diaglog_convert_to_360').dialog("close");
                    $("#ct_amount, #ct_days").val('');
                }

            },
        });
    });
});

function caculateClosedEnrollment(type){
    ajaxStatus.showStatus('Saving <img src="custom/include/images/loader32.gif" align="absmiddle" width="32">');
    $.ajax({
        url: "index.php?module=Opportunities&action=handleCloseEnr&sugar_body_only=true",
        type: "POST",
        async: true,
        data:
        {
            enr_id: $('input[name=record]').val(),
            end_date: $('#expire_date').val(),
            drop_date: $('#drop_date').val(),
            free_balance: unformatNumber($('#total_free_balance').text(),num_grp_sep,dec_sep),
            type : type,
            total_payment: unformatNumber($('#total_payment_amount').val(),num_grp_sep,dec_sep),
        },
        dataType: "json",
        success: function(res){
            if(res.success == "1")
                window.location = 'index.php?module=Opportunities&action=DetailView&record='+$('input[name=record]').val();
            else
                alertify.alert('An error occurred. Please, refresh the page!');

            ajaxStatus.hideStatus();
        },
    });
}


function caculateFreeBalance(){
    var expire_date = $('#expire_date').val();
    if(expire_date == ''){
        alertify.alert('An error occurred. Please, Try again!');
        return false;
    }
    ajaxStatus.showStatus('Checking <img src="custom/include/images/loader32.gif" align="absmiddle" width="32">');
    $.ajax({
        url: "index.php?module=Opportunities&action=handleCloseEnr&sugar_body_only=true",
        type: "POST",
        async: true,
        data:
        {
            enr_id: $('input[name=record]').val(),
            end_date: expire_date,
            total_payment: unformatNumber($('#total_payment_amount').val(),num_grp_sep,dec_sep),
            type : "caculate_balance",
        },
        dataType: "json",
        success: function(res){
            if(res.success == "1"){
                $('#total_delivery').text(res.delivery_amount).effect("highlight", {color: 'red'}, 1000);
                $('#total_free_balance').text(res.balance).effect("highlight", {color: 'red'}, 1000);
                $('#expire_date_text').text($('#drop_date').val());
                $('#team_name_text').text(res.team_name);
            }else{
                alertify.alert('An error occurred. Please, Try again!');
            }

            ajaxStatus.hideStatus();
        },
    });
}



//Add To Class
function showPopupConfirm(popup_reply_data){

    var class_id = popup_reply_data.name_to_value_array.class_id;
    var enrollments_id = $('input[name=record]').val();

    ajaxStatus.showStatus('Checking <img src="custom/include/images/loader32.gif" align="absmiddle" width="32">');
    $.ajax({
        url: "index.php?module=C_Classes&action=actCheckClassNull&sugar_body_only=true",
        type: "POST",
        async: true,
        data:
        {
            class_id: class_id,
            enrollments_id: enrollments_id,
            type : "addOneEnrollment",
        },
        dataType: "json",
        success: function(res){
            switch (res.success) {
                case 'error0':
                    alertify.alert('Student: '+res.student_name+' is already in class !');
                    ajaxStatus.hideStatus();
                    break;
                case 'error1':
                    alertify.alert('This class is not enough seating for' + enrollments_id.length + ' persons. Please, Check max size of class again.');
                    ajaxStatus.hideStatus();
                    break;
                case 'error2':
                    $( "#dialog-confirm" ).dialog({
                        resizable: false,
                        width: 600,
                        modal: true,
                        buttons: {
                            "Thêm vào tất cả các buổi": function() {
                                $(this).dialog('close');
                                ajaxAddToClass(class_id, enrollments_id, '1');
                            },
                            "Chỉ thêm vào các buổi chưa bắt đầu": function() {
                                $(this).dialog('close');
                                ajaxAddToClass(class_id, enrollments_id, '0');
                            },
                        }
                    });
                    break;
                case '1':
                    ajaxAddToClass(class_id, enrollments_id);
                    break;
                default:
                    alertify.alert('An error occurred. Please, try again!');
                    ajaxStatus.hideStatus();
            }
            ajaxStatus.hideStatus();
        },
    });
}

function ajaxAddToClass(class_id ,enrollments_id, comfirm) {

    ajaxStatus.showStatus('Waiting <img src="custom/include/images/loader32.gif" align="absmiddle" width="32">');
    $.ajax({
        url: "index.php?module=C_Classes&action=actAddToClass&sugar_body_only=true",
        type: "POST",
        async: true,
        data:
        {
            enrollments_id: enrollments_id,
            class_id: class_id,
            comfirm: comfirm,
            type : "addOneEnrollment",
        },
        dataType: "json",
        success: function(res){
            if(res.success == "1")
                window.location = 'index.php?module=C_Classes&return_module=C_Classes&action=DetailView&record='+class_id;
            else
                alertify.alert('An error occurred. Please, try again!');
            ajaxStatus.hideStatus();
        },
    });
}

function ajaxChangeDateField(){
    $.ajax({
        url: "index.php?entryPoint=ajaxChangeDate",
        type: "POST",
        async: true,
        data:
        {
            module: $('input[name=module]').val(),
            id:     $('input[name=record]').val(),
            date:   $('#date_text').val(),
            field:  $('#date_text').closest('div').closest('span').attr('id'),
        },
        dataType: "json",
        success: function(res){
            if(res.success == "1"){
                $('#date_text_span').text(res.date);
                $('#div_date_1').show();
                $('#date_text_span').show();
                $('#div_date_2').hide();
            }
        },
    });
}

//Ajax Undo
function ajaxUndo(){
    oppid = $('input[name=opportunity_id]').val();
    data = "&oppid="+oppid;
    if(confirm('Are you sure you want to Undo this enrollment ?')){
        ajaxStatus.showStatus('Waiting...'); //Sugar alert
        $.ajax({
            url:'index.php?module=Opportunities&action=undo_enrollment&sugar_body_only=true',
            type: "POST",
            data: data,
            dataType: "json",
            success: function(response){
                if(response.success == "1"){
                    ajaxStatus.showStatus('Done');
                    location.href='index.php?module=Opportunities&action=DetailView&record='+oppid;
                }else{
                    ajaxStatus.hideStatus(); //END:Sugar alert
                    alert("Unexpected error! Try again.");
                }
            },
        });
    }
}

Calendar.setup ({
    inputField : "ct_date",
    daFormat : cal_date_format,
    button : "ct_date_trigger",
    singleClick : true,
    dateStr : "",
    step : 1,
    weekNumbers:false
});