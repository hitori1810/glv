var record_id       = $('input[name=record]').val();
$(document).ready(function() {

    // Init QS for package
    sqs_objects["EditView_account_name"] = {
        "form":"EditView",
        "method":"query",
        "modules":['Accounts'],
        "group":"or",
        "field_list":["name", "id", "phone_office", "tax_code", "phone_fax", "bank_name", "bank_number", "billing_address_street"],
        "populate_list":["account_name", "account_id", "account_phone", "account_tax_code", "account_fax", "account_bank_name", "account_bank_number", "account_address"],
        "required_list":"id",
        "conditions":[{"name":"name","op":"like_custom","end":"%","value":""}],
        "order":"name",
        "limit":"30",
        "no_match_text":"No Match",
        "form":"EditView",
        "method":"query",
    };
    enableQS(true);

    $('#duration_session').live('change',function(){
        var duration_session        = Numeric.parse($(this).val());
        var duration_hour           = duration_session * 4;
        $('#duration_hour').val(Numeric.toFloat(duration_hour,2,2)).effect("highlight", {color: '#ff9933'}, 1000);
    });

    $('#vat_percent').live('change',function(){
        $(this).val(Numeric.toFloat($(this).val(),2,2));
    });
    autoGeneratePayment();
    $('#total_contract_value, :[id*=payment_amount_]').live('blur',function(){
        autoGeneratePayment();
    });

    displaySplitPayment(false);
    $('#number_of_payment').live('change',function(){
        displaySplitPayment(true);
    });

    $('#customer_signed_date').live('change',function(){
        var number_of_payment   = $('#number_of_payment').val();
        var contract_date       = $('#customer_signed_date').val();
        for(i = 1; i <= number_of_payment; i++ ){
            if( $('#pay_dtl_invoice_date_'+i).val() == '')  //In Case Create
                $('#pay_dtl_invoice_date_'+i).val(contract_date).effect("highlight", {color: '#ff9933'}, 1000);

        }
    });
    lockDataSigned();
    $('select#status').live('change',function(){
        if(record_id != ''){
            var status_db   = $(this).attr('db-data');
            var status      = $(this).val();
            if(status_db == 'signed' && status != status_db){
                alertify.confirm("Khi đổi trạng thái hợp đồn <b>"+SUGAR.language.languages['app_list_strings']['contract_status_dom'][status_db]+"</b> --> <b>"+SUGAR.language.languages['app_list_strings']['contract_status_dom'][status]+"</b> thì tất cả học viên sẽ bị xóa khỏi hợp đồng. <br> Click <b>OK</b>: Để xác nhận thao tác.<br>Click <b>Cancel</b>: để hủy thao tác.", function (e) {
                    if (e)
                        $('#total_contract_value, #number_of_student, #duration_session, #duration_hour').prop('readonly',false).removeClass('input_readonly');
                    else
                        $('select#status').val(status_db);
                });
            }else if(status_db == 'signed' && status == status_db){
                $('#total_contract_value').val($('#total_contract_value').attr('db-data'));
                $('#number_of_student').val($('#number_of_student').attr('db-data'));
                $('#duration_session').val($('#duration_session').attr('db-data'));
                $('#duration_hour').val($('#duration_hour').attr('db-data'));
            }
        }
        lockDataSigned();
    });
});

function lockDataSigned(){
    var status = $('select#status').val();
    if(status == 'signed' && record_id != ''){
        $('#total_contract_value, #number_of_student, #duration_session, #duration_hour').prop('readonly',true).addClass('input_readonly');
    }
}

function autoGeneratePayment(){
    var number_of_payment     = $('#number_of_payment').val();
    var grand_total         = Numeric.parse($("#total_contract_value").val());
    var discount_amount     = Numeric.parse(0);
    var final_sponsor       = Numeric.parse(0);

    var payment_amount_1    = Numeric.parse($("#payment_amount_1").val());
    var payment_amount_2    = Numeric.parse($("#payment_amount_2").val());
    var payment_amount_3    = Numeric.parse($("#payment_amount_3").val());
    var payment_amount_4    = Numeric.parse($("#payment_amount_4").val());
    var payment_amount_5    = Numeric.parse($("#payment_amount_5").val());
    switch (number_of_payment){
        case '1':
            payment_amount_1    = grand_total;
            $("#payment_amount_1").val(Numeric.toInt(payment_amount_1));
            break;
        case '2':
            if(payment_amount_1 == 0) return ;
            payment_amount_2        = grand_total - payment_amount_1;
            $("#payment_amount_2").val(Numeric.toInt(payment_amount_2));
            break;
        case '3':
            if(payment_amount_1 == 0 || payment_amount_2 == 0) return ;
            payment_amount_3 = grand_total - payment_amount_1 - payment_amount_2;
            $("#payment_amount_3").val(Numeric.toInt(payment_amount_3));
            break;
        case '4':
            if(payment_amount_1 == 0 || payment_amount_2 == 0 || payment_amount_3 == 0) return ;
            payment_amount_4 = grand_total - payment_amount_1 - payment_amount_2 - payment_amount_3;
            $("#payment_amount_4").val(Numeric.toInt(payment_amount_4));
            break;
        case '5':
            if(payment_amount_1 == 0 || payment_amount_2 == 0 || payment_amount_3 == 0 || payment_amount_4 == 0) return ;
            payment_amount_5 = grand_total - payment_amount_1 - payment_amount_2 - payment_amount_3 - payment_amount_4;
            $("#payment_amount_5").val(Numeric.toInt(payment_amount_5));
            break;
    }
}


function displaySplitPayment(clear){
    //Clear Payment Amount
    var contract_date = $('#customer_signed_date').val();
    var number_of_payment = $('#number_of_payment').val();

    if(clear)
        $(":[id*=payment_amount_]").val('');

    $(":[id*=tbl_split_payment_]").hide();

    for(i = 1; i <= number_of_payment; i++ ){
        if(number_of_payment == '1')
            $('#payment_amount_1').prop('readonly',true).addClass('input_readonly');
        else
            $('#payment_amount_1').prop('readonly',false).removeClass('input_readonly');

        if($('#pay_dtl_invoice_date_'+i).val() == '')  //In Case Create
            $('#pay_dtl_invoice_date_'+i).val(contract_date).effect("highlight", {color: '#ff9933'}, 1000);

        $('#tbl_split_payment_'+i).show();
    }
    autoGeneratePayment();
}


function check_form(formname) {
    //Validate sum amount of split payments
    var payment_amount          = Numeric.parse($('#total_contract_value').val());
    var payment_amount_1        = Numeric.parse($('#payment_amount_1').val());
    var payment_amount_2        = Numeric.parse($('#payment_amount_2').val());
    var payment_amount_3        = Numeric.parse($('#payment_amount_3').val());
    var payment_amount_4        = Numeric.parse($('#payment_amount_4').val());
    var payment_amount_5        = Numeric.parse($('#payment_amount_5').val());

    if  (((payment_amount_1 + payment_amount_2 + payment_amount_3 + payment_amount_4 + payment_amount_5 ) != payment_amount)) {
        var mes = SUGAR.language.get('J_Payment', 'LBL_ALERT_SUM_SPLIT');
        alertify.error(mes);
        $(":[id*=payment_amount_]").effect("highlight", {color: '#FF0000'}, 5000);
        return false;
    }

    var result = validate_form(formname, '');
    if(result){
        ajaxStatus.showStatus('Saving...');
        return true;
    }else return false;

}

Calendar.setup ({
    inputField : "pay_dtl_invoice_date_1",
    daFormat : cal_date_format,
    button : "pay_dtl_invoice_date_1_trigger",
    singleClick : true,
    dateStr : "",
    step : 1,
    weekNumbers:false
});
Calendar.setup ({
    inputField : "pay_dtl_invoice_date_2",
    daFormat : cal_date_format,
    button : "pay_dtl_invoice_date_2_trigger",
    singleClick : true,
    dateStr : "",
    step : 1,
    weekNumbers:false
});
Calendar.setup ({
    inputField : "pay_dtl_invoice_date_3",
    daFormat : cal_date_format,
    button : "pay_dtl_invoice_date_3_trigger",
    singleClick : true,
    dateStr : "",
    step : 1,
    weekNumbers:false
});
Calendar.setup ({
    inputField : "pay_dtl_invoice_date_4",
    daFormat : cal_date_format,
    button : "pay_dtl_invoice_date_4_trigger",
    singleClick : true,
    dateStr : "",
    step : 1,
    weekNumbers:false
});
Calendar.setup ({
    inputField : "pay_dtl_invoice_date_5",
    daFormat : cal_date_format,
    button : "pay_dtl_invoice_date_5_trigger",
    singleClick : true,
    dateStr : "",
    step : 1,
    weekNumbers:false
});