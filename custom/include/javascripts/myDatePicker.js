/**
 * @author Tran Khanh Toan
 * @param formName
 * @description: to use, add to field class='date_picker'
 * to update field date_picker, call function loadMyDatePicker(formName)
 */
function myDatePicker(formName) {
    $('.date_picker').each(function (index) {
        if ($(this).is(':visible')) {
            if ($(this).parent().find('.date_picker_input, .date_picker_button').attr('id')) return true;
            var id = "abcdefghijklmnopqrstuvwxyz0123456789".charAt(Math.floor(Math.random() * 62)) + new Date().getTime();
            $(this).after("<input class='date_picker_input' style='display: none' name='date_picker_" + id + "' id='date_picker_" + id + "' />");
            $(this).after("<img class='date_picker_button' src='themes/OnlineCRM-Blue/images/jscalendar.png' id='date_picker_trigger_" + id + "' style='position:relative; top:6px'>");
            Calendar.setup({
                inputField: 'date_picker_' + id,
                form: formName,
                ifFormat: cal_date_format,
                daFormat: cal_date_format,
                button: 'date_picker_trigger_' + id,
                singleClick: true,
                dateStr: '',
                startWeekday: 0,
                step: 1,
                weekNumbers: false
            });
        }
    });
}
function loadMyDatePicker(formName) {
    myDatePicker(formName);
    $(document).on('change', '.date_picker_input', function () {
        $(this).parent().find('.date_picker').val($(this).val());
    });
}