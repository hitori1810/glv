// OVERRIDE SET_RETURN
set_return = function(popup_reply_data) {
    from_popup_return = true;
    var form_name = popup_reply_data.form_name;
    var name_to_value_array = popup_reply_data.name_to_value_array;
    if (typeof name_to_value_array != 'undefined' && name_to_value_array['account_id'])
    {
        var label_str = '';
        var label_data_str = '';
        var current_label_data_str = '';
        var popupConfirm = popup_reply_data.popupConfirm;
        for (var the_key in name_to_value_array)
        {
            if (the_key == 'toJSON')
            {
            }
            else
            {
                var displayValue = replaceHTMLChars(name_to_value_array[the_key]);
                if (window.document.forms[form_name] && document.getElementById(the_key + '_label') && !the_key.match(/account/)) {
                    var data_label = document.getElementById(the_key + '_label').innerHTML.replace(/\n/gi, '').replace(/<\/?[^>]+(>|$)/g, "");
                    label_str += data_label + ' \n';
                    label_data_str += data_label + ' ' + displayValue + '\n';
                    if (window.document.forms[form_name].elements[the_key]) {
                        current_label_data_str += data_label + ' ' + window.document.forms[form_name].elements[the_key].value + '\n';
                    }
                }
            }
        }
        if (label_data_str != label_str && current_label_data_str != label_str) {
            if (typeof popupConfirm != 'undefined')
            {
                if (popupConfirm > -1) {
                    set_return_basic(popup_reply_data, /\S/);
                } else {
                    set_return_basic(popup_reply_data, /account/);
                }
            }
            else if (confirm(SUGAR.language.get('app_strings', 'NTC_OVERWRITE_ADDRESS_PHONE_CONFIRM') + '\n\n' + label_data_str))
            {
                set_return_basic(popup_reply_data, /\S/);
            }
            else
            {
                set_return_basic(popup_reply_data, /account/);
            }
        } else if (label_data_str != label_str && current_label_data_str == label_str) {
            set_return_basic(popup_reply_data, /\S/);
        } else if (label_data_str == label_str) {
            set_return_basic(popup_reply_data, /account/);
        }
    } else {
        set_return_basic(popup_reply_data, /\S/);
    }

    // Custom code
    for (var the_key in name_to_value_array) {
        $('#' + the_key).trigger('change');
    }
}

SUGAR.clearRelateField = function(formObj, name_field, id_field) {
    $(formObj).find('#' + name_field).val('').trigger('change');
    $(formObj).find('#' + id_field).val('').trigger('change');
}

