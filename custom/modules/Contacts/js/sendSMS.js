$(document).ready(function() {
    var options = {
        success: showResponse
    };
    $('#submitFileForm').ajaxForm(options);
    $('#submit_file').on('click', function(){
        ajaxStatus.showStatus("Uploading...");
        $('#txt_receiver').clearAllTags();
    });

    $("#template").on("change",function(){
        var content = $("#template option:selected").attr("content");
        $("#txt_content").val(content);
        countSms($("#txt_content"));
    });

    $("#send_sms").on("click",function(){
        sendSMS();
    });
    countSms($("#txt_content"));
    $('#txt_receiver').tagThis({
        defaultText         : 'type to add',
        width               : '550px',
        height              : '100px',
        noDuplicates        : true
    });
    $('.simple-clear-all-button').on('click', function(){
        $('#txt_receiver').clearAllTags();
    });
});

function showResponse(responseText, statusText, xhr, form){
    var json =  jQuery.parseJSON(responseText);
    ajaxStatus.hideStatus();
    if(json.success == "1"){
        //        $("#txt_receiver").val(json.receiversText);
        $("#receiver_json").val(json.receiversJson);
        if(json.receiversJson != '' && json.receiversJson != null ){
            objs = JSON.parse(json.receiversJson);
            $.each(objs, function(phone, name) {
                var tagData = {
                    text    : name+' '+phone,
                    id      : phone
                }
                $('#txt_receiver').addTag(tagData);
            });
        }
        if(json.countRecipients > 0)
            alertify.alert(json.errorLabel);
    }
    else{
        alertify.error(SUGAR.language.get('Contacts',json.errorLabel));
    }
}

function checkContent(){
    if($("#txt_content").val() == ""){
        alertify.error(SUGAR.language.get('Contacts','LBL_EMPTY_CONTENT'));
        return false;
    }
    else{
        var phones = $('#txt_receiver').data('tags');
        if(phones.length <= 0){
            alertify.error(SUGAR.language.get('Contacts','LBL_NO_PHONE_NUMBER'));
            return false;
        }
        else return true;
    }
}

function sendSMS(){
    if(checkContent()){
        var phones = $('#txt_receiver').data('tags');
        debugger;
        var msg    = $("#txt_content").val();
        alertify.set({ labels: {
            ok     : "Send SMS",
            cancel : "Back"
        } });
        alertify.confirm('Are you sure you want to send <b>'+msg+'</b> to '+phones.length+' recipients ?', function (e) {
            if (e) {
                ajaxStatus.showStatus("Sending...");
                var count_success   = 0;
                var count_fail      = 0;
                var send_to_multi   = 0;
                var count           = 0;
                var ptype           = "Contacts";
                var url             = "./index.php?module=Administration&action=smsProvider&sugar_body_only=1&option=send";
                var pid             = $("#current_user_id").val();
                var template_id     = $("#template").val();
                var team_id         = $("#brand_name").val();
                $("#count_failed").text(count_fail);
                $("#count_received").text(count_success);
                $("#sending_result tbody").html("");
                $("#count_total").text(phones.length);
                $.each(phones, function (num, value) {
                    debugger;
                    var result = "RECEIVED";
                    var name   = get_text_between('<','>',value.text).trim();
                    var split  = value.text.split(">");
                    var num    = split[split.length - 1];

                    var sms_msg= msg;
                    if(name == 'none' || name == '-none-') name = '';
                    if(name != null && name != '')
                        sms_msg = sms_msg.replace("$student_name", name).replace("$name", name).replace("$full_name", name);
                    else
                        sms_msg = sms_msg.replace("$student_name", '').replace("$name", '').replace("$full_name", '');

                    if (typeof num !== "undefined" && num != '')
                        $.post(url,{num:num, sms_msg:sms_msg, send_to_multi:0, pid:pid, ptype:"Users", pname:"", template_id: template_id, team_id: team_id}, function(data) {
                            if(data.indexOf("Failed") < 0){
                                count++;
                                count_success++;
                                $("#count_received").text(count_success);
                            }else{
                                count++;
                                count_fail++;
                                $("#count_failed").text(count_fail);
                                result = "FAILED";
                            }
                            $('#sending_result > tbody:last-child').append("<tr><td><pre>"+ name +' '+ num + "</pre></td><td><pre>"+ result +"</pre></td></tr>");
                            if (count >= phones.length) ajaxStatus.hideStatus();
                        });
                });

            }else{
                return ;
            }
        });
    }
}

function showRecentSMS(){
    var current_user = $("#current_user_id").val();
    open_popup('C_SMS', 600, 400, "&assigned_user_id_advanced="+current_user+"&lvso=DESC&C_SMS2_C_SMS_ORDER_BY=date_entered", true, false, {"call_back_function":"set_sms_return","form_name":"EditView","field_to_name_array":{"id":"id","name":"name"}}, "single", true);
}
function get_text_between(start, end, test_str){
    var start_pos = test_str.indexOf(start) + 1;
    var end_pos = test_str.indexOf(end,start_pos);
    return test_str.substring(start_pos,end_pos)
}