$(function(){
        
    $("#popup").click(function() {
        var popupRequestData = {
            "call_back_function" : "set_return",
            "form_name" : "form_filter",
            "field_to_name_array" : {
            "name" : "center",
            "id" : "center_id",

            }
        };

        open_popup('Teams', 800, 850, '', true, true, popupRequestData);    
    });
    
    Calendar.setup({
        inputField: 'date_report_from' ,
        form: 'form_filter' ,
        ifFormat: cal_date_format,
        daFormat: cal_date_format,
        button: 'date_report_from_trigger' ,
        singleClick: true,
        dateStr: '' ,
        startWeekday: 0,
        step: 1,
        weekNumbers: false
    });
    Calendar.setup({
        inputField: 'date_report_to' ,
        form: 'form_filter' ,
        ifFormat: cal_date_format,
        daFormat: cal_date_format,
        button: 'date_report_to_trigger' ,
        singleClick: true,
        dateStr: '' ,
        startWeekday: 0,
        step: 1,
        weekNumbers: false
    });
    
    function checkCenter() {
        // check month
        var center = $('#center').val();
        
        if( center == '') {
            $("#center").effect("highlight", {color: '#FF0000'}, 2000);
            $("#center").parent().find('.validation-message').remove();
            $("#center").parent().append('<div class="required validation-message">' + SUGAR.language.get('app_strings', 'ERR_INVALID_VALUE') + '</div>');
            $('#center').focus();
            return false;
        } else {
            $("#center").parent().find('.validation-message').remove();
        }
        
        return true;
    }
    
    function checkDateReportFrom() {
        var date = $('#date_report_from').val();
        if( date == '' || isDate(date) == false ) {
            $("#date_report_from").effect("highlight", {color: '#FF0000'}, 2000);
            $("#date_report_from").parent().find('.validation-message').remove();
            $("#date_report_from").parent().append('<div class="required validation-message">' + SUGAR.language.get('app_strings', 'ERR_INVALID_VALUE') + '</div>');
            $('#date_report_from').focus();
            return false;
        } else {
            $("#date_report_from").parent().find('.validation-message').remove();
            return true;
        }
    }
    
    function checkDateReportTo() {
        var date = $('#date_report_to').val();
        if( date == '' || isDate(date) == false ) {
            $("#date_report_to").effect("highlight", {color: '#FF0000'}, 2000);
            $("#date_report_to").parent().find('.validation-message').remove();
            $("#date_report_to").parent().append('<div class="required validation-message">' + SUGAR.language.get('app_strings', 'ERR_INVALID_VALUE') + '</div>');
            $('#date_report_to').focus();
            return false;
        } else {
            $("#date_report_to").parent().find('.validation-message').remove();
            return true;
        }
    }
    
    function checkDateBefore() {
        var dateFrom = $('#date_report_from').val();
        var dateTo = $('#date_report_to').val();
        if( isBefore(dateFrom, dateTo) == false ) {
            $("#date_report_to").effect("highlight", {color: '#FF0000'}, 2000);
            $("#date_report_to").parent().find('.validation-message').remove();
            $("#date_report_to").parent().append('<div class="required validation-message">' + SUGAR.language.get('app_strings', 'ERR_INVALID_VALUE') + '</div>');
            $('#date_report_to').focus();
            return false;
        } else {
            $("#date_report_from").parent().find('.validation-message').remove();
            return true;
        }
    }

    $("#btnReport").click(function(event) {
        //validate form submit 
        var dateFrom =  checkDateReportFrom();
        var dateTo =  checkDateReportTo();
        var checkDate =  checkDateBefore();
        var center = checkCenter();

        if( center && dateFrom && dateTo && checkDate ) {        
            return true;
        }
        else {
             event.preventDefault();
        }                   
    });
});

