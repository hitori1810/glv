$(document).ready(function() {
    $('#btn_sel_atc').live('click',function(){
        var student_list = $('#contacts_checked_str').val();
        if (typeof(student_list) == "undefined" || student_list == ''){
            alertify.alert("<span class=\"ui-icon ui-icon-alert\" style=\"float:left;\"></span> " + SUGAR.language.get('Contracts','LBL_PLEASE_SELECT_STUDENT'));
            return ;
        }
        open_popup("J_Class", 600, 400, "&contracts_j_class_1_id_advanced="+$('input[name=record]').val(), true, false, {"call_back_function":"set_atc_return","form_name":"DetailView","field_to_name_array":{"id":"atc_class_id"}}, "single", true);
    });


    $('#atc_start_study, #atc_end_study').on('change',function(){
        //Validate Is Between and check valid input
        var rs1 = validateDateIsBetween($(this).val(), $('#atc_start_date').text(), $('#atc_end_date').text());
        if(!rs1) {
            $(this).val('').effect("highlight", {color: 'red'}, 1000);
            return ;
        }
        //Validate Data Lock
        if(!checkDataLockDate($(this).attr('id'),true))
            return ;
        var res = calSession($('#atc_start_study').val(), $('#atc_end_study').val());

    });

    $('.btn_submit_add_to_class').live( 'click', submitAddToClass);

    $('.btn_refresh_page').live( 'click', function(){
        $('.btn_refresh_page').hide();
        $('.btn_refresh_page').parent().append('<span id="atc_saving" >'+SUGAR.language.get('app_strings','LBL_AJAX_PLEASE_WAIT')+' <img src="custom/include/images/loader.gif" align="absmiddle" width="16"></span>');
    });


    var contract_status = $('#status').val();
    if(contract_status != 'signed'){
        $('#contracts_contacts_tạo_button, #import_student, #contracts_contacts_select_button, #btn_sel_atc').hide();
        $('#contracts_contacts_tạo_button').closest('div').append('<span style="color:red;">Change Status = Signed before you handle it.</span>');
    }

    //HANDLE PAYMENT DETAIL
    $('#payment_method').live('change',function(){
        if($('#payment_method').val() == 'Card')
            $('#card_type').show();
        else $('#card_type').hide();

        if($('#payment_method').val() == 'Bank Transfer')
            $('#bank_type').show();
        else $('#bank_type').hide();

        if($('#payment_method').val() == 'Other')
            $('#method_note').show();
        else $('#method_note').hide();
        //            removeFromValidate('DetailView','card_type');
    });
    $('#payment_date_collect').on('change',function(){
        var rs3 = checkDataLockDate($(this).attr('id'));
    });

    $('#btn_dt_cancel').live("click",function(){
        $('.diaglog_payment').dialog('close');
    });
    $('#btn_dt_save, #btn_dt_save_get_vat').live("click",function(){
        updatePaymentDetail($(this).attr('action'));
    });
});
//Function Adult
function set_atc_return(popup_reply_data){
    var form_name = popup_reply_data.form_name;
    var name_to_value_array = popup_reply_data.name_to_value_array;
    $('#atc_class_id').val(name_to_value_array.atc_class_id);
    get_class_info_adult();
}

function get_class_info_adult(){                                                                                 
    ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_AJAX_PLEASE_WAIT'));
    $.ajax({
        type:"POST",
        url: "index.php?module=J_Payment&action=handleAjaxPayment&sugar_body_only=true",
        data:{
            type            : "get_class_info_adult",
            class_id        : $('#atc_class_id').val(),
            contract_id     : $('input[name=record]').val(),
        },
        dataType: "json",
        success:function(data){
            ajaxStatus.hideStatus();
            if (data.success == "1"){
                //Show Add To Class Dialog
                $('#atc_class_code').text(data.class_code);
                $('#atc_class_name').text(data.class_name);
                $('#atc_class_type').text(data.class_type);
                $('#atc_start_date').text(data.start_date);
                $('#atc_end_date').text(data.end_date);
                $('#atc_koc').text(data.koc);
                $('#atc_level').text(data.level);
                $('#atc_schedule').html(data.schedule);

                $('#atc_sessionList').val(data.session_list);
                $('#atc_remain_practice').val(data.remain_practice);
                $('#atc_remain_skill').val(data.remain_skill);
                $('#atc_remain_connect').val(data.remain_connect);
                $('#atc_start_study').val(data.start_study);
                $('#atc_end_study').val(data.end_study);
                calSession($('#atc_start_study').val(), $('#atc_end_study').val());

                //Show Dialog
                $('#add_to_class_dialog').dialog({
                    resizable    : false,
                    width        :'auto',
                    height       :'auto',
                    modal        : true,
                    position     : ['middle',40],
                    visible      : true,
                    beforeClose: function(event, ui) {
                        clearClassInfo();
                    },
                    buttons: {
                        "Submit":{
                            click:function() {
                            },
                            class    : 'button primary btn_submit_add_to_class',
                            text    : 'Submit',
                        },
                        "Cancel":{
                            click:function() {
                                $(this).dialog('close');
                            },
                            class    : 'button btn_cancel_add_to_class',
                            text    : 'Cancel',
                        },
                    },
                });
            }else{
                alertify.alert(data.notify);
            }
        },
        error: function(xhr, textStatus, errorThrown){
            ajaxStatus.hideStatus();
            alertify.error(SUGAR.language.get('app_strings','LBL_AJAX_ERROR'));
        }
    });
}

function submitAddToClass(){
    var start_study = $('#atc_start_study').val();
    var end_study   = $('#atc_end_study').val();
    var class_id    = $('#atc_class_id').val();
    var cal_session = $('#atc_session').val();
    var cal_session_remain = $('#atc_session_remain').val();
    if(start_study == '' || end_study == '' || class_id == '' || cal_session == 0){
        alertify.error(SUGAR.language.get('Contracts','LBL_PLEASE_FILL_OUT_COMPLETELY')); 
        return ;
    }
    if( cal_session_remain < 0){      
        alertify.error(SUGAR.language.get('Contracts','LBL_SESSIONS_REMAINING_ARE_NOT_ENOUGH'));
        return ;
    }
    $('.btn_submit_add_to_class, .btn_cancel_add_to_class').hide();
    $('.btn_submit_add_to_class').parent().append('<span id="atc_saving" >'+SUGAR.language.get('Contracts','LBL_SAVING')+'.. <img src="custom/include/images/loader.gif" align="absmiddle" width="16"></span>');
    $.ajax({
        type: "POST",
        url: "index.php?module=J_Payment&action=handleAjaxPayment&sugar_body_only=true",
        data:  {
            type        : "submitAddToClass",
            start_study : start_study,
            end_study   : end_study,
            contract_id : $('input[name=record]').val(),
            student_list: $('#contacts_checked_str').val(),
            class_id    : class_id,
        },
        dataType: "json",
        success:function(data){
            if (data.success == "1"){
                $('#add_to_class_dialog').dialog('close');
                var notify = '';
                var count = 1;
                $.each(data.result, function( index, value ) {
                    var stu_res = $.parseJSON(value.result);
                    notify += count+' - '+value.student_name + ': '+
                    ((stu_res.success == '1') ?
                        '<span style="color: #169400; font-weight: bold;">'+SUGAR.language.get('Contracts','LBL_SUCCESSFULL')+'</span>' :
                        ('<span style="color: #d14;  font-weight: bold;">'+SUGAR.language.get('Contracts','LBL_FAIL')+'</span>' + '<br><span style="color: #d14;"> Lỗi: '+stu_res.notify+'</span>'));
                    notify += '<br><br>';
                    count++;

                });
                $('#add_to_class_notify').html(notify);
                $('#add_to_class_notify').dialog({
                    resizable    : false,
                    width        :'auto',
                    height       :'auto',
                    modal        : true,
                    visible      : true,
                    position     : ['middle',30],
                    beforeClose: function(event, ui) {
                        location.reload();
                        ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_AJAX_PLEASE_WAIT')+' <img src="custom/include/images/loader32.gif" align="absmiddle" width="32">');
                    },
                    buttons: {
                        "Refresh":{
                            click:function() {
                                location.reload();
                            },
                            class    : 'button primary btn_refresh_page',
                            text    : SUGAR.language.get('Contracts','LBL_REFRESH_PAGE'),
                        },
                    },
                });
            }
        },
    });
}
function calSession(start_study, end_study){
    var rs = new Object();
    var count_ss            = 0
    var total_hours         = 0;
    var remain_practice     = $('#atc_remain_practice').val();

    var json_sessions     = $("#atc_sessionList").val();
    if(start_study == '' || end_study == '' || json_sessions == ''){
        return ;
    }
    var start_study = SUGAR.util.DateUtils.parse(start_study,cal_date_format);
    var end_study   = SUGAR.util.DateUtils.parse(end_study,cal_date_format);

    obj = JSON.parse(json_sessions);
    var flag_start  = false;
    var flag_end    = false;

    $.each(obj, function( key, value){
        var key_date = Date.parse(value['date']);
        if(start_study.toDateString() == key_date.toDateString())
            flag_start = true;

        if(end_study.toDateString() == key_date.toDateString())
            flag_end = true;

        if( (key_date <= end_study) && (key_date >= start_study) ){
            count_ss++;
            total_hours = total_hours + Numeric.parse(value['hour']);
        }
    });

    if( !flag_start || !flag_end ){
        if(!flag_start){
            alertify.error(SUGAR.language.get('Contracts','LBL_START_STUDY_NOT_IN_CLASS_SCHEDULE'));
            $('#atc_start_study').val('').effect("highlight", {color: 'red'}, 1000);
        }
        else if(!flag_end){                                        
            alertify.error(SUGAR.language.get('Contracts','LBL_START_STUDY_NOT_IN_CLASS_SCHEDULE'));
            $('#atc_end_study').val('').effect("highlight", {color: 'red'}, 1000);
        }
        count_ss    = '0';
        total_hours = '0';
    }
    $('#atc_cal_session').text(count_ss).effect("highlight", {color: 'blue'}, 1000);
    $('#atc_session').val(count_ss);
    if(remain_practice == 0){
        $('#atc_cal_session_remain').text('----').effect("highlight", {color: 'blue'}, 1000);
        $('#atc_session_remain').val(0);
    }else{
        $('#atc_cal_session_remain').text(remain_practice - count_ss).effect("highlight", {color: 'blue'}, 1000);
        $('#atc_session_remain').val(remain_practice - count_ss);
    }
    $('#atc_cal_total_hours').text(Numeric.toFloat(total_hours,2,2)).effect("highlight", {color: 'blue'}, 1000);
}

function validateDateIsBetween(check_date, from, to){
    if(check_date == '') return true;
    check_date     = SUGAR.util.DateUtils.parse(check_date, cal_date_format);
    alertify.set({ delay: 10000 });
    if(from != '' && to != ''){
        from_check           = SUGAR.util.DateUtils.parse(from, cal_date_format).getTime();
        to_check             = SUGAR.util.DateUtils.parse(to, cal_date_format).getTime();
        if(check_date == false){
            alertify.error(SUGAR.language.get('J_Class', 'LBL_INVALID_DATE_RANGE_DATE_MUST_BETWEEN')+from+SUGAR.language.get('J_Class', 'LBL_AND')+to+'.');
            return false;
        }else{
            check_date = check_date.getTime();
            if(check_date < from_check || check_date > to_check){
                alertify.error(SUGAR.language.get('J_Class', 'LBL_INVALID_DATE_RANGE_DATE_MUST_BETWEEN')+from+SUGAR.language.get('J_Class', 'LBL_AND')+to+'.');
                return false;
            }
        }
    }else if(from != ''){
        from_check           = SUGAR.util.DateUtils.parse(from, cal_date_format).getTime();

        if(check_date < from_check){
            alertify.error(SUGAR.language.get('J_Class', 'LBL_INVALID_DATE_RANGE_DATE_MUST_AFTER')+from+'.');
            return false;
        }
    } else{
        to_check             = SUGAR.util.DateUtils.parse(to, cal_date_format).getTime();
        if(check_date > to_check){
            alertify.error(SUGAR.language.get('J_Class', 'LBL_INVALID_DATE_RANGE_DATE_MUST_BEFORE')+to+'.');
            return false;
        }
    }
    return true;
}

function clearClassInfo(){
    $('#atc_class_code').text('');
    $('#atc_class_name').text('');
    $('#atc_class_type').text('');
    $('#atc_start_date').text('');
    $('#atc_end_date').text('');
    $('#atc_koc').text('');
    $('#atc_level').text('');
    $('#atc_cal_session').text('');
    $('#atc_cal_session_remain').text('');
    $('#atc_schedule').html('');
    $('#atc_cal_total_hours').text('');

    $('#atc_sessionList').val('');
    $('#atc_remain_practice').val('');
    $('#atc_remain_skill').val('');
    $('#atc_remain_connect').val('');
    $('#atc_start_study').val('');
    $('#atc_end_study').val('');
    $('#atc_session').val('');
    $('#atc_session_remain').val('');
}


Calendar.setup ({
    inputField : "atc_start_study",
    daFormat : cal_date_format,
    button : "atc_start_study_trigger",
    singleClick : true,
    dateStr : "",
    step : 1,
    weekNumbers:false
});
Calendar.setup ({
    inputField : "atc_end_study",
    daFormat : cal_date_format,
    button : "atc_end_study_trigger",
    singleClick : true,
    dateStr : "",
    step : 1,
    weekNumbers:false
});

//***HANDLE PAYMENT DETAIL
function get_invoice(payment_detail){                                                                            
    ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_AJAX_PLEASE_WAIT'));
    $.ajax({
        type: "POST",
        url: "index.php?module=J_Payment&action=handleAjaxPayment&sugar_body_only=true",
        data:  {
            type                : "ajaxGetInvoice",
            payment_detail      : payment_detail,
        },
        success:function(data){
            ajaxStatus.hideStatus();
            data = JSON.parse(data);
            if (data.success == "1") {
                showSubPanel('payment_paymentdetails', null, true);
                alertify.success('Success !<br>You changes has been saved');
                if(data.sale_type != '' && data.sale_type != null){
                    $('#label_sale_type').text(data.sale_type);
                    $('#value_sale_type').val(data.sale_type);
                }
                if(data.sale_type_date != '' && data.sale_type_date != null){
                    $('#label_sale_type_date').text(data.sale_type_date);
                    $('#value_sale_type_date').val(data.sale_type_date);
                }

            }
            else{
                alertify.alert(data.errorLabel);
            }

        },
        error: function(xhr, textStatus, errorThrown){
            ajaxStatus.hideStatus();
            alertify.error(SUGAR.language.get('app_strings','LBL_AJAX_ERROR'));
        }
    });
}
function finish_printing(printing_id){                                                                            
    ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_AJAX_PLEASE_WAIT'));
    $.ajax({
        type: "POST",
        url: "index.php?module=J_Payment&action=handleAjaxPayment&sugar_body_only=true",
        data:  {
            type             : "finish_printing",
            printing_id      : printing_id,
        },
        success:function(data){
            ajaxStatus.hideStatus();
            data = JSON.parse(data);
            if (data.success == "1") {
                showSubPanel('payment_paymentdetails', null, true);
                alertify.success(SUGAR.language.get('app_strings','LBL_AJAX_SAVE_SUCCESS'));
            }else{
                alertify.error(SUGAR.language.get('app_strings','LBL_AJAX_ERROR'));
            }
        },
        error: function(xhr, textStatus, errorThrown){
            ajaxStatus.hideStatus();
            alertify.error(SUGAR.language.get('app_strings','LBL_AJAX_ERROR'));
        }
    });
}

function ex_invoice(thisButton) {
    var payment_detail = thisButton.getAttribute('payment_detail_id');
    var is_corporate = $('#is_corporate').val();
    if(is_corporate == '1'){
        var ex_corporate = function() {
            window.open('index.php?module=J_Payment&type=corporate&action=invoiceVoucher&record='+payment_detail+'&sugar_body_only=true','_blank');
            confirmExportPopup.destroy(document.body);
        };
        var ex_student = function() {
            window.open('index.php?module=J_Payment&type=student&action=invoiceVoucher&record='+payment_detail+'&sugar_body_only=true','_blank');
            confirmExportPopup.destroy(document.body);
        };
        var ex_both = function() {
            window.open('index.php?module=J_Payment&type=both&action=invoiceVoucher&record='+payment_detail+'&sugar_body_only=true','_blank');
            confirmExportPopup.destroy(document.body);
        };
        var ex_cancel = function() {
            confirmExportPopup.destroy(document.body);
        };

        var confirm_text = SUGAR.language.get('J_Payment', 'LBL_CONFIRM_EXPORT');
        var confirmExportPopup = new YAHOO.widget.SimpleDialog("export_vat_popup",{
            width: "400px",
            draggable: true,
            constraintoviewport: true,
            modal: true,
            fixedcenter: true,
            text: confirm_text,
            bodyStyle: "padding:5px",
            buttons: [{
                text: SUGAR.language.get('J_Payment', 'LBL_CORPORATE'),
                handler: ex_corporate,
                isDefault: true
                }, {
                    text: SUGAR.language.get('J_Payment', 'LBL_STUDENT'),
                    handler: ex_student
                },{
                    text: SUGAR.language.get('J_Payment', 'LBL_BOTH'),
                    handler: ex_both
                },{
                    text: SUGAR.language.get('J_Payment', 'LBL_CANCEL_EXPORT'),
                    handler: ex_cancel
            }]
        });

        confirmExportPopup.setHeader(SUGAR.language.get('J_Payment', 'LBL_CONFIRM'));
        confirmExportPopup.render(document.body);
    } else {
        window.open('index.php?module=J_Payment&type=student&action=invoiceVoucher&record='+payment_detail+'&sugar_body_only=true','_blank');
    }
}

function cancel_invoice(payment_detail){
    // prompt dialog
    alertify.prompt(SUGAR.language.get('Contracts','LBL_CONFIRM_CANCEL_INVOICE'), function (e, str) {
        // str is the input text
        if (e) {                                                                                                                                    
            ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_AJAX_PLEASE_WAIT'));
            $.ajax({
                type: "POST",
                url: "index.php?module=J_Payment&action=handleAjaxPayment&sugar_body_only=true",
                data:  {
                    type                : "ajaxCancelInvoice",
                    payment_detail      : payment_detail,
                    description         : str,
                },
                success:function(data){
                    data = JSON.parse(data);
                    if (data.success == "1") {
                        //                        showSubPanel('payment_paymentdetails', null, true);
                        SUGAR.ajaxUI.showLoadingPanel();
                        location.reload();

                    }else{                 
                        alertify.error(SUGAR.language.get('Contracts','LBL_PAYMENT_HAS_BEAN_USED'));
                    }
                    ajaxStatus.hideStatus();
                },
            });
        } else {
            // user clicked "cancel"
        }
        }, "");
}

function pay(thisButton){
    var today = SUGAR.util.DateUtils.formatDate(new Date());
    var payment_detail = thisButton.getAttribute('payment_detail_id');
    var payment_amount = thisButton.getAttribute('payment_detail_amount');
    $('#dt_payment_amount').val(payment_amount);
    $('#dt_payment_detail_id').val(payment_detail);
    $('#payment_date_collect').val(today).trigger('change');
    $("body").css({ overflow: 'hidden' });
    $('.diaglog_payment').dialog({
        resizable: false,
        width:'450px',
        height:'auto',
        modal: true,
        visible: true,
        position: ['center',50],
        beforeclose: function (event, ui) {
            $('#payment_method').val('').trigger('change');
            $("body").css({ overflow: 'inherit' });
        },
    });
}
function edit_invoice(thisButton){
    var payment_detail = thisButton.getAttribute('payment_detail_id');
    var payment_amount = thisButton.getAttribute('payment_detail_amount');
    var payment_method = thisButton.getAttribute('payment_method');
    var payment_date = thisButton.getAttribute('payment_date');
    var card_type = thisButton.getAttribute('card_type');
    var bank_type = thisButton.getAttribute('bank_type');
    var invoice_no = thisButton.getAttribute('invoice_no');
    var serial_no = thisButton.getAttribute('serial_no');

    $('#dt_payment_amount').val(payment_amount);
    $('#dt_payment_detail_id').val(payment_detail);
    $('#payment_method').val(payment_method).trigger('change');
    $('#payment_date_collect').val(payment_date).trigger('change');
    $('#card_type').val(card_type);
    $('#bank_type').val(bank_type);
    $('#dt_invoice_no').val(invoice_no);
    $('#dt_invoice_serial').val(serial_no);
    $('#btn_dt_save_get_vat').hide();

    $("body").css({ overflow: 'hidden' });
    $('.diaglog_payment').dialog({
        resizable: false,
        width:'450px',
        height:'auto',
        modal: true,
        visible: true,
        position: ['center',50],
        beforeclose: function (event, ui) {
            $("body").css({ overflow: 'inherit' });
            $('#payment_method').val('').trigger('change');
            $('#btn_dt_save_get_vat').show();
        },
    });
}

function updatePaymentDetail(handle_action){
    var payment_date = $("#payment_date_collect").val();
    var payment_mt = $('#payment_method').val();
    var card_type  = $('#card_type').val();
    var bank_type  = $('#bank_type').val();
    var invoice_no  = $('#dt_invoice_no').val();
    var invoice_serial  = $('#dt_invoice_serial').val();
    if(payment_date == '' || payment_mt == '' || (payment_mt== 'Other' && $('#method_note').val() == '') || (payment_mt == 'Card' && card_type == '')|| (payment_mt == 'Bank Transfer' && bank_type == '')){
        alertify.error('Please fill out fields completely !');
        if(payment_mt == '')
            $('#payment_method').effect("highlight", {color: 'red'}, 2000);
        return ;
    }
    $('#btn_dt_save, #btn_dt_save_get_vat, #btn_dt_cancel').hide();
    $("#save_loading").show();
    $.ajax({
        type: "POST",
        url: "index.php?module=J_Payment&action=handleAjaxPayment&sugar_body_only=true",
        data:  {
            type                : "ajaxUpdatePaymentDetail",
            payment_detail      : $('#dt_payment_detail_id').val(),
            payment_method      : $('#payment_method').val(),
            card_type           : $('#card_type').val(),
            bank_type           : $('#bank_type').val(),
            method_note         : $('#method_note').val(),
            payment_date        : payment_date,
            invoice_no          : invoice_no,
            invoice_serial      : invoice_serial,
            module_name         : module_sugar_grp1,
            handle_action       : handle_action,
        },
        success:function(data){
            data = JSON.parse(data);
            if (data.success == "1") {
                //Comment by Tung Bui - Cần xử lý lại logic tính paid amount cho trường hợp contract
                
                //$('#pmd_paid_amount').text(data.paid);
//                $('#pmd_unpaid_amount').text(data.unpaid);
//                showSubPanel('payment_paymentdetails', null, true);
//
//                if(data.sale_type != '' && data.sale_type != null){
//                    $('#label_sale_type').text(data.sale_type);
//                    $('#value_sale_type').val(data.sale_type);
//                }
//                if(data.sale_type_date != '' && data.sale_type_date != null){
//                    $('#label_sale_type_date').text(data.sale_type_date);
//                    $('#value_sale_type_date').val(data.sale_type_date);
//                }
                alertify.success('Success !<br>Please, confirm infomation and get invoice no');
                location.reload();
            }
            $('#btn_dt_save, #btn_dt_save_get_vat, #btn_dt_cancel').show();
            $("#save_loading").hide();
            $(".diaglog_payment").dialog("close");
            if (data.errorLabel != "" && data.errorLabel != undefined){
                alertify.alert(data.errorLabel);
            }
        },

    });
}

Calendar.setup ({
    inputField : "payment_date_collect",
    daFormat : cal_date_format,
    button : "payment_date_trigger",
    singleClick : true,
    dateStr : "",
    step : 1,
    weekNumbers:false
});
