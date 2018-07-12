$(document).ready(function(){

    if($('input[name="record"]').val()=='')
    {
        $('#contract_type').live('change', function(){
            if($('#contract_type').val()=='FT')
            {
                $('#less_non_working_hours').val('3');
                $('#working_hours_monthly').val('74');
            }
        });
    }

    $('#contract_type').live('change', function(){
        if($('#contract_type').val()=='PT')
        {
            $('#less_non_working_hours').val('0');
            $('#working_hours_monthly').val('0');
            $('#Default_J_Teachercontract_Subpanel > tbody > tr:nth-child(4)').hide();
        }
        else
        {
            $('#Default_J_Teachercontract_Subpanel > tbody > tr:nth-child(4)').show();
        }
    });

    $('#contract_type').trigger('change');

    $('input[name="dayoff[]"]').live('click', function(){
        if($('input[name="dayoff[]"]:checked').length >2)
        {
            alert(SUGAR.language.translate('J_Teachercontract','LBL_ERROR'));
            return false;
        }
    });
    //add by Lam Hai 17/08/2016
    if($('input[name="record"]').val() != '')
    {
        $('#contract_until').live('change', function(){
            checkSession($(this).closest("form").attr('id'));
        });

        $('#status').live('change', function(){
            if($('#status').val() == 'Inactive')
                checkContractInactive($(this).closest("form").attr('id'));
        });
    }

    if($('input[name="record"]').val() == '')
    {
        $('#contract_date').live('change', function(){
            checkContractTime($(this).closest("form").attr('id'));
        });

        $('#c_teachers_j_teachercontract_1c_teachers_ida').live('change', function(){
            checkContractTime($(this).closest("form").attr('id'));
        });
    }
});

function set_return(popup_reply_data){
    var form_name = popup_reply_data.form_name;
    var name_to_value_array = popup_reply_data.name_to_value_array;
    for (var the_key in name_to_value_array) {
        if (the_key == 'toJSON') {
            continue;
        } else {
            var val = name_to_value_array[the_key].replace(/&amp;/gi, '&').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&#039;/gi, '\'').replace(/&quot;/gi, '"');
            switch (the_key)
            {
                case 'c_teachers_j_teachercontract_1_name':
                    debugger;
                    window.document.forms[form_name].elements['c_teachers_j_teachercontract_1_name'].value = val;
                    break;
                case 'c_teachers_j_teachercontract_1c_teachers_ida':
                    window.document.forms[form_name].elements['c_teachers_j_teachercontract_1c_teachers_ida'].value = val;
                    break;
            }
        }
    }
}