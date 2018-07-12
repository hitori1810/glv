$(function(){
    debugger;
    if(action_sugar_grp1 != 'EditView'){
        $('#public_holiday').closest('tr').hide();
        var formated = cal_date_format.replace('%d','DD');
        formated =  formated.replace('%m','MM');
        formated = formated.replace('%Y','YYYY');

        $('#holidays_range').dateRangePicker(
            {
                inline:true,
                container: '#date-range12-container',
                format: formated, 
                alwaysOpen:true 
        });
        $("#type option[value='Public Holiday']").remove();
        setTimeout(function(){ removeFromValidate('form_SubpanelQuickCreate_Holidays','public_holiday') }, 2000);   
    }
    else{
        $('#holidays_range').closest('tr').hide();
        $('#type').closest('tr').hide();
        removeFromValidate('EditView','holidays_range');
        $("#type option[value='Public Holiday']").prop('selected','selected');



        var today = new Date();
        var y = today.getFullYear();
        $('#full_year').multiDatesPicker({
            dateFormat: "yy-mm-dd",
            //            addDates: [y+'-01-02'],
            numberOfMonths: [3,4],
            defaultDate: y+'-01-01',
            altField: '#public_holiday',
        });
    }
});
