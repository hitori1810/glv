function showPopupConfirm(popup_reply_data){

    var class_id = popup_reply_data.name_to_value_array.class_id;
    var enrollments_id = [];
    $("input.checkbox").each(function(){
        if($(this).is(':checked') && $(this).val() != ''){
            enrollments_id.push($(this).val());
        }  
    });

    if(enrollments_id.length == 0)
        alertify.alert('You must select at least 1 enrollment.');
    else{
        ajaxStatus.showStatus('Checking <img src="custom/include/images/loader32.gif" align="absmiddle" width="32">');
        $.ajax({
            url: "index.php?module=C_Classes&action=actCheckClassNull&sugar_body_only=true",
            type: "POST",
            async: true,
            data:  
            {
                class_id: class_id,
                enrollments_id: enrollments_id,
                type : "addEnrollment",
            },
            dataType: "json", 
            success: function(res){
                ajaxStatus.hideStatus();
                switch (res.success) {
                    case 'error1':
                        alertify.alert('This class is not enough seating for' + enrollments_id.length + ' persons. Please, Check max size of class again.');
                        ajaxStatus.hideStatus();
                        break;
                    case 'error3':
                        alertify.alert('Can not add deleted enrollment to class. Please, try again!');
                        ajaxStatus.hideStatus();
                        break;
                    case 'error2':
                        $( "#dialog-confirm" ).dialog({
                            resizable: false,
                            width: 600,
                            modal: true,
                            buttons: {
                                "Thêm vào tất cả các buổi": function() {
                                    $(this).dialog('close');
                                    ajaxAddToClass(class_id, enrollments_id, '1');
                                },
                                "Chỉ thêm vào các buổi chưa bắt đầu": function() {
                                    $(this).dialog('close');
                                    ajaxAddToClass(class_id, enrollments_id, '0');
                                },
                            }
                        });
                        break;
                    case '1':
                        ajaxAddToClass(class_id, enrollments_id); 
                        break;
                    default:
                        alertify.alert('An error occurred. Please, try again!');
                }   
            },       
        });  
    }
}

function ajaxAddToClass(class_id ,enrollments_id,comfirm) {

    ajaxStatus.showStatus('Waiting <img src="custom/include/images/loader32.gif" align="absmiddle" width="32">');
    $.ajax({
        url: "index.php?module=C_Classes&action=actAddToClass&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  
        {
            enrollments_id: enrollments_id,
            class_id: class_id,
            comfirm: comfirm,
            type : "addEnrollment",
        },
        dataType: "json", 
        success: function(res){
            if(res.success == "1")
                window.location = 'index.php?module=C_Classes&return_module=C_Classes&action=DetailView&record='+class_id; 
            else
                alertify.alert('An error occurred. Please, try again!'); 
            ajaxStatus.hideStatus();   
        },       
    });
}