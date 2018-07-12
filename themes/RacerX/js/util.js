function handleCheckBox(cell){
    var cell_tbl = cell.closest('table');

    var module_name = cell.attr('module_name');
    var count     = 0;
    var ckb     = 0;
    //Make string selected - User str.split(",") to explorer
    var str  = '';
    cell_tbl.find('input.custom_checkbox').each(function(){
        if($(this).val() != ''){
            if(cell.attr('class') == 'checkall_custom_checkbox' && $(this).closest('tr').attr('class') != 'tr_not_in_class'){
                $(this).prop('checked',cell.is(':checked'));
            }
            if($(this).is(":checked")){
                ckb++;
                if(ckb == 1)
                    str = str + $(this).val();
                else
                    str = str + ',' + $(this).val();
            }
            count++;
        }
    });
    if(ckb != count)
        cell_tbl.find('input.checkall_custom_checkbox').prop('checked',false);
    else
        cell_tbl.find('input.checkall_custom_checkbox').prop('checked',true);

    if(ckb> 0)
        cell_tbl.find(".selectedTopSupanel").html('<input type="hidden" id="'+module_name+'_checked_str" value="'+str+'"><p style="color:red; padding:5px;">'+SUGAR.language.get('app_strings','LBL_SELECTED')+': '+ckb+'</p>');
    else
        cell_tbl.find(".selectedTopSupanel").html('');
}

function checkDataLockDate(id_field_date, has_trigger, has_clear){
    var check_date_str = $('#'+id_field_date).val();
    if (has_trigger === undefined)
        has_trigger = true;

    if (has_clear === undefined)
        has_clear = true;

    if(sugar_config_lock_info == true || sugar_config_lock_info == '1'){
        if(except_lock_for_user_name != ''){
            var count_match = 0;
            var val_user_name = except_lock_for_user_name.split(',');
            $.each(val_user_name, function( index, user_name ) {
                if(user_name == current_user_name)
                    count_match++;
            });
            if(count_match > 0) return true;
        }
        if(is_admin == '1' || is_admin == true){
            return true;
        }else{
            if(check_date_str != ''){
                var input_date = SUGAR.util.DateUtils.parse(check_date_str,cal_date_format);
                var now_date = new Date();
                var splitted = sugar_config_lock_date.split("-");
                var check_date = new Date(input_date.getFullYear(), input_date.getMonth()+1, parseInt(splitted[0]), parseInt(splitted[1]));
                var suggestion = SUGAR.util.DateUtils.formatDate(  new Date(now_date.getFullYear(), now_date.getMonth(), 1) );
                //neu ngay hien tai dang thao tac sua Out > ngay lock cua thang do thi false
                if(now_date.getTime() > check_date.getTime()){
                    if(has_clear){
                        alertify.error('Date: '+check_date_str +' has been prevented by data-lock funtion. <br> Date should be greater than '+suggestion, 10000);
                        //$('#'+id_field_date).val(SUGAR.util.DateUtils.formatDate(now_date));
                        $('#'+id_field_date).val('').effect("highlight", {color: 'red'}, 2000);
                    }

                    if(has_trigger)
                        $('#'+id_field_date).trigger('change');
                    return false;
                }
                else return true;
            }
        }
    }
    else return true;
}

// Util function to fix the callOnChangeListers function
SUGAR.util.callOnChangeListers = function(field) {
    var listeners = YAHOO.util.Event.getListeners(field, 'change');
    if (listeners != null) {
        for (var i = 0; i < listeners.length; i++) {
            var l = listeners[i];
            l.fn.call(l.scope ? l.scope : this, l.obj);
        }
    }

    // Trigger jquery change event
    $(field).trigger('change');
}

/*
*    Spinner.js
*    Author: Hieu Nguyen
*    Date: 2015-06-10
*    Purpose: To handle the loading spinner effect
*    Requires: Spinner.css
*   Modify : Trung Nguyen
*/

function Spinner () {

    this.init = function() {
        if($('#spinner')[0] == null) {
            var html = '<div id="spinner"><div id="spinner-background"></div><div id="slide-wrapper"><div id="slide"><div id="loading-container"><div id="loading"></div><div id="message"></div></div></div><div id="align"></div></div></div>';
            $('body').append(html);
        }
    }

    this.init();

    this.show = function(message) {
        $('#spinner').find('#message').html(message);
        $('#spinner').show();
    }

    this.hide = function() {
        $('#spinner').hide();
    }

    this.changeMessage = function(message) {
        $('#spinner').find('#message').html(message);
    }

    this.remove = function() {
        $('#spinner').remove();
    }
}

/*
* Customize Subpanel as tabs
* Author: Hieu Nguyen
* Date: 08-01-2013
*/

$(document).ready(function(){

    // Only do when subpanel tabs is enabled
    if($('#groupTabs')[0] != null){

        var customStyle = '<style type="text/css">#groupTabs li{display: block; float: left; margin-bottom: 8px;}</style>';
        $('head').append(customStyle);
        function hideAllSubpanel(){
            $('#subpanel_list li').each(function(){
                $(this).hide();
            });
        }

        function showAllSubpanel(){
            $('#subpanel_list li').each(function(){
                $(this).show();
            });
        }

        function markActive(tab){
            $('#groupTabs li a.current').removeClass('current');
            $(tab).addClass('current');
        }

        // Hide all subpanel on load
        hideAllSubpanel();
        // Hide all default tabs
        $('#groupTabs li').hide();
        $('#groupTabs li a.current').removeClass('current');
        $('#groupTabs li:first').show();

        // Generate each subpanel as a tab
        $('#subpanel_list li').each(function(){
            var moduleName = $(this).find('h3').text();
            var subpanelID = $(this).attr('id');
            $('#groupTabs').append('<li><a data-subpanel="'+subpanelID+'" href="">'+moduleName+'</a></li>');
        });

        // Onclick on a tab
        $('#groupTabs li a').click(function(){
            markActive($(this));
            var subpanelID = $(this).attr('data-subpanel');
            hideAllSubpanel();
            $('#'+subpanelID).show();
            $('#subpanel_list li.sugar_action_button').show();
            $('#subpanel_list li.single').show();
            $('#subpanel_list li.sugar_action_button').find('.subnav').find('li').show();
            return false;
        });

        // Onclick show all
        $('#groupTabs li:first a').click(function(){
            markActive($(this));
            showAllSubpanel();
        });

        jQuery('a[data-subpanel="undefined"]').parent().hide();
    } 
});

function quickAdminEdit(table, field){
    $('#btnedit_'+field).on('click',function(){
        $('#panel_1_'+field).hide();
        $('#panel_2_'+field).show();
    });
    $('#btncancel_'+field).on('click',function(){
        $('#panel_1_'+field).show();
        $('#panel_2_'+field).hide();
    });
    $('#btnsave_'+field).on('click',function(){
        $('#panel_2_'+field).hide();
        $('#loading_'+field).show();
        $.ajax({
            url: "index.php?entryPoint=quickEditAdmin",
            type: "POST",
            async: true,
            data:  {
                type : 'quickAdminEdit',
                table : table,
                field : field,
                module : module_sugar_grp1 ,
                record: $('input[name=record]').val(),
                value : $('#value_'+field).val(),
            },
            dataType: "json",
            success: function(res){
                if(res.success == 1){
                    $('#value_'+field).val(res.value);
                    $('#label_'+field).text(res.value);
                }
                else
                    alertify.error(res.error);

                $('#panel_1_'+field).show();
                $('#loading_'+field).hide();
            },
        });
    });
}

function quickAdminEdit2(table, field, field2){
    $('#btnedit_'+field).on('click',function(){
        $('#panel_1_'+field).hide();
        $('#panel_2_'+field).show();
    });
    $('#btncancel_'+field).on('click',function(){
        $('#panel_1_'+field).show();
        $('#panel_2_'+field).hide();
    });
    $('#btnsave_'+field).on('click',function(){
        $('#panel_2_'+field).hide();
        $('#loading_'+field).show();
        if($('#value_'+field2).is(':checkbox'))
            if($('#value_'+field2).is(':checked'))
                $('#value_'+field2).val('1');
            else $('#value_'+field2).val('0');
        $.ajax({
            url: "index.php?entryPoint=quickEditAdmin",
            type: "POST",
            async: true,
            data:  {
                type    : 'quickAdminEdit',
                table   : table,
                field   : field,
                field2  : field2,
                module  : module_sugar_grp1 ,
                record  : $('input[name=record]').val(),
                value   : $('#value_'+field).val(),
                value2  : $('#value_'+field2).val(),
            },
            dataType: "json",
            success: function(res){
                if(res.success == 1){
                    $('#value_'+field).val(res.value);
                    $('#label_'+field).text(res.value);
                    if(res.value2 != null && res.value2 != ''){
                        $('#value_'+field2).val(res.value2);
                        $('#label_'+field2).val(res.value2);
                        if($('#label_'+field2).is(':checkbox'))
                            if(res.value2 == '1')  $('#label_'+field2).prop('checked', true);
                            else $('#label_'+field2).prop('checked', false);
                    }
                }
                else
                    alertify.error(res.error);

                $('#panel_1_'+field).show();
                $('#loading_'+field).hide();
            },
        });
    });
}

function countSms(text){
    var length, messages, per_message, remaining;
    length = text.val().length;
    if(typeof maximum_sms == 'undefined' || maximum_sms == '')
        maximum_sms = 3;
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
    if(messages > maximum_sms)
        messages_str = 'Messages: <span style="color:red">'+messages+'/'+maximum_sms+' Limited messages, SMS will be failed!</span>';

    text.closest("tr").find(".message_counter").html(messages_str);
}         