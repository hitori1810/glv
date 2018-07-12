$(document).ready(function() {
    $('#payment_date').on('change',function(){
        var rs3 = checkDataLockDate($(this).attr('id'));
    });
    displaySplitPayment(false);
    $('#number_of_payment, .foc_type').live('change',function(){

        displaySplitPayment(true);
    });

    toggleIsCorporate();
    $('#is_corporate').on('change',function(){
        $('#btn_clr_account_name').trigger('click');
        toggleIsCorporate();
    });
    $('#btn_account_name').live('click',function(){
        open_popup("Accounts", 600, 400, "", true, true, {
            "call_back_function": "set_return_corp",
            "form_name": "EditView",
            "field_to_name_array": {
                "id": "account_id",
                "name": "account_name",
                "billing_address_street": "company_address",
                "tax_code": "tax_code",
            },
            }, "Select", true);
    });

    $('#btn_clr_account_name').live('click',function(){
        $('#account_id, #account_name, #company_name, #company_address, #tax_code').val('');
    });

    $('#payment_amount_1, #payment_amount_2, #payment_amount_3, #discount_amount_1, #discount_amount_2, #discount_amount_3').live('blur',function(){
        autoGeneratePayment();
    });

    $("#payment_type").on("change",function(){

        displaySplitPayment(true);
    });
    //Set up payment date button
    Calendar.setup ({
        inputField : "payment_date_1",
        daFormat : cal_date_format,
        button : "payment_date_1_trigger",
        singleClick : true,
        dateStr : "",
        step : 1,
        weekNumbers:false
        }
    );
    Calendar.setup ({
        inputField : "payment_date_2",
        daFormat : cal_date_format,
        button : "payment_date_2_trigger",
        singleClick : true,
        dateStr : "",
        step : 1,
        weekNumbers:false
        }
    );
    Calendar.setup ({
        inputField : "payment_date_3",
        daFormat : cal_date_format,
        button : "payment_date_3_trigger",
        singleClick : true,
        dateStr : "",
        step : 1,
        weekNumbers:false
        }
    );
});

function autoGeneratePayment(){
    var number_of_payment 	= $('#number_of_payment').val();
    var grand_total         = Numeric.parse($("#payment_amount").val());
    var discount_amount     = Numeric.parse($("#discount_amount").val());
    var loyalty_amount      = Numeric.parse($("#loyalty_amount").val());
    var final_sponsor       = Numeric.parse($("#final_sponsor").val());

    var payment_amount_1    = Numeric.parse($("#payment_amount_1").val());
    var payment_amount_2    = Numeric.parse($("#payment_amount_2").val());
    var payment_amount_3    = Numeric.parse($("#payment_amount_3").val());

    var before_discount_1   = Numeric.parse($("#before_discount_1").val());
    var before_discount_2   = Numeric.parse($("#before_discount_2").val());
    var before_discount_3   = Numeric.parse($("#before_discount_3").val());

    var discount_amount_1   = Numeric.parse($("#discount_amount_1").val());
    var discount_amount_2   = Numeric.parse($("#discount_amount_2").val());
    var discount_amount_3   = Numeric.parse($("#discount_amount_3").val());


    var count_buv = 0;
    $('.row_tpl_sponsor').not(":eq(0)").each(function(index, brand){
        var foc_type = $(this).find('.foc_type').val();
        if(foc_type == 'BUV, BEP')
        count_buv++;
    });


    switch (number_of_payment){
        case '1':
            if(count_buv == 0){
                payment_amount_1    = grand_total;
                $("#payment_amount_1").val(Numeric.toInt(payment_amount_1));
            }else{
                payment_amount_1    = before_discount_1 - discount_amount_1;
                $("#payment_amount_1").val(Numeric.toInt(payment_amount_1));
            }
            break;
        case '2':
            if(count_buv == 0){
                if(payment_amount_1 == 0) return ;
                payment_amount_2        = grand_total - payment_amount_1;
                $("#payment_amount_2").val(Numeric.toInt(payment_amount_2));
            }else{
                if(payment_amount_1 == 0 || before_discount_1 == 0){
                    payment_amount_1    = before_discount_1 - discount_amount_1;
                    payment_amount_2    = before_discount_2 - discount_amount_2;
                    $("#payment_amount_1").val(Numeric.toInt(payment_amount_1));
                    $("#payment_amount_2").val(Numeric.toInt(payment_amount_2));
                }else{
                    discount_amount_2   = ( discount_amount + final_sponsor + loyalty_amount ) - discount_amount_1;
                    payment_amount_2    = grand_total - payment_amount_1;
                    before_discount_2   =  payment_amount_2 + discount_amount_2;
                    $("#discount_amount_2").val(Numeric.toInt(discount_amount_2));
                    $("#payment_amount_2").val(Numeric.toInt(payment_amount_2));
                    $("#before_discount_2").val(Numeric.toInt(before_discount_2));
                }
            }
            break;
        case '3':
            if(count_buv == 0){
                if(payment_amount_1 == 0 || payment_amount_2 == 0) return ;
                payment_amount_3 = grand_total - payment_amount_1 - payment_amount_2;
                $("#payment_amount_3").val(Numeric.toInt(payment_amount_3));
            }else{
                if(payment_amount_1 == 0 || before_discount_1 == 0 || payment_amount_2 == 0 || before_discount_2 == 0){
                    payment_amount_1    = before_discount_1 - discount_amount_1;
                    payment_amount_2    = before_discount_2 - discount_amount_2;
                    payment_amount_3    = before_discount_3 - discount_amount_3;
                    $("#payment_amount_1").val(Numeric.toInt(payment_amount_1));
                    $("#payment_amount_2").val(Numeric.toInt(payment_amount_2));
                    $("#payment_amount_3").val(Numeric.toInt(payment_amount_3));
                }else{
                    discount_amount_3   = ( discount_amount + final_sponsor + loyalty_amount) - discount_amount_1 - discount_amount_2;
                    payment_amount_3    = grand_total - payment_amount_1 - payment_amount_2;
                    before_discount_3   =  payment_amount_3 + discount_amount_3;
                    $("#discount_amount_3").val(Numeric.toInt(discount_amount_3));
                    $("#payment_amount_3").val(Numeric.toInt(payment_amount_3));
                    $("#before_discount_3").val(Numeric.toInt(before_discount_3));
                }
            }
            break;
    }
    //Lock Assigned to
    if($('#lock_assigned_to').val() == 1){
        $('#assigned_user_name').prop('readonly',true).addClass('input_readonly');
        $('#btn_assigned_user_name, #btn_clr_assigned_user_name').prop('disabled',true);
    }
}

// Ẩn hiện fieldset split payment
function displaySplitPayment(clear){
    //Clear Payment Amount
    var payment_date = $('#payment_date').val();
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
            $('#pay_dtl_invoice_date_'+i).val(payment_date).effect("highlight", {color: '#ff9933'}, 1000);

        $('#tbl_split_payment_'+i).show();
    }
    autoGeneratePayment();
}
// Ẩn hiện thông tin corporate
function toggleIsCorporate(){
    if($('#is_corporate').is(':checked')){
        $('#vat-corp-info').slideDown('fast');
        addToValidateBinaryDependency('EditView', 'account_name', 'alpha', true, SUGAR.language.get('app_strings', 'ERR_SQS_NO_MATCH_FIELD') + SUGAR.language.get('J_Payment','LBL_ACCOUNT_ID') , 'account_id' );
        addToValidate('EditView', 'company_name', 'text', true, SUGAR.language.get('J_Payment','LBL_COMPANY_NAME'));
        addToValidate('EditView', 'tax_code', 'text', true, SUGAR.language.get('J_Payment','LBL_TAX_CODE'));
        addToValidate('EditView', 'company_address', 'text', true, SUGAR.language.get('J_Payment','LBL_COMPANY_ADDRESS'));
    }else{
        $('#vat-corp-info').slideUp('fast');
        removeFromValidate('EditView','company_name');
        removeFromValidate('EditView','tax_code');
        removeFromValidate('EditView','company_address');
        removeFromValidate('EditView','account_name');
    }
}
// Overwirite set_return Parent Type
function set_return_corp(popup_reply_data){
    $('#company_name_temp, #company_id_temp, #company_name, #company_address, #tax_code').val('');
    var form_name = popup_reply_data.form_name;
    var name_to_value_array = popup_reply_data.name_to_value_array;
    for (var the_key in name_to_value_array) {
        if (the_key == 'toJSON') {
            continue;
        } else {
            var val = name_to_value_array[the_key].replace(/&amp;/gi, '&').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&#039;/gi, '\'').replace(/&quot;/gi, '"');
            switch (the_key)
            {
                case 'account_id':
                    $('#account_id').val(val);
                    break;
                case 'account_name':
                    $('#account_name, #company_name').val(val);
                    break;
                case 'company_address':
                    $('#company_address').val(val);
                    break;
                case 'tax_code':
                    $('#tax_code').val(val);
                    break;
            }
        }
    }
}

function togglePaymentMethod(){
    if($('#payment_method_1').val() == 'Card') $('#card_type_1').show();
    else $('#card_type_1').hide();

    if($('#payment_method_2').val() == 'Card') $('#card_type_2').show();
    else $('#card_type_2').hide();

    if($('#payment_method_3').val() == 'Card')$('#card_type_3').show();
    else $('#card_type_3').hide();
}



//Overwrite check_form to validate
function check_form(formname) {
    //Validate sum amount of split payments
    var payment_amount          = Numeric.parse($('#payment_amount').val());
    var payment_amount_1        = Numeric.parse($('#payment_amount_1').val());
    var payment_amount_2        = Numeric.parse($('#payment_amount_2').val());
    var payment_amount_3        = Numeric.parse($('#payment_amount_3').val());
    var tuition_hours           = Numeric.parse($('#tuition_hours').val());
    var payment_type            = $('#payment_type').val();
    var count_buv = 0;
    $('.row_tpl_sponsor').not(":eq(0)").each(function(index, brand){
        var foc_type = $(this).find('.foc_type').val();
        if(foc_type == 'BUV, BEP')
            count_buv++;
    });

    if  (((payment_amount_1 + payment_amount_2 + payment_amount_3 ) != payment_amount)) {
        var mes = SUGAR.language.get('J_Payment', 'LBL_ALERT_SUM_SPLIT');
        alertify.error(mes);
        $('#payment_amount_1, #payment_amount_2, #payment_amount_3').effect("highlight", {color: '#FF0000'}, 5000);
        return false;
    }
    if(count_buv == 0){
        if ((payment_amount_1 == 0 && payment_amount != 0) || payment_amount_1 < 0 || (payment_amount_2 <= 0 && $('#number_of_payment').val() >= 2) || (payment_amount_3 <= 0 && $('#number_of_payment').val() == 3)){
            var mes = SUGAR.language.get('J_Payment', 'LBL_ALERT_INVALID_AMOUNT');
            alertify.error(mes);
            $('#payment_amount_1, #payment_amount_2, #payment_amount_3').effect("highlight", {color: '#FF0000'}, 5000);
            return false;
        }
    }


    if(( payment_type == 'Deposit' || payment_type == 'Placement Test') && payment_amount <= 0 ){
        alertify.error(SUGAR.language.get('J_Payment', 'LBL_ALERT_INVALID_AMOUNT'));
        $('#payment_amount').effect("highlight", {color: '#FF0000'}, 5000);
        return false;
    }
    if(( payment_type == 'Cashholder' || payment_type == 'Enrollment')){
        if($("#coursefee").val() == ""){
            alertify.error(SUGAR.language.get('J_Payment', 'LBL_COURSE_FEE_EMPTY'));
            $('#select2-coursefee-container').effect("highlight", {color: '#FF0000'}, 5000);
            $('#coursefee').effect("highlight", {color: '#FF0000'}, 5000);
            return false;    
        }
        
        if(tuition_hours <= 0){
            alertify.error(SUGAR.language.get('J_Payment', 'LBL_COURSE_FEE_EMPTY'));
            $('#tuition_hours').effect("highlight", {color: '#FF0000'}, 5000);
            return false;    
        }   
    }

    var result = validate_form(formname, '');
    if(result && alertSelectPayment()){
        ajaxStatus.showStatus('Saving...');
        return true;
    }else return false;

}

function alertSelectPayment(){
    var count_pm           = 0;
    var count_pm_checked   = 0;
    $('.pay_check').each(function(index, brand){
        count_pm++;
        if($(this).is(':checked'))
            count_pm_checked++;
    });
    var total_hours     = Numeric.parse($('#total_hours').val());
    var course_hour     = parseInt($("#coursefee option:selected").attr('type'));
    var course_fee_name = $("#coursefee option:selected").text();
    var payment_type    = $('#payment_type').val();
    var team_type    = $('#team_type').val();

    if(count_pm > 0 && payment_type_begin == 'Enrollment' && count_pm_checked == 0){
        alertify.confirm(SUGAR.language.get('J_Payment', 'LBL_CONFIRM_SAVE_ENROLLMENT'), function (e) {
            if (e) {
                ajaxStatus.showStatus('Saving...');
                var _form = document.getElementById('EditView');
                _form.action.value='Save';
                SUGAR.ajaxUI.submitForm(_form);
                return false;
            } else {
                return false;
            }
        });
    }else if((team_type == 'Junior') && (payment_type == 'Enrollment' || payment_type == 'Cashholder') && count_pm_checked == 0 && ((total_hours <= 36 && course_hour >= 72) || (total_hours <= 72 && course_hour >= 108))){
        var notify = 'Are you sure to add a payment for <b>'+total_hours+' hours</b> with course fee ID<br> <b>'+course_fee_name+'</b> ? <br> Click <b>OK</b> to continue saving.<br>Click <b>Cancel</b> to cancel hold and check again.';
        alertify.confirm(notify, function (e) {
            if (e){
                ajaxStatus.showStatus('Saving...');
                var _form = document.getElementById('EditView');
                _form.action.value='Save';
                SUGAR.ajaxUI.submitForm(_form);
                return false;
            }else
                return false;

        });
    }else return true;
}