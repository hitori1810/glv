$( document ).ajaxStop(function() {
    $("#btn_send_all").show();
});
$(document).ready(function() {
    $('#btn_select_class').click(function(){
        open_popup('J_Class', 600, 400, "", true, false, {"call_back_function":"set_class_return","form_name":"EditView","field_to_name_array":{"id":"class_id","name":"class_name"}}, "single", true);
    });

    $('#btn_clr_class').click(function(){
        $("#class_id").val('');
        $("#class_name").val('');
        $(".student_list").find("tbody").html("");
        $('#date_in_content').html('');
        //loadClassInfo();
        $('#td_class_info').html('');
    });

    $('#btn_generate').on("click",function(){
        var template_content = $("#template_content").val();
        $(".sms_content").each(function() {
            if ($(this).closest("tr").find(".custom_checkbox").prop("checked")){
                var student_name = $(this).closest("tr").attr("student_name_en");
                $(this).val(template_content.replace("$student_name",student_name));
            }
        });
        countAllContentCharater();
    });

    $("#btn_send_all").on("click",function(){
        if(checkValidateSendAll()){
            var pageButtons = $("input[name='btn_send']");
            var promises = [];
            $("input[name='btn_send']").each(function() {
                if ($(this).closest("tr").find(".custom_checkbox").prop("checked")){
                    var request = sendSMS($(this));
                    promises.push( request);
                }
            });
        }else{
            $("#btn_send_all").val("Re-send All");
        }
    });

    if ($("#class_id").val() != "" && $("#date_in_content").val() != "") load_student();

    // Setup date field
    Calendar.setup ({
        inputField : "date_in_content",
        daFormat : cal_date_format,
        button : "date_in_content_trigger",
        singleClick : true,
        dateStr : "",
        step : 1,
        weekNumbers:false
        }
    );

    $("#date_in_content").on("change",function(){
        if ($("#class_id").val() != "") load_student();
        load_template();
    });

    loadClassInfo();
    $("#class_id").on("change",function(){
        loadClassInfo();
    });
})

function set_class_return(popup_reply_data){
    var form_name = popup_reply_data.form_name;
    var name_to_value_array = popup_reply_data.name_to_value_array;
    for (var the_key in name_to_value_array) {
        if (the_key == 'toJSON') {
            continue;
        } else {
            var val = name_to_value_array[the_key].replace(/&amp;/gi, '&').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&#039;/gi, '\'').replace(/&quot;/gi, '"');
            switch (the_key)
            {
                case 'class_id':
                    $("#class_id").val(val);
                    break;
                case 'class_name':
                    $("#class_name").val(val);
                    break;
            }
        }
    }
    // Load SessionOptions
    loadSessionOption();
    if ($("#date_in_content").val() != "") load_student();
    load_template();
}

function loadSessionOption(){
        $.ajax({
        url : 'index.php?module=J_Class&action=sendSMS&sugar_body_only=1',
        type : 'post',
        data : {
            class_id : $("#class_id").val(),
            type : 'getSessionOptionAjax'
        },
        success:function(result){
            $('#date_in_content').html(result);
        }
    });
}

function load_template() {
    var class_id = $("#class_id").val();
    var url = "index.php?module=Administration&action=smsProvider&sugar_body_only=1&option=template";
    $.get(url, { id: $("#template").val(), mod: 'J_Class', rec: class_id, }, function(data) {       
        var date_in_content = $("#date_in_content option:selected").attr('date');
        if(date_in_content == "") date_in_content = $("#date_in_content option:eq(0)").attr('date');
        $("#template_content").val(data.replace("$date_in_content",date_in_content));
    });
}

// Load session id & all student of this session
function load_student(){
    ajaxStatus.showStatus('Loading student list...');
    $.ajax({
        url: "index.php?module=J_Class&action=handleAjaxJclass&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  {
            type            : 'ajaxGetStudentList',
            class_id        : $("#class_id").val(),
            session_id      : $("#date_in_content").val(),
        },
        dataType: "json",
        success: function(res){
            ajaxStatus.hideStatus();
            $("#tbl_student tbody").html(res.content);
            $("#session_id").val(res.session_id);
            $("#session_description").val(res.session_description);
            if(res.content != "") countAllContentCharater();
            $(".checkall_custom_checkbox").prop("checked",true);
            handleCheckBox($(".checkall_custom_checkbox"));
        },
        error: function(xhr, textStatus, errorThrown){
            load_student();
        }
    });
}

function showRecentSMS(){
    var current_user = $("#current_user_id").val();
    open_popup('C_SMS', 600, 400, "&assigned_user_id_advanced="+current_user+"&lvso=DESC&C_SMS2_C_SMS_ORDER_BY=date_entered", true, false, {"call_back_function":"set_sms_return","form_name":"EditView","field_to_name_array":{"id":"id","name":"name"}}, "single", true);
}
function checkValidateSendAll(){
    var count_student   = $('input.custom_checkbox:checked').length;
    var empty_content   = 0 ;
    var empty_phone     = 0 ;
    $("input.custom_checkbox:checked").each(function(){
        var content = $(this).closest('tr').find(".sms_content").val();
        if(content == '')
            empty_content++;
        var sdt = $(this).closest('tr').attr("student_phone");
        if(sdt == ''){
            empty_phone++;
            alertify.error(SUGAR.language.get('J_Class','LBL_NO_PHONE_NUMBER').replace("student_name"), $(this).closest('tr').find(".student_name").text());
        }

    });
    if(empty_content > 0){
        alertify.error(SUGAR.language.get('J_Class','LBL_EMPTY_CONTENT'));
        return false;
    }
    return true;

}
function checkContent(this_btn, action){
    if(this_btn.closest('tr').find(".sms_content").val() == ""){
        alertify.error(SUGAR.language.get('J_Class','LBL_EMPTY_CONTENT'));
    }
    else{
        if(this_btn.closest('tr').attr("student_phone") == ""){
            alertify.error(SUGAR.language.get('J_Class','LBL_NO_PHONE_NUMBER').replace("student_name"),this_btn.closest('tr').find(".student_name").text());
        }
        else{
            sendSMS(this_btn);
        }
    }
}

function sendSMS(this_btn){
    this_btn.closest('tr').find(".loading_icon").show();
    this_btn.hide();
    $("#btn_send_all").hide();
    var ptype   = this_btn.attr('p_type');
    var msg     = this_btn.closest('tr').find(".sms_content").val();
    var num     = this_btn.closest('tr').attr("student_phone");
    var pname   = this_btn.closest('tr').find(".student_name").text();
    var pid     = this_btn.closest('tr').attr("student_id");
    var date_in_content = $("#date_in_content option:eq(1)").attr('date');
    
    $.ajax({
        url: "index.php?module=J_Class&action=handleAjaxJclass&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  {
            type                : 'sendSMS',
            ptype               : ptype,
            sms_msg             : msg,
            num                 : num,
            pname               : pname,
            pid                 : pid,
            template_id         : $("#template").val(),
            date_in_content     : date_in_content,
        },
        dataType: "json",
        success: function(res){
            if(res.status == 'RECEIVED'){
                alertify.success(SUGAR.language.get('J_Class','LBL_SMS_SENT') + pname + "!");
                this_btn.closest('tr').find(".loading_icon").hide();
                this_btn.closest('td').append( "<span style='color: red;'> Sent!</span>" );
                this_btn.remove();
            }else{
                alertify.error(SUGAR.language.get('J_Class','LBL_SMS_NOT_SENT') + pname + "!");
                this_btn.closest('tr').find(".loading_icon").hide();
                this_btn.val("Re-send");
                this_btn.css("background-color","orange");
                this_btn.show();
            }
        },
    });
}

function saveAttendance(_this){
    var checked = 0;
    var this_tr = _this.closest('tr');
    if (this_tr.find(".chk_attendance").prop("checked")) checked = 1;

    var checked_homework = 0;
    if (this_tr.find(".chk_homework").prop("checked")) checked_homework = 1;

    //Check student in session
    var inClass = true;
    if(this_tr.hasClass("tr_not_in_class")){
        var inClass = false;
    }

    if(checked){
        this_tr.find(".chk_absent_for_hour").prop('disabled',false).removeClass('readonly');
        if(_this.hasClass('chk_attendance')){
             this_tr.find("select.chk_absent_for_hour option:last").prop("selected", "selected");
             this_tr.find(".chk_absent_for_hour").effect("highlight", {color: '#ff9933'}, 1000);
        }
    }else{
        this_tr.find(".chk_absent_for_hour").prop('disabled',true).addClass('readonly').val('0').effect("highlight", {color: '#ff9933'}, 1000);
    }
    $.ajax({
        url: "index.php?module=J_Class&action=handleAjaxJclass&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  {
            type          : 'ajaxSaveAttendance',
            session_id    : $("#session_id").val(),
            student_id    : this_tr.attr("student_id"),
            attended      : checked,
            absent_for_hour: this_tr.find(".chk_absent_for_hour").val(),
            homework      : checked_homework,
            in_class      : inClass,
            description   : this_tr.find(".description").val(),
            attend_id     : this_tr.attr("attend_id")
        },
        dataType: "json",
        success: function(res){
        },
    });
}

function saveSessionDescription(){
    if ($("#session_id").val() == "") return false;
    $.ajax({
        url: "index.php?module=J_Class&action=handleAjaxJclass&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  {
            type            : 'ajaxSaveSessionDescription',
            session_id      : $("#session_id").val(),
            description     : $("#session_description").val(),
        },
        dataType: "json",
        success: function(res){
        },
    });
}

function expandContent(this_btn){
    this_btn.closest("tr").find(".sms_content").autoGrow();
    this_btn.hide();
    this_btn.closest("tr").find(".btn_collapse").show();
}

function collapseContent(this_btn){
    this_btn.closest("tr").find(".sms_content").css("height","");
    this_btn.hide();
    this_btn.closest("tr").find(".btn_expand").show();
}

function countAllContentCharater(){
    $(".sms_content").each(function() {
        countSms($(this));
    });

}

function loadClassInfo(){

    if ($("#class_id").val() == ""){
        $("#td_class_info").hmtl("");
    }
    else{
        $.ajax({
            url: "index.php?module=J_Class&action=handleAjaxJclass&sugar_body_only=true",
            type: "POST",
            async: true,
            data:  {
                type        : 'ajaxLoadClassInfo',
                class_id  : $("#class_id").val(),
            },
            dataType: "json",
            success: function(res){
                $("#td_class_info").html(res.html);
            },
            error: function(xhr, textStatus, errorThrown){
            }
        });
    }
}
