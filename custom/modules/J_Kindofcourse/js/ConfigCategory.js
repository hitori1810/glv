function saveConfig(){
    ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_AJAX_PLEASE_WAIT'));
    $.ajax({
        'url': 'index.php?module=J_Kindofcourse&action=handleAjax&sugar_body_only=true',
        'type': 'POST',
        'async': true,
        'data': {
            type: 'ajaxSaveConfigCategory',
            koc_options         : $("#koc_options").val(),              
            level_options       : $("#level_options").val(),              
            module_options      : $("#module_options").val(),              
            pt_result_options   : $("#pt_result_options").val(),              
        },
        dataType: "json",
        'success': function (data) {
            if (data.success) {
                alertify.success(SUGAR.language.get('app_strings', 'LBL_AJAX_SAVE_SUCCESS')); 
            }
            else {
                alertify.error(SUGAR.language.get('app_strings', 'LBL_AJAX_ERROR'));
                console.log(data.error);
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