

/////////////////////------Atlantic Junior: Save PT Demo From Module Student By Quyen.Cao-------/////////////////
$(document).ready(function(){
    $('.remove_pt').live('click',function(){
        var pt_id = this.getAttribute('id');
        alertify.confirm(SUGAR.language.get('Contacts', 'LBL_CONFIRM_REMOVE_PT'), function (e, str) {
            if(e) ajaxRemovePTFromStudent(pt_id);
        });
    });
    $('.remove_demo').live('click',function(){
        var meeting_id = this.getAttribute('id');
        alertify.confirm(SUGAR.language.get('Contacts', 'LBL_CONFIRM_REMOVE_DEMO'), function (e, str) {
            if(e) ajaxRemoveDemoFromStudent(meeting_id);
        });
    });
});
//get val from url by Quyen.Cao
function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}

///////////////////////----Demo-------/////////////////////////
////Add Demo
function handleSelect_contact_demo(){
    open_popup("Meetings",600, 400, "", true, false, 
        {"call_back_function":"set_return_demo_contact","form_name":"DetailView",
            "field_to_name_array":{"id":"id", "meeting_type":"meeting_type"}}, 
        "single", true, 'popupfordemodefs');
}

function set_return_demo_contact(popup_reply_data, filter){
    if($('input[name=module]').val() == "Contacts"){
        var student_id = $('input[name=record]').val();
    }else{
        student_id = student_id_ptdemo;
    }
    var form_name = popup_reply_data.form_name;
    var name_to_value_array = popup_reply_data.name_to_value_array;
    if(name_to_value_array.meeting_type != 'Demo'){
        alertify.alert(SUGAR.language.get('Meetings','LBL_ERROR_PLEASE_CHOOSE_DEMO_ITEM'));
        return false;
    }                                  
    ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_AJAX_PLEASE_WAIT'));
    $.ajax({
        type: "POST",
        url: "index.php?module=Contacts&action=savePTResult&sugar_body_only=true",
        data: {
            meeting_id : name_to_value_array["id"],
            parent:"Contacts",
            student_id :student_id,
            student_name :  $('#name').text(),
            type : 'ajaxSaveDemo'
        },
        success:function(data){
            if(data) {
                if($('input[name=module]').val() == "Contacts"){
                    showSubPanel('contact_demo', null, true);
                    alertify.success(SUGAR.language.get('Contacts','LBL_COMPLETE'));
                }
            } else {
                alertify.error(SUGAR.language.get('Contacts','LBL_ERROR'));
            }

            ajaxStatus.hideStatus();
        }
    });

}

function handleCreate_contact_demo(){
    var contact_id = $('input[name=record]').val();
    window.location.replace('index.php?module=Meetings&action=EditView&return_module=Contacts&return_action=DetailView&return_id='+contact_id+'&type=Demo');
}

//Remove Demo
function ajaxRemoveDemoFromStudent(meeting_id){
    $("#availability_status").remove();
    $('#'+meeting_id).parent().append("<span id='availability_status' style = 'padding-top: 5px;'></span>");
    $("#availability_status").html('<img src="custom/include/images/loader.gif" align="absmiddle" width="16">');
    $.ajax({
        url:'index.php?module=Contacts&action=savePTResult&sugar_body_only=true',
        type:'POST',
        data:{
            meeting_id : meeting_id,
            type : 'ajaxRemoveDemo',
        },
        success:function(data){
            showSubPanel('contact_demo', null, true);
            $("#availability_status").remove();
        }
    });
}


///////////////////////-----PT-----------////////////////////////
//Create PT
function handleCreate_contact_pt(){
    var contact_id = $('input[name=record]').val();;
    window.location.replace('index.php?module=Meetings&action=EditView&return_module=Contacts&return_action=DetailView&return_id='+contact_id+'&type=PT');

}

///Add PT
function handleSelect_contact_pt(){
    open_popup("Meetings",600, 400, "", true, false, 
        {"call_back_function":"set_return_pt_contact","form_name":"DetailView",
            "field_to_name_array":{"id":"id", "meeting_type":"meeting_type"}}, 
        "single", true,'popupforptdefs');
}

function set_return_pt_contact(popup_reply_data, filter){
    if($('input[name=module]').val() == "Contacts"){
        var student_id = $('input[name=record]').val();
    }else{
        student_id = student_id_ptdemo;
    }
    var form_name = popup_reply_data.form_name;
    var name_to_value_array = popup_reply_data.name_to_value_array;
    if(name_to_value_array.meeting_type != SUGAR.language.get('app_list_strings','type_meeting_list')['Placement Test']){
        alertify.alert(SUGAR.language.get('Meetings','LBL_ERROR_PLEASE_CHOOSE_PT_ITEM'));
        return false;
    }
    for (var the_key in name_to_value_array) {
        var val = name_to_value_array[the_key].replace(/&amp;/gi, '&').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&#039;/gi, '\'').replace(/&quot;/gi, '"');
        switch (the_key)
        {
            case 'id':                             
                ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_AJAX_PLEASE_WAIT'));
                $.ajax({
                    type: "POST",
                    url: "index.php?module=Contacts&action=savePTResult&sugar_body_only=true",
                    data: {
                        meeting_id : val,
                        parent:"Contacts",
                        student_id :student_id,
                        student_name :  $('#name').text(),
                        type : 'ajaxSavePT',
                    },
                    success:function(data){
                        if(data) {
                            if($('input[name=module]').val() == "Contacts"){
                                showSubPanel('contact_pt', null, true);
                            }
                            alertify.success(SUGAR.language.get('Contacts','LBL_COMPLETE'));
                        }
                        else {                                                        
                            alertify.error(SUGAR.language.get('app_strings','LBL_AJAX_ERROR'));  
                        }
                        ajaxStatus.hideStatus();
                    }
                });
                break;
        }
    }
}

function ajaxRemovePTFromStudent(pt_id){
    $("#availability_status").remove();
    $('#'+pt_id).parent().append("<span id='availability_status' style = 'padding-top: 5px;'></span>");
    $("#availability_status").html('<img src="custom/include/images/loader.gif" align="absmiddle" width="16">');
    $.ajax({
        url:'index.php?module=Contacts&action=savePTResult&sugar_body_only=true',
        type:'POST',
        data:{
            pt_id : pt_id,
            type : 'ajaxRemovePT',
        },
        success:function(data){
            showSubPanel('contact_pt', null, true);
            $("#availability_status").remove();
        }
    });
}

/////////////////////------End Atlantic Junior: Save PT Demo From Module Student By Quyen.Cao---------/////////////////