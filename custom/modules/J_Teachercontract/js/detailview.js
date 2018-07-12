$(document).ready(function(){
    if($('#contract_type').val()=='PT')
    {
        $('#DEFAULT > tbody > tr:nth-child(4)').hide();
    }
    
    checkSession();

});

function checkSession() {
    var date = new Date();
    var dateUntil = date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear();
    var teacherContract = $('input[name="record"]').val(); 
    $.ajax({
        url: "index.php?module=J_Teachercontract&action=checksession&sugar_body_only=true",
        type: "POST",
        async: false,
        data:  
        {
            teacher_id : $('#c_teachers_j_teachercontract_1c_teachers_ida').data('idValue'),
            contract_until : dateUntil,
            teacher_contract : teacherContract,
            type : 'check_session',
        },
        success: function(data){
            if(data != 'false')
                $('#delete_button').hide();        
        },        
    });
}