$(document).ready(function(){
    quickAdminEdit('contacts', 'assigned_user_id');
    quickAdminEdit2('contacts', 'additional_week', 'show_add_week');


    $("#edit_free_balance").click(function(){
        $(".entry-form").fadeIn("fast");
    });
    $("#close_ct, #cancel_ct").click(function(){
        $(".entry-form").fadeOut("fast");
    });
    addToValidate('configinfo', 'expire_date', 'date', true,'Expire date' );
    addToValidate('configinfo', 'hour_balance', 'int', true,'Hour Balance' );
    addToValidate('configinfo', 'enroll_balance', 'currency', true,'Enroll Balance' );
    addToValidate('configinfo', 'free_balance', 'currency', true,'Free Balance' );
    $("#save_contact").click(function(){
        var _form = document.getElementById('configinfo');
        if(check_form('configinfo')){
            $('#record_use').val($('input[name=record]').val());
            ajaxStatus.showStatus('Saving');
            _form.submit();
        }else
            return false;
    });
    $('#suspend_student').live('click', function(){
        $( "#dialog-confirm" ).dialog({
            resizable: false,
            width: 600,
            modal: true,
            buttons: {
                "Đồng ý": function() {
                    $(this).dialog('close');
                    ajaxSuspendStudent(true);
                },
                "Hủy thao tác": function() {
                    $(this).dialog('close');
                },
            }
        });
    });
    $('#move_student').live('click', function(){
        $( "#dialog-confirm_5" ).dialog({
            resizable: false,
            width: 600,
            modal: true,
            buttons: {
                "Suspend & Move Student": function() {
                    $(this).dialog('close');
                    ajaxSuspendStudent(false);
                },
                "Move Student": function() {
                    $(this).dialog('close');
                    window.open('index.php?module=C_Refunds&action=EditView&return_module=C_Refunds&return_action=DetailView&contact_id='+$('input[name=record]').val()+'&type=Moving-out','_blank');
                },
            }
        });
    });
});

//Ajax Supend a Student
function ajaxSuspendStudent(reload){
    ajaxStatus.showStatus('Waiting <img src="custom/include/images/loader32.gif" align="absmiddle" width="32">'); //Sugar alert
    $.ajax({
        url:'index.php?module=C_Classes&action=actAddToClass&sugar_body_only=true',
        type:'POST',
        data:{
            student_id : $('input[name=record]').val(),
            type : 'suspendStudent',
        },
        dataType: "json",
        success:function(data){
            if(data.success == "1"){
                if(reload == true)
                    location.reload();
                else
                    window.location.href = 'index.php?module=C_Refunds&action=EditView&return_module=C_Refunds&return_action=DetailView&contact_id='+$('input[name=record]').val()+'&type=Moving-out';
            }else {
                alert(SUGAR.language.get('Contacts','LBL_ERROR_2'));
            }
            ajaxStatus.hideStatus();
        }
    });
}

function ajaxResetPassword(){
    /*$('#password').pGenerator({
    'bind': 'click',
    'passwordLength': 8,
    'uppercase': true,
    'lowercase': true,
    'numbers':   false,
    'specialChars': false,
    'onPasswordGenerated': function(generatedPassword) {
    $('#password').val(generatedPassword);
    }
    });

    $('#password').trigger('click');*/

    ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_AJAX_PLEASE_WAIT'));
    $.ajax({
        url:'index.php?module=Contacts&action=resetpassword&sugar_body_only=true',
        type:'POST',
        data:{
            student_id : $('input[name=record]').val(),
            //password : $('input[name=password]').val(),
        },
        dataType: "json",
        success: function(data){
            if( data.success == '1'){             
                alertify.alert(data.errorLabel);
                $('#password_generated').text(data.new_password).effect("highlight", {color: '#ff9933'}, 5000);
            }else                                 
                alert(SUGAR.language.get('app_strings','LBL_AJAX_ERROR'));
            ajaxStatus.hideStatus();
        }
    });
}

function ajaxStopStudent(){                                                         
    ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_AJAX_PLEASE_WAIT'));
    $.ajax({
        url:'index.php?module=Contacts&action=handelAjaxsContact&sugar_body_only=true',
        type:'POST',
        data:{
            student_id : $('input[name=record]').val(),
            type : 'ajaxStopStudent',
        },
        success:function(data){
            ajaxStatus.hideStatus();
            if(data != 1) {                                       
                alert(SUGAR.language.get('app_strings','LBL_AJAX_ERROR'));
            }
            else {                                               
                alertify.success(SUGAR.language.get('J_Class','LBL_LOAD_SUCCESSFULL'));
                location.reload();
            }

        }
    });
}