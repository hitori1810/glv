$( document ).ready(function(){ 
    $('.number').numeric({type: "int", negative: false});
}); 

function saveConfig() {
    if(!checkVoucherPercent()) return false;
    
    ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_AJAX_PLEASE_WAIT'));
    $.ajax({
        'url': 'index.php?module=Administration&action=handleAjax&sugar_body_only=true',
        'type': 'POST',
        'async': true,
        'data': {
            type: 'ajaxSaveGeneralConfig',
            default_mapping_lead: $("#default_mapping_lead").val(),  
            refer_voucher_type: $("#refer_voucher_type").val(),
            refer_voucher_value: Numeric.parse($("#refer_voucher_value").val()),
        },
        dataType: "json",
        'success': function (data) {
            if (data.success) {
                alertify.success(SUGAR.language.get('app_strings', 'LBL_AJAX_SAVE_SUCCESS'));

            }
            else {
                alertify.error(SUGAR.language.get('app_strings', 'LBL_AJAX_ERROR'));
            }
            ajaxStatus.hideStatus();
        },
        'error': function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
            alertify.error(SUGAR.language.get('app_strings', 'LBL_AJAX_ERROR'));
            ajaxStatus.hideStatus();
        }
    });
}

function checkVoucherPercent(){  
    var refer_voucher_type = $("#refer_voucher_type").val();
    var refer_voucher_value = Numeric.parse($("#refer_voucher_value").val());
    
    if(refer_voucher_value == ""){
        refer_voucher_value = 0;
        $("#refer_voucher_value").val(0);    
    } 
    
    if(refer_voucher_type == "percent"){
        if(refer_voucher_value < 0 || refer_voucher_value > 100){
            $("#refer_voucher_value").effect("highlight", {color: '#FF0000'}, 2000); 
            alertify.error(SUGAR.language.get('Administration', 'LBL_ERROR_REFER_VOUCHER_VALUE_INVALID'));
            return false;
        }   
    }
    else{
        if(refer_voucher_value < 0){
            $("#refer_voucher_value").effect("highlight", {color: '#FF0000'}, 2000); 
            alertify.error(SUGAR.language.get('Administration', 'LBL_ERROR_REFER_VOUCHER_VALUE_INVALID'));
            return false;
        }
    }  
    
    return true; 
}

function ajaxApplyVoucherConfig(){
    if(!checkVoucherPercent()) return false;
    
    ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_AJAX_PLEASE_WAIT'));
    $.ajax({
        'url': 'index.php?module=Administration&action=handleAjax&sugar_body_only=true',
        'type': 'POST',
        'async': true,
        'data': {
            type: 'ajaxApplyVoucherConfig',                     
            refer_voucher_type: $("#refer_voucher_type").val(),
            refer_voucher_value: Numeric.parse($("#refer_voucher_value").val()),   
        },
        dataType: "json",
        'success': function (data) {
            if (data.success) {
                alertify.success(SUGAR.language.get('app_strings', 'LBL_AJAX_SAVE_SUCCESS'));
            }
            else {
                alertify.error(SUGAR.language.get('app_strings', 'LBL_AJAX_ERROR'));
            }
            ajaxStatus.hideStatus();
        },
        'error': function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
            alertify.error(SUGAR.language.get('app_strings', 'LBL_AJAX_ERROR'));
            ajaxStatus.hideStatus();
        }
    });
}

