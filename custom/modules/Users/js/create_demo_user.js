$(document).ready(function () {
    loadMyDatePicker('EditView');     
    addRowUser();       
});

function generateUsername(){
    $(".div_user_list tbody tr").each(function(){
        var email = $(this).find(".user_company_email").val();
        var user_account = email.replace(/@.*/, "");
        $(this).find(".user_account").val(user_account);    
    });
}

function saveUserList() {
     user_array = [];
     data_array = [];   
    
     $(".div_user_list tbody tr").each(function(){
        var row = $(this);
        var user_name = row.find(".user_account").val();
        var user_email = row.find(".user_company_email").val();
        var user_first_name = row.find(".user_first_name").val();
        var user_last_name = row.find(".user_last_name").val();
        var user_role = row.find(".user_role").val();

        user_array.push(user_name);    
        data_array.push({
            'user_name'         : user_name,
            'user_email'        : user_email,
            'user_first_name'   : user_first_name,
            'user_last_name'    : user_last_name,
            'user_role'         : user_role,
        });   
    });
    
    
    ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_AJAX_PLEASE_WAIT'));
    $.ajax({
        'url': 'index.php?module=Users&action=handleAjax&sugar_body_only=true',
        'type': 'POST',
        'async': true,
        'data': {
            type: 'createDemoUser',    
            team_name : $("#account_name").val(),
            expiry_date : $("#expiry_date").val(),
            user_list : user_array,   
            data_array : data_array,   
        },
        dataType: "json",
        success: function(res) {
            if(res.success == 1){     
                alertify.success(SUGAR.language.get('app_strings', 'LBL_AJAX_SAVE_SUCCESS')); 
                //window.location.href = 'index.php?module=Teams&action=index';
            }
            else if(res.success == -1){
                $(".div_user_list tbody tr:eq("+res.index+")").find(".user_account").effect("highlight", {color: '#FF0000'}, 2000);
                alertify.error(SUGAR.language.get('Users', res.error_label));
            }                                                                 
            else{
                alertify.error(SUGAR.language.get('Users', res.error_label)); 
            }
            ajaxStatus.hideStatus();
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {    
            alertify.error(SUGAR.language.get('app_strings', 'LBL_AJAX_ERROR')); 
            ajaxStatus.hideStatus();           
        }
    });
}

function addRowUser() {                                  
    var new_row = $(".tbl_user_list tfoot").html();
    
    if($(".tbl_user_list tbody tr").size() == 0){
        $(".tbl_user_list tbody").html(new_row);    
    }
    else{
        $(".tbl_user_list tbody tr:last").after(new_row);    
    }                                               
}

function delRow(cursor) {
    cursor.closest('tr').remove();
}

function checkForm(){
    checkEmpty();
    checkDuppicateUsername();
    checkDuppicateDB();
}

function checkForm(){
    //account name
    if($("#account_name").val() == ""){
        $("#account_name").effect("highlight", {color: '#FF0000'}, 2000);
        alertify.error(SUGAR.language.get('Users', 'ERROR_ACCOUNT_NAME_EMPTY'));
        return false;    
    }

    // user list
    var emailEmpty = false;
    var userLastnameEmpty = false;
    var usernameEmpty = false;

    $(".div_user_list tbody tr").each(function(){
        var row = $(this);
        if(row.find(".user_company_email").val() == ""){
            row.find(".user_company_email").effect("highlight", {color: '#FF0000'}, 2000);
            emailEmpty = true;    
        }   
        if(row.find(".user_last_name").val() == ""){
            row.find(".user_last_name").effect("highlight", {color: '#FF0000'}, 2000);
            userLastnameEmpty = true;    
        } 
        if(row.find(".user_account").val() == ""){
            row.find(".user_account").effect("highlight", {color: '#FF0000'}, 2000);
            usernameEmpty = true;    
        }        
    }); 

    if(emailEmpty) alertify.error(SUGAR.language.get('Users', 'ERROR_USER_COMPANY_EMAIL_EMPTY')); 
    if(userLastnameEmpty) alertify.error(SUGAR.language.get('Users', 'ERROR_USER_LAST_NAME_EMPTY')); 
    if(usernameEmpty) alertify.error(SUGAR.language.get('Users', 'ERROR_USER_NAME_EMPTY')); 
    
    if(emailEmpty || userLastnameEmpty || usernameEmpty){
        return false;
    }
    
    if($(".div_user_list tbody tr").size() == 0){
        alertify.error(SUGAR.language.get('Users', 'ERROR_USER_LIST_EMPTY'));    
        return false; 
    }
    
    //check duplicate in screen
    
    var user_array = [];
    
    $(".div_user_list tbody tr").each(function(){
        var row = $(this);
        var user_name = row.find(".user_account").val()
        var index = user_array.indexOf(user_name);
        if(index >= 0 ){
            $(".div_user_list tbody tr:eq("+index+")").find(".user_account").effect("highlight", {color: '#FF0000'}, 2000);
            row.find(".user_account").effect("highlight", {color: '#FF0000'}, 2000);
            alertify.error(SUGAR.language.get('Users', 'ERROR_USER_NAME_DUPLICATE'));    
            return false;    
        }
        else user_array.push(user_name);    
    });    
    
    return true;                 
}   

function createUser(){      
    if(checkForm()){
        saveUserList();                
    }                              
}

function handleUpdateEmail(this_input){
    var this_row = this_input.closest("tr");
    if(this_row.find(".user_account").val() == ""){
        var email = this_row.find(".user_company_email").val();
        var user_account = email.replace(/@.*/, "");
        this_row.find(".user_account").val(user_account);     
    }
}