$(document).ready(function () {
    $('#list_subpanel_sub_demo_result table:eq(1)').hide();
    //set multifield
    $('#diagnosis_list_demo').multifield({
        section: '.demo_template',
        addTo   :   '#tbodyDemo', // Append new section to position
        btnAdd:'#btnAddRowDemo',
        btnRemove:'.btn_delete',
        min: 0,
    });
    $('.click_choose').live("click", function(){
        $(this).closest('tr').find('.subnav').parent('.sugar_action_button').children('ul').toggle();
        return false;
    });
    $('.attended').live('change',function(){
        var tr_template=$(this).closest('tr');
        if(tr_template.find('.attended').prop('checked') == true){
            tr_template.find('.check_attended').val(1);
        }else{
            tr_template.find('.check_attended').val(0);
        }
        saveAttended(tr_template.find('input'));
    });
    $(".ec_note").live('blur',function(){
        ajaxStatus.showStatus('Saving ...');
        var tr_template=$(this).closest('tr').find('input, textarea');
        $.ajax({
            type: "POST",
            url: "index.php?module=Meetings&action=saveResult&sugar_body_only=true",
            data:tr_template.serialize()+'&type=ajaxUpdateECNoteDemo',
            dataType: "json",
            success:function(data){
                ajaxStatus.hideStatus();
            }
        });
        var tr_template=$(this).closest('tr');
        tr_template.find('.attended').prop('checked',true);
        tr_template.find('.check_attended').val(1);
        saveAttended(tr_template.find('input'));
    });
});


function clickChooseStudentDemo(){
    var module = $('#parent_type').val();
    open_popup(module,600, 400, "", true, false, {"call_back_function":"set_return_parent_demo","form_name":"DetailView","field_to_name_array":{"name":"name","id":"id","birthdate":"birthdate","gender":"gender","phone_mobile":"phone_mobile","email1":"email1","status":"status","guardian_name":"guardian_name","c_contacts_contacts_1_name":"c_contacts_contacts_1_name","contact_status":"contact_status","assigned_user_name":"assigned_user_name","lead_source":"lead_source"}}, "single", true);
}
//function set_return of open popup
function set_return_parent_demo(popup_reply_data, filter) {
    var form_name = popup_reply_data.form_name;
    var name_to_value_array = popup_reply_data.name_to_value_array;
    var check = checkStudentLeadInDemo(name_to_value_array['id'],$("input[name='record']").val(),'Demo');
    if(check){
        $("#btnAddRowDemo").trigger( "click" );
        var student_type =  $('#parent_type').val();
        var _lastrow = $('#tbodyDemo tr:last');
        _lastrow.find('.attended').prop('checked',false);
        _lastrow.find('.check_attended').val(0);
        _lastrow.find('.count_res').text(_lastrow.index());
        for (var the_key in name_to_value_array) {
            var val = name_to_value_array[the_key].replace(/&amp;/gi, '&').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&#039;/gi, '\'').replace(/&quot;/gi, '"');
            switch (the_key) {
                case 'name':
                    _lastrow.find('.demo_name').text(val);
                    break;
                case 'birthdate':
                    _lastrow.find('.birthdate').text(val);
                    break;
                case 'gender':
                    _lastrow.find('.gender').text(val);
                    break;
                case 'phone_mobile':
                    _lastrow.find('.phone_mobile').text(val);
                    break;
                case 'email1':
                    _lastrow.find('.email').text(val);
                    break;
                case 'school_name':
                    _lastrow.find('.school_name').text(val);
                    break;
                case 'status':
                    if(student_type=="Leads"){
                        _lastrow.find('.status').text(val);
                    }
                    break;
                case 'contact_status':
                    if(student_type=="Contacts"){
                        _lastrow.find('.status').text(val);
                    }
                    break;
                case 'c_contacts_contacts_1_name':
                case 'guardian_name':
                    if(val != "undefined")
                        _lastrow.find('.parent').text(val);
                    break;
                case 'phone_mobile':
                    _lastrow.find('.phone_mobile').text(val);
                    break;
                case 'assigned_user_name':
                    _lastrow.find('.assigned_user_name').text(val);
                    break;
                case 'lead_source':
                    _lastrow.find('.lead_source').text(val);
                    break;
                case 'id':
                    _lastrow.find('.demo_id').val(val);
                    _lastrow.find('.parent').val(student_type);
                    _lastrow.find('.demo_name').attr('href', 'index.php?module='+student_type+'&action=DetailView&record='+val);
                    _lastrow.find('.waiting_class').attr('student_id', val);
                    _lastrow.find('.waiting_class').attr('parent', student_type);
                    _lastrow.find('.waiting_class').attr('onclick', 'addDemoToWaitingClass(this)');
                    _lastrow.find('.add_outstading').attr('student_id', val);
                    _lastrow.find('.lession_demo').attr('student_id', val);
                    _lastrow.find('.another_pt').attr('student_id', val);
                    if(student_type=="Contacts"){
                        _lastrow.find('.add_outstading').attr('onclick', 'addContactOutstanding(this)');
                        _lastrow.find('.lession_demo').attr('onclick', 'addContactToLessionDemo(this)');
                        _lastrow.find('.another_pt').attr('onclick', 'addContactToAnotherPT(this)');

                    }else{
                        _lastrow.find('.lession_demo').attr('onclick', 'addLeadToLessionDemo(this)');
                        _lastrow.find('.another_pt').attr('onclick', 'addLeadToAnotherPT(this)');
                        _lastrow.find('.add_outstading').attr('onclick', 'window.open(\'index.php?module=Leads&action=ConvertLead&record='+val+'\',\'_blank\');');
                    }
                    break;
            }
        }
        saveDemo(_lastrow.find('input, select, textarea'));
    }else{
        alertify.error(SUGAR.language.get('Meetings','LBL_EXIST_STUDENT'));
    }
}

function saveDemo(tr_template){
    ajaxStatus.showStatus('Saving...');
    $.ajax({
        type: "POST",
        url: "index.php?module=Meetings&action=saveResult&sugar_body_only=true",
        data: tr_template.serialize()+'&type=ajaxSaveDemoResult'+'&meeting_id='+$("input[name='record']").val(),
        dataType: "json",
        success:function(data){
            tr_template.closest('tr').find('.id_of_result,  .custom_checkbox').val(data.id_result);
            ajaxStatus.hideStatus();
        }
    });
}

function saveAttended(demo_template){
    ajaxStatus.showStatus('Saving...');
    $.ajax({
        type: "POST",
        url: "index.php?module=Meetings&action=saveResult&sugar_body_only=true",
        data: demo_template.serialize()+'&type=ajaxSaveAttended',
        success:function(data){
            ajaxStatus.hideStatus();
        }
    });
}

function click_choose_action(this_span, e){
    e.stopPropagation();
    $('.ab').closest('.fancymenu').find('.subnav').hide();
    $(this_span).closest('.fancymenu').find('.subnav').toggle();
    return false;
}

function checkStudentLeadInDemo(id,meeting_id,type){
    var check = false;
    $.ajax({
        type: "POST",
        async:false,
        url: "index.php?module=Meetings&action=saveResult&sugar_body_only=true",
        data:{
            student_id : id,
            meeting_id : meeting_id,
            meeting_type :  type,
            type : 'checkStudentLeadMeeting'
        },
        dataType: "json",
        success:function(data){
            if(data.id_result) {
                check = true;
            }else{
                check = false;
            }

        }
    });
    return check;
}

function beforeRemoveSection(section) {
    ajaxStatus.showStatus('Deleting...');
    var result = false;
    $.ajax({
        type: "POST",
        url: "index.php?module=Meetings&action=saveResult&sugar_body_only=true",
        async: false,
        data:{
            type: 'deletePTResult',
            result_id: $(section).find('.id_of_result').val(),
        },
        dataType: "json",
        success:function(data){
            if(data.success == "1"){
                result = true;
                ajaxStatus.hideStatus();
            }else{
                ajaxStatus.hideStatus();
                alertify.alert('Đã có lỗi xảy ra, vui lòng thử lại sau!');
            }
        }
    });
    return result;
}