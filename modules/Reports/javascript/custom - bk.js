var current_index = 0;
//This function Overwrite by Lap Nguyen
function addFilterInput(cell, filter) {
    var filter_row = filters_arr[filters_count_map[current_filter_id]];
    var qualifier_name = filter_row.qualify_select.options[filter_row.qualify_select.selectedIndex].value;
    var module_select = filter_row.module_select;
    var table_key = module_select.options[module_select.selectedIndex].value;
    var module_select = filters_arr[filters_count_map[current_filter_id]].module_select;
    var table_key = module_select.options[module_select.selectedIndex].value;
    if (table_key == 'self') {
        selected_module = current_module;
        var field_defs = module_defs[selected_module].field_defs;
    } else {
        selected_module = table_key;
        var field_defs = module_defs[full_table_list[table_key].module].field_defs;
    }
    if (typeof(qualifier_name) == 'undefined' || qualifier_name == '') {
        qualifier_name = 'equals';
    }
    var column_name = filter_row.column_select.options[filter_row.column_select.selectedIndex].value;
    if (typeof(column_name) == 'undefined' || column_name == '') {
        column_name = '';
    }
    var field = all_fields[column_name].field_def;
    var field_type = field.type;
    if (typeof(field.custom_type) != 'undefined') {
        field_type = field.custom_type;
    }
    cell.innerHTML = "<table><tr></tr></table>";
    var row = cell.getElementsByTagName("tr")[0];
    if (qualifier_name == 'between') {
        addFilterInputTextBetween(row, filter);
    } else if (qualifier_name == 'between_dates') {
        addFilterInputDateBetween(row, filter);
    } else if (qualifier_name == 'between_datetimes') {
        addFilterInputDatetimesBetween(row, filter);
    } else if (qualifier_name == 'empty' || qualifier_name == 'not_empty') {
        //This part edit By Lap Nguyen
        add_filter_option(filter_row.qualify_select,qualifier_name);
        //END
        addFilterNoInput(row, filter);
    } else if (field_type == 'date' || field_type == 'datetime'|| field_type == 'datetime') {
        if (qualifier_name.indexOf('tp_') == 0) {
            addFilterInputEmpty(row, filter);
        } else {
            addFilterInputDate(row, filter);
        }
    }
     
//    else if (field_type == 'datetimecombo') {
//        if (qualifier_name.indexOf('tp_') == 0) {
//            addFilterInputEmpty(row, filter);
//        } else {
//            addFilterInputDatetimecombo(row, filter);
//        }
//    } 
    else if (field_type == 'id' || field_type == 'name' || field_type == 'fullname') {
        //This part edit By Lap Nguyen
        if (typeof filter.qualifier_name != "undefined"){
            qualifier_name = filter.qualifier_name;  
        }
        add_filter_option(filter_row.qualify_select,qualifier_name);
        if (qualifier_name == 'is' || qualifier_name == 'is_not') {
            addFilterInputRelate(row, field, filter, false);
        } else if(qualifier_name == 'one_of' || qualifier_name == 'not_one_of') { 
            ajax_get_option_array(table_key, field.name,row ,filter);
        }else {
            addFilterInputText(row, filter);
        }
        //End
    } else if (field_type == 'relate') {
        //This part edit By Lap Nguyen
        if (typeof filter.qualifier_name != "undefined"){
            qualifier_name = filter.qualifier_name;  
        }
        add_filter_option(filter_row.qualify_select,qualifier_name);
        if (qualifier_name == 'is' || qualifier_name == 'is_not') {
            addFilterInputRelate(row, field, filter, true);
        } else if(qualifier_name == 'one_of' || qualifier_name == 'not_one_of') { 
            ajax_get_option_array_relate(field, row ,filter)
        }else {
            addFilterInputText(row, filter);
        }
    } else if ((field_type == 'user_name') || (field_type == 'assigned_user_name')) {
        if (users_array == "") {
            loadXML();
        }
        if (qualifier_name == 'one_of' || qualifier_name == 'not_one_of') {
            addFilterInputSelectMultiple(row, users_array, filter);
            $('select[id=multiple_'+current_filter_id+']').css('min-width','200px');
            $('select[id=multiple_'+current_filter_id+']').css('max-width','500px');
            $('select[id=multiple_'+current_filter_id+']').select2();
        } else {
            addFilterInputSelectSingle(row, users_array, filter);
        }
        //End
    } else if (field_type == 'enum' || field_type == 'radioenum' || field_type == 'multienum' || field_type == 'parent_type' || field_type == 'timeperiod' || field_type == 'currency_id') {
        if (qualifier_name == 'one_of' || qualifier_name == 'not_one_of') {
            addFilterInputSelectMultiple(row, field.options, filter);
            $('select[id=multiple_'+current_filter_id+']').css('min-width','200px');
            $('select[id=multiple_'+current_filter_id+']').css('max-width','500px');
            $('select[id=multiple_'+current_filter_id+']').select2();
        } else {
            addFilterInputSelectSingle(row, field.options, filter);
        }
    } else if (field_type == 'bool') {
        var options = ['yes', 'no'];
        addFilterInputSelectSingle(row, options, filter);
    } else {
        addFilterInputText(row, filter);
    }
    return row;
}
//This function write by Lap Nguyen
function add_filter_option(select, qualifier_name){
    var len = 0;
    for (i = 0; i < select.length; i++) {
        if(select.options[i].value == 'one_of' || select.options[i].value == 'not_one_of')
            len++;
    }
    if(len == 0){
        var opt1 = document.createElement("option");
        opt1.value = 'one_of';
        opt1.innerHTML = 'Is One Of';
        select.appendChild(opt1);
        var opt2 = document.createElement("option");
        opt2.value = 'not_one_of';
        opt2.innerHTML = 'Is Not One Of';
        select.appendChild(opt2);
    }
    select.value =  qualifier_name;
}
//This function write by Lap Nguyen
function ajax_get_option_array(table_key, field_name, row ,filter){
    var report_id = $('input[name=id]').val();
    var module_lap = full_table_list[table_key].module;
    $.ajax({
        url: "index.php?module=Reports&action=custom_ajax&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  
        {
            module_lap: module_lap,
            field: field_name,
            report_id: report_id, 
        },
        dataType: "json",
        success: function(data){
            addFilterInputSelectMultiple(row, data.opt_arrar, filter);
            $('select[id=multiple_'+current_filter_id+']').css('min-width','200px');
            $('select[id=multiple_'+current_filter_id+']').css('max-width','500px');
            $('select[id=multiple_'+current_filter_id+']').select2();
        },        
    });

}
//This function write by Lap Nguyen
function ajax_get_option_array_relate(table_key, row ,filter){
    var report_id = $('input[name=id]').val();
    var module_lap = table_key.module;
    $.ajax({
        url: "index.php?module=Reports&action=custom_ajax&sugar_body_only=true",
        type: "POST",
        async: true,
        data:  
        {
            module_lap: module_lap,
            field: field_name,
            report_id: report_id, 
        },
        dataType: "json",
        success: function(data){
            addFilterInputSelectMultiple(row, data.opt_arrar, filter);
            $('select[id=multiple_'+current_filter_id+']').css('min-width','200px');
            $('select[id=multiple_'+current_filter_id+']').css('max-width','500px');
            $('select[id=multiple_'+current_filter_id+']').select2();
        },        
    });

}
//This function overwrite by Lap Nguyen - Create Mutil select
function addFilterInputSelectMultiple(row, options, filter) {
    var cell = document.createElement('td');
    row.appendChild(cell);
    var select_html_info = new Object();
    var options_arr = new Array();
    var select_info = new Object();
    select_info['size'] = '5';
    select_info['multiple'] = true;
    select_info['id'] = 'multiple_'+current_filter_id;
    select_html_info['select'] = select_info;
    var selected_map = new Object();
    for (i = 0; i < filter.input_name0.length; i++) {
        selected_map[filter.input_name0[i]] = 1;
    }
    //This part edit By Lap Nguyen
    for (i = 0; i < Object.keys(options).length; i++) {
        //END     
        var option_name;
        var option_value;
        if (typeof(options[i].text) == 'undefined') {
            option_text = options[i];
            option_value = options[i];
        } else if (options[i].value == '') {
            continue;
        } else {
            option_text = options[i].text;
            option_value = options[i].value;
        }
        if (typeof(selected_map[option_value]) != 'undefined') {
            selected = true;
        } else {
            selected = false;
        }
        var option_info = new Object();
        option_info['value'] = option_value;
        option_info['text'] = option_text;
        option_info['selected'] = selected;
        options_arr[options_arr.length] = option_info;
    }
    select_html_info['options'] = options_arr;
    cell.innerHTML = buildSelectHTML(select_html_info);
    var filter_row = filters_arr[filters_count_map[current_filter_id]];
    filter_row.input_field0 = cell.getElementsByTagName('select')[0];
    filter_row.input_field1 = null;
}

function refreshFilterInput(filter, index) {
    current_filter_id = index;
    var filter_row = filters_arr[filters_count_map[index]];
    if (typeof(filter_row.input_field0) != 'undefined' && typeof(filter_row.input_field0.value) != 'undefined') {
        type = "input";
    }
    filter_row.input_cell.removeChild(filter_row.input_cell.firstChild);
    addFilterInput(filter_row.input_cell, filter);
}

function validateFilterRow(filter, returnObject) {
    if (filter != null && filter.runtime != null && filter.runtime == 1) {
        var row = document.getElementById(filter.id);
        var cell0 = row.cells[2];
        var cell1 = row.cells[4];
        var cell2 = row.cells[5];
        var column_name = cell0.getElementsByTagName('select')[0].value;
        var field = all_fields[column_name].field_def;
        filter.name = field.name;
        filter.table_key = all_fields[column_name].linked_field_name;
        column_vname = all_fields[column_name].label_prefix + ": " + field['vname'];
        filter.qualifier_name = cell1.getElementsByTagName('select')[0].value;
        var input_arr = cell2.getElementsByTagName('input');
        //This part edited by Lap Nguyen - Add select2
        if(filter.qualifier_name == 'not_one_of' || filter.qualifier_name == 'one_of'){
            var got_selected = 0;
            var select_input = cell2.getElementsByTagName('select')[0];
            filter.input_name0 = new Array();
            for (j = 0; j < select_input.options.length; j++) {
                if (select_input.options[j].selected == true) {
                    filter.input_name0.push(decodeURI(select_input.options[j].value));
                    got_selected = 1;
                }
            }
            if (got_selected == 0) {
                returnObject.error_msgs += "\"" + column_vname + "\": " + lbl_missing_second_input_value + "\n";
                returnObject.got_error = 1;
            }
            //END
        }else{
            if (typeof(input_arr[0]) != 'undefined') {
                filter.input_name0 = input_arr[0].value;
                if (input_arr[0].value == '') {
                    returnObject.got_error = 1;
                    returnObject.error_msgs += "\"" + column_vname + "\"" + lbl_missing_input_value + "\n";
                }
                if (typeof(input_arr[1]) != 'undefined') {
                    filter.input_name1 = input_arr[1].value;
                    if (input_arr[1].value == '') {
                        returnObject.got_error = 1;
                        returnObject.error_msgs += "\"" + column_vname + "\"" + lbl_missing_second_input_value + "\n";
                    }
                }
                if (field.type == 'datetimecombo') {
                    if (typeof(input_arr[2]) != 'undefined') {
                        filter.input_name2 = input_arr[2].value;
                        if (input_arr[2].value == '' && input_arr[2].type != 'checkbox') {
                            got_error = 1;
                            error_msgs += "\"" + column_vname + "\" " + lbl_missing_input_value + "\n";
                        }
                    }
                    if (typeof(input_arr[3]) != 'undefined') {
                        filter.input_name3 = input_arr[3].value;
                        if (input_arr[3].value == '' && input_arr[3].type != 'checkbox') {
                            got_error = 1;
                            error_msgs += "\"" + column_vname + "\" " + lbl_missing_input_value + "\n";
                        }
                    }
                    if (typeof(input_arr[4]) != 'undefined') {
                        filter.input_name4 = input_arr[4].value;
                        if (input_arr[4].value == '' && input_arr[4].type != 'checkbox') {
                            got_error = 1;
                            error_msgs += "\"" + column_vname + "\" " + lbl_missing_input_value + "\n";
                        }
                    }
                }
            } else {
                var got_selected = 0;
                var select_input = cell2.getElementsByTagName('select')[0];
                filter.input_name0 = new Array();
                for (j = 0; j < select_input.options.length; j++) {
                    if (select_input.options[j].selected == true) {
                        filter.input_name0.push(decodeURI(select_input.options[j].value));
                        got_selected = 1;
                    }
                }
                if (got_selected == 0) {
                    returnObject.error_msgs += "\"" + column_vname + "\": " + lbl_missing_second_input_value + "\n";
                    returnObject.got_error = 1;
                }
            }
            if (field.type == 'datetime' || field.type == 'date' || field.type == 'datetimecombo') { 
                if (typeof(filter.input_name0) != 'undefined' && typeof(filter.input_name0) != 'array') {
                    var date_match = filter.input_name0.match(date_reg_format);
                    if (date_match != null) {
                        filter.input_name0 = date_match[date_reg_positions['Y']] + "-" + date_match[date_reg_positions['m']] + "-" + date_match[date_reg_positions['d']];
                    }
                }
                if (typeof(filter.input_name1) != 'undefined' && typeof(filter.input_name1) != 'array') {
                    var date_match = filter.input_name1.match(date_reg_format);
                    if (date_match != null) {
                        filter.input_name1 = date_match[date_reg_positions['Y']] + "-" + date_match[date_reg_positions['m']] + "-" + date_match[date_reg_positions['d']];
                    }
                }
            }
            debugger; 
//            else if (field.type == 'datetimecombo') {
//                if ((typeof(filter.input_name0) != 'undefined' && typeof(filter.input_name0) != 'array') && (typeof(filter.input_name1) != 'undefined' && typeof(filter.input_name1) != 'array')) {
//                    var dbValue = convertReportDateTimeToDB(filter.input_name0, filter.input_name1);
//                    if (dbValue != '') {
//                        filter.input_name0 = dbValue;
//                    }
//                }
//                if (typeof(filter.input_name2) != 'undefined' && typeof(filter.input_name2) != 'array' && typeof(filter.input_name3) != 'undefined' && typeof(filter.input_name3) != 'array') {
//                    var dbValue = convertReportDateTimeToDB(filter.input_name2, filter.input_name3);
//                    if (dbValue != '') {
//                        filter.input_name2 = dbValue;
//                    }
//                }
//            }

        }  

    }
}

function addFilter(filter) {
    filters_arr[filters_arr.length] = new Object();
    filters_count++;
    filters_count_map[filters_count] = filters_arr.length - 1;
    current_filter_id = filters_count;
    if (typeof(filter) == 'undefined') {
        filter = default_filter;
    }
    var the_table = document.getElementById('filters');
    var row = document.createElement('tr');
    filters_arr[filters_count_map[filters_count]].row = row;
    row.valign = "top";
    row.id = "rowid" + filters_count;
    filter.id = row.id;
    var module_cell = document.createElement('td');
    module_cell.valign = "top";
    row.appendChild(module_cell);
    filters_arr[filters_count_map[filters_count]].module_cell = module_cell;
    addModuleSelectFilter(module_cell, filter, row);
    var column_cell = document.createElement('td');
    column_cell.valign = "top";
    row.appendChild(column_cell);
    filters_arr[filters_count_map[filters_count]].column_cell = column_cell;
    addColumnSelectFilter(column_cell, filter, row);
    var qualify_cell = document.createElement('td');
    qualify_cell.valign = "top";
    row.appendChild(qualify_cell);
    filters_arr[filters_count_map[filters_count]].qualify_cell = qualify_cell;
    addFilterQualify(qualify_cell, filter, row);
    var input_cell = document.createElement('td');
    input_cell.valign = "top";
    row.appendChild(input_cell);
    filters_arr[filters_count_map[filters_count]].input_cell = input_cell;
    addFilterInput(input_cell, filter);
    var cell = document.createElement('td');
    cell.valign = "top";
    row.appendChild(cell);
    if (isRuntimeFilter(filter)) {} else {
        row.style.display = "none";
    }
    the_table.appendChild(row);
    //This part added by Lap nguyen
    $('select[id=multiple_'+current_filter_id+']').css('min-width','200px');
    $('select[id=multiple_'+current_filter_id+']').css('max-width','500px');
    $('select[id=multiple_'+current_filter_id+']').select2();
    //END
}