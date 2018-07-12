
/////////////////////------Atlantic Junior: Save PT Demo From Module Leads By Quyen.Cao-------///////////

//get val from url by Quyen.Cao
function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}

$(document).ready(function(){
    quickAdminEdit('leads', 'assigned_user_id');      
    quickAdminEdit('leads', 'potential');
    $('.remove_pt').live('click',function(){
        var pt_id = this.getAttribute('id');                                  
        alertify.confirm(SUGAR.language.get('Contacts', 'LBL_CONFIRM_REMOVE_PT'), function (e, str) {
            if(e) ajaxRemovePTFromLead(pt_id);
        });
    });

    $('.remove_demo').live('click',function(){
        var meeting_id = this.getAttribute('id');
        alertify.confirm(SUGAR.language.get('Contacts', 'LBL_CONFIRM_REMOVE_PT'), function (e, str) {
            if(e) ajaxRemoveDemoFromLead(meeting_id);
        });
    });

});

//////////////////////-------- PT---------------------///////////////////////////////
function handleSelect_lead_pt(){
    open_popup("Meetings",600, 400, "", true, false, 
        {"call_back_function":"set_return_lead_pt","form_name":"DetailView",
            "field_to_name_array":{"id":"id", "meeting_type":"meeting_type"}}, 
        "single", true, 'popupforptdefs');
}

function set_return_lead_pt(popup_reply_data, filter){
    if($('input[name=module]').val() == "Leads"){
        var lead_id = $('input[name=record]').val();
    }else{
        var lead_id = student_id_ptdemo;
    }
    var form_name = popup_reply_data.form_name;
    var name_to_value_array = popup_reply_data.name_to_value_array;
    if(name_to_value_array.meeting_type != SUGAR.language.get('app_list_strings','type_meeting_list')['Placement Test']){
        alertify.alert(SUGAR.language.get('Meetings','LBL_ERROR_PLEASE_CHOOSE_PT_ITEM'));
        return false;
    }
    for (var the_key in name_to_value_array) {
        var val = name_to_value_array[the_key].replace(/&amp;/gi, '&').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&#039;/gi, '\'').replace(/&quot;/gi, '"');
        switch (the_key) {
            case 'id':                                                                          
                ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_AJAX_PLEASE_WAIT'));
                $.ajax({
                    type: "POST",
                    url: "index.php?module=Leads&action=savePTResult&sugar_body_only=true",
                    data: {
                        meeting_id : val,
                        parent:"Leads",
                        lead_id :lead_id,
                        lead_name :$('#name').text(),
                        type: 'ajaxSavePT',
                    },
                    success:function(data){
                        if(data) {
                            if($('input[name=module]').val() == "Leads"){
                                showSubPanel('lead_pt', null, true);
                                alertify.success(SUGAR.language.get('Leads','LBL_COMPLETE'));
                            }
                        } else {
                            alertify.error(SUGAR.language.get('Leads','LBL_ERROR'));
                        }

                        ajaxStatus.hideStatus();
                    }
                });
                break;
        }
    }
}

//Create PT
function handleCreate_lead_pt(){
    var lead_id = $('input[name=record]').val();
    window.location.replace('index.php?module=Meetings&action=EditView&return_module=Leads&return_action=DetailView&return_id='+lead_id+'&type=PT');
}

//Remove PT
function ajaxRemovePTFromLead(pt_id){
    $("#availability_status").remove();
    $('#'+pt_id).parent().append("<span id='availability_status' style = 'padding-top: 5px;'></span>");
    $("#availability_status").html('<img src="custom/include/images/loader.gif" align="absmiddle" width="16">');
    $.ajax({
        url:'index.php?module=Leads&action=savePTResult&sugar_body_only=true',
        type:'POST',
        data:{
            pt_id : pt_id,
            type: 'ajaxRemovePT',
        },
        success:function(data){
            $("#availability_status").remove();
            showSubPanel('lead_pt', null, true);
        }
    });
}

///////////////////------------ Demo----------------------------/////////////////
function handleSelect_lead_demo(){
    open_popup("Meetings",600, 400, "", true, false, 
        {"call_back_function":"set_return_lead_demo",
            "form_name":"DetailView",
            "field_to_name_array":{"id":"id","meeting_type":"meeting_type"}}, 
        "single", true, 'popupfordemodefs');
}

function set_return_lead_demo(popup_reply_data, filter){
    if($('input[name=module]').val() == "Leads"){
        var lead_id = $('input[name=record]').val();
    }else{
        lead_id = student_id_ptdemo;
    }
    var form_name = popup_reply_data.form_name;
    var name_to_value_array = popup_reply_data.name_to_value_array;
    if(name_to_value_array.meeting_type != 'Demo'){
        alertify.alert(SUGAR.language.get('Meetings','LBL_ERROR_PLEASE_CHOOSE_DEMO_ITEM'));
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
                    url: "index.php?module=Leads&action=savePTResult&sugar_body_only=true",
                    data: {
                        meeting_id : val,
                        parent:"Leads",
                        lead_id :lead_id,
                        lead_name : $('#name').text(),
                        type : 'ajaxSaveDemo',
                    },
                    success:function(data){
                        if(data) {
                            if($('input[name=module]').val() == "Leads"){
                                showSubPanel('lead_demo', null, true);
                                alertify.success(SUGAR.language.get('Leads','LBL_COMPLETE'));
                            }
                        } else {                                                      
                            alertify.error(SUGAR.language.get('app_strings','LBL_AJAX_ERROR'));
                        }
                        ajaxStatus.hideStatus(); 
                    }
                });
                break;
        }
    }
}

//Remove Demo
function ajaxRemoveDemoFromLead(meeting_id){
    $("#availability_status").remove();
    $('#'+meeting_id).parent().append("<span id='availability_status' style = 'padding-top: 5px;'></span>");
    $("#availability_status").html('<img src="custom/include/images/loader.gif" align="absmiddle" width="16">');
    $.ajax({
        url:'index.php?module=Leads&action=savePTResult&sugar_body_only=true',
        type:'POST',
        data:{
            meeting_id : meeting_id,
            type : 'ajaxRemoveDemo',
        },
        success:function(data){
            showSubPanel('lead_demo', null, true);
            $("#availability_status").remove();   
        }
    });
}

//create Demo chua add hoc vien do vao lop demo
function handleCreate_lead_demo(){
    var lead_id = $('input[name=record]').val();;
    window.location.replace('index.php?module=Meetings&action=EditView&return_module=Leads&return_action=DetailView&return_id='+lead_id+'&type=Demo');
}

/////////////////////------End Atlantic Junior: Save PT Demo From Module Leads By Quyen.Cao-------///////////