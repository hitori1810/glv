function validateElements(){
    if(validate['AttendanceCheck']!='undefined'){delete validate['AttendanceCheck']};
    //validate Class
    addToValidateBinaryDependency('AttendanceCheck', 'class_name', 'alpha', true, SUGAR.language.get('app_strings', 'ERR_SQS_NO_MATCH_FIELD') + SUGAR.language.get('C_Attendance','LBL_CLASS') , 'class_id' );
    var ac_for = [];
    $("input[name='ac_for']").each(function(){
        if($(this).is(':checked'))  
            ac_for.push($(this).val());
    });
    if(ac_for.length == 0){
        addToValidate('AttendanceCheck', 'validate_ac', 'varchar', true, SUGAR.language.get('C_Attendance','LBL_AC_TITLE') ); 
    }
    return check_form('AttendanceCheck');     
}
function diableAndClear($order,$bool){
    if($order == 'top'){
        $("input[type=radio]").attr('disabled', true);
        $('#select_month').prop('disabled', 'disabled');
        if($bool == false){
            $('#class_name').val('');
            $('#result_table').html('');
            $('#result_help').html('');
            $("input[name='ac_for']").each(function(){
                if($(this).is(':checked'))  
                    $(this).removeAttr('checked');    
            });
            $('#select_class').html('');
            $('#select_month').hide();
            $("input[type=radio]").attr('disabled', false);
            $('#select_month').prop('disabled', false);
        }
    }
}
function ajaxAttendanceCheck(){
    //Run Ajax
    ajaxStatus.showStatus('Loading...');
    //    var class_id = $('#class_id').val();
    var class_id = $('#class_id').val();
    var ac_for = $('input[name=ac_for]:checked').val();
    var select_month = $('#select_month').val();
    diableAndClear('top',true);    
    $.ajax({
        url: "index.php?module=C_Attendance&action=ajaxAttendanceCheck&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  
        {
            class_id : class_id,
            ac_for : ac_for,
            select_month : select_month,
        },
        dataType: "json",
        success: function(data){           
            if(data.success == "1"){
                $('div#result_table').html(data.html); 
                $('div#result_help').html(data.help); 
            }
            ajaxStatus.hideStatus();  
        },        
    });
}
function selectMonthEvent()
{
    $('#ac_for_3').click(function(){
        $('#select_month').show();
        $('div#select').html('<select></select>');
        ajaxGetClass();
    });
    $('#ac_for_2').click(function(){
        $('#select_month').hide(); 
        ajaxGetClass();   
    });
    $('#ac_for_1').click(function(){
        $('#select_month').hide(); 
        ajaxGetClass();   
    }); 
    $('#ac_for_0').click(function(){
        $('#select_month').hide(); 
        ajaxGetClass();   
    });       
}

function ajaxGetClass()
{
    //ajaxStatus.showStatus('Loading...');
    $("div#select_class").html('<select></select> <img src="custom/include/images/loader.gif" align="absmiddle" width="16">');
    var ac_for = $('input[name=ac_for]:checked').val();
    var select_month = $('#select_month').val();
    $.ajax({
        url: "index.php?module=C_Attendance&action=ajaxGetClass&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  
        {
            ac_for : ac_for,
            select_month : select_month,
        },
        dataType: "json",
        success: function(data){           
            if(data.success == "1"){
                $('div#select_class').html(data.select);  
            }
            ajaxStatus.hideStatus();  
        },        
    });


}







$(document).ready(function(){
    //button Find class click event
    $('#ac_check').click(function(){
        if(validateElements())
            ajaxAttendanceCheck();
    })
    // button select click event
    $('#btn_select_class').click(function(){
        open_popup("C_Classes", 600, 400, "", true, false, {"call_back_function":"set_return","form_name":"AttendanceCheck","field_to_name_array":{"id":"class_id","name":"class_name"}});  
    });
    selectMonthEvent();  
})






