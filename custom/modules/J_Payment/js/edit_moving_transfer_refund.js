var record_id           = $('input[name=record]').val();
var payment_type_begin  = $('#payment_type').val();

$(document).ready(function() {
    $("#payment_list_div").show();
    
    $('#moving_tran_out_date, #moving_tran_in_date, #payment_date').on('change',function(){
        if(!checkDataLockDate($(this).attr('id'),false))
            return ;
    });
    if(is_admin != '1')
        addToValidateMoreThan('EditView', 'payment_amount', 'int', true, 'Please choose at least one payment!', 1);

    $('#btn_contacts_j_payment_1_name').removeAttr('onclick');
    $('#btn_contacts_j_payment_1_name').click(function(){
        open_popup('Contacts', 600, 400, "", true, false, {"call_back_function":"set_contact_return","form_name":"EditView","field_to_name_array":{"id":"contacts_j_payment_1contacts_ida","name":"name","assigned_user_id":"assigned_user_id","assigned_user_name":"assigned_user_name"}}, "single", true);
    });

    sqs_objects["EditView_contacts_j_payment_1_name"] = {
        "form":"EditView",
        "method":"query",
        "modules":['Contacts'],
        "group":"or",
        "field_list":["name", "id","assigned_user_id","assigned_user_name"],
        "populate_list":["contacts_j_payment_1_name", "contacts_j_payment_1contacts_ida","assigned_user_id","assigned_user_name"],
        "required_list":"contacts_j_payment_1contacts_ida",
        "conditions":[{"name":"name","op":"like_custom","end":"%","value":""}],
        "order":"contacts_j_payment_1_name",
        "limit":"30",
        "no_match_text":"No Match",
        "post_onblur_function": "ajaxGetStudentInfo"
    };

    if ($("#contacts_j_payment_1contacts_ida").val() != "") ajaxGetStudentInfo();

    $('[name="pay_check"]').live('change',function(){
        caculated();
    });

    $('#btn_clr_contacts_j_payment_1_name').on('click',function(){
        $('#tbodypayment').html("");
        $('#assigned_user_id').val("");
        $('#assigned_user_name').val("");
        $('#total_hours').val(0);
        $('#payment_amount').val(0);
    });

    $('input#payment_amount,input#refund_revenue').live('blur',function(){
        if(payment_type_begin == 'Refund'){
            var total_amount = 0;
            $('.pay_check:checked').each(function(index, brand){
                var pay_amount = Numeric.parse($(this).closest('tr').find('.pay_remain_amount').text());
                var pay_type = $(this).closest('tr').find('.pay_payment_type').text()
                payment_list[$(this).val()] = {};
                payment_list[$(this).val()]["id"] = $(this).val();
                payment_list[$(this).val()]["payment_type"] = pay_type;
                payment_list[$(this).val()]["used_amount"] = pay_amount;

                total_amount += pay_amount;
            });

            var payment = Numeric.parse($('input#payment_amount').val());
            var revenue = Numeric.parse($('input#refund_revenue').val());
            if(total_amount == 0){
                alertify.error(SUGAR.language.get('J_Payment','LBL_ALERT_REFUND'));
                $('input#payment_amount, input#refund_revenue').val('');
                return ;
            }
            if($(this).attr('id') == 'payment_amount')
                $('input#refund_revenue').val(formatNumber(total_amount - payment,num_grp_sep,dec_sep));
            else  $('input#payment_amount').val(formatNumber(total_amount - revenue,num_grp_sep,dec_sep));

        }
    });
});

function set_contact_return(popup_reply_data){
    var form_name = popup_reply_data.form_name;
    var name_to_value_array = popup_reply_data.name_to_value_array;
    for (var the_key in name_to_value_array) {
        if (the_key == 'toJSON') {
            continue;
        } else {
            var val = name_to_value_array[the_key].replace(/&amp;/gi, '&').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&#039;/gi, '\'').replace(/&quot;/gi, '"');
            debugger;
            switch (the_key)
            {
                case 'name':
                    $("#contacts_j_payment_1_name").val(val);
                    break;
                case 'contacts_j_payment_1contacts_ida':
                    $("#contacts_j_payment_1contacts_ida").val(val);
                    break;
                case 'assigned_user_id':
                    $("#assigned_user_id").val(val);
                    break;
                case 'assigned_user_name':
                    $("#assigned_user_name").val(val);
                    break;
            }
        }
    }
    ajaxGetStudentInfo();
}

// Đối type của nút SAVE thành button (để không tự động save form khi user ấn enter trong input)
function changeTypeInputSubmit(inputItem){
    var newInput = inputItem.clone();
    newInput.attr("type", "button");
    newInput.insertBefore(inputItem);
    inputItem.remove();
}

//Ajax get Student Info
function ajaxGetStudentInfo(){
    ajaxStatus.showStatus('Processing...');
    $.ajax({
        url: "index.php?module=J_Payment&action=handleAjaxPayment&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  {
            type            : 'ajaxGetStudentInfo',
            enrollment_id   : record_id,
            payment_type    : payment_type_begin,
            payment_type    : $('#payment_type').val(),
            student_id      : $('#contacts_j_payment_1contacts_ida').val(),
        },
        dataType: "json",
        success: function(res){
            ajaxStatus.hideStatus();
            if(res.success == "1"){
                $('input#json_student_info').val(res.content);
                showPaymentTable();
                caculated();
            }
            else {
                $('input#json_student_info').val('');
            }
        },
    });
}

//Show Dialog
function showPaymentTable(){
    var json = $('input#json_student_info').val();
    if (json != "") {
        obj = JSON.parse(json);
    }

    //Show Payment List
    var html    = '';
    var count   = 0;
    $.each(obj.top_list, function( key, value ) {
        html += "<tr>";
        //        if(is_admin == '1'){
        if(value['is_expired'])
            html += "<td align='center'></td>";
        else
            html += "<td align='center'><input type='checkbox' name='pay_check' style='vertical-align: baseline;zoom: 1.2;' class='pay_check' value='"+value['payment_id']+"'"+value['checked']+"></td>";
        //        }else{
        //            if(value['is_expired'])
        //                html += "<td align='center'></td>";
        //            else
        //                html += "<td align='center'><input type='radio' name='pay_check' class='pay_check' value='"+value['payment_id']+"'"+value['checked']+"></td>";
        //        }

        html += "<td align='center'><a target='_blank' style='text-decoration: none;font-weight: bold;' href='index.php?module=J_Payment&action=DetailView&record="+value['payment_id']+"'>"+value['payment_code']+"</a></td>";
        html += "<td align='center' class='pay_payment_type'>"+value['payment_type']+"</td>";
        html += "<td align='center'>"+value['payment_date']+"</td>";
        if(value['is_expired'])
            html += "<td align='center' style='color: red;'>"+value['payment_expired']+"</td>";
        else html += "<td align='center'>"+value['payment_expired']+"</td>";
        html += "<td align='center' class='pay_payment_amount'>"+Numeric.toFloat(value['payment_amount'],0,0)+"</td>";
        html += "<td align='center' class='pay_total_hours'>"+Numeric.toFloat(value['total_hours'],2,2)+"</td>";
        html += "<td align='center' class='pay_remain_amount'>"+Numeric.toFloat(value['remain_amount'],0,0)+"</td>";
        html += "<td align='center' class='pay_remain_hours'>"+Numeric.toFloat(value['remain_hours'],2,2)+"</td>";
        html += "<td align='center' class='pay_course_fee'>"+value['course_fee']+"  <input type='hidden' class='corporate_id' value='"+value['corporate_id']+"'/> <input type='hidden' class='corporate_name' value='"+value['corporate_name']+"'/>  </td>";
        html += "<td align='center'><a target='_blank' style='text-decoration: none;' href='index.php?module=Users&action=DetailView&record="+value['assigned_user_id']+"'>"+value['assigned_user_name']+"</a></td>";
        html += "</tr>";
        count++
    });
    if(count == 0){
        html += '<tr><td colspan="10" style="text-align: center;">No Payment Avaiable</td></tr>';
    }
    //Add Student Corporate Name
    //    $('#student_corporate_id').val(obj['info']['student_corporate_id'])
    //    $('#student_corporate_name').val(obj['info']['student_corporate_name'])

    $('#tbodypayment').html(html);
}

// Caculate total hours, total amount

function caculated(){
    var total_hours = 0;
    var total_amount = 0;
    var payment_list = {};
    var pay_corporate_id = '';
    var paymentType = $('#payment_type').val();

    //Count total hour & Total Amount
    $('.pay_check:checked').each(function(index, brand){
        var pay_amount  = Numeric.parse($(this).closest('tr').find('.pay_remain_amount').text());
        var pay_hours   = Numeric.parse($(this).closest('tr').find('.pay_remain_hours').text());
        total_hours     += pay_hours;
        total_amount    += pay_amount;
    });

    $('.pay_check:checked').each(function(index, brand){
        var pay_amount  = Numeric.parse($(this).closest('tr').find('.pay_remain_amount').text());
        var pay_hours   = Numeric.parse($(this).closest('tr').find('.pay_remain_hours').text());
        var pay_type    = $(this).closest('tr').find('.pay_payment_type').text();
        if( (paymentType != "Refund") && ((total_hours == 0 && total_amount > 0 && pay_hours > 0) || (total_hours > 0 && pay_hours <= 0))){
            alertify.error(SUGAR.language.get('J_Payment','LBL_ERR_MERGE_PAYMENT'));
            $(this).prop('checked', false).closest('tr').effect("highlight", {color: '#ff9933'}, 1000);
            total_hours     -= pay_hours;
            total_amount    -= pay_amount;
        }else{
            payment_list[$(this).val()]                 = {};
            payment_list[$(this).val()]["id"]           = $(this).val();
            payment_list[$(this).val()]["payment_type"] = pay_type;
            payment_list[$(this).val()]["used_amount"]  = pay_amount;
            payment_list[$(this).val()]["used_hours"]   = pay_hours;
            pay_corporate_id    = $(this).closest('tr').find('.corporate_id').val();
        }

    });

    var str_json_payment_list = JSON.stringify(payment_list);
    $('#json_payment_list').val(str_json_payment_list);

    $('#total_hours').val(Numeric.toFloat(total_hours,2,2));
    if($("#payment_type").val() == "Transfer Out" && pay_corporate_id == ''){
        $('#total_hours').val(Numeric.toFloat(0,2,2));
    }
    $('#payment_amount').val(Numeric.toFloat(total_amount,0,0));
    // $('#remain_amount').val(Numeric.toInt(total_amount));
    $('#refund_revenue').val('');
}

function check_form(formname) {
    if (typeof (siw) != 'undefined' && siw && typeof (siw.selectingSomething) != 'undefined' && siw.selectingSomething)
        return false;
    if (payment_type_begin == "Transfer Out" && $("#transfer_to_student_id").val() == "") {
        alertify.error(SUGAR.language.get('J_Payment','LBL_NO_STUDENT_SELECTED'));
        return false;
    }
    if (payment_type_begin == "Moving Out"  && $("#move_to_center_id").val() == "") {
        alertify.error(SUGAR.language.get('J_Payment','LBL_NO_CENTER_SELECTED'));
        return false;
    }
    if ($('.pay_check:checked').length == 0) {
        alertify.error(SUGAR.language.get('J_Payment','LBL_NO_PAYMENT_SELECTED'));
        return false;
    }
    var pay_corporate_id = '';
    var pay_corporate_name = '';
    $('.pay_check:checked').each(function(index, brand){
        pay_corporate_id    = $(this).closest('tr').find('.corporate_id').val();
        pay_corporate_name  = $(this).closest('tr').find('.corporate_name').val();
    });
    //    Comment tạm để fix bug !!!
    //    var student_corporate_id = $('#student_corporate_id').val();
    //    if(pay_corporate_id != '' && (pay_corporate_id !=  student_corporate_id) && payment_type_begin == "Transfer Out"){
    //        alertify.alert('This is payment from Corporate Name: <b>'+pay_corporate_name+'</b>. <br>You can not transfer to the student not from <b>'+pay_corporate_name+'</b>');
    //        return false;
    //    }
    //Check refund amount & refund revenue
    var total_amount = 0;
    var payment_out_date = SUGAR.util.DateUtils.parse($('#moving_tran_out_date').val(),cal_date_format);
    var paymentType = $('#payment_type').val();
    if (paymentType != "Refund"){
        var payment_in_date = SUGAR.util.DateUtils.parse($('#moving_tran_in_date').val(),cal_date_format);
    }

    var payment_date = new Date;

    $('.pay_check:checked').each(function(index, brand){
        var pay_amount = Numeric.parse($(this).closest('tr').find('.pay_remain_amount').text());
        var pay_type = $(this).closest('tr').find('.pay_payment_type').text()
        payment_date = SUGAR.util.DateUtils.parse($(this).closest('tr').find("td:nth(3)").text(),cal_date_format);
        total_amount += pay_amount;
    });
    var refundAmount = Numeric.parse($("#payment_amount").val());
    var refundRevenue = Numeric.parse($("#refund_revenue").val());
    if(total_amount < (refundAmount + refundRevenue)){
        alertify.error(SUGAR.language.get('J_Payment','LBL_ALERT_REFUND_AMOUNT'));
        return false;
    }

    //Check payment date
    //If payment type = refund, do not check payment_in_date
    if (paymentType == "Refund"){
        if (payment_out_date < payment_date){
            alertify.error(SUGAR.language.get('J_Payment','LBL_ALERT_REFUND_DATE'));
            return false;
        }
    }
    else{
        if (payment_out_date < payment_date || payment_in_date < payment_date){
            if (paymentType == "Transfer In" || paymentType == "Transfer Out") alertify.error(SUGAR.language.get('J_Payment','LBL_ALERT_TRANSFER_DATE'));
            else if (paymentType == "Moving In" || paymentType == "Moving Out") alertify.error(SUGAR.language.get('J_Payment','LBL_ALERT_MOVING_DATE'));
            return false;
        }
    }

    var result = validate_form(formname, '');
    if(result)
        ajaxStatus.showStatus('Saving...');
    return result;
}