$(document).ready(function(){
    $('#export').click(function(){    
        $("#checkAll").trigger("click");
        $(".check").attr("checked", true);
        $("body").css({ overflow: 'hidden' });

        $('#div_export_form').dialog({
            resizable    : false,
            width        : 700,
            height       :'auto',
            modal        : true,
            visible      : true,
            position     : ['center',130],
            beforeClose: function(event, ui) {
                $("body").css({ overflow: 'inherit' });
            },
            buttons: {
                "SaveSession":{
                    click:function() {
                        var studentID = new Array();
                        var template = $('#template').val();
                        var classID = $('#classID').val();

                        if(template == 'student_list'){
                            var url = 'index.php?action=ReportCriteriaResults&module=Reports&page=report&id=91197da3-e243-594f-9149-56c53c5f634b';
                            window.open(url,'_blank');
                        }
                        else{
                            $('#tbl_student_list_export').parent().find('[name="student_id[]"]:checked').each(function(){
                                studentID.push($(this).val());                            
                            });

                            studentID = JSON.stringify(studentID);
                            var url = 'index.php?module=J_Class&action=exportfile&template=' + template + '&classID=' + classID + '&studentID=' + studentID + '&certificate_no='+$('#certificate_no').val();
                            window.open(url,'_blank');
                        }           
                    },
                    class   : 'button primary btn_save_session',
                    text    : SUGAR.language.get('J_Class','BTN_EXPORT'),
                },
                "Cancel":{
                    click:function() {
                        $(this).dialog('close');
                    },
                    class   : 'button btn_cancel',
                    text    : SUGAR.language.get('J_Class','LBL_BTN_CANCEL'),
                },  
            },                                      
        });
    });

    $('#checkAll').click(function(){
        if ($(this).is(':checked')) {
            $(".check").attr("checked", true);
        } else {
            $(".check").attr("checked", false);
        }
    });

    $(".check").click(function(){  
        $(".check").each(function(){
            if(!$(this).is(':checked')){
                $("#checkAll").attr("checked", false);      
                return;
            }
        });      

    });  
});