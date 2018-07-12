/**
 * From this entry point admin can access the inner file in their instance from out side.
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
//create condition from popup.
function workflow_condition(module) {
    $.ajax({
        url: "index.php",
        data: {
            module: 'bc_automizer_condition',
            action: 'get_module_list',
            module_name: module,
        },
        success: function (result_module) {

            $.ajax({
                url: "index.php",
                data: {
                    module: 'bc_automizer_condition',
                    action: 'get_Related_Fields',
                    flow_module: module,
                },
                success: function (result) {
                    var option = $.trim(result);
                    $('body').append('<div id="backgroundpopup">&nbsp;</div>');
                    $('body').append('<form name="EditView" id="EditView"><div id="survey_main_div">' +
                            '<div id="survey_content">' +
                            '<div id="button_div">' +
                            '<table class="list view table footable-loaded footable default suite-model-table survey-auto-table" cellpadding="5px" cellspacing="5px" style="width: 100%; ">' +
                            '<thead><tr><th style="width: 100%;" colspan="2"><h2>Create Survey Automation Condition</h2></th></tr></thead>' +
                            '<tbody>' +
                            '<tr><td class="lable">Module</td><td class="table-desc"><select id="rel_module" style="" onchange="get_rel_fields(this)">' + result_module + '</select></td></tr>' +
                            '<tr id="filter_row" style="display:none;"><td class="lable">Filter By</td><td class="table-desc"><select style="" id="filter_by_fields"><option value="all_fields">All Related</option><option value="any_field">Any Related</option></select></td></tr>' +
                            '<tr id="fields_list"><td class="lable">Fields</td><td class="table-desc"><select style="" id="rel_fields" name="field_name" onchange="show_operator(this)">' + option + '</select>&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:red; display:none;"></i></td>' +
                            '<tr id="operators_row" style="display:none;"><td class="lable">Operator</td><td class="table-desc"></td></tr>' +
                            '<tr id="types_row" style="display:none;"><td class="lable">Type</td><td class="table-desc"></td></tr>' +
                            '<tr id="values_row" style="display:none;"><td class="lable">Value</td><td class="table-desc"></td></tr>' +
                            '</tr></tbody>' +
                            '<tfoot><tr><td style="width: 100%;" colspan="2">' +
                            '<input type="button" onclick="save_condition()" value="Save Condition">&nbsp;&nbsp;&nbsp;' +
                            '<input style="float:right" type="button" value="Cancel" onclick="close_survey_div()">' +
                            '</td></tr></tfoot>' +
                            '</table>' +
                            '</div>' +
                            '</div>' +
                            '<a onclick="close_survey_div();" href="javascript:close_survey_div();"></a>' +
                            '</div></form>');
                    $('#backgroundpopup').fadeIn();
                    $('#survey_main_div').fadeIn();
                    $('#survey_main_div').append('<img src="themes/default/images/loading.gif" id="loading-image"  class="ajax-loader" style="display:none; left: 30%; top: 70%; position: absolute;"/>');
                    var arr;
                    $.each($('#rel_module').find('option'), function () {
                        if (this.value) {
                            try {
                                arr = this.value.split('_');
                                if (arr[0] == "bc" || arr[0] == "AOW") {
                                    $('#rel_module').find('option[value=' + this.value + ']').remove();
                                }
                            }
                            catch (err) {
                            }
                        }
                    });
                }
            });
        }
    });
}
//close popup function
function close_survey_div() {
    $('#backgroundpopup').fadeOut(function () {
        $('#backgroundpopup').remove();
    });
    $('#survey_main_div').parent('#EditView').remove();
    $("#indivisual_report_main_div").fadeOut(function () {
        $("#indivisual_report_main_div").remove();
    });
}
//onload function
$(document).ready(function () {
    try {
        if (record_id) {
            $('#flow_module').attr('disabled', 'disabled');
            $('#run_when').attr('disabled', 'disabled');
        }
    } catch (err) {
    }
    retrive_condition_records();
    retrive_action_records();
});
//get related fields function and show hide rows.
function get_rel_fields(el) {
    var module = $(el).val();
    var record_id = $('input[name=record]').val();
    $('#loading-image').show();
    $.ajax({
        url: "index.php",
        data: {
            module: 'bc_automizer_condition',
            action: 'get_Module_Related_Fields',
            module_name: module,
            record_id: record_id,
        },
        success: function (result) {
            var option = $.trim(result);
            $("#rel_fields").html(option);

            $.each($("#rel_fields").find('option'), function () {
                if (this.value == $("#field_value").val()) {
                    $(this).attr('selected', 'selected');
                    $(this).trigger('change');
                }
            });
            $('#loading-image').hide();
        },
    });
    if ($('input[name=base_module]').val() == module) {
        $("#filter_row").hide();
        $("#operators_row").hide();
        $("#types_row").hide();
        $("#values_row").hide();
    } else {
        $("#filter_row").show();
        $("#operators_row").hide();
        $("#types_row").hide();
        $("#values_row").hide();
    }
}
//show operator , type and value fields in popup
function show_operator(el) {
    $(el).parent().find('i').hide();
    var module = $('#rel_module').val();
    var fields = $(el).val();
    var record_id = $('input[name=record]').val();
    if (el.value != "") {
        $("#loading-image").show();
        $.ajax({
            url: "index.php",
            data: {
                module: 'bc_automizer_condition',
                action: 'getModuleOperatorField',
                module_name: module,
                record_id: record_id,
                field_name: fields,
            },
            success: function (result) {
                $("#operators_row").show();
                $("#types_row").show();
                $("#values_row").show();
                $('#operators_row').find('td').eq(1).html(result);
                if ($("#operator_value").val()) {
                $.each($("#operators_row").find('option'), function () {
                    if (this.value == $("#operator_value").val()) {
                        $(this).attr('selected', 'selected');
                        $(this).trigger('change');
                    }
                });
                }
            },
        });
        $.ajax({
            url: "index.php",
            data: {
                module: 'bc_automizer_condition',
                action: 'getFieldTypeOptions',
                module_name: module,
                record_id: record_id,
                field_name: fields,
            },
            success: function (result) {
                var option = $.trim(result);
                $('#types_row').find('td').eq(1).html(option);
                if ($("#value_type").val()) {
                $.each($("#option").find('option'), function () {
                    if (this.value == $("#value_type").val()) {
                        $(this).attr('selected', 'selected');
                            $(this).trigger('change');
                    }
                });
            }
            }
        });
        $.ajax({
            url: "index.php",
            data: {
                module: 'bc_automizer_condition',
                action: 'getModuleFieldType',
                module_name: module,
                record_id: record_id,
                field_name: fields,
            },
            success: function (result) {
                var option = $.trim(result);
                $('#values_row').find('td').eq(1).html(option);
                $('#loading-image').hide();
            }
        });
    } else {
        $("#operators_row").hide();
        $("#types_row").hide();
        $("#values_row").hide();
    }
}
//show hide fields
function show_hide_fields(el) {
    var operaor = $(el).val();
    if (operaor == "Any_change" || operaor == "is_null") {
        $("#condition_value").val('');
        $("#option").val('Value');
        $("#types_row").hide();
        $("#values_row").hide();
    } else {
        $("#types_row").show();
        $("#values_row").show();
    }
}
//get value fields in popup
function getvalueFields() {
    var module = $('#rel_module').val();
    var fields = $('#rel_fields').val();
    var record_id = $('input[name=record]').val();
    var type = $("#types_row option:selected").val();
    $("#loading-image").show();
    $.ajax({
        url: "index.php",
        data: {
            module: 'bc_automizer_condition',
            action: 'getModuleFieldType',
            module_name: module,
            record_id: record_id,
            field_name: fields,
            type_value: type,
        },
        success: function (result) {
            var option = $.trim(result);
            $('#values_row').find('td').eq(1).html(option);
            if ($("#condition_value").find('option').length >= 1) {
                if ($("#value_type").val() == "Multi") {
                    var string = ($("#value_field").val()).split(',');
                    string.pop();
                    $.each($("#condition_value").find('option'), function (index, val) {
                        if (this.value == string[index]) {
                            $(this).attr('selected', 'selected');
                        }
                    });
                } else {
                    $("#condition_value").val($("#value_field").val());
                }
            } else {
                $("#condition_value").val($("#value_field").val());
                $("#condition_value_display").val($("#value_field").val());
            }
            $('#loading-image').hide();
        },
    });
}
//Save Condition from popup
function save_condition() {
    $("#values_row").find('input[type=text]').attr("id", 'condition_value');
    var flag = true;
    if (!$("#rel_fields").val()) {
        $("#fields_list").find('i').show();
        flag = false;
    }
    $("#rel_fields").parent().find('img').hide();
    var module = $('#rel_module').val();
    var fields = $('#rel_fields').val();
    var filter_by = $('#filter_by_fields').val();
    var record_id = $('input[name=record]').val();
    var operator = $("#operator_row option:selected").val();
    var type = '';
    if (operator != "is_null" && operator != "Any_change") {
        type = $("#types_row option:selected").val();
        if ($("#condition_value").val() == "" || $("#condition_value").val() == "http://") {
            $('#condition_value').parent().find('i').show();
            flag = false;
        }
    } else {
        type = '';
    }

    var condition_value = $("#condition_value").val();
    if (condition_value && typeof condition_value == "object") {
        var str = '';
        condition_value.pop();
        $.each(condition_value, function (k, v) {
            str += v + ',';
        });
        str.slice(0, -1);
    } else {
        var str = $("#condition_value").val();
    }
    if (flag) {
        $.ajax({
            url: "index.php",
            data: {
                module: 'bc_automizer_condition',
                action: 'save',
                record_id: record_id,
                field_value: fields,
                filter_by: filter_by,
                type_value: type,
                operator_value: operator,
                module_name: module,
                condition_value: str,
            },
            success: function (result) {
                close_survey_div();
                retrive_condition_records();
            }
        });
    }
}
//retrive all conditions list for perticular automation
function retrive_condition_records() {
    var record_id = $('input[name=record]').val();
    var module_name = $('input[name=base_module]').val();
    $.ajax({
        url: "index.php",
        data: {
            module: 'bc_automizer_condition',
            action: 'getConditionRecords',
            record_id: record_id,
            base_module:module_name,
        },
        success: function (result) {
            var detaildata = JSON.parse(result);
            var html = '';
            $.each(detaildata, function (index, value) {
                html += '<tr id="' + value.id + '" class="' + value.module + '">';
                html += '<td  width="10%">' + (index + 1) + '</td>';
                html += '<td  width="10%">' + value.module + '</td>';
                html += '<td  width="10%">' + value.fields + '</td>';
                html += '<td  width="10%">' + value.operator + '</td>';
                html += '<td  width="10%">' + value.value_type + '</td>';
                html += '<td  width="10%">' + value.value + '</td>';
                html += '<td  width="10%"><a style="cursor:pointer;" onclick="edit_record(this)">Edit</a>&nbsp;&nbsp;/&nbsp;&nbsp;<a style="cursor:pointer;"onclick="delete_record(this)">Delete</a></td></tr>';
            });
            $('#condtion_table').find('tbody').eq(1).html(html);
        }
    });
}
//delete condition from list view
function delete_record(el) {
    if (confirm("Are you sure to Remove this Condition ?")) {
        var record_id = $(el).parent().parent().attr('id');
        $.ajax({
            url: "index.php",
            data: {
                module: 'bc_automizer_condition',
                action: 'delete_record',
                record_id: record_id,
            },
            success: function (result) {
                retrive_condition_records();
            }
        });
    }
}
//edit condition from list view.
function edit_record(el) {
    var record_id = $(el).parent().parent().attr('id');
    var field_value = '';
    $.ajax({
        url: "index.php",
        data: {
            module: 'bc_automizer_condition',
            action: 'edit_record',
            record_id: record_id,
        },
        success: function (resultedit) {

            var detaildata = JSON.parse(resultedit);
            var html = '';
            $.each(detaildata, function (index, value_module) {

                $.ajax({
                    url: "index.php",
                    data: {
                        module: 'bc_automizer_condition',
                        action: 'get_module_list',
                        module_name: value_module.flow_module,
                    },
                    success: function (result_module) {

                        $.ajax({
                            url: "index.php",
                            data: {
                                module: 'bc_automizer_condition',
                                action: 'get_Related_Fields',
                                flow_module: value_module.flow_module,
                                module_name: value_module.module,
                            },
                            success: function (result) {

                                var option = $.trim(result);
                                $('body').append('<div id="backgroundpopup">&nbsp;</div>');
                                $('body').append('<form name="EditView" id="EditView"><div id="survey_main_div">' +
                                        '<div id="survey_content">' +
                                        '<div id="button_div">' +
                                        '<table class="list view table footable-loaded footable default suite-model-table survey-auto-table" cellpadding="5px" cellspacing="5px" style="width: 100%;">' +
                                        '<thead><tr><th style="width: 100%;" colspan="2"><h2>Create Survey Automation Condition</h2></th></tr></thead>' +
                                        '<input type="hidden" id="field_value" value="' + value_module.fields + '">' +
                                        '<input type="hidden" id="operator_value" value="' + value_module.operator + '">' +
                                        '<input type="hidden" id="value_type" value="' + value_module.value_type + '">' +
                                        '<input type="hidden" id="value_field" value="' + value_module.value + '">' +
                                        '<tbody>' +
                                        '<tr><td class="lable">Module</th><td class="table-desc"><select id="rel_module" style="" onchange="get_rel_fields(this)">' + result_module + '</select></td></tr>' +
                                        '<tr id="filter_row"><td class="lable">Filter By</th><td class="table-desc"><select id="filter_by_fields" style=""><option value="all_fields">All Related</option><option value="any_field">Any Related</option></select></td></tr>' +
                                        '<tr id="fields_list"><td class="lable">Fields</th><td class="table-desc"><select id="rel_fields" name="field_name" style="" onchange="show_operator(this)">' + option + '</select>&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:red; display:none;"></i></td>' +
                                        '<tr id="operators_row" style="display:none;"><td class="lable">Operator</th><td class="table-desc"></td></tr>' +
                                        '<tr id="types_row" style="display:none;"><td class="lable">Type</th><td class="table-desc"></td></tr>' +
                                        '<tr id="values_row" style="display:none;"><td class="lable">Value</th><td class="table-desc"></td></tr>' +
                                        '</tr></tbody>' +
                                        '<tfoot><tr><td style="width: 100%;" colspan="2">' +
                                        '<input type="button" onclick="update_condition(\'' + record_id + '\')" value="Update Condition">&nbsp;&nbsp;&nbsp;' +
                                        '<input style="float:right" type="button" value="Cancel" onclick="close_survey_div()">' +
                                        '</td></tr></tfoot>' +
                                        '</table>' +
                                        '</div>' +
                                        '</div>' +
                                        '<a onclick="close_survey_div();" href="javascript:close_survey_div();"></a>' +
                                        '</div></form>');
                                $('#backgroundpopup').fadeIn();
                                $('#survey_main_div').fadeIn();
                                $('#survey_main_div').append('<img src="themes/default/images/loading.gif" id="loading-image"  class="ajax-loader" style="display:none; left: 30%; top: 70%; position: absolute;"/>');
                                $.each($('#filter_by_fields').find('option'), function () {
                                    if (this.value == value_module.filter_by) {
                                        $(this).attr('selected', 'selected');
                                    }
                                });

                                $.each($('#rel_module').find('option'), function () {
                                    if (this.value == value_module.module) {
                                        $(this).attr('selected', 'selected');
                                        $(this).trigger('change');
                                    }
                                });
                            },
                        });
                    }
                });
            });
        }
    });
}
//update condition from popup
function update_condition(record_id) {
    $("#values_row").find('input[type=text]').attr("id", 'condition_value');
    var flag = true
    if (!$("#rel_fields").val()) {
        $("#fields_list").find('i').show();
        flag = false
    }
    $("#rel_fields").parent().find('img').hide();
    var module = $('#rel_module').val();
    var fields = $('#rel_fields').val();
    var filter_by = $('#filter_by_fields').val();
    var record_id_work = $('input[name=record]').val();
    var operator = $("#operator_row option:selected").val();
    var type = '';
    var condition_value = '';
    if (operator != "is_null" && operator != "Any_change") {
        type = $("#types_row option:selected").val();
        condition_value = $("#condition_value").val();
        if (!condition_value) {
            $("#condition_value").parent().find('i').show();
            flag = false;
        }
        if (condition_value && typeof condition_value == "object") {
            var str = '';
            condition_value.pop();
            $.each(condition_value, function (k, v) {
                str += v + ',';
            });
            str.slice(0, -1);
    } else {
            var str = $("#condition_value").val();
        }
    } else {
        type = '';
        condition_value = '';
    }
    var base_module=$('input[name=base_module]').val();
    if (flag) {
        $.ajax({
            url: "index.php",
            data: {
                module: 'bc_automizer_condition',
                action: 'update_records',
                record_id_work: record_id_work,
                field_value: fields,
                type_value: type,
                operator_value: operator,
                filter_by: filter_by,
                module_name: module,
                condition_value: str,
                record_id: record_id,
                base_module:base_module,
            },
            success: function (result) {
                close_survey_div();
                retrive_condition_records();
            }
        });
    }
}
//create action popup for automation.
function workflow_action(module) {
    $.ajax({
        url: "index.php",
        data: {
            module: 'bc_automizer_actions',
            action: 'get_module_list',
            module_name: module,
        },
        success: function (data, textStatus, jqXHR) {

            $('body').append('<div id="backgroundpopup">&nbsp;</div>');
            $('body').append('<form name="EditView" id="EditView"><div id="survey_main_div">' +
                    '<div id="survey_content">' +
                    '<div id="button_div">' +
                    '<table class="list view table footable-loaded footable default suite-model-table survey-auto-table" cellpadding="5px" cellspacing="5px" style="width: 100%;">' +
                    '<thead><tr><th style="width: 100%;" colspan="2"><h2>Create Survey Automation Action</h2></th></tr></thead>' +
                    '<input type="hidden" value="" name="action_id">' +
                    '<input type="hidden" value="" name="filter_by">' +
                    '<input type="hidden" value="" name="fields_value">' +
                    '<input type="hidden" value="" name="operator_value">' +
                    '<input type="hidden" value="" name="compare_value">' +
                    '<tbody>' +
                    '<input type="hidden" name="target_module" value="' + module + '">' +
                    '<tr id="relate_module"><td class="lable">Module Type</td><td class="table-desc"><select id="module" style="" onchange="show_module_list(this)"><option value="none">Select Recipient Module</option><option value="related_module">Recipient associated with a related module</option><option value="target_module">Recipient associated with the target module</option></select>&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:red; display:none;"></i></td></tr>' +
                    '<tr id="module_list" style="display:none;"><td class="lable">Recipient Module</td><td class="table-desc"><select style="" id="module_name" name="module_name" onchange="show_fields_list(this)">' + data + '</select>&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:red; display:none;"></i></td>' +
                    '<tr id="filter_row" style="display:none;"><td class="lable">Filter By</td><td class="table-desc"><select style="" id="filter_by_fields" onchange="show_relate_fields(this)"><option value="all_fields">All Related</option><option value="any_field">Any Related</option></select></td></tr>' +
                    '<tr id="fields_row" style="display:none;"><td class="lable">Field</td><td class="table-desc"><select id="fields_list" style="" onchange="show_operator_value(this)"></select>&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:red; display:none;"></i></td></tr>' +
                    '<tr id="operator_row" style="display:none;"><td class="lable">Operator</td><td class="table-desc"><select class="operator_selection" style=""><option value="Equal_To">Equals to</option><option value="Not_Equal_To">Not Equals to</option></select></td></tr>' +
                    '<tr id="value_row" style="display:none;"><td class="lable">Value</td><td class="table-desc"></td></tr>' +
                    '<tr id="related_field" style="display:none;"><td class="lable">Related Field</td><td class="table-desc"><select id="target_relate_field" style=""></select></td></tr>' +
                    '<tr id="email_show" style="display:none;"><td class="lable">Recipient Email</td><td class="table-desc"><select style="" id="email_type" name="email_name"><option value="to">To</option><option value="cc">Cc</option><option value="bcc">Bcc</option></select></td>' +
                    '<tr id="survey_show" style="display:none;"><td class="lable">Survey</td><td class="table-desc"><select id="survey_name" style="" name="survey_name" onclick="check_for_template(this)"></select>&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:red; display:none;"></i></td>' +
                    '</tr></tbody>' +
                    '<tfoot><tr><td style="width: 100%;" colspan="2">' +
                    '<input type="button" onclick="save_action(this)" value="Save Action">&nbsp;&nbsp;&nbsp;' +
                    '<input style="float:right" type="button" value="Cancel" onclick="close_survey_div()">' +
                    '</td></tr></tfoot>' +
                    '</table>' +
                    '</div>' +
                    '</div>' +
                    '<a onclick="close_survey_div();" href="javascript:close_survey_div();"></a>' +
                    '</div></form>');
            $('#backgroundpopup').fadeIn();
            $('#survey_main_div').fadeIn();
            $('#survey_main_div').append('<img src="themes/default/images/loading.gif" id="loading-image"  class="ajax-loader" style="display:none; left: 30%; top: 70%; position: absolute;"/>');
            if ($("#module_name").find('option').length == 1) {
                $("#module").find('option').eq(1).remove()
            }
        },
    });

}
//show operator value.
function show_operator_value(el) {
    $(el).parent().find('i').hide();
    var target_module = $("input[name=base_module]").val();
    var module = $("#module_name").val();
    var field = $(el).val();
    if (field) {
        $("#loading-image").show();
        $.ajax({
            url: 'index.php',
            data: {
                module: 'bc_automizer_actions',
                action: 'getModuleFieldType',
                module_name: module,
                flow_module: target_module,
                field_name: field,
            },
            success: function (data, textStatus, jqXHR) {
                $("#value_row").find('td').eq(1).html(data);
                $("#operator_row").show();
                $("#value_row").show();
                $.each($("#operator_row").find('option'), function () {
                    if (this.value == $("input[name=operator_value]").val()) {
                        $(this).attr('selected', 'selected');
                        $(this).trigger('change');
                    }
                });
                if ($("input[name=compare_value]").val() != "null" && $("input[name=compare_value]").val() != '') {
                    $("#condition_value").val($("input[name=compare_value]").val());
                    $("#condition_value_display").val($("input[name=compare_value]").val());
                }
                $('#loading-image').hide();
            },
        });
    } else {
        $("#operator_row").hide();
        $("#value_row").hide();
    }

}
//show related fields from action popup.
function show_relate_fields(el) {
    var target_module = $("input[name=base_module]").val();
    var module = $("#module_name").val();
    var field = $(el).val();
    if (field == "any_field") {
        $("#loading-image").show();
        $.ajax({
            url: 'index.php',
            data: {
                module: 'bc_automizer_actions',
                action: 'get_fields_list',
                module_name: module,
                flow_module: target_module,
            },
            success: function (data, textStatus, jqXHR) {
                $("#fields_list").html(data);
                $("#fields_row").show();
                $.each($("#fields_row").find('option'), function () {
                    if (this.value == $("input[name=fields_value]").val()) {
                        $(this).attr('selected', 'selected');
                        $(this).trigger('change');
                    }
                });
                $('#loading-image').hide();
            },
        });
    } else {
        $("#fields_row").hide();
        $("#operator_row").hide();
        $("#value_row").hide();
    }
}
//show field list
function show_fields_list(el) {
    $(el).parent().find('i').hide();
    var relate_module = $(el).val();
    if (relate_module == 0) {
        $("#fields_row").hide();
        $("#operator_row").hide();
        $("#filter_row").hide();
        $("#value_row").hide();
        $("#filter_by_fields").val('all_fields');
        $("#fields_list").val('');
    } else {
        $("#filter_row").show();
    }
}
//module list
function show_module_list(el) {
    $(el).parent().find('i').hide();
    var record_id = $('input[name=record]').val();
    var action_id = $('input[name=action_id]').val();
    var module_name =$("input[name=base_module]").val();
    var apiData = {module: 'bc_automizer_actions', action: 'getsurvey', record: record_id, action_id: action_id,module_name:module_name};
    $('#loading-image').show();
    $.ajax({
        url: "index.php",
        data: apiData,
        success: function (data, textStatus, jqXHR) {
            if (data == '<option value="0">Select Survey</option>') {
                $("#survey_name").parent().html('<input style="width:auto;margin-top: 11px;" type="button" name="create_template" style="" onclick="window.open(\'index.php?module=bc_survey&action=EditView&return_module=bc_survey&return_action=DetailView\');" Value="Create New Survey"/>');
            } else {
                $("#survey_name").html(data);
                $('#survey_name').parent().find('input[name=preview_template]').hide();
                $.each($("#survey_name").find('option'), function () {
                    if (this.value == $("input[name=survey_name]").val()) {
                        $(this).attr('selected', 'selected');
                        $(this).parent().next().show();
                    }
                });
            }
            if ($(el).val() == "related_module") {
                $("#module_list").show();
                $("#email_show").show();
                $("#survey_show").show();
                $("#related_field").hide();
            } else if ($(el).val() == "target_module") {
                $("#module_list").hide();
                var relate_fields = get_retaled_fields();
                $("#module_name").val('0');
                $("#filter_by_fields").val('all_fields');
                $("#fields_list").val('');
                $("#fields_row").hide();
                $("#filter_row").hide();
                $("#operator_row").hide();
                $("#value_row").hide();
                $("#related_field").show();
                $("#email_show").show();
                $("#survey_show").show();
            } else {
                $("#module_name").val('0');
                $("#filter_by_fields").val('all_fields');
                $("#fields_list").val('');
                $("#fields_row").hide();
                $("#filter_row").hide();
                $("#operator_row").hide();
                $("#value_row").hide();
                $("#related_field").hide();
                $("#module_list").hide();
                $("#email_show").hide();
                $("#survey_show").hide();
            }
             $('#loading-image').hide();
        },
    });
}
//get related fields.
function get_retaled_fields() {
    var module = $('input[name=base_module]').val();
    $('#loading-image').show();
    $.ajax({
        url: "index.php",
        data: {
            module: 'bc_automizer_actions',
            action: 'get_related_fields',
            module_name: module,
        },
        success: function (data, textStatus, jqXHR) {
            $("#target_relate_field").html(data);
            $.each($("#related_field").find('option'), function () {
                if (this.value == $("input[name=fields_value]").val()) {
                    $(this).attr('selected', 'selected');
                }
            });
            $('#loading-image').hide();
        },
    });
}
//check for email template is exists or not.
function check_for_template(el) {

    $('#survey_name').parent().find('i').hide();
    $('#survey_name').parent().find('input[name=preview_template]').show();
    var record = $(el).val();
    var module = '';
    if ($("#module_name").val() == 0) {
        module = $("input[name=target_module]").val();
    } else {
        module = $("#module_name").val();
    }
    $.ajax({
        url: "index.php",
        data: {
            module: 'bc_survey',
            action: 'checkEmailTemplateForSurvey',
            survey_ID: record,
        },
        success: function (data, textStatus, jqXHR) {

            var result = $.trim(data);
            if ($("#survey_name").val() != 0) {
                var html = '';
                if (result) {
                    html += '<input style="width: auto;margin-top: 11px;" type="button" id="' + result + '" style="" name="preview_template" Value="Preview Email Template" onclick="window.open(\'index.php?module=EmailTemplates&action=DetailView&record=' + result + '\');"/>';
                } else {
                    html += '<input style="width: auto;margin-top: 11px;" type="button" name="create_template" style="" onclick="chkBeforeCreatetemplate(\'' + record + '\');" Value="Create Email Template"/>';
                }
                
                if ($("#survey_name").parent().find('input[type=button]').length < 1) {
                    $("#survey_name").after(html);
                } else {
                    $("#survey_name").parent().find('input[type=button]').replaceWith(html);
                }
            } else {
                $("#survey_name").parent().find('input[type=button]').remove();
            }
        }
    });
}
//onclick create email template check for email template.
function chkBeforeCreatetemplate(surveyId) {
    $.ajax({
        url: "index.php",
        data: {
            module: 'bc_survey',
            action: 'checkEmailTemplateForSurvey',
            survey_ID: surveyId,
        },
        success: function (data, textStatus, jqXHR) {
            var result = $.trim(data);
            var html = '';
            if (result) {
                html += '<input style="width: auto;margin-top: 9px;" type="button" id="' + result + '" style="" name="preview_template" Value="Preview Email Template" onclick="window.open(\'index.php?module=EmailTemplates&action=DetailView&record=' + result + '\');"/>';
            } else {
                html += '<input style="width: auto;margin-top: 9px;" type="button" name="create_template" style="" onclick="chkBeforeCreatetemplate(\'' + surveyId + '\');" Value="Create Email Template"/>';
                window.open('index.php?module=EmailTemplates&action=EditView&return_module=EmailTemplates&return_action=DetailView&survey_name='+surveyId);
            }
            if ($("#survey_name").parent().find('input[type=button]').length < 1) {
                $("#survey_name").after(html);
            } else {
                $("#survey_name").parent().find('input[type=button]').replaceWith(html);
            }
        }
    });
}
//save action from popup
function save_action(el) {

    var email_id = '';
    var record = $('#survey_name').val();
    var flag = validation_fields();
    if (flag == true) {
        $.ajax({
            url: "index.php",
            data: {
                module: 'bc_survey',
                action: 'checkEmailTemplateForSurvey',
                survey_ID: record,
            },
            success: function (data, textStatus, jqXHR) {

                email_id = $.trim(data);
                $("#value_row").find('input[type=text]').attr("id", 'condition_value');
                var rec_type = $("#module").val();
                var email_type_one = $("#email_type").val();
                var survey_name_one = $("#survey_name").val();
                var automizer_id = $("input[name=record]").val();
                var action_id = $("input[name=action_id]").val();
                var filter_by_fields = $("#filter_by_fields").val();
                var fields_list = '';
                var module_name = '';
                if (rec_type == "target_module") {
                    fields_list = $("#target_relate_field").val();
                    module_name = $("input[name=base_module]").val();
                } else {
                    fields_list = $("#fields_list").val();
                    module_name = $("#module_name").val();
                }
                var operator_selection = $(".operator_selection").val();
                var value = $("#condition_value").val();
                if (email_id) {
                    $.ajax({
                        url: "index.php",
                        data: {
                            module: 'bc_automizer_actions',
                            action: 'save_actionview',
                            rec_type: rec_type,
                            email_type: email_type_one,
                            survey_name: survey_name_one,
                            module_name: module_name,
                            automizer_id: automizer_id,
                            email_id: email_id,
                            action_id: action_id,
                            filter_by: filter_by_fields,
                            fields_list: fields_list,
                            operator_selection: operator_selection,
                            value: value,
                        },
                        success: function (data, textStatus, jqXHR) {
                            close_survey_div();
                            retrive_action_records();
                        }
                    });
                } else {
                    alert('Please create email template for selected survey.');
                }
            },
        });
    }
}
//validation function for action
function validation_fields() {
    $("#survey_main_div").find('i').hide();
    var flag = true;
    var relate_module = $("#module").val();
    var module_name = $("#module_name").val();
    var survey_name = $("#survey_name").val();
    if (relate_module == "related_module") {
        if (module_name == 0) {
            $("#module_list").find('i').show();
            flag = false;
        } else {
            var filter_by_fields = $("#filter_by_fields").val();
            if (filter_by_fields == "any_field") {
                var field_list = $("#fields_list").val();
                if (!field_list) {
                    $("#fields_row").find('i').show();
                    flag = false;
                } else {
                    flag = true;
                }
            } else {
                flag = true;
            }
        }
    }
    if (relate_module == "none") {
        $("#relate_module").find('i').show()
        flag = false;
    }
    if (survey_name == 0) {
        $("#survey_show").find('i').show()
        flag = false;
    }
    return flag;
}
//retrive action in list
function retrive_action_records() {
    var automizer_id = $("input[name=record]").val();
    $.ajax({
        url: "index.php",
        data: {
            module: 'bc_automizer_actions',
            action: 'retrive_actions',
            automizer_id: automizer_id,
        },
        success: function (data, textStatus, jqXHR) {
            var detaildata = JSON.parse(data);
            var html = '';
            $.each(detaildata, function (index, value) {

                html += '<tr id="' + value.id + '" class="' + value.module + '">';
                html += '<td  width="10%">' + index + '</td>';
                html += '<td  width="10%">' + value.recipient_module + '</td>';
                html += '<td  width="10%">' + value.module + '</td>';
                html += '<td  width="10%">' + value.recipient_email_field + '</td>';
                html += '<td  width="10%">' + value.survey + '</td>';
                html += '<td  width="10%"><a style="cursor:pointer;" onclick="edit_action_record(this)">Edit</a>&nbsp;&nbsp;/&nbsp;&nbsp;<a style="cursor:pointer;"onclick="delete_action_record(this)">Delete</a></td></tr>';
            });
            $('#action_table').find('tbody').eq(2).html(html);
        }
    });
}
//delete action from list view
function delete_action_record(el) {
    if (confirm("Are you sure to Remove this Action ?")) {
        var record_id = $(el).parent().parent().attr('id');
        $.ajax({
            url: "index.php",
            data: {
                module: 'bc_automizer_actions',
                action: 'delete_record',
                record_id: record_id,
            },
            success: function (result) {
                retrive_action_records();
            }
        });
    }
}
//edit action from action popup
function edit_action_record(el) {
    var record_id = $(el).parent().parent().attr('id');
    $.ajax({
        url: "index.php",
        data: {
            module: 'bc_automizer_actions',
            action: 'edit_record',
            record_id: record_id,
        },
        success: function (result, textStatus, jqXHR) {
            var detaildata = JSON.parse(result);
            $.each(detaildata, function (index, detail) {
                var base_module = $('input[name=base_module]').val();
                $.ajax({
                    url: "index.php",
                    data: {
                        module: 'bc_automizer_actions',
                        action: 'get_module_list',
                        module_name: base_module,
                    },
                    success: function (data, textStatus, jqXHR) {
                        $('body').append('<div id="backgroundpopup">&nbsp;</div>');
                        $('body').append('<form name="EditView" id="EditView"><div id="survey_main_div">' +
                                '<div id="survey_content">' +
                                '<div id="button_div">' +
                                '<table class="list view table footable-loaded footable default suite-model-table survey-auto-table" cellpadding="5px" cellspacing="5px" style="width: 100%;">' +
                                '<thead><tr><th style="width: 100%;" colspan="2"><h2>Create Survey Automation Action</h2></th></tr></thead>' +
                                '<input type="hidden" value="' + detail.id + '" name="action_id">' +
                                '<input type="hidden" value="' + detail.module + '" name="module_select">' +
                                '<input type="hidden" value="' + detail.filter_by + '" name="filter_by">' +
                                '<input type="hidden" value="' + detail.recipient_field + '" name="fields_value">' +
                                '<input type="hidden" value="' + detail.recipient_operator + '" name="operator_value">' +
                                '<input type="hidden" value="' + detail.compare_value + '" name="compare_value">' +
                                '<input type="hidden" value="' + detail.survey + '" name="survey_name">' +
                                '<tbody>' +
                                '<tr id="relate_module"><td class="lable">Module Type</td><td class="table-desc"><select id="module" style="" onchange="show_module_list(this)"><option value="none">Select Recipient Module</option><option value="related_module">Recipient associated with a related module</option><option value="target_module">Recipient associated with the target module</option></select>&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:red; display:none;"></i></td></tr>' +
                                '<tr id="module_list" style="display:none;"><td class="lable">Recipient Module</td><td class="table-desc"><select style="" id="module_name" name="module_name" onchange="show_fields_list(this)">' + data + '</select>&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:red; display:none;"></i></td>' +
                                '<tr id="filter_row" style="display:none;"><td class="lable">Filter By</td><td class="table-desc"><select style="" id="filter_by_fields" onchange="show_relate_fields(this)"><option value="all_fields">All Related</option><option value="any_field">Any Related</option></select></td></tr>' +
                                '<tr id="fields_row" style="display:none;"><td class="lable">Field</td><td class="table-desc"><select id="fields_list" style=" " onchange="show_operator_value(this)"></select>&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:red; display:none;"></i></td></tr>' +
                                '<tr id="operator_row" style="display:none;"><td class="lable">Operator</td><td class="table-desc"><select class="operator_selection" style=""><option value="Equal_To">Equals to</option><option value="Not_Equal_To">Not Equals to</option></select></td></tr>' +
                                '<tr id="value_row" style="display:none;"><td class="lable">Value</td><td class="table-desc"></td></tr>' +
                                '<tr id="related_field" style="display:none;"><td class="lable">Related Field</td><td class="table-desc"><select id="target_relate_field" style=""></select></td></tr>' +
                                '<tr id="email_show" style="display:none;"><td class="lable">Recipient Email</td><td class="table-desc"><select style="" id="email_type" name="email_name"><option value="to">To</option><option value="cc">Cc</option><option value="bcc">Bcc</option></select></td>' +
                                '<tr id="survey_show" style="display:none;"><td class="lable">Survey</td><td class="table-desc"><select id="survey_name" style="" name="survey_name" onclick="check_for_template(this)"></select>&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:red; display:none;"></i></td>' +
                                '</tr></tbody>' +
                                '<tfoot><tr><td style="width: 100%;" colspan="2">' +
                                '<input type="button" onclick="save_action(this)" value="Update Action">&nbsp;&nbsp;&nbsp;' +
                                '<input style="float:right" type="button" value="Cancel" onclick="close_survey_div()">' +
                                '</td></tr></tfoot>' +
                                '</table>' +
                                '</div>' +
                                '</div>' +
                                '<a onclick="close_survey_div();" href="javascript:close_survey_div();"></a>' +
                                '</div></form>');
                        $('#backgroundpopup').fadeIn();
                        $('#survey_main_div').fadeIn();
                        $('#survey_main_div').append('<img src="themes/default/images/loading.gif" id="loading-image"  class="ajax-loader" style="display:none; left: 30%; top: 70%; position: absolute;"/>');
                        if ($("#module_name").find('option').length == 1) {
                            $("#module").find('option').eq(1).remove();
                        }
                        $.each($("#module").find('option'), function () {
                            if (this.value == detail.recipient_type) {
                                $(this).attr('selected', 'selected');
                                $(this).trigger('change');
                            }
                        });
                        $("#survey_name").after('<input style="width:auto;margin-top: 11px;" type="button" id="' + detail.email_template_id + '" name="preview_template" Value="Preview Email Template" onclick="window.open(\'index.php?module=EmailTemplates&action=DetailView&record=' + detail.email_template_id + '\');"/>');
                        $.each($("#email_type").find('option'), function () {
                            if (this.value == detail.recipient_email_field) {
                                $(this).attr('selected', 'selected');
                            }
                        });
                        $.each($("#module_list").find('option'), function () {
                            if (this.value == $("input[name=module_select]").val()) {
                                $(this).attr('selected', 'selected');
                                $(this).trigger('change');
                            }
                        });
                        $.each($("#filter_row").find('option'), function () {
                            if (this.value == $("input[name=filter_by]").val()) {
                                $(this).attr('selected', 'selected');
                                $(this).trigger('change');
                            }
                        });
                    },
                });
            });
        }
    });
}