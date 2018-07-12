/* javascript */
/*
* This script is being used by
* ./modules/Administration/izeno_smsPhone/smsPhone.js.php
* ./modules/Administration/izeno_smsPhone/sms_processor.php
* ./modules/Administration/smsPhone.php
* ./modules/Administration/smsProvider.php
*
* */

$(document).ready(function() {

    /* for some reason, the jQ click() shorthand suddenly didn't work so i had to convert this into a crappy function instead.. duh!  */
    move_to = function (direction) {

        $("input[type=button]#save").attr("disabled", false);
        if (direction == 'right') {
            var items = '';
            $.each($("div#original div.item_sel"), function() {
                items += "<div class='item' id='" + $(this).attr('id') + "' onclick='select(this);' title='Click to select or deselect'>" + $(this).text() + "</div>";
            });
            $("div#selection").html( $("div#selection").html() + items );
            $("div#original div.item_sel").remove();
        } else {
            var items = '';
            $.each($("div#selection div.item_sel"), function() {
                items += "<div class='item' id='" + $(this).attr('id') + "' onclick='select(this);' title='Click to select or deselect'>" + $(this).text() + "</div>";
            });
            $("div#original").html( $("div#original").html() + items );
            $("div#selection div.item_sel").remove();
        }
    }

    save_customization = function() {

        if ($("div#selection div").length==0) {
            if (!confirm("Click OK to clear phone field customization for " + $("select#module").val())) {
                return false;
            }
        }

        var fields = Array();
        $("input[type=button]#save").attr("disabled", true);
        $.each($("div#selection div"), function() {
            fields.push($(this).attr('id'));
        });
        $("#field_panels").html("Processing... Please wait!");
        $.get("./index.php?module=Administration&action=smsPhone&sugar_body_only=1", { m:$("select#module").val(), option:2, 'fields[]':fields  }, function(data) {
            $("#field_panels").html(data);
        });
    }

    check_macro_requirements = function () {
        var str = jQuery.trim(document.getElementById('macro_string').value);
        if (document.getElementById('macro_field')==null || str=='') {
            alert("You need to select a field and enter an SMS macro string."); return false;
        }

        if (str.split(" ").length > 1) {
            alert("Your SMS macro string should not contain any spaces."); return false;
        }

        return true;
    }

    rem_sms_macro = function (m) {
        if (confirm("Click OK to remove SMS macro for " + m +".")) {
            $("input[type=hidden]#macro_to_remove").val(m);
            document.macro_remove.submit();
            return true;
        } else return false;
    }

    load_fields = function(x) {
        if(x=='panel') {
            $("div#field_panels").html("");
            if ($("select#module").val() != '') {
                $("div#field_panels").html("Processing... Please wait!");
                $.get("./index.php?module=Administration&action=smsPhone&sugar_body_only=1", { m:$("select#module").val(), option:1  }, function(data) {
                    $("div#field_panels").html(data);
                });
                $("input[type=button]#save").attr("disabled", false);
            } else {
                $("input[type=button]#save").attr("disabled", true);
            }
        } else {
            if ($("select#module").val() != '') {
                $("td#field_cell").html("Loading...");
                $.get("./index.php?module=Administration&action=smsProvider&sugar_body_only=1", { m:$("select#mod").val(), option:'load_mod_fields' }, function(data) {
                    $("td#field_cell").html(data);
                });
            }
        }
    };

    select = function(obj) {
        var c;
        if ($("#"+obj.id).attr("class") == "item") {
            c = "item_sel";
        } else {
            c = "item";
        }
        $("#"+obj.id).removeClass();
        $("#"+obj.id).addClass(c);
    };

    check_sms_len = function (len) {
        var length, messages, per_message, remaining;
        if(typeof maximum_sms == 'undefined' || maximum_sms == '')
            maximum_sms = 3;
        length = $("textarea#sms_msg").val().length;
        per_message = 160;
        if (length > per_message) {
            per_message = 153;
        }
        messages = Math.ceil(length / per_message);
        remaining = (per_message * messages) - length;
        if(remaining == 0 && messages == 0){
            remaining = per_message;
        }
        messages_str = 'Messages: '+messages+'/'+maximum_sms+' ('+remaining+' remaining).';
        if(messages > maximum_sms){
            messages_str = 'Messages: <span style="color:red">'+messages+'/'+maximum_sms+' Limited messages, SMS will be failed!</span>';
            $("input[type=button]#send").prop('disabled', true);
        }else $("input[type=button]#send").prop('disabled', false);

        $("span#sms_len_notifier").html(messages_str);
    };

    show_tip = function() {
        var tip = "<p>Please key in the full number without any separator in the following format. Do not need enter the country code for Vietnam." +
        "<div align='center'>&lt;Country Code&gt;&lt;Phone Number&gt;</div></p>" +
        "<p>Example:</p><table><tr><td>Singapore +65 </td><td>&nbsp;&nbsp;<strong>6582994488</strong></td></tr>" +
        "<tr><td>USA +1 </td><td>&nbsp;&nbsp;<strong>17801234567</strong></td></tr>" +
        "</table>";

        $('div#smstip').html(tip).fadeIn("fast");
    };

    hide_tip = function () {
        $('div#smstip').html('').fadeOut("fast");
    };

    save_settings = function () {
        var url = "./index.php?module=Administration&action=smsProvider&sugar_body_only=1&option=save&g=" + $("select#sms_gateway").val();
        $("div#response_text").html("Saving... Please wait!");
        $.post(url, $("form#frm_settings").serialize(), function(data) {
            $("div#response_text").html(data);
        });
    };

    test_connection = function () {
        var url = "./index.php?module=Administration&action=smsProvider&sugar_body_only=1&option=test_conn";
        $("div#response_text").html("Establishing connection to the server... Please wait!");
        $.post(url, $("form#frm_settings").serialize(), function(data) {
            $("div#response_text").html(data);
        });


    };

    send_sms = function () {

        var send_to_multi = $("input[type=hidden]#send_to_multi").val();
        var ptype = $("input[type=hidden]#ptype").val();
        var url = "./index.php?module=Administration&action=smsProvider&sugar_body_only=1&option=send";
        var msg = $("textarea#sms_msg").val();
        var num = $("input[type=text]#number").val();
        var pname = $("input[type=hidden]#pname").val();

        if(send_to_multi == 1) {
            // retrieve parent ids
            var x = document.getElementsByName('pids');
            var arr = new Array();
            for(var i=0; i<document.getElementsByName('pids').length;  i++) {
                arr.push(x[i].value);
            }
            var pid = arr.toString();
            if ($("div.recipient").length == 0) {
                alert("You must have at least 1 recipient.");
                return false;
            }
        } else {
            var pid = $("input[type=hidden]#pid").val();
        }

        $("div#editor_style").html("Sending... Please wait!").css("padding", "30px").css("font-weight","bold");

        $.post(url, { num:num, sms_msg:msg, send_to_multi:send_to_multi, pid:pid, ptype:ptype, pname:pname}, function(data) {
            if ((jQuery.trim(data)=="SENT") && send_to_multi==0) {
                var res = "Message sent. Click RELOAD button to refresh your window.<br>";
                res += "<input type='button' class='button' value='RELOAD' onclick='window.location.reload();'>";
                $("div#editor_style").html(res).css("padding", "30px").css("font-weight","bold");
            } else {
                $("div#editor_style").html(data).css("padding", "30px").css("font-weight","bold");
            }

        });

    };

    resend_sms = function() {
        var msg = $("textarea#sms_msg").val();
        var num = $("input[type=text]#number").val();
        var url = "./index.php?module=Administration&action=smsProvider&sugar_body_only=1&option=resend";
        var rec = $("input[type=hidden]#pid").val();

        $("div#editor_style").html("Resending... Please wait!").css("padding", "30px").css("font-weight","bold");

        $.post(url, { num:num, sms_msg:msg, rec:rec }, function(data) {
            if ((jQuery.trim(data)=="SENT")) {
                var res = "Message sent. Click RELOAD button to refresh your window.<br>";
                res += "<input type='button' class='button' value='RELOAD' onclick='window.location.reload();'>";
                $("div#editor_style").html(res).css("padding", "30px").css("font-weight","bold");
            } else {
                $("div#editor_style").html(data).css("padding", "30px").css("font-weight","bold");
            }
        });
    };

    load_sms_for_resending = function () {
        var url = "./index.php?module=Administration&action=smsProvider&sugar_body_only=1&option=editor&rec=" + $("input[type=hidden][name=record]").val();
        window.top.$.openPopupLayer({
            name: "div_sms_sender",
            width: 400,
            url: url
        });
    }

    open_url = function (url) {
        if (url.substr(0,4) != "http")
            url = "http://" + url;
        var w = window.open(url,"win","heigh=400,width=700,top=0,left=0,scrollbars=yes,resizable=yes");
        w.focus();
        return true;
    };

    function inform_closing() {
        $('div#editor_style').html('You removed all recipients. <br>Press ESC key or click the gray area to close this message.').css('padding', '30px').css('font-weight','bold');
    }

    // the scripts below are being used on the Detail view for sending sms messages
});

function openAjaxPopup(num,ptype,pid,pname) {
    var url = "./index.php?module=Administration&action=smsProvider&sugar_body_only=1&option=editor&num=" + num + "&ptype=" + ptype + "&pid=" + pid + "&pname=" + pname;
    window.top.$.openPopupLayer({
        name: "div_sms_sender",
        width: 400,
        url: url
    });
}

function openAjaxPopupForMulti(arr,ptype,pid, template) {
    var temp = new Array();
    if(pid != ''){
        temp = pid.split(",");
    }

    if(arr != ''){
        var obj = document.getElementsByName(arr);
        var i;
        for (i=0;i<obj.length;i++) {
            if (obj.item(i).checked)
                temp.push(obj.item(i).value);
        }
    }
    template = typeof template !== 'undefined' ? template : '';
    if(template == '')
        template = ptype;

    var url = "./index.php?module=Administration&action=smsProvider&sugar_body_only=1&option=editor&num=multi&ptype=" + ptype + "&pid=" + temp.toString() + "&template=" + template;

    window.top.$.openPopupLayer({
        name: "div_sms_sender",
        width: 500,
        url: url
    });

}

function load_message(id) {
    var url = "index.php?module=Administration&action=smsProvider&sugar_body_only=1&option=template";
    $.get(url, { id: id, mod: $_GET('module'), rec: $_GET('record'), }, function(data) {
        $("textarea#sms_msg").val(data);
        check_sms_len(data.length);
    });


}
function $_GET(param) {
    var vars = {};
    window.location.href.replace(
        /[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
        function( m, key, value ) { // callback
            vars[key] = value !== undefined ? value : '';
        }
    );

    if ( param ) {
        return vars[param] ? vars[param] : null;
    }
    return vars;
}

function showRecentSMS(){
    var current_user = $("#current_user").val();
    open_popup('C_SMS', 600, 400, "&assigned_user_id_advanced="+current_user+"&lvso=DESC&C_SMS2_C_SMS_ORDER_BY=date_entered", true, false, {"call_back_function":"set_sms_return","form_name":"EditView","field_to_name_array":{"id":"id","name":"name"}}, "single", true);
}
