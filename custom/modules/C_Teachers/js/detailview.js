//create by leduytan 4/8/2014
$(document).ready(function(){
    $('#send_email').live('click', function(){
        ajaxSendMailTeacher();        
    });
    //end
    var type = $('#teacher_type').val();
    if(type == 'TA')
        $('#detailpanel_2').hide();
    
    if(type == 'Teacher')
        $('#detailpanel_2').show();   
});

function ajaxSendMailTeacher()
{
    ajaxStatus.showStatus('Sending Email... Please wait, this will take less than a minute...');
    var record = $("input[name='record']").val();
    $.ajax({
        url: "index.php?module=C_Teachers&action=sendMailTeacher&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  
        {
            record: record,
        },
        dataType: "json",
        success: function(data){
            if(data.success == "1"){   
                ajaxStatus.hideStatus(); 
                alertify.success(SUGAR.language.get('C_Teachers','LBL_SEND_MAIL_SUCCESS'));    
            }
            else alert("Something was wrong. Please try again!")


        }         
    });        
}

//end by leduytan