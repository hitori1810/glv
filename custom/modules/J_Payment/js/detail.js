var record_id = $('input[name=record]').val();
$( document ).ready(function() {
    quickAdminEdit('j_payment', 'sale_type');
    quickAdminEdit('j_payment', 'sale_type_date');
    quickAdminEdit('j_payment', 'assigned_user_id');
    quickAdminEdit('j_payment', 'payment_expired');

    $('.inventory_id').live("click",function(){
        var inventory_id = this.getAttribute('inventory_id');
        window.open("index.php?module=J_Inventory&action=exportInventory&record="+inventory_id,'_blank');
    });

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

    //Undo moving/ transfer
    $("#btn_undo").on("click",function(){
        undoPayment();
    });

    $('.container-close').click(function(){

    });

    $('#convert_payment').click(function(){
        $('#diaglog_convert_payment').dialog({
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

    autoGetNextInvoice();
    setTimeout(function(){autoCheckInvoiceReleased();}, 5000);
    //Custom Adult360 - Add to Class
    $('#add_to_class').live('click',function(){
        var pay_status      = $('input#status').val();
        var is_paid         = $('input#is_paid').val();
        var today_date      = (new Date()).getTime();
        var finish_study    = $('input#end_study').val();
        if(finish_study == '' || finish_study == null)
            finish_study = today_date;
        else finish_study    = SUGAR.util.DateUtils.parse(finish_study, cal_date_format).getTime();

        if( (pay_status == 'Closed' || today_date > finish_study) && is_admin != '1' ){                                                                                    
            alertify.alert("<span class=\"ui-icon ui-icon-alert\" style=\"float:left;\"></span> " + SUGAR.language.get('J_Payment', 'LBL_PAYMENT_EXPIRIED_OR_NOT_FULLY_PAID'));
            return ;
        }
        open_popup("J_Class", 600, 400, "", true, false, {"call_back_function":"set_atc_return","form_name":"DetailView","field_to_name_array":{"id":"atc_class_id"}}, "single", true);
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

    // Convert Payment
    $('#cp_convert_type').live('change',function(){
        if($(this).val() == 'To Amount'){
            $('#cp_tuition_hours, #cp_remain_hours').val('0').prop('disabled',true).addClass('input_readonly');
        }else{
            $('#cp_tuition_hours, #cp_remain_hours').val('0').prop('disabled',false).removeClass('input_readonly');
        }
    });
    $('#cp_convert_type').trigger('change');
    $('#btn_submit_convert').live('click',function(){
        if( $("#cp_convert_type").val() == 'To Hour' && ($('#cp_tuition_hours').val() == 0 || $('#cp_remain_hours').val() == 0)){
            alertify.error('');
            return ;
        }
        //Submit class in progress             
        ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_AJAX_PLEASE_WAIT'));
        $('#submit_convert_loading').show();
        $('#btn_submit_convert').hide();
        $.ajax({
            url: "index.php?module=J_Payment&action=handleAjaxPayment&sugar_body_only=true",
            type: "POST",
            async: true,
            data:
            {
                type            : 'ajaxConvertPayment',
                payment_id      : $("input[name='record']").val(),
                tuition_hours   : $("#cp_tuition_hours").val(),
                remain_hours    : $("#cp_remain_hours").val(),
                convert_to_type : $("#cp_convert_type").val(),
            },
            dataType: "json",
            success: function(res){
                if(res.success == '1'){
                    location.reload();
                }else{                                                                   
                    alertify.error(SUGAR.language.get('app_strings','LBL_AJAX_ERROR'));
                    ajaxStatus.hideStatus();
                    $('#submit_convert_loading').hide();
                    $('#btn_submit_convert').show();
                    $('#diaglog_convert_payment').dialog("close");
                    $("#cp_tuition_hours").val('');
                }
            },
        });
    });
    //END: Convert Payment

});

function autoGetNextInvoice(){
    if($('#nextInvoice').length > 0){
        $.ajax({
            type: "POST",
            url: "index.php?module=J_Payment&action=handleAjaxPayment&sugar_body_only=true",
            data:  {
                type                : "autoGetNextInvoice",
                team_id             : $('#team_id').val(),
            },
            success:function(data){
                data = JSON.parse(data);
                if (data.success == "1"){
                    $('#nextInvoice').text(data.nextInvoiceNo);
                    setTimeout(function(){autoGetNextInvoice();}, 5000);
                }
            },
        });
    }
}

function checkPaymentType(){
    if ($("#payment_type").val() != "Cashholder")
    {
        // Hide Cashholder field
        $("#tuition_hours").closest("tr").hide();
        $("#amount_bef_discount").closest("tr").hide();
        $("#discount_percent").closest("tr").hide();
        $("#discount_amount").closest("tr").hide();
        $("#total_after_discount").closest("tr").hide();
        $("#final_sponsor").closest("tr").hide();
    }
}

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

function ex_invoice_2(thisButton) {
    var invoice_id = thisButton.getAttribute('invoice_id');
    window.open('index.php?module=J_Payment&type=student&action=invoiceVoucher_2&record='+invoice_id+'&sugar_body_only=true','_blank');
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

    $('#dt_payment_amount').val(payment_amount);
    $('#dt_payment_detail_id').val(payment_detail);
    $('#payment_method').val(payment_method).trigger('change');
    $('#payment_date_collect').val(payment_date).trigger('change');
    $('#card_type').val(card_type);
    $('#bank_type').val(bank_type);
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
    if(payment_date == '' || payment_mt == '' || (payment_mt== 'Other' && $('#method_note').val() == '') || (payment_mt == 'Card' && card_type == '')|| (payment_mt == 'Bank Transfer' && bank_type == '')){
        alertify.error(SUGAR.language.get('J_Payment', 'LBL_PLEASE_FILL_OUT_INFO_COMPLETELY'));
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
            handle_action       : handle_action,
        },
        success:function(data){
            data = JSON.parse(data);
            if (data.success == "1") {
                $('#pmd_paid_amount').text(data.paid);
                $('#pmd_unpaid_amount').text(data.unpaid);
                showSubPanel('payment_paymentdetails', null, true);

                if(data.sale_type != '' && data.sale_type != null){
                    $('#label_sale_type').text(data.sale_type);
                    $('#value_sale_type').val(data.sale_type);
                }
                if(data.sale_type_date != '' && data.sale_type_date != null){
                    $('#label_sale_type_date').text(data.sale_type_date);
                    $('#value_sale_type_date').val(data.sale_type_date);
                }
                alertify.success(SUGAR.language.get('J_Payment', 'LBL_CONFIRM_INVOICE'));

                if(data.unpaidNum == 0){
                    $('#pmd_paid_amount').closest('tr').find("td:eq(1)").text(SUGAR.language.get('app_list_strings','status_paid_payment_list')['Fully Paid']);
                } else if (data.paidNum > 0) {
                    $('#pmd_paid_amount').closest('tr').find("td:eq(1)").text(SUGAR.language.get('app_list_strings','status_paid_payment_list')['Partly Paid']);
                } else {
                    $('#pmd_paid_amount').closest('tr').find("td:eq(1)").text(SUGAR.language.get('app_list_strings','status_paid_payment_list')['Unpaid']);
                }
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

function undoPayment(){
    var paymentType = $('#payment_type').val();

    if (paymentType == "Transfer Out" || paymentType == "Transfer In"){
        var rsconfirm = confirm("Sau khi undo, hệ thống sẽ:\n- Xóa thông tin payment transfer out va transfer in.\n- Phục hồi lại số tiền đã chuyển đi.\n\nBạn có chắc chắn muốn thực hiện thao tác này?")
    }
    else if (paymentType == "Moving Out" || paymentType == "Moving In"){
        var rsconfirm = confirm("Sau khi undo, hệ thống sẽ:\n- Xóa thông tin payment moving out va moving in.\n- Phục hồi lại số tiền đã chuyển đi.\n- Chuyển học viên về lại center cũ.\n\nBạn có chắc chắn muốn thực hiện thao tác này?")
    }
    else if (paymentType == "Refund"){
        var rsconfirm = confirm("Sau khi undo, hệ thống sẽ:\n- Xóa thông tin payment refund.\n- Phục hồi lại số tiền đã refund.\n\nBạn có chắc chắn muốn thực hiện thao tác này?")
    }

    if (rsconfirm) {
        ajaxStatus.showStatus('Processing...');
        $.ajax({
            url: "index.php?module=J_Payment&action=handleAjaxPayment&sugar_body_only=true",
            type: "POST",
            async: true,
            data:  {
                type            : 'ajaxUndoPayment',
                payment_id      : $('input[name="record"]').val(),
                payment_type    : paymentType,
            },
            dataType: "json",
            success: function(res){
                ajaxStatus.hideStatus();
                if(res.success == "1"){
                    window.location.replace("index.php?module=Contacts&action=DetailView&record="+$('#contacts_j_payment_1contacts_ida').attr('data-id-value'));
                }
                else {
                    alertify.error(SUGAR.language.get('J_Payment','LBL_PAYMENT_IN_USED'));
                }
            },
        });
    }
}

function autoCheckInvoiceReleased(){
    $(".select_invoice_no").each(function() {
        var this_select = $(this);
        $.ajax({
            url: "index.php?module=J_Payment&action=handleAjaxPayment&sugar_body_only=true",
            type: "POST",
            async: true,
            data:  {
                type            : 'autoCheckInvoiceReleased',
                pay_dtl_id      : $(this).closest('tr').find(".pay_dtl_id").val(),
            },
            dataType: "json",
            success: function(res){
                if(res.is_release == '0'){
                    var htm = "<div style='display: inline-flex;'><span class='span_invoice_no'>"+res.invoice_number+"</span>";
                    htm += "<input type='hidden' class='pay_dtl_id' value='"+res.id+"'/>";
                    htm += ' &nbsp<a onclick="releaseInvoiceNo($(this).closest(\'tr\')); setTimeout(function(){autoCheckInvoiceReleased();}, 10000);" id="btn_release_invoice" title="Release VAT No"><i style="font-size: 20px;cursor: pointer;" class="icon icon-edit"></i></a>';
                    htm += "</div>";
                    this_select.closest('div').html(htm);
                    alertify.success('Saved Swapping!');
                }else{
                    var arr = new Array;
                    this_select.find('option').each ( function() {
                        arr.push($(this).val());
                    });
                    var release_list = $.map(res.release_list, function(el) { return el });
                    if(!isEqArrays(arr, release_list)){
                        var selected = this_select.val();
                        this_select.find('option').remove().end();
                        $.each(release_list, function(key, value) {
                            this_select
                            .append($("<option></option>")
                                .attr("value",value)
                                .text(value));
                        });
                        this_select.val(selected);
                    }
                    setTimeout(function(){autoCheckInvoiceReleased();}, 5000);
                }
            },
        });
    });
}

function releaseInvoiceNo (this_tr){
    ajaxStatus.showStatus('Processing...');
    $.ajax({
        url: "index.php?module=J_Payment&action=handleAjaxPayment&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  {
            type            : 'ajaxRealseInvoiceNo',
            pay_dtl_id      : this_tr.find(".pay_dtl_id").val(),
        },
        dataType: "json",
        success: function(res){
            ajaxStatus.hideStatus();
            if(res.success == '1'){
                showSubPanel('payment_paymentdetails', null, true);
                alertify.success(res.notify);
            }else{
                alertify.alert(res.notify);
            }

        },
        error: function(xhr, textStatus, errorThrown){
            ajaxStatus.hideStatus();
            alertify.error(SUGAR.language.get('app_strings','LBL_AJAX_ERROR'));
        }
    });
}

function saveInvoiceNo(this_tr){
    ajaxStatus.showStatus('Processing...');
    $.ajax({
        url: "index.php?module=J_Payment&action=handleAjaxPayment&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  {
            type            : 'ajaxSaveInvoiceNo',
            pay_dtl_id      : this_tr.find(".pay_dtl_id").val(),
            new_invoice_no  : this_tr.find(".select_invoice_no").val(),
        },
        dataType: "json",
        success: function(res){
            ajaxStatus.hideStatus();
            if(res.success == 1){
                alertify.success(res.errorLabel);
            }
            else{
                alertify.alert(res.errorLabel);
            }

            showSubPanel('payment_paymentdetails', null, true);
        },
        error: function(xhr, textStatus, errorThrown){
            ajaxStatus.hideStatus();
            alertify.error(SUGAR.language.get('app_strings','LBL_AJAX_ERROR'));
        }
    });
}

function reloadReleaseOptions(){
    ajaxStatus.showStatus('Processing...');
    showSubPanel('payment_paymentdetails', null, true);
    ajaxStatus.hideStatus();
}

Calendar.setup ({
    inputField : "value_sale_type_date",
    daFormat : cal_date_format,
    button : "sale_type_date_trigger",
    singleClick : true,
    dateStr : "",
    step : 1,
    weekNumbers:false
});

//Function Adult
function set_atc_return(popup_reply_data){
    var form_name = popup_reply_data.form_name;
    var name_to_value_array = popup_reply_data.name_to_value_array;
    $('#atc_class_id').val(name_to_value_array.atc_class_id);
    get_class_info_adult();
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

function get_class_info_adult(){            
    ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_AJAX_PLEASE_WAIT'));
    $.ajax({
        type:"POST",
        url: "index.php?module=J_Payment&action=handleAjaxPayment&sugar_body_only=true",
        data:{
            type            : "get_class_info_adult",
            class_id        : $('#atc_class_id').val(),
            payment_id      : record_id,
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
                $("body").css({ overflow: 'hidden' });
                $('#add_to_class_dialog').dialog({
                    resizable    : false,
                    width        :'auto',
                    height       :'auto',
                    modal        : true,
                    position     : ['middle',40],
                    visible      : true,
                    beforeClose: function(event, ui) {
                        $("body").css({ overflow: 'inherit' });
                        clearClassInfo();
                    },
                    buttons: {
                        "Submit":{
                            click:function() {
                            },
                            class    : 'button primary btn_submit_add_to_class',   
                            text    : SUGAR.language.get('J_Payment','LBL_SAVE'),
                        },
                        "Cancel":{
                            click:function() {
                                $(this).dialog('close');
                            },
                            class    : 'button btn_cancel_add_to_class',   
                            text    : SUGAR.language.get('J_Payment','LBL_CANCEL'),
                        },
                    },
                });
            }else{
                alertify.alert(data.notify);
            }
        },
        error: function(xhr, textStatus, errorThrown){
            ajaxStatus.hideStatus();
        }
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
            alertify.error(SUGAR.language.get('J_Payment','LBL_START_STUDY_NOT_IN_SCHEDULE'));   
            $('#atc_start_study').val('').effect("highlight", {color: 'red'}, 1000);
        }
        else if(!flag_end){                                         
            alertify.error(SUGAR.language.get('J_Payment','LBL_START_STUDY_NOT_IN_SCHEDULE')); 
            $('#atc_end_study').val('').effect("highlight", {color: 'red'}, 1000);
        }
        count_ss    = '0';
        total_hours = '0';
    }
    $('#atc_cal_session').text(count_ss).effect("highlight", {color: 'blue'}, 1000);
    $('#atc_session').val(count_ss);
    if(remain_practice == 0){
        $('#atc_cal_session_remain').text('-unlimited-').effect("highlight", {color: 'blue'}, 1000);
        $('#atc_session_remain').val(0);
    }else{
        $('#atc_cal_session_remain').text(remain_practice - count_ss).effect("highlight", {color: 'blue'}, 1000);
        $('#atc_session_remain').val(remain_practice - count_ss);
    }
    $('#atc_cal_total_hours').text(Numeric.toFloat(total_hours,2,2)).effect("highlight", {color: 'blue'}, 1000);
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
            label = SUGAR.language.get('J_Payment','LBL_START_STUDY_NOT_IN_SCHEDULE'); 
            label = label.replace("date_form", from);
            label = label.replace("date_to", to);
            alertify.error(label);
            return false;
        }else{
            check_date = check_date.getTime();
            if(check_date < from_check || check_date > to_check){                                
                label = SUGAR.language.get('J_Payment','LBL_START_STUDY_NOT_IN_SCHEDULE'); 
                label = label.replace("date_form", from);
                label = label.replace("date_to", to);
                alertify.error(label);
                return false;
            }
        }
    }else if(from != ''){
        from_check           = SUGAR.util.DateUtils.parse(from, cal_date_format).getTime();

        if(check_date < from_check){            
            label = SUGAR.language.get('J_Payment','LBL_DATE_MUST_AFTER'); 
            label = label.replace("date_form", from);
            alertify.error(label);
            return false;
        }
    } else{
        to_check             = SUGAR.util.DateUtils.parse(to, cal_date_format).getTime();
        if(check_date > to_check){
            label = SUGAR.language.get('J_Payment','LBL_DATE_MUST_BEFORE'); 
            label = label.replace("date_to", to);                         
            alertify.error(label);
            return false;
        }
    }
    return true;
}

function daydiff(first, second) {
    return Math.round((second-first)/(1000*60*60*24) + 1);
}

function submitAddToClass(){
    var start_study = $('#atc_start_study').val();
    var end_study   = $('#atc_end_study').val();
    var class_id    = $('#atc_class_id').val();
    var cal_session = $('#atc_session').val();
    var cal_session_remain = $('#atc_session_remain').val();
    if(start_study == '' || end_study == '' || class_id == '' || cal_session == 0){
        alertify.error(SUGAR.language.get('J_Payment','LBL_PLEASE_FILL_OUT_INFO_COMPLETELY'));   
        return ;
    }
    if( cal_session_remain < 0){                       
        alertify.error(SUGAR.language.get('J_Payment','LBL_SESSION_REMAINING_ARE_NOT_ENOUGH')); 
        return ;
    }
    $('.btn_submit_add_to_class, .btn_cancel_add_to_class').hide();
    $('.btn_submit_add_to_class').parent().append('<span id="atc_saving" >Saving.. <img src="custom/include/images/loader.gif" align="absmiddle" width="16"></span>');
    $.ajax({
        type: "POST",
        url: "index.php?module=J_Payment&action=handleAjaxPayment&sugar_body_only=true",
        data:  {
            type        : "submitAddToClass",
            start_study : start_study,
            end_study   : end_study,
            payment_id  : record_id,
            class_id    : class_id,
        },
        dataType: "json",
        success:function(data){
            $('#add_to_class_dialog').dialog('close');
            if (data.success == "1"){
                alertify.success(data.notify);
                location.reload();                     
                ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_AJAX_PLEASE_WAIT'));
            }else{
                alertify.error(data.notify);
                $('.btn_submit_add_to_class, .btn_cancel_add_to_class').show();
                $('#atc_saving').remove();
            }
        },
    });
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
Calendar.setup ({
    inputField : "value_payment_expired",
    daFormat : cal_date_format,
    button : "payment_expired_trigger",
    singleClick : true,
    dateStr : "",
    step : 1,
    weekNumbers:false
});

function inArray(array, el) {
    for ( var i = array.length; i--; ) {
        if ( array[i] === el ) return true;
    }
    return false;
}

function isEqArrays(arr1, arr2) {
    if ( arr1.length !== arr2.length ) {
        return false;
    }
    for ( var i = arr1.length; i--; ) {
        if ( !inArray( arr2, arr1[i] ) ) {
            return false;
        }
    }
    return true;
}