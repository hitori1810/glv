function runNow(){
    ajaxStatus.showStatus(SUGAR.language.get('Schedulers','LBL_PLEASE_WAIT'));
    $.ajax({
        url: "index.php?module=Schedulers&action=handleajax&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  {                      
            type        : "ajaxRunNow",
            record      : $("input[name='record']").val()
        },
        dataType: "json",
        success: function(res){ 
            if(res.success == "1"){
                alertify.success(SUGAR.language.get('Schedulers','LBL_RUN_SUCCESSFULL'));    
            }
            else{
                alertify.error(SUGAR.language.get('Schedulers',res.error_label));    
            }
            ajaxStatus.hideStatus();
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alertify.error(SUGAR.language.get('Schedulers','LBL_AJAX_ERROR'));
            ajaxStatus.hideStatus();
        } 
    }); 
}