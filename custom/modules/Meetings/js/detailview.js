var id_contact;
var id_lead;
var parent;
$( document ).ready(function() {
    //add action for Upgrade To Class Button
    $('#select_from_publ').live('click',function(){
        open_popup("Opportunities", 600, 400, "&sales_stage_advanced=Success", true, true, {
            "call_back_function": "actAddToSession_Public",
            "form_name": "DetailView",
            "field_to_name_array": {
                "id": "enroll_id"
            },
            }, "Select", true);
    });
    //Custom Send SMS 
    $('#btnFreeText').live('click',function(){
        if($("input.custom_checkbox:checked").length>0)
            openAjaxPopupForMulti("","J_PTResult",$('#J_PTResult_checked_str').val()); 
        else 
            alertify.alert(SUGAR.language.get('Meetings','LBL_NOTIFY'));
    });

    // Add Junior To Session
    $('#select_from_situa').live('click',function(){
        var class_name  = $(this).attr('class_name');
        var class_id    = $(this).attr('class_id');

        open_popup("J_StudentSituations", 600, 400, "&ju_class_name_advanced="+class_name+"&ju_class_id_advanced="+class_id, true, true, {
            "call_back_function": "ajaxAddJuniorToSession",
            "form_name": "DetailView",
            "field_to_name_array": {
                "id": "situation_id"
            },
            }, "Select", true);
    });
    // Remove Junior To Session
    $('.remove_junior_student').live('click',function(){
        var student_id = $(this).attr('id');
        if(confirm("Are you sure to remove student ?"))
            ajaxRemoveJuniorToSession(student_id);
    });


    $('#select_from_corp').live('click',function(){
        window.location = 'index.php?module=Contracts&action=index';
    });

    $('.remove_student').live('click', function(){
        student_id = this.getAttribute('id');
        if(confirm("Are you sure to remove student ?")){
            ajaxRemoveStudentFromSession(student_id);    
        }  
    });
    $('.add_pt_contact').live('click', function(){
        id_contact = this.getAttribute('id');
        open_popup("Meetings",600, 400, "", true, false, {"call_back_function":"set_return_parent","form_name":"DetailView","field_to_name_array":{"id":"id"}}, "single", true,'popupforptdefs'); 
    });

    $('.add_pt_lead').live('click', function(){
        id_lead = this.getAttribute('id');
        open_popup("Meetings",600, 400, "", true, false, {"call_back_function":"set_return_pt_lead","form_name":"DetailView","field_to_name_array":{"id":"id"}}, "single", true,'popupforptdefs'); 
    });


    $('.remove_lead').live('click', function(){
        var lead_id = this.getAttribute('id');
        if(confirm("Are you sure to remove this lead ?")){
            ajaxRemoveLeadFromDemo(lead_id);    
        }  
    });

    $('.add_class').live('click', function(){
        var lead_id = $(this).closest('tr').find('.pt_id').val();
        var parent=$(this).closest('tr').find('.parent').val();
        if(parent=='Leads'){
            window.open('index.php?module=Leads&action=ConvertLead&record='+lead_id+'','_blank');   
        }
        else{
            window.open('index.php?module=J_Payment&action=EditView&return_module=J_Payment&return_action=DetailView&payment_type=Enrollment&student_id='+lead_id+'','_blank');   
        }


    });


    /////////////////////////////////////----------Apolo Junior--------//////////////////////////////////////////
    //Fill id meeting in input value at subpanel Placement Test by Quyen.Cao
    var url= window.location.search.substring(1);
    var id = getUrlVars(url)['record'];
    $('#meeting_id').val(id);

    $('#btnAddAnother').live("click", function(){
        if($("input.custom_checkbox:checked").length == 0)
            alertify.alert(SUGAR.language.get('Meetings','LBL_NOTIFY'));
        else{
            open_popup('Meetings',600, 400, 
                "&meeting_type_advanced="+$("#meeting_type").val(),
                true, false, {
                    "call_back_function":"moveResult",
                    "form_name":"DetailView",
                    "field_to_name_array":
                    {"name":"name","id":"id","meeting_type":"meeting_type"}}, 
                "single", true);           
        }
    });
});

function moveResult(popup_reply_data, filter) {
    var form_name = popup_reply_data.form_name;
    var name_to_value_array = popup_reply_data.name_to_value_array;
    if(name_to_value_array["meeting_type"] == $("#meeting_type").val()){
        ajaxStatus.showStatus('Moving...'); 
        results = [];
        $("input.custom_checkbox:checked").each(function(){
            results.push($(this).val());
        });

        $.ajax({
            type: "POST",
            url: "index.php?module=Meetings&action=saveResult&sugar_body_only=true",
            async: false,
            data:{
                type        : 'ajaxMovePTResult',
                results     : results,
                meeting_id  : name_to_value_array["id"],
            },
            dataType: "json",
            success:function(data){
                if(data.success == "1"){ 
                    window.open('index.php?module=Meetings&action=DetailView&record='+name_to_value_array["id"],'_self');   
                }
                else{    
                    alertify.alert(SUGAR.language.get('Meetings','LBL_ERROR'));
                }
                ajaxStatus.hideStatus();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    }else{
        alertify.error(SUGAR.language.get('Meetings','LBL_SELECTED_RECORD_INVALID'));
    }
}

function ajaxRemoveStudentFromSession(student_id){
    $("#availability_status").remove();
    $('#'+student_id).parent().append("<span id='availability_status' style = 'padding-top: 5px;'></span>");
    $("#availability_status").html('<img src="custom/include/images/loader.gif" align="absmiddle" width="16">');
    $.ajax({
        url:'index.php?module=Meetings&action=actAddToSession&sugar_body_only=true',
        type:'POST',
        data:{
            type : 'removeStudentFromSession',
            student_id : student_id,
            ss_id: $('input[name=record]').val(),
        },
        dataType: "json",
        success:function(data){
            if(data.success == "1"){
                showSubPanel('contacts', null, true);
            } else {
            }
            $("#availability_status").remove();
        }
    });
}

function ajaxRemoveLeadFromDemo(lead_id){
    $("#availability_status").remove();
    $('#'+lead_id).parent().append("<span id='availability_status' style = 'padding-top: 5px;'></span>");
    $("#availability_status").html('<img src="custom/include/images/loader.gif" align="absmiddle" width="16">');
    $.ajax({
        url:'index.php?module=Meetings&action=actAddToSession&sugar_body_only=true',
        type:'POST',
        data:{
            type : 'removeLeadFromDemo',
            lead_id : lead_id,
            ss_id: $('input[name=record]').val(),
        },
        dataType: "json",
        success:function(data){
            if(data.success == "1"){
                showSubPanel('leads', null, true);
            } else {
            }
            $("#availability_status").remove();
        }
    });
}

function actAddToSession_Public(popup_reply_data){

    var enroll_id = popup_reply_data.name_to_value_array.enroll_id;
    var ss_id = $('input[name=record]').val();

    ajaxStatus.showStatus('Waiting <img src="custom/include/images/loader32.gif" align="absmiddle" width="32">');

    $.ajax({
        url: "index.php?module=Meetings&action=actAddToSession&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  
        {
            ss_id: ss_id,
            enroll_id: enroll_id,
            type: 'addPublic',
        },
        dataType: "json", 
        success: function(res){
            if (res.success == '1') {
                showSubPanel('contacts', null, true);   
            }else{
                alert(SUGAR.language.get('Meetings','LBL_ERROR'));
            }
            ajaxStatus.hideStatus();   
        },       
    });
}

function ajaxAddJuniorToSession(popup_reply_data){
    var situation_id    = popup_reply_data.name_to_value_array.situation_id;
    var ss_id           = $('input[name=record]').val();
    var class_id        = $('#select_from_situa').attr('class_id');

    ajaxStatus.showStatus('Waiting <img src="custom/include/images/loader32.gif" align="absmiddle" width="32">');

    $.ajax({
        url: "index.php?module=Meetings&action=ajaxMeeting&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  
        {
            ss_id           : ss_id,
            situation_id    : situation_id,
            class_id        : class_id,
            type            : 'ajaxAddJuniorToSession',
        },
        dataType: "json", 
        success: function(res){
            if (res.success == '1') {
                showSubPanel('contacts', null, true);
                alertify.success(res.error);   
            }else{
                alertify.error(res.error);
            }
            ajaxStatus.hideStatus();   
        },       
    });
}

function ajaxRemoveJuniorToSession(student_id){
    var ss_id           = $('input[name=record]').val();
    var class_id        = $('#select_from_situa').attr('class_id');

    ajaxStatus.showStatus('Waiting <img src="custom/include/images/loader32.gif" align="absmiddle" width="32">');

    $.ajax({
        url: "index.php?module=Meetings&action=ajaxMeeting&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  
        {
            ss_id           : ss_id,
            student_id      : student_id,
            class_id        : class_id,
            type            : 'ajaxRemoveJuniorToSession',
        },
        dataType: "json", 
        success: function(res){
            if (res.success == '1') {
                showSubPanel('contacts', null, true);
                alertify.success(res.error);   
            }else{
                alertify.error(res.error);
            }
            ajaxStatus.hideStatus();   
        },       
    });
}

function actAddToSession_Corp(popup_reply_data){

    var student_id = popup_reply_data.name_to_value_array.student_id;
    var ss_id = $('input[name=record]').val();

    ajaxStatus.showStatus('Waiting <img src="custom/include/images/loader32.gif" align="absmiddle" width="32">');

    $.ajax({
        url: "index.php?module=Meetings&action=actAddToSession&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  
        {
            ss_id: ss_id,
            student_id: student_id,
            type: 'addCorp',
        },
        dataType: "json", 
        success: function(res){
            if (res.success == '1') {
                showSubPanel('contacts', null, true);   
            }else{
                alert(SUGAR.language.get('Meetings','LBL_ERROR'));
            }
            ajaxStatus.hideStatus();   
        },       
    });
}

//get record_id form url by Quyen.Cao
function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}

///Add PT

function set_return_parent(popup_reply_data, filter){
    var form_name = popup_reply_data.form_name;
    var name_to_value_array = popup_reply_data.name_to_value_array;
    for (var the_key in name_to_value_array) {
        var val = name_to_value_array[the_key].replace(/&amp;/gi, '&').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&#039;/gi, '\'').replace(/&quot;/gi, '"');
        switch (the_key)
        {   
            case 'id':
                ajaxStatus.showStatus('Waiting <img src="custom/include/images/loader32.gif" align="absmiddle" width="32">'); //Sugar alert
                $.ajax({
                    type: "POST",
                    url: "index.php?module=Contacts&action=savePTFromStudent&sugar_body_only=true",
                    data: {
                        id : val,
                        parent:"Contacts",
                        student_id :id_contact,
                    },
                    success:function(data){
                        ajaxStatus.hideStatus();
                        window.open('index.php?module=Meetings&action=DetailView&record='+val+'');
                    }
                });
                break;
        }   
    } 
}

function set_return_pt_lead(popup_reply_data, filter){
    var form_name = popup_reply_data.form_name;
    var name_to_value_array = popup_reply_data.name_to_value_array;
    for (var the_key in name_to_value_array) {
        var val = name_to_value_array[the_key].replace(/&amp;/gi, '&').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&#039;/gi, '\'').replace(/&quot;/gi, '"');
        switch (the_key)
        {   
            case 'id':
                ajaxStatus.showStatus('Waiting <img src="custom/include/images/loader32.gif" align="absmiddle" width="32">'); //Sugar alert
                $.ajax({
                    type: "POST",
                    url: "index.php?module=Leads&action=savePTFromLead&sugar_body_only=true",
                    data: {
                        id : val,
                        parent:"Leads",
                        lead_id  :id_lead,
                    },
                    success:function(data){
                        ajaxStatus.hideStatus();
                        window.open('index.php?module=Meetings&action=DetailView&record='+val+'');
                    }
                });
                break;
        }   
    } 
}


//Button In Demo - PT by Hoang Quyen
function addPTToWaitingClass(choose){
    var choose_pt = $(choose);
    id_contact = choose_pt.attr('student_id');
    parent = choose_pt.attr('parent');
    open_popup("J_Class",600, 400, "", true, false, {"call_back_function":"set_return_waiting","form_name":"DetailView","field_to_name_array":{"id":"class_id"}}, "single", true,'popupwaitingdefs');    
}
function addDemoToWaitingClass(choose){
    var choose_pt = $(choose);
    id_contact = choose_pt.attr('student_id');
    parent = choose_pt.attr('parent');
    open_popup("J_Class",600, 400, "", true, false, {"call_back_function":"set_return_waiting","form_name":"DetailView","field_to_name_array":{"id":"class_id"}}, "single", true,'popupwaitingdefs');    
}

function addToClassDemo(choose){
    var choose_pt = $(choose);
    dm_student_id = choose_pt.attr('student_id');
    dm_student_name = choose_pt.attr('student_name');
    dm_student_type = choose_pt.attr('parent');
    open_popup("J_Class",600, 400, "", true, false, {"call_back_function":"set_return_class","form_name":"DetailView","field_to_name_array":{"name":"name","id":"id","start_date":"start_date","end_date":"end_date","class_code":"class_code"}}, "single", true);    
}

function addContactToLessionDemo(choose){
    var choose_pt = $(choose);
    student_id_ptdemo = choose_pt.attr('student_id');
    open_popup("Meetings",600, 400, "", true, false, {"call_back_function":"set_return_demo_contact","form_name":"DetailView","field_to_name_array":{"id":"id"}}, "single", true, 'popupfordemodefs');    
}

function addContactToAnotherPT(choose){
    var choose_pt = $(choose);
    student_id_ptdemo = choose_pt.attr('student_id');
    open_popup("Meetings",600, 400, "", true, false, {"call_back_function":"set_return_pt_contact","form_name":"DetailView","field_to_name_array":{"id":"id"}}, "single", true,'popupforptdefs');   
}

function addLeadToLessionDemo(choose){
    var choose_pt = $(choose);
    student_id_ptdemo = choose_pt.attr('student_id');
    open_popup("Meetings",600, 400, "", true, false, {"call_back_function":"set_return_lead_demo","form_name":"DetailView","field_to_name_array":{"id":"id"}}, "single", true, 'popupfordemodefs');    
}

function addLeadToAnotherPT(choose){
    var choose_pt = $(choose);
    student_id_ptdemo = choose_pt.attr('student_id');
    open_popup("Meetings",600, 400, "", true, false, {"call_back_function":"set_return_lead_pt","form_name":"DetailView","field_to_name_array":{"id":"id"}}, "single", true,'popupforptdefs');   
}


function addContactOutstanding(choose){
    var choose_pt = $(choose);
    student_id_ptdemo = choose_pt.attr('student_id');
    student_name_ptdemo = choose_pt.attr('student_name');
    open_popup("J_Class",600, 400, "", true, false, {"call_back_function":"set_return_student","form_name":"DetailView","field_to_name_array":{"class_code":"class_code","name":"name","id":"id","start_date":"start_date","end_date":"end_date"}}, "single", true);    
}

//kiem tra admin
function check_lock_data(lock_date, str_date, is_admin){
    if(is_admin === '1'){
        return true;   
    }else{
        var now_date = new Date();
        //ngay truyen vao
        var input_date = SUGAR.util.DateUtils.parse(str_date,cal_date_format);
        var check_date = new Date(input_date.getFullYear(), input_date.getMonth()+1, parseInt(lock_date));

        //neu ngay hien tai dang thao tac sua Out > ngay lock cua thang do thi false
        if(now_date.getTime() > check_date.getTime())
            return false;
        //nguoc lai la true
        else return true;   
    }
}

//so sanh 2 ngay
function compare_date(str_date_1,str_date_2,method){
    var date_1   = SUGAR.util.DateUtils.parse(str_date_1,cal_date_format).getTime();
    var date_2     = SUGAR.util.DateUtils.parse(str_date_2,cal_date_format).getTime();
    switch (method)
    {
        case '>':
            if(date_1 > date_2) return true;
            else return false;
            break;
        case '>=':
            if(date_1 >= date_2) return true;
            else return false;
            break; 
        case '=':
            if(date_1 == date_2) return true;
            else return false;
            break;
        case '<':
            if(date_1 < date_2) return true;
            else return false;
            break;
        case '<=':
            if(date_1 <= date_2) return true;
            else return false;
            break;
    } 
}

function createEnrollment(this_tr){
    //Nếu là lead thì check xem đã convert chưa, chưa thì đòi convert trước khi enrollment
    var parent_type = this_tr.find(".parent").val();
    var parent_id = this_tr.find(".pt_id").val();

    if (parent_type == "Leads"){
        var contactId = "";
        $.ajax({
            url: "index.php?module=Meetings&action=ajaxMeeting&sugar_body_only=true",
            type: "POST",
            async: true,
            data:  {
                type        : 'ajaxCheckConvertedLead',
                lead_id     : parent_id,
            },
            dataType: "json",
            success: function(res){
                contactId = res.contact_id;
                if (contactId != ""){//Converted
                    window.open('index.php?module=J_Payment&action=EditView&return_module=J_Payment&return_action=DetailView&payment_type=Enrollment&student_id=' + contactId);
                }
                else{
                    window.open('index.php?module=Leads&action=ConvertLead&record=' + parent_id);
                }
            },
            error: function(xhr, textStatus, errorThrown){
                alert(textStatus);
                //                createEnrollment(this_tr);
            }
        });


    }
    else{
        window.open('index.php?module=J_Payment&action=EditView&return_module=J_Payment&return_action=DetailView&payment_type=Enrollment&student_id=' + parent_id);
    }
}
