
//when load editview feedback
SUGAR.util.doWhen(function() { return $("#EditView").val() == ""; },
    function() { checkForm(); });

//handle checkform when form load
function checkForm(){
    //when no choose Student then delete value from fullform Student
    $('#btn_clr_contacts_j_feedback_1_name').live('click', function(){
        $('form#EditView').find('input[name="relate_id"]').val('');
    });
    //when no choose Class, delete value from fullform Class
    $('#btn_clr_j_class_j_feedback_1_name').live('click', function(){
        $('form#EditView').find('input[name="relate_id"]').val('');
    });

    //check required
    check_form = function(formname) {
        if (typeof (siw) != 'undefined' && siw && typeof (siw.selectingSomething) != 'undefined' && siw.selectingSomething)
            return false;
        return validate_form(formname, '') && checkRequire();
    }
}


function checkRequire(){
    //check checkbox "to Other Email" checked or not checked?
    var input_other = document.getElementById("to_other_email");
    var isChecked_Other = input_other.checked;
    isChecked_Other = (isChecked_Other)? "checked" : "not checked";

    //check checkbox "to Assign To" checked or not checked?
    var input_assign = document.getElementById("to_assigned_to");
    var isChecked_Assign = input_assign.checked;
    isChecked_Assign = (isChecked_Assign)? "checked" : "not checked"

    //if both checkbox  not checked, require at least 1 checkbox checked
    if(isChecked_Other=='not checked' &&  isChecked_Assign=='not checked'){
        $('#notify').html('<div class="required validation-message">Missing required field: '+SUGAR.language.translate('J_Feedback','LBL_EMAIL_REMINDER')+'</div>');
    }
    else{
        //IF " to other email id checked but input other email null, required enter email
        if(isChecked_Other=='checked' && $("#other_email").val()==""){
            $('#notify').html('<div class="required validation-message">Missing required field: '+SUGAR.language.translate('J_Feedback','LBL_TO_OTHER_EMAIL')+'</div>');
            $("#other_email").effect("highlight", {color: '#E81D25'}, 1000);
        }
        else{
            document.forms[formname].submit();
        }
    }
}

$(document).ready(function(){

    //check and uncheck email other
    $('#to_assigned_to').prop('checked', true);
    $("#other_email").hide();
    $("#to_other_email").change(function() {
        if(this.checked) {
            $("#other_email").show();
        }else{
            $("#other_email").hide();
            $("#other_email").val('');
            $('#notify').html('');
        }
    });

//    if(is_admin != '1'){
        if($('#type_feedback_list').closest('form').find('input[name=record]').val() == '') {
            $("#type_feedback_list option[value=Customer]").prop('disabled', true).addClass('input_readonly');
        }else{
           $("#type_feedback_list option:not(:selected)").prop('disabled', true).addClass('input_readonly');
        }

//    }
    //    $('#type_feedback_list').change(function(){
    //        if( $(this).val() == 'Internal'){
    //            $('#text_feedback').closest('tr').hide();
    //            $('#feedback').closest('tr').hide();
    //            $('#receiver').closest('tr').hide();
    //        }else{
    //          $('#text_feedback').closest('tr').show();
    //            $('#feedback').closest('tr').show();
    //            $('#receiver').closest('tr').show();
    //        }
    //    });
    //    $('#type_feedback_list').trigger('change');

});