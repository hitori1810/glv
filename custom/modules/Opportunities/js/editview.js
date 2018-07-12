//Toggle invoice
function toggleInvoice(){
    if($('#isinvoice').is(':checked')){
        $('#is_company').closest('tr').show();
        $('#ispayment').closest('tr').show();
        $('#ispayment').prop('checked',true);
        $('#ispayment').closest('tr').hide();
        togglePayment();
        toggleCompany();
    }else{
        $('#ispayment').prop('checked',false);
        $('#is_company').prop('checked',false);
        $('#is_company').closest('tr').hide();
        $('#ispayment').closest('tr').hide();
        togglePayment();
        toggleCompany();
    }   
}
function toggleCompany(){
    if($('#is_company').is(':checked')){
        $('#vat-info').closest('tr').show();
    }else{
        $('#vat-info').closest('tr').hide();
    }
}
function togglePayment(){
    if($('#ispayment').is(':checked')){
        $('#detailpanel_2').slideDown('fast');
    }else{
        $('#detailpanel_2').hide();
    }   
}
//Toggle Is Deposit
function toggleIsDeposit(){
    switch($('#payment_type').val()) {
        case 'Deposit':
            $('#payment_amount').hide();
            $('#payment_move').hide();
            $('#free_balance_label').hide();
            $('#free_balance').hide();
            $('#payment_deposit').show();
            break;
        case 'FreeBalance':
            $('#payment_amount').show();
            $('#payment_move').hide();
            $('#free_balance_label').show();
            $('#free_balance').show();
            $('#payment_deposit').hide();
            break;
        default:
            $('#payment_amount').show();
            $('#free_balance_label').hide();
            $('#free_balance').hide();
            $('#payment_move').hide();
            $('#payment_deposit').hide();
    }
}

function CalFreebalance(){
    $('#free_balance').html('<img src="custom/include/images/loading.gif" style="width: 24px;" align="absmiddle">');
    $.ajax({
        url: "index.php?module=Opportunities&action=ajaxFreeBalance&sugar_body_only=true",
        type: "POST",
        async: true,
        data:{
            parent_type: $('#parent_type').val(),
            parent_id: $('#parent_id').val(),
        },
        dataType: "json",
        success: function(res){
            $('#free_balance').html(res.html);
        }        
    });
}

function PackageChange(){   
    Calculated();
    ChangeType(); 
} 
// Overwrite set_return - Package
function set_package_return(popup_reply_data) {
    cleanInfo();
    from_popup_return = true;
    var form_name = popup_reply_data.form_name;
    var name_to_value_array = popup_reply_data.name_to_value_array;
    if (typeof name_to_value_array != 'undefined' && name_to_value_array['account_id']) {
        var label_str = '';
        var label_data_str = '';
        var current_label_data_str = '';
        var popupConfirm = popup_reply_data.popupConfirm;
        for (var the_key in name_to_value_array) {
            if (the_key == 'toJSON') {} else {
                var displayValue = replaceHTMLChars(name_to_value_array[the_key]);
                if (window.document.forms[form_name] && document.getElementById(the_key + '_label') && !the_key.match(/account/)) {
                    var data_label = document.getElementById(the_key + '_label').innerHTML.replace(/\n/gi, '').replace(/<\/?[^>]+(>|$)/g, "");
                    label_str += data_label + ' \n';
                    label_data_str += data_label + ' ' + displayValue + '\n';
                    if (window.document.forms[form_name].elements[the_key]) {
                        current_label_data_str += data_label + ' ' + window.document.forms[form_name].elements[the_key].value + '\n';
                    }
                }
            }
        }
        if (label_data_str != label_str && current_label_data_str != label_str) {
            if (typeof popupConfirm != 'undefined') {
                if (popupConfirm > -1) {
                    set_return_basic(popup_reply_data, /\S/);
                } else {
                    set_return_basic(popup_reply_data, /account/);
                }
            }
            else if (confirm(SUGAR.language.get('app_strings', 'NTC_OVERWRITE_ADDRESS_PHONE_CONFIRM') + '\n\n' + label_data_str)) {
                set_return_basic(popup_reply_data, /\S/);
            }
            else {
                set_return_basic(popup_reply_data, /account/);
            }
        } else if (label_data_str != label_str && current_label_data_str == label_str) {
            set_return_basic(popup_reply_data, /\S/);
        } else if (label_data_str == label_str) {
            set_return_basic(popup_reply_data, /account/);
        }
    } else {
        set_return_basic(popup_reply_data, /\S/);
    }

    //After Callback 
    Calculated();
    ChangeType();
}

//Caculated                                           
function Calculated(){
    //Prepare
    var new_grp_sep = num_grp_sep;
    var new_dec_sep = dec_sep;
    if($.isNumeric($('#amount').val()) ){
        new_grp_sep = ',';
        new_dec_sep = '.'; 
    }
    var price = unformatNumber($('#amount').val(), new_grp_sep, new_dec_sep);

    var discountRate = unformatNumber($('#discount').val() , num_grp_sep, dec_sep)/ 100;
    var discountAmount = unformatNumber($('#discount_amount').val(),num_grp_sep, dec_sep);
    
    var taxRate = unformatNumber($('#tax_rate').val(), ',', '.');
    //Payment
    var isdiscount = $('#isdiscount').val();
    if(isdiscount != '1'){
        var paymentRate = unformatNumber($('#payment_rate_1').val(), new_grp_sep, new_dec_sep)/ 100;
        if(discountAmount != 0){
            var payment_1 = (Math.round((price - discountAmount) * paymentRate / 1000)) * 1000; 
        }else{
            var payment_1 = unformatNumber($('#payment_price_1').val(), new_grp_sep, new_dec_sep);
        }   
    }else{
        var discountAmountPack = unformatNumber($('#discount_amount_pack').val(), new_grp_sep, new_dec_sep);
        if(discountAmountPack == discountAmount){
            var payment_1 = unformatNumber($('#after_discount_1').val(), new_grp_sep, new_dec_sep); 
        }
        if(discountAmountPack < discountAmount){
            var after_discount_1  = unformatNumber($('#after_discount_1').val(), new_grp_sep, new_dec_sep);
            var payment_1 = (Math.round(((after_discount_1 * (price - discountAmount))/(price - discountAmountPack)) / 1000))*1000; 
        }
        if(discountAmountPack > discountAmount){
            var payment_1 = unformatNumber($('#after_discount_1').val(), new_grp_sep, new_dec_sep);
            discountAmount = discountAmountPack;
            $("#discount_amount").effect("highlight", {color: '#ff9933'}, 1000); 
        }
    }
    var taxAmount = taxRate * price;
    var total = price - discountAmount + taxAmount;
    var remaining = unformatNumber(total, num_grp_sep, dec_sep);

    if(discountAmount > price){
        alert(SUGAR.language.get('Opportunities','ERR_DISCOUNT')+ formatNumber(price) + 'đ');  
        $('#discount_amount').val(formatNumber(price,num_grp_sep,dec_sep));
        $('#discount').val(100);
        Calculated();
    }else{
        var discountRate = ((discountAmount / price) * 100).toFixed(2);
        !isNaN(discountRate) ? $('#discount').val(formatNumber(discountRate,num_grp_sep,dec_sep)) : $('#discount').val(0);
    }

    switch($('#payment_type').val()) {
        case 'Deposit':
            var paymentAmount = unformatNumber($('#payment_deposit').val(), num_grp_sep, dec_sep);
            //Kiem tra tien deposit phai nho hon lan thu thu nhat
            if (paymentAmount > payment_1){
                alert('1st Payment = '+ formatNumber(payment_1,num_grp_sep,dec_sep) + 'đ'+ SUGAR.language.get('Opportunities','ERR_PAYMENT')+'1st Payment.');
                paymentAmount = payment_1;
                $('#payment_deposit').val(payment_1).trigger('change');
            }
            break;
        case 'FreeBalance':
            var paymentAmount = 0;
            $.each( $('#payment_list option:selected'), function() {
                var free_balance = unformatNumber($(this).attr('amount'), num_grp_sep, dec_sep);
                paymentAmount += free_balance;
            });
            break;
        default:
            var paymentAmount = payment_1; 
    }

    var paymentBalance = remaining - paymentAmount;

    //Marketing Fee & Center Fee
    var marketingPercent = ($("input:radio[name=rdItemId]").is(":checked") && $('#c_promotions_opportunities_1c_promotions_ida').val() !='') ? unformatNumber($( "input:radio[name=rdItemId]:checked" ).closest('tr').find('.marketingPercent').text(), num_grp_sep, dec_sep) : 0; 
    var centerPercent = ($("input:radio[name=rdItemId]").is(":checked") && $('#c_promotions_opportunities_1c_promotions_ida').val() !='') ? unformatNumber($( "input:radio[name=rdItemId]:checked" ).closest('tr').find('.centerPercent').text(), num_grp_sep, dec_sep) : 0; 

    var marketingFee = (marketingPercent/100) * discountAmount;  
    var centerFee = (centerPercent/100) * discountAmount;

    //Assign
    $('#amount').val(price).trigger('change');
    $('#discount_amount').val(discountAmount).trigger('change');
    $('#tax_amount').val(taxAmount).trigger('change');
    $('#total_in_invoice').val(total).trigger('change');

    $('#remaining').val(remaining).trigger('change');
    $('#payment_amount').val(paymentAmount).trigger('change');
    $('#payment_balance').val(paymentBalance).trigger('change');

    $('#marketing_fee').val(marketingFee);
    $('#center_fee').val(centerFee);

    //Bo sung ham Doc tien bang chu
    $('#amount_in_words_payment').val(DocTienBangChu(paymentAmount));
    $('#amount_in_words_invoice').val(DocTienBangChu(total));

    //Calculate payment fee
    calculatedPaymentMethod();
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

//Get Paremeter from URL
function GetURLParameter(sParam){
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) 
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) 
        {
            return sParameterName[1];
        }
    }
}


// Overwirite set_return Parent Type
function set_parent_return(popup_reply_data){
    var form_name = popup_reply_data.form_name;
    var name_to_value_array = popup_reply_data.name_to_value_array;
    for (var the_key in name_to_value_array) {
        if (the_key == 'toJSON') {
            continue;
        } else {
            var val = name_to_value_array[the_key].replace(/&amp;/gi, '&').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&#039;/gi, '\'').replace(/&quot;/gi, '"');
            switch (the_key)
            {
                case 'parent_name':
                    window.document.forms[form_name].elements['parent_name'].value = val;
                    break;
                case 'parent_id':
                    window.document.forms[form_name].elements['parent_id'].value = val;
                    break;
                case 'lead_source':
                    $("#lead_source option[value='"+val+"']").attr('selected', 'selected'); 
                    break;
                case 'campaign_name':
                    window.document.forms[form_name].elements['campaign_name'].value = val;
                    break;
                case 'campaign_id':
                    window.document.forms[form_name].elements['campaign_id'].value = val; 
                    break;
                case 'free_balance_temp':
                    window.document.forms[form_name].elements['free_balance_temp'].value = val;
                    CalFreebalance();
                    break;
                case 'closed_date':
                    window.document.forms[form_name].elements['closed_date'].value = val;
                    break;  
                case 'assigned_user_name':
                    if($('#check_access').val() == 0){
                        $('#assigned_user_name').prop('readonly',true);   
                        $('#btn_assigned_user_name').prop('disabled',true);
                        $('#btn_clr_assigned_user_name').prop('disabled',true);   
                    } 
                    window.document.forms[form_name].elements['assigned_user_name'].value = val;
                    break;   
                case 'assigned_user_id':
                    window.document.forms[form_name].elements['assigned_user_id'].value = val;
                    break;
                case 'team_id':
                    $('input[name=id_'+collection["EditView_team_name"].primary_field+']').val(val);
                    break;
                case 'team_name':
                    $('input[name='+collection["EditView_team_name"].primary_field+']').val(val);
                    break;
            }
            ChangeType();
        }
    }
    Calculated();   
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
                    window.document.forms[form_name].elements['company_id_temp'].value = val;
                    break;
                case 'account_name':
                    window.document.forms[form_name].elements['company_name_temp'].value = val;
                    $('#company_name').val(val);
                    break;
                case 'company_address':
                    $('#company_address').val(val);
                    break; 
                case 'city_address':
                    $('#company_address').val($('#company_address').val() +' '+val);
                    break; 
                case 'state_address':
                    $('#company_address').val($('#company_address').val() +' '+val);
                    break; 
                case 'country_address':
                    $('#company_address').val($('#company_address').val() +' '+val);
                    break;
                case 'tax_code':
                    window.document.forms[form_name].elements['tax_code'].value = val; 
                    break;
            }
        }
    }
}

//Load promotions list
function LoadPromotions(){
    if($('#itemSelectorHolder').html()== "") {
        $("#availability_status").remove();
        $("#addMoreItems").parent().append("<span id='availability_status'></span>");
        $("#availability_status").html('<img src="themes/default/images/loading.gif" align="absmiddle" width="16">');
        $.ajax({        
            url:'index.php?entryPoint=ajaxLoadItems',
            type:'POST',
            data:{
            },
            success:function(data){
                //alert(data);
                $("#itemSelectorHolder").html(data);
                $("#availability_status").remove();
                $('#itemSelector').dialog('open');
            }
        });
    } else {
        $('#itemSelector').dialog('open');
    }
}

//Add promotion percentage and close dialog
function AddPromotion(){
    if($("input:radio[name=rdItemId]").is(":checked")){
        var row = $( "input:radio[name=rdItemId]:checked" ).closest('tr');                       
        var promotionDiscount = row.find('.promotionDiscount').text();

        $('#discount').val(promotionDiscount).trigger('blur');
        //Add Promotion ID 
        var promotionId = row.find('.promotionId').val();
        $('#c_promotions_opportunities_1c_promotions_ida').val(promotionId);
        //Remove Sponsor Code
        $('#sponsor_code').val('');
    }
    $('#itemSelector').dialog('close');
}

//Change opportunity type 
function ChangeType(){
    $('#opportunity_type_span').html('<img src="custom/include/images/loading.gif" style="width: 24px;" align="absmiddle">');
    $.ajax({
        url: "index.php?module=Opportunities&action=ajaxCheckNewSale&sugar_body_only=true",
        type: "POST",
        async: true,
        data:{
            parent_type: $('#parent_type').val(),
            parent_id: $('#parent_id').val(),
            package_id: $('#c_packages_opportunities_1c_packages_ida').val(),
            sale_date: $('#date_closed').val(),
        },
        dataType: "json",
        success: function(res){
            if(res.sale_stages == 'Existing Business')
                var sale_stages = 'Retention';
            if(res.sale_stages == 'New Business')
                var sale_stages = 'New Sale';
            $('#opportunity_type').val(res.sale_stages);           
            $('#opportunity_type_span').html(''); 
            $('#opportunity_type_span').text(sale_stages); 
        },        
    });
}
//After Parent Changed
function parentChanged(){
    $("#lead_source option[value='"+$('#lead_source_temp').val()+"']").attr('selected', 'selected');
    $('#campaign_name').val($('#campaign_name_temp').val());
    $('#campaign_id').val($('#campaign_id_temp').val());

    CalFreebalance();
    ChangeType();
}

//Clean Info
function cleanInfo(){
    $('#amount,#discount_amount,#payment_price_1,#payment_rate_1,#after_discount_1,#discount_amount,#discount_amount_pack,#isdiscount,#total_hours,#interval,#discount_amount,#sponsor_code,#sponsor_id').val('');
    Calculated();
}

//Check Sponsor Code
function ajax_check_sponsor(){
    var sponsor_code = $('#sponsor_code').val();
    //Reset notified icon.
    $('label#valid_code').css('display', 'none');
    $('label#invalid_valid_code').css('display', 'none');
    $('#sponsor_id').val(''); 
    $.ajax({
        url: "index.php?module=C_Sponsors&action=ajaxCheckSponsor&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  
        {
            sponsor_code: sponsor_code,
        },
        dataType: "json",
        success: function(res){           
            if(res.success == "1"){
                $('label#valid_code').slideDown();
                setTimeout(function(){ $('label#valid_code').slideUp(); }, 3000);
                var sponsor_amount = unformatNumber(res.sponsor_amount,num_grp_sep,dec_sep);
                var sponsor_percent = unformatNumber(res.sponsor_percent,num_grp_sep,dec_sep);

                if(sponsor_amount != null && sponsor_amount != '')
                    var sponsor_discount = formatNumber(sponsor_amount,num_grp_sep,dec_sep,0,0);
                else   
                    var sponsor_discount = sponsor_percent.toString()+' %';
                alert('               ##### MÃ HỢP LỆ ######\nMã sponsor: '+res.sponsor_code+'\nGiảm giá thêm: '+sponsor_discount+'\nMô tả: '+res.description+'\nSố tiền giảm sẽ được trừ vào lần thu thứ nhất !!\n --------------- END -------------- ');

                $('#sponsor_id').val(res.sponsor_id);
                $('#sponsor_code').val(res.sponsor_code);
                //Cong tien giam gia
                var discount_pack = unformatNumber($('#discount_amount_pack').val(),num_grp_sep,dec_sep);
                var total_in_invoice = unformatNumber($('#total_in_invoice').val(),num_grp_sep,dec_sep);
                var total_price = unformatNumber($('#amount').val(),num_grp_sep,dec_sep);
                if(sponsor_amount != null && sponsor_amount != ''){
                    discount_amount = discount_pack + sponsor_amount;
                }else{
                    discount_amount = discount_pack + (sponsor_percent * total_in_invoice / 100);
                }
                if(discount_amount > total_price)
                    discount_amount = total_price;
                $('#discount_amount').val(discount_amount);
            }else{
                $('label#invalid_valid_code').slideDown();
                alert('               ##### MÃ KHÔNG HỢP LỆ ######\n Vui lòng kiểm tra lại hạn sử dụng hoặc nhập lại mã lần nữa !!');
                $('#sponsor_id').val('');
                $('#sponsor_code').val('');
                $('#discount_amount').val($('#discount_amount_pack').val());
                setTimeout(function(){ $('label#invalid_valid_code').slideUp(); }, 3000);
            }
            Calculated();  
        },        
    }); 
}


$(document).ready(function() {
    //    alert('Hello');
    $('#isinvoice').prop('checked',true);
    $('select[name=taxrate_id] option:not(:selected)').prop('disabled', true);
    toggleInvoice();
    $('#bank_fee_rate').prop('readonly', true);
    $('#loan_fee_rate').prop('readonly', true);
    $('#isinvoice, #ispayment, #is_company').change(function(){
        toggleInvoice();
    });
    toggleIsDeposit();
    $('#payment_type').change(function(){
        toggleIsDeposit();
    });

    //Auto update Sale Date
    $('#date_closed').change(function(){
        //        $('#publish_invoice_date').val($(this).val());
        $('#payment_date').val($(this).val());
    });
    $('.selector').live('click',function(){
        if($(this).closest('table').attr('id') == 'date_closed_trigger_div_t'){
            //            $('#publish_invoice_date').val($('#date_closed').val());
            $('#payment_date').val($('#date_closed').val());
        }
    });
    //END: UPDate Sale Date   

    $('#taxrate_id, #ispayment, #payment_type').change(Calculated);
    $('#payment_deposit, #payment_move, #discount_amount,#discount').blur(Calculated);

    // Init QS for package
//    sqs_objects["EditView_c_packages_opportunities_1_name"] = {
//        "form":"EditView",
//        "method":"query",
//        "modules":['C_Packages'],
//        "group":"or",
//        "field_list":[
//            "name",
//            "id",
//            "price",
//            "payment_rate_1",
//            "payment_price_1",
//            "total_hours",
//            "interval_package",
//            "after_discount_1",
//            "discount_amount",
//            "isdiscount"],
//        "populate_list":[
//            "c_packages_opportunities_1_name",
//            "c_packages_opportunities_1c_packages_ida",
//            "amount",
//            "payment_rate_1",
//            "payment_price_1",
//            "total_hours",
//            "interval",
//            "after_discount_1",
//            "discount_amount_pack",
//            "isdiscount"],
//        "required_list":"c_packages_opportunities_1c_packages_ida",
//        "conditions":[{"name":"name","op":"like_custom","end":"%","value":""}],
//        "order":"name",
//        "limit":"30",
//        "no_match_text":"No Match",
//        "post_onblur_function": "PackageChange"
//    };
    enableQS(true);

    //Remove event and call back funtion parent
    $('#btn_parent_name').removeAttr('onclick');
    $('#btn_parent_name').click(function(){
        open_popup($('#parent_type').val(), 600, 400, "", true, false, {"call_back_function":"set_parent_return","form_name":"EditView","field_to_name_array":{"id":"parent_id","name":"parent_name","lead_source":"lead_source","phone_mobile":"phone_mobile","free_balance":"free_balance_temp","closed_date":"closed_date","assigned_user_name":"assigned_user_name","assigned_user_id":"assigned_user_id","team_id":"team_id","team_name":"team_name"}}, "single", true);
    });
    // Init QS for User Approching package
    sqs_objects["EditView_username_approached"] = {
        "form":"EditView",
        "method":"query",
        "modules":['Users'],
        "group":"or",
        "field_list":["name", "id"],
        "populate_list":["username_approached", "user_apprached_id"],
        "required_list":"user_apprached_id",
        "conditions":[{"name":"name","op":"like_custom","end":"%","value":""}],
        "order":"name",
        "limit":"30",
        "no_match_text":"No Match",
    };

    // Init QS for User Assign package
    sqs_objects["EditView_assigned_user_name"] = {
        "form":"EditView",
        "method":"query",
        "modules":['Users'],
        "group":"or",
        "field_list":["name", "id"],
        "populate_list":["assigned_user_name", "assigned_user_id"],
        "required_list":"assigned_user_id",
        "conditions":[{"name":"name","op":"like_custom","end":"%","value":""}],
        "order":"name",
        "limit":"30",
        "no_match_text":"No Match",
    };
    // REMOVE SQS
    sqs_objects["EditView_parent_name"] = {};
    sqs_objects["EditView_c_packages_opportunities_1_name"] = {};

    //Load popup list Promotion
    $('#addMoreItems').click(LoadPromotions);

    //Add Programs from popup to Editviews
    $('#btnAddItems').live('click',AddPromotion);

    $('#sponsor_code').change(function(){
        if($(this).val() != ''){
            $('#c_promotions_opportunities_1c_promotions_ida').val('');
        }else{
            var row = $( "input:radio[name=rdItemId]:checked" ).closest('tr');                       
            //Add Promotion ID 
            var promotionId = row.find('.promotionId').text();
            $('#c_promotions_opportunities_1c_promotions_ida').val(promotionId);

        }
        if($('#c_packages_opportunities_1c_packages_ida').val() == ''){
            alert('Vui lòng chọn Package trước khi nhập mã Sponsor !');
            $('#sponsor_id').val('');
            $('#sponsor_code').val('');  
        }
        else
            ajax_check_sponsor(); 
    });

    $("#card_type, #bank_fee_rate, #loan_fee_rate, #bank_name").change(calculatedPaymentMethod);

    $('#credit_info').hide();
    $('#loan_info').hide();
    $('input:radio[name="payment_method"]').click(function(){
        togglePaymentMethod(this) ;
    });

    CalFreebalance();
    ChangeType();
    $("#parent_type").change(ChangeType);

    $('#btn_clr_c_packages_opportunities_1_name').click(cleanInfo);
    $("#c_packages_opportunities_1_name").keyup(function() {
        if (!this.value) {
            cleanInfo();
        }
    });
    //Disable Student when editing
    if($('input[name=record]').val() != ''){
        $('#parent_type, #parent_name, #btn_parent_name, #btn_clr_parent_name').prop('disabled', true);  
    }

    $('input.currency').css("text-align", "right"); 

    //Remove Dropdown Option
    $("select option[value='Normal']").attr('label','Create a Payment Normal');
    $("select option[value='Normal']").text('Create a Payment Normal');
    $("select option[value='Deposit']").attr('label','Create a Payment Deposit');
    $("select option[value='Deposit']").text('Create a Payment Deposit');
    $("select option[value='FreeBalance']").attr('label','Get Payments Remain');
    $("select option[value='FreeBalance']").text('Get Payments Remain');
    
    $("#payment_type option[value='Moving in']").remove();
    $("#payment_type option[value='Transfer in']").remove();
    $("#payment_type option[value='Penalty']").remove();
    $("#payment_type option[value='Extend Balance']").remove();
    $("#payment_type option[value='Placement Test']").remove();
    $("#sales_stage option[value='Deleted']").remove();

    $('#btn_account_name').live('click',function(){
        // Open popup Corporate
        open_popup("Accounts", 600, 400, "", true, true, {
            "call_back_function": "set_return_corp",
            "form_name": "EditView",
            "field_to_name_array": {
                "id": "account_id",
                "name": "account_name",
                "billing_address_street": "company_address",
                "billing_address_city": "city_address",
                "billing_address_state": "state_address",
                "billing_address_country": "country_address",
                "tax_code": "tax_code",
            },
            }, "Select", true);
    });

    //    $('#c_packages_opportunities_1_name').live('change',function(){
    //        cleanInfo();  
    //    });
});