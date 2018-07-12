/**
 * All Drag Drop Functionality at EditView and Detailview .
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
/**
 * show hide theme and page component
 *
 * @author     Original Author Biztech Co.
 * @param      string - el
 * @return
 */
function change_pagecompo(el) {
    if ($(el).attr('id') == "page")
    {
        $(".custom_theme_inner").hide();
        $(".list-group").show();
        $(el).parent().find('#page').addClass('active');
        $(el).parent().find('#theme').removeClass("active");
    } else {
        $(".custom_theme_inner").show();
        $(".list-group").hide();
        $(el).parent().find('#theme').addClass('active');
        $(el).parent().find('#page').removeClass("active");
    }
}
function validate_logic_tab(el, pg_no, ques_no) {
    var action_value = $(el).parent().parent().find('.condition').find('select').find('option:selected');
    var flag = true;
    $.each(action_value, function () {
        $(this).parent().parent().parent().next().find('.validation_error').remove();
        if (this.value == "redirect_url") {
            $(this).parent().parent().parent().next().find('.logic-url-box').css('border-color', '#94c1e8');
            if ($(this).parent().parent().parent().next().find('.logic-url-box').val() == "http://" || $(this).parent().parent().parent().next().find('.logic-url-box').val() == "") {
                $(this).parent().parent().parent().parent().parent().parent().parent().parent().find('.lgc').css("color", "red");
                $(this).parent().parent().parent().next().find('.logic-url-box').css('border-color', 'red');
                flag = false;
            }
            $(this).parent().parent().parent().next().find('i').remove()
        } else if (this.value == "show_hide_question") {
            if (!$(this).parent().parent().parent().next().find('.show_hide_dd').val()) {
                $(this).parent().parent().parent().parent().parent().parent().parent().parent().find('.lgc').css("color", "red");
                if ($(this).parent().parent().parent().next().find('.validation_error').length == 0) {
                    $(this).parent().parent().parent().next().append("&nbsp;&nbsp;&nbsp;<span class='validation_error' style='color: red;font-size:12px;margin-top: 8px;display: inline-block;'>Select At Least One Question.</span>");
                }
                flag = false;
            }
        }
        if (flag == true) {
            $(this).parent().parent().parent().parent().parent().parent().parent().parent().find('.lgc').css("color", "#000");
        }
    });
    return flag;
}
/**
 * show hide Advance option and general option
 *
 * @author     Original Author Biztech Co.
 * @param      string - el
 * @param      integer - pg_no,que_no
 * @return
 */
function change_tab(el, pg_no, que_no) {
    var validation_meassge = true;
    if ($(el).attr('id') == 'gen') {
        validation_meassge = validate_logic_tab(el, pg_no, que_no);
        if (validation_meassge == true) {
            $(el).parent().find('#gen').addClass('active');
            $(el).parent().find('#adv').removeClass("active");
            $(el).parent().find('#lgc').removeClass("active");
            $(el).parent().find('#pipe').removeClass('active');
            $("#advanced" + pg_no + que_no).hide();
            $("#logic_skipp" + pg_no + que_no).hide();
            $('#pipe' + pg_no + que_no).hide();
            $("#general" + pg_no + que_no).show();
        }
    } else if ($(el).attr('id') == 'adv') {
        validation_meassge = validate_logic_tab(el, pg_no, que_no);
        if (validation_meassge == true) {
            var op_length = $('.answer' + pg_no + que_no).length;
            var other = $('#general' + pg_no + que_no).find('.other_option').find('input[type=checkbox]').prop('checked');
            if (other) {
                op_length += 1;
            }
            for (var i = 1; i <= op_length; i++) {
                if ($('#limit_dropdown' + pg_no + que_no + ' option[value=' + i + ']').length == 0) {
                    $('#limit_dropdown' + pg_no + que_no).append('<option value=' + i + '>' + i + '</option>');
                }
            }
            $(el).parent().find('#adv').addClass('active');
            $(el).parent().find('#gen').removeClass("active");
            $(el).parent().find('#lgc').removeClass("active");
            $(el).parent().find('#pipe').removeClass('active');
            $("#general" + pg_no + que_no).hide();
            $("#logic_skipp" + pg_no + que_no).hide();
            $('#pipe' + pg_no + que_no).hide();
            $("#advanced" + pg_no + que_no).show();
        }
    } else if ($(el).attr('id') == 'pipe') {
        validation_meassge = validate_logic_tab(el, pg_no, que_no);
        if (validation_meassge == true) {
            if ($('#enable_data_piping:checked').length) {
                $(el).parent().find('#adv').removeClass('active');
                $(el).parent().find('#gen').removeClass("active");
                $(el).parent().find('#lgc').removeClass("active");
                $(el).parent().find('#pipe').addClass('active');
                $("#general" + pg_no + que_no).hide();
                $("#logic_skipp" + pg_no + que_no).hide();
                $("#advanced" + pg_no + que_no).hide();
                $('#pipe' + pg_no + que_no).show();
            } else {
                alert('Data Piping is not enabled for this survey. Please check \'Enable Piping\' checkbox to enable data piping feature.');
            }
        }
    } else {
        if ($(el).parent().parent().find('#question_table' + pg_no + que_no).find('#que_id' + pg_no + que_no).val()) {
            var optionsArr = $('.answer' + pg_no + que_no);
            $.each(optionsArr, function (item, val) {
                var data = '';
                var optionId = $(val).parent().parent().find('#ans_id').val();
                if (optionId == '' || optionId == null) {
                    optionId = val.id;
                }
                var optionLable = val.value;
                var exist_logicTr = $('#logic_skipp' + pg_no + que_no).find('.opid_' + optionId).attr('class');
                if (exist_logicTr == '' || exist_logicTr == null) {
                    $(val).parent().parent().find('#ans_id').val(optionId);
                    data += "<tr class='opid_" + optionId + "' id='con_value" + pg_no + que_no + item + "'><td class='logic-table-first-column'><label style='color:#000;'>" + val.value + "</label></td><td class='condition'><span><select style='width: auto;cursor:pointer;' onchange='skipp_logic(this," + pg_no + "," + que_no + "," + item + ")' name='option_value[" + pg_no + "][" + que_no + "][" + item + "]'><option value='no_logic'>No Logic</option><option value='redirect_page'>Redirect To Page</option><option value='end_page'>End Of Survey</option><option value='redirect_url'>Redirect to Url</option><option value='show_hide_question'>Show/Hide Questions</option></select></td><td></td><td style='color:#000; text-align:center;'><a id='clear_" + pg_no + que_no + item + "' onclick='clear_element(this," + pg_no + "," + que_no + "," + item + ")' class='fa fa-remove' style='cursor:pointer; font-size:14px;margin: 5px 3px;'></a></td></tr>";
                    if ($('#logic_table' + pg_no + que_no).find('.opid_' + optionId).length == 0) {
                        if ($(el).parent().parent().find('#question_table' + pg_no + que_no).parent().find('.warning_outer').find('input[type=checkbox]').prop('checked') && $('#logic_table' + pg_no + que_no).find('.Other_Option').length != 0) {
                            var no_ans = $('#question_table' + pg_no + que_no).find('.answer-section').find('li').length;
                            $('#logic_table' + pg_no + que_no).find('.Other_Option').find('select').attr('id', 'skipp_logic_function' + pg_no + que_no + no_ans);
                            $('#logic_table' + pg_no + que_no).find('.Other_Option').find('select').attr('name', 'option_value[' + pg_no + '][' + que_no + '][' + no_ans + ']');
                            $('#logic_table' + pg_no + que_no).find('.Other_Option').find('select').attr('onchange', 'skipp_logic(this,' + pg_no + ',' + que_no + ',' + no_ans + ')');
                            $('#logic_table' + pg_no + que_no).find('.Other_Option').attr('id', 'con_value' + pg_no + que_no + no_ans);
                            $('#logic_table' + pg_no + que_no).find('.Other_Option').before(data);
                        } else if ($(val).attr('class').split(' ')[1] == "Other" && $(el).parent().parent().find('#question_table' + pg_no + que_no).parent().find('.warning_outer').find('input[type=checkbox]').prop('checked')) {
                            $('#logic_skipp' + pg_no + que_no).find('table').append(data);
                        }
                    }
                } else {
                    $('#logic_skipp' + pg_no + que_no).find('.opid_' + optionId).find("label").html(optionLable);
                }
                if (!$(el).parent().parent().find('#question_table' + pg_no + que_no).parent().find('.warning_outer').find('input[type=checkbox]').prop('checked')) {
                    $('#logic_table' + pg_no + que_no).find('.Other_Option').remove();
                }
                $.each($("#skipp_logic_function" + pg_no + que_no + item).find('option'), function () {
                    if (this.value == $("#action" + pg_no + que_no + item).val()) {
                        $(this).attr('selected', 'selected');
                        $(this).trigger('change');
                    }
                });
            });
            $(el).parent().find('#lgc').addClass('active');
            $(el).parent().find('#gen').removeClass("active");
            $(el).parent().find('#adv').removeClass("active");
            $(el).parent().find('#pipe').removeClass("active");
            $("#logic_skipp" + pg_no + que_no).show();
            $('#pipe' + pg_no + que_no).hide();
            $("#general" + pg_no + que_no).hide();
            $("#advanced" + pg_no + que_no).hide();
        } else {
            alert(" You must save this Survey before using skip logic.");
        }
    }
}

function warning_message(el, page_no, que_no) {
    var msg = 0;
    $.each($('#logic_table' + page_no + que_no).find('.logic-page-select'), function () {
        var warn = parseInt($('#' + this.value).val());
        if (page_no > warn) {
            msg = 1
        }
    });
    if (msg == 1) {
        $('#logic_skipp' + page_no + que_no).find(".warning_outer").show();
    } else {
        $('#logic_skipp' + page_no + que_no).find(".warning_outer").hide();
    }
}

function skipp_logic(el, page_no, que_no, con_no) {
    var selcted_val = $(el).val();
    var target_value = $('#target' + page_no + que_no + con_no).val();
    var data = '';
    var msg = 0;
    $.each($('.clear_all' + page_no + que_no), function () {
        if (this.value == "redirect_page") {
            var page_id = $(this).parent().parent().next().find('.logic-page-select').val();
            var warn = parseInt($('#' + page_id).val());
            if (page_no > warn) {
                msg = 1
            }
        }
    });
    if (msg == 1) {
        $('#logic_skipp' + page_no + que_no).find(".warning_outer").show();
    } else {
        $('#logic_skipp' + page_no + que_no).find(".warning_outer").hide();
    }
    if (selcted_val == "redirect_page") {

        data += '<select style="width: auto;" onchange="warning_message(this,' + page_no + ',' + que_no + ')" id="page_skipp_logic' + page_no + que_no + con_no + '" class="logic-page-select" name="page_skipp_logic[' + page_no + '][' + que_no + '][' + con_no + ']"><option value="0">Select</option>';
        var cnt = 0;
        $.each($('.pagetitle_text'), function (pg_indx, attr) {
            var page_id = $("#page_" + pg_indx).find("#page_id" + pg_indx).val();
            if (page_id) {
                var id = $(this).attr('id');
                if ("page_title" + page_no != "page_title" + pg_indx) {
                    data += ' <option value="' + page_id + '">' + this.value + '</option>';
                    cnt = 1;
                }
            }
        });
        if (cnt == 1) {
            $("#logic_skipp" + page_no + que_no).find('#con_value' + page_no + que_no + con_no).find('td').eq(2).html(data);
        } else {
            alert('You Have Only One Page Which is Not Skipp.');
            $('#skipp_logic_function' + page_no + que_no + con_no).val('no_logic');
            $("#logic_skipp" + page_no + que_no).find('#con_value' + page_no + que_no + con_no).find('td').eq(2).empty();
        }
        $.each($('#page_skipp_logic' + page_no + que_no + con_no).find('option'), function (index, question_value) {
            if (this.value == target_value) {
                $(this).attr('selected', 'selected').trigger('change');
            }
        });
    } else if (selcted_val == "redirect_url") {
        var action_name = $('#action' + page_no + que_no + con_no).val();
        if (!target_value || action_name != "redirect_url") {
            data += '<input class="input-text logic-url-box" type="text" name="page_skipp_logic[' + page_no + '][' + que_no + '][' + con_no + ']" value="http://">';
        } else {
            data += '<input class="input-text logic-url-box" type="text" name="page_skipp_logic[' + page_no + '][' + que_no + '][' + con_no + ']" value="' + target_value + '">';
        }
        $("#logic_skipp" + page_no + que_no).find('#con_value' + page_no + que_no + con_no).find('td').eq(2).html(data);
    } else if (selcted_val == "show_hide_question") {
        try {
            var que = target_value.split(',');
        } catch (e) {
        }
        data += '<select style="width: auto;" class="show_hide_dd" id="show_hide_dd' + page_no + que_no + con_no + '" name="page_skipp_logic[' + page_no + '][' + que_no + '][' + con_no + '][]" multiple>';
        var cnt = 0;
        var flag = false;
        $.each($('#page_' + page_no).find('.que_title'), function (index, question) {
            if ($(this).parent().parent().prev().find('input[type=hidden]').val() != "Video") {
                var que_title = $("#que_" + page_no + que_no).find('.que_title').val();
                var qn_id = $(this).parent().parent().parent().parent().parent().find(".question-section").find('input[type=hidden]').val();
                if (que_title != question.value && flag) {
                    if (qn_id) {
                        data += '<option value="' + qn_id + '">' + question.value + '</option>';
                        cnt = 1;
                    }
                } else if (que_title == question.value) {
                    flag = true;
                    cnt = 2;
                }
            }
        });
        $.each($('#page_' + page_no).find('.title'), function (index, question) {
            var qn_id = $(this).parent().parent().parent().parent().parent().find(".question-section").find('input[type=hidden]').val();
            if (qn_id) {
                data += '<option value="' + qn_id + '">' + question.value + '</option>';
                cnt = 1;
            }
        });
        if (cnt == 1) {
            $("#logic_skipp" + page_no + que_no).find('#con_value' + page_no + que_no + con_no).find('td').eq(2).html(data);
        } else if (cnt == 2) {
            alert('There is no any further Question to skip');
            $('#skipp_logic_function' + page_no + que_no + con_no).val('no_logic');
            $("#logic_skipp" + page_no + que_no).find('#con_value' + page_no + que_no + con_no).find('td').eq(2).empty();
        }
        else {
            alert('You Have Only One Question On this Page Which is Not Skip.');
            $('#skipp_logic_function' + page_no + que_no + con_no).val('no_logic');
            $("#logic_skipp" + page_no + que_no).find('#con_value' + page_no + que_no + con_no).find('td').eq(2).empty();
        }
        try {
            $.each($('#show_hide_dd' + page_no + que_no + con_no).find('option'), function (index, question_value) {
                if ($.inArray(this.value, que) != -1) {
                    $(this).attr('selected', 'selected');
                }
            });
        }
        catch (e) {
        }
        $('.show_hide_dd').multipleSelect({filter: true});
    } else {
        $("#logic_skipp" + page_no + que_no).find('#con_value' + page_no + que_no + con_no).find('td').eq(2).empty();
    }
}
function clear_element(el, page_no, que_no, con_no) {
    var action = $("#skipp_logic_function" + page_no + que_no + con_no).val();
    if (action != "no_logic") {
        if (confirm('Are you sure to remove this logic?')) {
            $("#action" + page_no + que_no + con_no).val('');
            $("#target" + page_no + que_no + con_no).val('');
            $("#skipp_logic_function" + page_no + que_no + con_no).val("no_logic").trigger('change');
        }
    }
}
function clear_all_element(el, page_no, que_no) {
    var count = 0;
    $.each($('.condition').find('.clear_all' + page_no + que_no), function () {
        if ($(this).val() != 'no_logic') {
            count = 1;
        }
    });
    if (count == 1) {
        if (confirm('Are you sure to remove all logic from this Question?')) {
            $.each($('.action_clear_all' + page_no + que_no), function () {
                $(this).val('');
            });
            $.each($('.clear_all' + page_no + que_no), function () {
                $(this).val('no_logic').trigger('change');
            });
            $.each($('.target_clear_all' + page_no + que_no), function () {
                $(this).val('');
            });
        }
    }
}
/**
 * show hide Advance option of textbox
 *
 * @author     Original Author Biztech Co.
 * @param      string - el
 * @param      integer - pageno,que_no
 * @return
 */
function textbox_dropdown(el, pageno, que_no) {
    var data_type = $("#data_type" + pageno + que_no).val();
    $('#data_type_hidden' + pageno + que_no).val(data_type);
    var data = "";
    if (data_type == "Integer") {
        $(".integer" + pageno + que_no).parent().find('li').eq(3).hide();
        $(".integer" + pageno + que_no).show();
        $(".float" + pageno + que_no).hide();
    } else if (data_type == "Float") {
        $(".integer" + pageno + que_no).parent().find('li').eq(3).hide();
        $(".integer" + pageno + que_no).hide();
        $(".float" + pageno + que_no).show();
    } else if (data_type == "Email") {
        $(".integer" + pageno + que_no).hide();
        $(".float" + pageno + que_no).hide();
        $(".integer" + pageno + que_no).parent().find('li').eq(3).hide();
    } else if (data_type == "Char") {
        $(".integer" + pageno + que_no).hide();
        $(".float" + pageno + que_no).hide();
        $(".integer" + pageno + que_no).parent().find('li').eq(3).show();
    } else {
        $(".integer" + pageno + que_no).hide();
        $(".float" + pageno + que_no).hide();
        $(".integer" + pageno + que_no).parent().find('li').eq(3).hide();
    }
}
/**
 * show hide contact information check boxes
 *
 * @author     Original Author Biztech Co.
 * @param      string - el
 * @param      integer - page_no,que_no
 * @return
 */
function change_checkbox(el, page_no, que_no) {
    if ($(el).prop('checked') == true)
    {
        $(el).parent().parent().parent().find('.row' + page_no + que_no).show();
    } else {
        $(el).parent().parent().parent().find('.row' + page_no + que_no).hide();
    }
}
/**
 * show hide image type question radiobutton change
 *
 * @author     Original Author Biztech Co.
 * @param      string - el
 * @param      integer - page_no,que_no
 * @return
 */
function change_radio(el, page_no, que_no) {
    if ($(el).attr('class') == 'url') {
        $(el).parent().parent().parent().find('.upload' + page_no + que_no).hide();
        $(el).parent().parent().parent().find('.img_src' + page_no + que_no).hide();
        $(el).parent().parent().parent().find('.url' + page_no + que_no).show();
    } else {
        $(el).parent().parent().parent().find('.url' + page_no + que_no).hide();
        if ($(el).parent().parent().parent().find('.img_src' + page_no + que_no).length == 1) {
            $(el).parent().parent().parent().find('.img_src' + page_no + que_no).show();
        } else {
            $(el).parent().parent().parent().find('.upload' + page_no + que_no).show();
        }
    }
}
/**
 * click on remove button of image type question
 *
 * @author     Original Author Biztech Co.
 * @param      string - el
 * @param      integer - page_no,que_no
 * @return
 */
function change_image(el, page_no, que_no, adv_type) {
    $(el).parent().find('input[type=hidden]').val('');
    var que_id = $(el).parent().parent().parent().find("#que_id" + page_no + que_no).val();
    if (confirm("Are you sure you want to remove logo?")) {
        $.ajax({
            url: "index.php",
            data: {
                module: 'bc_survey_questions',
                action: 'removeImageQuestionFromEdit',
                record: que_id,
                adv_type: adv_type,
            },
            success: function (result) {
                $(el).parent().parent().parent().find('.img_src' + page_no + que_no).hide();
                $(el).parent().parent().parent().find('.upload' + page_no + que_no).show();
            }
        });
    }
}
/**
 * validate Numeric value of textbox ,scale type question
 *
 * @author     Original Author Biztech Co.
 * @param      string - el
 * @param      integer - page_no,que_no
 * @return
 */
function validateNumbericValue(el, page_no, que_no) {
    //if the letter is not digit then display error and don't type anything
    if (el.which != 8 && el.which != 0 && el.which != 45 && (el.which < 48 || el.which > 57)) {
        //display error message
        alert('Please enter only numeric values(0-9)');
        return false;
    }
}
/**
 * validate decimal value of textbox type question
 *
 * @author     Original Author Biztech Co.
 * @param      string - el
 * @param      integer - page_no,que_no
 * @return
 */
function validateDecimalValue(el, page_no, que_no) {
    var dot_flag = $(el.currentTarget).val().includes('.');
    //if the letter is not digit then display error and don't type anything
    if ((el.which != 8 && el.which != 0 && (el.which < 48 || el.which > 57) && el.which != 46) || (dot_flag && el.which == 46)) {
        //display error message
        alert('Please enter only numeric values(0-9) and only 1 dot(.)');
        return false;
    }
}
/**
 * validate Image type question
 *
 * @author     Original Author Biztech Co.
 * @param      string - el
 * @param      integer - page_no,que_no
 * @return
 */
function validate_Imagesize(files, page_no, que_no) {
    $('#logo_validate').html('');
    var file, img;
    if ((file = files[0])) {
        var ext = file.type.split('/');
        if ($.inArray(ext[1], ['png', 'jpg', 'jpeg']) == -1) {
            alert('Please upload only png , jpg and jpeg image file.');
            $("#file" + page_no + que_no).val('');
        }
    }
}
function score_value_display(el, page_no, que_no) {
    if ($(el).prop('checked') == true) {
        $(el).parent().parent().parent().find('.score_weight' + page_no + que_no).show();
        $(el).parent().parent().parent().parent().find('.other_option_div').find('span').show();
    } else {
        $(el).parent().parent().parent().find('.score_weight' + page_no + que_no).hide();
        $(el).parent().parent().parent().parent().find('.other_option_div').find('span').hide();
    }
}
function checkValue(el) {
    if (!$(el).val()) {
        $(el).val('0');
    }
}
function click_other_option(el, page_no, que_no) {
    if ($(el).prop('checked')) {
        $(el).parent().parent().next().show();
        var value = $('#advanced' + page_no + que_no).find('.limit_dropdown').find('select').find('option').length;
        $('#advanced' + page_no + que_no).find('.limit_dropdown').find('select').append('<option value="' + value + '">' + value + '</option>');
        var no_ans = $('#question_table' + page_no + que_no).find('.answer-section').find('li').length;
        var label = $(el).parent().parent().parent().find('.other_option_div').find('input[type=text]').val();
        var data = "<tr class='opid_ Other_Option' id='con_value" + page_no + que_no + no_ans + "'><td class='logic-table-first-column'><label style='color:#000;'>" + label + "</label></td><td class='condition'><span><select style='width: auto;cursor:pointer;' onchange='skipp_logic(this," + page_no + "," + que_no + "," + no_ans + ")' name='option_value[" + page_no + "][" + que_no + "][" + no_ans + "]'><option value='no_logic'>No Logic</option><option value='redirect_page'>Redirect To Page</option><option value='end_page'>End Of Survey</option><option value='redirect_url'>Redirect to Url</option><option value='show_hide_question'>Show/Hide Questions</option></select></td><td></td><td style='color:#000; text-align:center;'><a id='clear_" + page_no + que_no + no_ans + "' onclick='clear_element(this," + page_no + "," + que_no + "," + no_ans + ")' class='fa fa-remove' style='cursor:pointer; font-size:14px;margin: 5px 3px;'></a></td></tr>";
        $(el).parent().parent().parent().parent().find('#logic_table' + page_no + que_no).find('tr:last').after(data)
    } else {
        $(el).parent().parent().next().hide();
        $('#advanced' + page_no + que_no).find('.limit_dropdown').find('select').find('option:last').remove();
        $('#logic_table' + page_no + que_no).find('.Other_Option').remove();
    }
}
function other_option_value(el) {
    var value = $(el).val();
    if (value.trim() == "") {
        $(el).val('Other');
    }
}
function addSection(el, page_no, que_no) {
    var new_que_no = parseInt($('#page_' + page_no).find('input[name=que_no]').val());
    if ($(el).parents('#que_' + page_no + que_no).prev('.section_title').length == 0) {
        $(el).parents('#que_' + page_no + que_no).before('<div class="survey-body section_title" id="que_' + page_no + new_que_no + '"><div class="section row"><label style="align:right; width:12.5%;">Section Title: </label><input type="text" class="que_title" name="que_title[' + page_no + '][' + new_que_no + ']" id="section_title' + page_no + new_que_no + '" style="width:79%;" placeholder="(Required) Section Title"><input type="hidden" name="que_type[' + page_no + '][' + new_que_no + ']" value="question_section"/><a style="cursor:pointer;" id="remove_page_' + page_no + new_que_no + '" onclick="remove_section(this,' + page_no + ',' + new_que_no + ')"><i class="fa fa-remove" id="remove_section' + page_no + new_que_no + '" title="Remove Question" style="font-size:14px;margin: 5px 3px;"></i></a><span id="validate_name" class="error-msg"></span></div></div>');
    } else {
        alert("Section is already exists for this question.");
    }
    new_que_no += 1;
    $('#page_' + page_no).find('input[name=que_no]').val(new_que_no);
}
function remove_section(el, page_no, que_no) {
    if (confirm("Are you sure to remove this Question section ?")) {
        if ($("#que_deleted").val()) {
            $("#que_deleted").val($("#que_deleted").val() + ',' + $(el).parent().find('#que_id_del' + page_no + que_no).val());
        } else {
            $("#que_deleted").val($(el).parent().find('#que_id_del' + page_no + que_no).val());
        }
        $(el).parents('.section_title').remove();
    }
}
function desablepiping_checkbox(el, page_no, que_no) {
    var sync_field = $(el).parent().parent().find('#sync_field' + page_no + que_no).val();
    if ($(el).prop('checked')) {
        $(el).parent().parent().find('#sync_field' + page_no + que_no).parent().hide();
        $(el).parent().parent().parent().parent().find('#data_type' + page_no + que_no).removeAttr('disabled');
        if (this.value != "0") {
            var que_type = $(el).parent().parent().find('#sync_field' + page_no + que_no).parent().parent().parent().parent().find('.s-type').find('input[type=hidden]').val();
            if (sync_field && sync_field != 0 && (que_type == "DrodownList" || que_type == "MultiSelectList" || que_type == "RadioButton")) {
                $.each($(this).parent().parent().parent().parent().find('.s-option').find('#ans_id'), function () {
                    if ($('#ans_deleted').val()) {
                        $("#ans_deleted").val($("#ans_deleted").val() + ',' + $(this).val());
                    } else {
                        $("#ans_deleted").val($(this).val());
                    }
                });
                var data_option = '';
                data_option += "<ul class='answer-section' id='answer_div" + page_no + "" + que_no + "' class='answer_div'><li id='ans_op" + page_no + "" + que_no + "0'><label class='left-label'>Option </label><span><input type='text' name='answer[" + page_no + "][" + que_no + "][0]' class='answer" + page_no + "" + que_no + "'/></span>&nbsp;<input type='number' name='score_dropdownlist[" + page_no + "][" + que_no + "][0]' value='1' onblur='checkValue(this)' class='score_weight" + page_no + que_no + "' style='margin-left: 6px; text-align: center;max-width: 7%;height: 22px;display:none;'><input type='button' value='&#10005;' name='btnRemoveOption' style='display:none;margin-left: 9px;height:25px;width:25px;' onclick='removeOption(" + page_no + "," + que_no + ",this)'/>";
                data_option += "<input type='hidden' name='ans_id[" + page_no + "][" + que_no + "][0]' id='ans_id' style='width: 37.5%' />";
                data_option += "<input type='hidden' name='ans_id_del[" + page_no + "][" + que_no + "][0]' id='ans_id_del' style='width: 37.5%' /></li>";
                data_option += "<li id='ans_op" + page_no + "" + que_no + "1'><label class='left-label'></label><span><input type='text' class='answer" + page_no + "" + que_no + "' name=answer[" + page_no + "][" + que_no + "][1]/></span>&nbsp;<input type='number' name='score_dropdownlist[" + page_no + "][" + que_no + "][1]' value='2' onblur='checkValue(this)' class='score_weight" + page_no + que_no + "' style='margin-left: 6px; text-align: center;max-width: 7%;height: 22px;display:none;'>";
                data_option += "<input type='hidden' name='ans_id[" + page_no + "][" + que_no + "][1]' id='ans_id' style='width: 37.5%' />";
                data_option += "<input type='hidden' name='ans_id_del[" + page_no + "][" + que_no + "][1]' id='ans_id_del' style='width: 37.5%' />&nbsp;";
                data_option += "<input type='button' value='&#10005;' name='btnRemoveOption' style='display:none;margin-left: 6px;height:25px;width:25px;' onclick='removeOption(" + page_no + "," + que_no + ",this)' /><input name='btnOptionAdd' type='button' value='&#43;' onclick='addNewOption(this," + page_no + "," + que_no + ")' style='margin:10px;height:25px;width:25px;'/>";
                data_option += "</li>";
                data_option += "</ul>";
                $(el).parent().parent().find('#sync_field' + page_no + que_no).parent().parent().parent().parent().find('.answer-section').replaceWith(data_option);
            }
            $(el).parent().parent().find('#sync_field' + page_no + que_no).val('0');
            $(el).parent().parent().parent().parent().find('#data_type' + page_no + que_no).removeAttr('disabled');
        }
    } else {
        $(el).parent().parent().find('#sync_field' + page_no + que_no).parent().show();
    }
}
function getfieldstype(el, page_no, que_no) {
    var module_name = $('#sync_module').val();
    var sync_field = $('#sync_field' + page_no + que_no).val();
    var count_previous = 0;
    $.each($('.fields_type'), function () {
        if (this.value == sync_field) {
            count_previous += 1
        }
    });
    if (count_previous == 1) {
        $.ajax({
            url: 'index.php',
            data: {
                module: 'bc_survey_questions',
                action: 'getfieldstype',
                module_name: module_name,
                sync_field: sync_field,
            },
            success: function (result) {
                var ans_id = Array();
                var ansid_cnt = 0;
                var title = $('#que_title' + page_no + que_no).val();
                var current_quetype = $('#que_type' + page_no + que_no).val();
                var help = $(el).parent().parent().parent().parent().find('.question_help_comment').val();
                var qn_id = $('#que_id' + page_no + que_no).val();
                $.each($(el).parent().parent().parent().parent().find('#ans_id'), function () {
                    ans_id[ansid_cnt] = $(this).val();
                    ansid_cnt += 1
                });
                var correct_data = JSON.parse(result);
                if (sync_field != "0" && current_quetype != correct_data.correct_que_type) {
                    if (confirm("By selecting this field question type will be changed and advance options fields will be reset. Are you sure that you want to proceed?")) {
                        if ($('#ans_deleted').val()) {
                            $('#ans_deleted').val($('#ans_deleted').val() + ',' + ans_id);
                        } else {
                            $('#ans_deleted').val(ans_id);
                        }
                        var data = addQueHTML(correct_data.correct_que_type, page_no, que_no);
                        $('#que_' + page_no + que_no).replaceWith(data);
                        if (correct_data.correct_que_type == "Textbox") {
                            $('#advanced' + page_no + que_no).find('.data_type').find('select').val(correct_data.correct_data_type).trigger('change');
                            $('#advanced' + page_no + que_no).find('.data_type').find('select').attr('disabled', true);
                        }

                        $('#sync_field_hidden' + page_no + que_no).val(sync_field);
                        $('#que_title' + page_no + que_no).val(title);
                        $('#que_title' + page_no + que_no).parent().parent().parent().parent().parent().find('.question_help_comment').val(help);
                        $.each($('#sync_field' + page_no + que_no).find('option'), function () {
                            if (this.value == sync_field) {
                                $(this).attr('selected', true);
                            }
                        });
                        var cnt = 0;
                        var ans_li_data = '';
                        var count_msg = 0
                        if ((typeof correct_data.options) == "object") {
                            $.each(correct_data.options, function (indx, value) {
                                if (value) {
                                    ans_li_data += '<li id="ans_op' + page_no + que_no + cnt + '">';
                                    if (cnt == 0) {
                                        ans_li_data += '<label class="left-label">Option </label>';
                                    } else {
                                        ans_li_data += '<label class="left-label">&nbsp;</label>';
                                    }
                                    ans_li_data += '<span><input type="text" name="answer[' + page_no + '][' + que_no + '][' + cnt + ']" class="answer' + page_no + que_no + '" value="' + value + '" readonly></span>\n\
                                    &nbsp;<input type="number" name="score_dropdownlist[' + page_no + '][' + que_no + '][' + cnt + ']" value="' + (cnt + 1) + '" onblur="checkValue(this)" class="score_weight' + page_no + que_no + '" style="margin-left: 6px; text-align: center;max-width: 7%;height: 22px;display:none;">\n\
                                      <input type="hidden" name="ans_id[' + page_no + '][' + que_no + '][' + cnt + ']" id="ans_id" style="width: 37.5%">\n\
                                      <input type="hidden" name="ans_id_del[' + page_no + '][' + que_no + '][' + cnt + ']" id="ans_id_del" style="width: 37.5%">\n\
                                        </li>';
                                    cnt += 1;
                                }
                            });
                            count_msg = 1;
                        }
                    } else {
                        $('#sync_field' + page_no + que_no).val($('#sync_field_hidden' + page_no + que_no).val());
                    }
                }
                if (correct_data.correct_que_type == "Textbox") {
                    if ($('#data_type_hidden' + page_no + que_no).val() != correct_data.correct_data_type && $('#data_type_hidden' + page_no + que_no).val() != correct_data.correct_data_type && !count_msg) {
                        if (confirm("By selecting this field question data type will be changed. Are you sure that you want to proceed?")) {
                            $('#advanced' + page_no + que_no).find('.data_type').find('select').val(correct_data.correct_data_type).trigger('change');
                            $('#advanced' + page_no + que_no).find('.data_type').find('select').attr('disabled', true);
                        }
                    }
                }

                if ((typeof correct_data.options) == "object" && !count_msg) {
                    var cnt = 0;
                    var ans_li_data = '';
                    if (confirm('By selecting this field question\'s options will be reset. Are you sure that you want to proceed?')) {
                        if ($('#ans_deleted').val()) {
                            $('#ans_deleted').val($('#ans_deleted').val() + ',' + ans_id);
                        } else {
                            $('#ans_deleted').val(ans_id);
                        }
                        $.each(correct_data.options, function (indx, value) {
                            if (value) {
                                ans_li_data += '<li id="ans_op' + page_no + que_no + cnt + '">';
                                if (cnt == 0) {
                                    ans_li_data += '<label class="left-label">Option </label>';
                                } else {
                                    ans_li_data += '<label class="left-label">&nbsp;</label>';
                                }
                                ans_li_data += '<span><input type="text" name="answer[' + page_no + '][' + que_no + '][' + cnt + ']" class="answer' + page_no + que_no + '" value="' + value + '" readonly></span>\n\
                                    &nbsp;<input type="number" name="score_dropdownlist[' + page_no + '][' + que_no + '][' + cnt + ']" value="' + (cnt + 1) + '" onblur="checkValue(this)" class="score_weight' + page_no + que_no + '" style="margin-left: 6px; text-align: center;max-width: 7%;height: 22px;display:none;">\n\
                                      <input type="hidden" name="ans_id[' + page_no + '][' + que_no + '][' + cnt + ']" id="ans_id" style="width: 37.5%">\n\
                                      <input type="hidden" name="ans_id_del[' + page_no + '][' + que_no + '][' + cnt + ']" id="ans_id_del" style="width: 37.5%">\n\
                                        </li>';
                                cnt += 1;
                            }
                        });
                    }
                }
                        $('#que_id' + page_no + que_no).val(qn_id);
                        $('#que_id_del' + page_no + que_no).val(qn_id);
                        $('#answer_div' + page_no + que_no).html(ans_li_data);
                        $('#general' + page_no + que_no).find('.warning_outer').hide();
                        $('#general' + page_no + que_no).find('.other_option_div').hide();
            },
        });
    } else {
        alert("Selected sync field already synced with another Survey Question.");
        $('#sync_field' + page_no + que_no).val($('#sync_field_hidden' + page_no + que_no).val());
    }
}
/**
 * add question while drag and drop
 *
 * @author     Original Author Biztech Co.
 * @param      integer - page_no,que_no
 * @param      String - queType
 * @return
 */
//for add question
function addQueHTML(queType, page_no, que_no) {
    var data = '';
    var que_icon = '';
    var multi_choice_que_type = ''; // store question type
    var multi_choice_que = '';
    var single_choice_que_type = ''; //store question for Textbox,CommentTextbox,etc
    var single_choice_que = '';
    var multi_choice_type_value = {
        "MultiSelectList": 'MultiSelect List',
        "Checkbox": 'Checkbox',
        "DrodownList": 'Dropdown List',
        "RadioButton": 'Radio Button',
    };
    var single_choice_type_value = {
        "Textbox": 'Textbox',
        "CommentTextbox": 'Comment Textbox',
        "ContactInformation": 'Contact Information',
        "Rating": 'Rating',
    };
    if ("Textbox" === queType) {
        que_icon = "<i class='fa fa-file-text-o' aria-hidden='true'></i>";
        single_choice_que_type = "Textbox";
        single_choice_que = true;
    } else if ("CommentTextbox" === queType) {
        que_icon = "<i class='fa fa-comments-o' aria-hidden='true'></i>";
        single_choice_que_type = "CommentTextbox";
        single_choice_que = true;
    } else if ("ContactInformation" === queType) {
        que_icon = "<i class='fa fa-list-alt' aria-hidden='true'></i>";
        single_choice_que_type = "ContactInformation";
        single_choice_que = true;
    } else if ("Rating" === queType) {
        que_icon = "<i class='fa fa-star' aria-hidden='true'></i>";
        single_choice_que_type = "Rating";
        single_choice_que = true;
    } else if ("MultiSelectList" === queType) {
        que_icon = "<i class='fa fa-list-ul' aria-hidden='true'></i>";
        multi_choice_que_type = "MultiSelectList";
        multi_choice_que = true;
    } else if ("Checkbox" === queType) {
        que_icon = "<i class='fa fa-check-square-o' aria-hidden='true'></i>";
        multi_choice_que_type = "Checkbox";
        multi_choice_que = true;
    } else if ("DrodownList" === queType) {
        que_icon = "<i class='fa fa-chevron-down' aria-hidden='true'></i>";
        multi_choice_que_type = "DrodownList";
        multi_choice_que = true;
    } else if ("RadioButton" === queType) {
        que_icon = "<i class='fa fa-dot-circle-o' aria-hidden='true'></i>";
        multi_choice_que_type = "RadioButton";
        multi_choice_que = true;
    } else if (queType === "Video") {
        que_icon = "<i class='fa fa-video-camera' aria-hidden='true'></i>";
    } else if (queType === "Matrix") {
        que_icon = '<i class="fa fa-th" aria-hidden="true"></i>';
    } else if (queType === "Scale") {
        que_icon = '<i class="fa fa-arrows-h" aria-hidden="true"></i>';
    } else if (queType === "Image") {
        que_icon = '<i class="fa fa-picture-o" aria-hidden="true"></i>';
    } else if (queType === "Date") {
        que_icon = '<i class="fa fa-calendar" aria-hidden="true"></i>';
    }
//html for multi choice question type
    if (multi_choice_que == true) {
        data += "<div class='survey-body' id='que_" + page_no + que_no + "'>";
        data += "<div class='s-row question' id='questionline" + page_no + "'>";
        data += "<div class='right-close-add-div' align='right'><a style='cursor:pointer;' onclick='addSection(this," + page_no + "," + que_no + ")'><i class='fa fa-pause fa-5 fa-rotate-90' title='Add Section' aria-hidden='true' style='font-size:14px;margin: 5px 3px;'></i></a><a id='que_remove_" + que_no + "' style='cursor:pointer;' onclick='removeQuestions(this," + page_no + ")'><i class='fa fa-remove' id='remove_que_" + que_no + "' title='Remove Question' style='font-size:14px;margin: 5px 3px;'></i></a></div>";
        data += "<div id='tabs" + page_no + que_no + "'>";
        data += "<div class='tab' style='margin-bottom: 20px;'>"
        data += '   <a class="advance_tab general_tab active" id="gen" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="gen">General</p></a>';
        data += '   <a class="advance_tab" id="adv" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="adv">Advanced Option</p></a>';
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += '   <a class="advance_tab" id="lgc" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="lgc">Logic</p></a>';
            data += '   <a class="advance_tab pipe_tab" id="pipe" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="pipe">Piping</p></a>';
        }
        data += '</div>';
        data += "<div id='general" + page_no + que_no + "' class='tabcontent general_tab_content' style='display:block;'><ul class='question-section' id='question_table" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id[" + page_no + "][" + que_no + "]'  id='que_id" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id_del[" + page_no + "][" + que_no + "]'  id='que_id_del" + page_no + que_no + "'>";
        data += "<li class='s-type'><label class='left-label'>Question Type </label><span style='cursor:pointer;' class='change_request' id='change_" + page_no + que_no + "' ondblclick='drop_down(this," + page_no + "," + que_no + ")'>" + que_icon + multi_choice_type_value[multi_choice_que_type] + "&nbsp;&nbsp;<a class='fa fa-pencil' aria-hidden='true'></a></span><span><input type='hidden' name='que_type[" + page_no + "][" + que_no + "]' id='que_type" + page_no + "" + que_no + "' value='" + multi_choice_que_type + "' /></span><span style='margin-left: 300px;'>Enable Scoring <input type='checkbox' style='cursor:pointer;margin-left: 65px;' class='enable_score" + page_no + que_no + "' onclick='score_value_display(this," + page_no + "," + que_no + ");' name='enable_scoring_dropdownlist[" + page_no + "][" + que_no + "]'></span></li>";
        data += "<li class='s-title'> <label class='left-label'>Question<span class='required'>*</span> </label><span><input class='input-text que_title' type='text' id='que_title" + page_no + que_no + "' name='que_title[" + page_no + "][" + que_no + "]'></span></li>";
        data += "<li class='s-option'>";
        data += "<ul class='answer-section' id='answer_div" + page_no + "" + que_no + "' class='answer_div'><li id='ans_op" + page_no + "" + que_no + "0'><label class='left-label'>Option </label><span><input type='text' name='answer[" + page_no + "][" + que_no + "][0]' class='answer" + page_no + "" + que_no + "'/></span>&nbsp;<input type='number' name='score_dropdownlist[" + page_no + "][" + que_no + "][0]' value='1' onblur='checkValue(this)' class='score_weight" + page_no + que_no + "' style='margin-left: 6px; text-align: center;max-width: 7%;height: 22px;display:none;'><input type='button' value='&#10005;' name='btnRemoveOption' style='display:none;margin-left: 9px;height:25px;width:25px;' onclick='removeOption(" + page_no + "," + que_no + ",this)'/>";
        data += "<input type='hidden' name='ans_id[" + page_no + "][" + que_no + "][0]' id='ans_id' style='width: 37.5%' />";
        data += "<input type='hidden' name='ans_id_del[" + page_no + "][" + que_no + "][0]' id='ans_id_del' style='width: 37.5%' /></li>";
        data += "<li id='ans_op" + page_no + "" + que_no + "1'><label class='left-label'></label><span><input type='text' class='answer" + page_no + "" + que_no + "' name=answer[" + page_no + "][" + que_no + "][1]/></span>&nbsp;<input type='number' name='score_dropdownlist[" + page_no + "][" + que_no + "][1]' value='2' onblur='checkValue(this)' class='score_weight" + page_no + que_no + "' style='margin-left: 6px; text-align: center;max-width: 7%;height: 22px;display:none;'>";
        data += "<input type='hidden' name='ans_id[" + page_no + "][" + que_no + "][1]' id='ans_id' style='width: 37.5%' />";
        data += "<input type='hidden' name='ans_id_del[" + page_no + "][" + que_no + "][1]' id='ans_id_del' style='width: 37.5%' />&nbsp;";
        data += "<input type='button' value='&#10005;' name='btnRemoveOption' style='display:none;margin-left: 6px;height:25px;width:25px;' onclick='removeOption(" + page_no + "," + que_no + ",this)' /><input name='btnOptionAdd' type='button' value='&#43;' onclick='addNewOption(this," + page_no + "," + que_no + ")' style='margin:10px;height:25px;width:25px;'/>";
        data += "</li>";
        data += "</ul>";
        data += "</ul>";
        data += "<div class='warning_outer' style='padding: 0px 5px;'><div class='other_option'><input type='checkbox' onclick='click_other_option(this," + page_no + "," + que_no + ")' name='enable_option[" + page_no + "][" + que_no + "]'><label>&nbsp;&nbsp;Add other option textbox</label></div></div>";
        data += "<ul class='other_option_div' style='display:none'><li><label class='left-label'>Other Option Label </label><input type='text' onblur='other_option_value(this)' value='Other' name='answer_other[" + page_no + "][" + que_no + "]'/><span style='display:none;'>&nbsp;<input type='number' name='score_dropdownlist_other[" + page_no + "][" + que_no + "]' value='0' onblur='checkValue(this)' class='score_weight" + page_no + que_no + "' style='margin-left: 6px; text-align: center;max-width: 7%;height: 22px;'></span></li></ul>";
        data += "</div>";
        //<span><label>Score</label><input type='number' name='score_dropdownlist[" + page_no + "][" + que_no + "][1]' value='' onblur='checkValue(this)' class='score_weight" + page_no + que_no + "' style='margin-left: 6px; text-align: center;max-width: 7%;height: 22px;display:none;'></span>
        if (multi_choice_que_type == "MultiSelectList") {
            data += "<div id='advanced" + page_no + que_no + "' class='tabcontent' style='display:none'>";
            data += "<ul><li class='s-title'><label class='left-label'>Help Tips </label><span><input  class='input-text question_help_comment' placeholder='Enter help tips for question' type='text' name='question_help_comment[" + page_no + "][" + que_no + "]'></span></li>";
            data += "<li class='s-required'><label class='left-label'>Is required  </label><span><input type='checkbox' name='is_required[" + page_no + "][" + que_no + "]' id='is_required' /></span></li>";
            data += '<li class="sortable"><label class="left-label">Sortable</label><input type="checkbox" name="is_sort_check-box[' + page_no + '][' + que_no + ']">';
            data += '<li class="limit_dropdown"><label class="left-label">Selection Limit</label><select style="width:auto;" id="limit_dropdown' + page_no + que_no + '" name="limit_dropdown[' + page_no + '][' + que_no + ']"><option value="0">Select</option></select>';
            data += "</ul></div>";
        } else if (multi_choice_que_type == "Checkbox") {
            data += "<div id='advanced" + page_no + que_no + "' class='tabcontent' style='display:none'>";
            data += "<ul><li class='s-title'><label class='left-label'>Help Tips </label><span><input  class='input-text question_help_comment' placeholder='Enter help tips for question' type='text' name='question_help_comment[" + page_no + "][" + que_no + "]'></span></li>";
            data += "<li class='s-required'><label class='left-label'>Is required  </label><span><input type='checkbox' name='is_required[" + page_no + "][" + que_no + "]' id='is_required' /></span></li>";
            data += '<li class="sortable"><label class="left-label">Sortable</label><input type="checkbox" name="is_sort_check-box[' + page_no + '][' + que_no + ']">';
            data += '<li class="display"><label class="left-label">Display</label><input type="radio" value="Vertical" name="display_radio_button[' + page_no + '][' + que_no + ']" checked>&nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;Vertical&nbsp;&nbsp;&nbsp;<input type="radio" value="Horizontal" name="display_radio_button[' + page_no + '][' + que_no + ']">&nbsp;&nbsp;<i class="fa fa-ellipsis-h"></i>&nbsp;&nbsp;Horizontal</li>';
            data += '<li class="limit_dropdown"><label class="left-label">Selection Limit</label><select style="width:auto;" id="limit_dropdown' + page_no + que_no + '" name="limit_dropdown[' + page_no + '][' + que_no + ']"><option value="0">Select</option></select>';
            data += "</ul></div>";
        } else if (multi_choice_que_type == "DrodownList") {
            data += "<div id='advanced" + page_no + que_no + "' class='tabcontent' style='display:none'>";
            data += "<ul><li class='s-title'><label class='left-label'>Help Tips </label><span><input  class='input-text question_help_comment' placeholder='Enter help tips for question' type='text' name='question_help_comment[" + page_no + "][" + que_no + "]'></span></li>";
            data += "<li class='s-required'><label class='left-label'>Is required  </label><span><input type='checkbox' name='is_required[" + page_no + "][" + que_no + "]' id='is_required' /></span></li>";
            data += '<li class="sortable"><label class="left-label">Sortable</label><input type="checkbox" name="is_sort_check-box[' + page_no + '][' + que_no + ']">';
            data += "</ul></div>";
        } else if (multi_choice_que_type == "RadioButton") {
            data += "<div id='advanced" + page_no + que_no + "' class='tabcontent' style='display:none'>";
            data += "<ul><li class='s-title'><label class='left-label'>Help Tips </label><span><input  class='input-text question_help_comment' placeholder='Enter help tips for question' type='text' name='question_help_comment[" + page_no + "][" + que_no + "]'></span></li>";
            data += "<li class='s-required'><label class='left-label'>Is required  </label><span><input type='checkbox' name='is_required[" + page_no + "][" + que_no + "]' id='is_required' /></span></li>";
            data += '<li class="sortable"><label class="left-label">Sortable</label><input type="checkbox" name="is_sort_check-box[' + page_no + '][' + que_no + ']">';
            data += '<li class="display"><label class="left-label">Display</label><input type="radio" value="Vertical" name="display_radio_button[' + page_no + '][' + que_no + ']" checked>&nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;Vertical&nbsp;&nbsp;&nbsp;<input type="radio" value="Horizontal" name="display_radio_button[' + page_no + '][' + que_no + ']">&nbsp;&nbsp;<i class="fa fa-ellipsis-h"></i>&nbsp;&nbsp;Horizontal</li>';
            data += "</ul></div>";
        }
        data += "<div id='logic_skipp" + page_no + que_no + "' class='tabcontent' style='display:none'>";
        data += "<div class='warning_outer' style='padding: 0px 5px; display:none;'><div class='warning'><b>CIRCULAR LOGIC &nbsp;:&nbsp;</b>This logic will cause the page to redirect to previous page, which will cause the respondent to go in an infinite loop if they keep picking that choice.</div></div><table cellspacing='20' style='width: auto;' class='logic-data-table' id='logic_table" + page_no + que_no + "'>";
        data += "<tr><th class='logic-table-first-column' style='color:#000;'>If answer is ...<i class='fa fa-question-circle' style='color:#4e8ccf;' aria-hidden='true'></i></th><th style='color:#000;'>Then skip to ...<i class='fa fa-question-circle' style='color:#4e8ccf;' aria-hidden='true'> </th><th>&nbsp;</th><th style='text-align:center;'><a style='cursor:pointer;color:#000;' onclick='clear_all_element(this," + page_no + "," + que_no + ")'>Clear All</a></th></tr>";
        data += "</table></div>";
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += "<div id='pipe" + page_no + que_no + "' class='pipecontent' style='display:none'>";
            data += "<ul><li class='s-title'><label class='left-label'>Disable Piping</label><input type='checkbox' onclick='desablepiping_checkbox(this," + page_no + "," + que_no + ")' name='desable_piping_que[" + page_no + "][" + que_no + "]'/></label></li>";
            data += "<li class='s-title'><label class='left-label'>Sync Field</label><select class='fields_type' onchange='getfieldstype(this," + page_no + "," + que_no + ")' id='sync_field" + page_no + que_no + "' name='sync_fields[" + page_no + "][" + que_no + "]'>";
            var selected_module = $('#sync_module').val();
            data += '<option value="0">Select Fields</option>';
            $.each(module_fields[selected_module], function (key, value) {
                if (value != "") {
                    data += '<option value="' + key + '">' + value + '</option>';
                }
            });
            data += "</select><input type='hidden' id='sync_field_hidden" + page_no + que_no + "' value='0'></li>";
            data += '</div>';
        }
        data += "</div>";
        data += "<input type='hidden' id='last_ans_no_" + page_no + "" + que_no + "' value='2' name='last_ans_no" + page_no + "" + que_no + "'>";
        data += "</div>";
    }

//html for single choice question type
    if (single_choice_que == true) {
        data += "<div class='survey-body' id='que_" + page_no + que_no + "'>";
        data += "<div class='s-row question' id='questionline" + page_no + "'>";
        data += "<div class='right-close-add-div' align='right'><a style='cursor:pointer;' onclick='addSection(this," + page_no + "," + que_no + ")'><i class='fa fa-pause fa-5 fa-rotate-90' title='Add Section' aria-hidden='true' style='font-size:14px;margin: 5px 3px;'></i></a><a id='que_remove_" + que_no + "' style='cursor:pointer;' onclick='removeQuestions(this," + page_no + ")'><i class='fa fa-remove' id='remove_que_" + que_no + "' title='Remove Question' style='font-size:14px;margin: 5px 3px;'></i></a></div>";
        data += "<div id='tabs" + page_no + que_no + "'>";
        data += "<div class='tab' style='margin-bottom: 20px;'>";
        data += '   <a class="advance_tab general_tab active" id="gen" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="gen">General</p></a>';
        data += '   <a class="advance_tab" id="adv" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="adv">Advanced Option</p></a>';
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += '   <a class="advance_tab pipe_tab" id="pipe" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="pipe">Piping</p></a>';
        }
        data += '</div>';
        data += "<div id='general" + page_no + que_no + "' class='tabcontent general_tab_content' style='display:block;'><ul class='question-section' id='question_table" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id[" + page_no + "][" + que_no + "]'  id='que_id" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id_del[" + page_no + "][" + que_no + "]'  id='que_id_del" + page_no + que_no + "'>";
        data += "<li class='s-type'><label class='left-label'>Question Type  </label><span style='cursor:pointer;' class='change_request' id='change_" + page_no + que_no + "' ondblclick='drop_down(this," + page_no + "," + que_no + ")'>" + que_icon + single_choice_type_value[single_choice_que_type] + "&nbsp;&nbsp;<a class='fa fa-pencil' aria-hidden='true'></a></span><span><input type='hidden' name='que_type[" + page_no + "][" + que_no + "]' id='que_type" + page_no + "" + que_no + "' value='" + single_choice_que_type + "' /></span>  </li>";
        data += "<li class='s-title'> <label class='left-label'>Question<span class='required'>*</span></label><span><input class='input-text que_title' type='text' id='que_title" + page_no + que_no + "' name='que_title[" + page_no + "][" + que_no + "]' /></span></li>";
        data += "</ul></div>";
        if (single_choice_que_type == "Textbox") {
            data += "<div id='advanced" + page_no + que_no + "' class='tabcontent' style='display:none'>";
            data += "<ul><li class='s-title'><label class='left-label'>Help Tips </label><span><input  class='input-text question_help_comment' placeholder='Enter help tips for question' type='text' name='question_help_comment[" + page_no + "][" + que_no + "]'></span></li>";
            data += "<li class='s-required'><label class='left-label'>Is required  </label><span><input type='checkbox' name='is_required[" + page_no + "][" + que_no + "]' id='is_required' /></span></li>";
            data += "<input type='hidden' id='data_type_hidden" + page_no + que_no + "' name='data_type_hidden[" + page_no + "][" + que_no + "]'/>"
            data += '<li class="data_type"><label class="left-label">DataType</label><select id="data_type' + page_no + que_no + '" onchange="textbox_dropdown(this,' + page_no + ',' + que_no + ')" name="datatype_textbox[' + page_no + '][' + que_no + ']" class="datatype-textbox"><option value="0">Select</option><option value="Integer">Number</option><option value="Float">Decimal</option><option value="Char">Char</option><option value="Email">Email</option></select>';
            data += '<li class="max_char" style="display:none;"><label class="left-label">Max Size</label><input type="text" onkeypress="return validateNumbericValue(event,' + page_no + ',' + que_no + ');" placeholder="Maximum acceptable char" name="size_textbox[' + page_no + '][' + que_no + ']" class="inherit-width">';
            data += '<li class ="integer' + page_no + que_no + '" style="display:none;"><label class="left-label">Min Value</label><input type="text" class="minint" onkeypress="return validateNumbericValue(event,' + page_no + ',' + que_no + ');" placeholder="Minimum value" name="min_integer[' + page_no + '][' + que_no + ']">';
            data += '<li class ="integer' + page_no + que_no + '" style="display:none;"><label class="left-label">Max Value</label><input type="text" class="maxint" onkeypress="return validateNumbericValue(event,' + page_no + ',' + que_no + ');" placeholder="Maximum value" name="max_integer[' + page_no + '][' + que_no + ']">';
            data += '<li class ="float' + page_no + que_no + '" style="display:none;"><label class="left-label">Precision</label><input type="text" onkeypress="return validateNumbericValue(event,' + page_no + ',' + que_no + ');" placeholder="Precision" name="precision_textbox[' + page_no + '][' + que_no + ']">';
            data += '<li class ="float' + page_no + que_no + '" style="display:none;"><label class="left-label">Min Value</label><input type="text" class="minfloat" onkeypress="return validateDecimalValue(event,' + page_no + ',' + que_no + ');" placeholder="Minimum value" name="min_float[' + page_no + '][' + que_no + ']">';
            data += '<li class ="float' + page_no + que_no + '" style="display:none;"><label class="left-label">Max value</label><input type="text" class="maxfloat" onkeypress="return validateDecimalValue(event,' + page_no + ',' + que_no + ');" placeholder="Maximum value" name="max_float[' + page_no + '][' + que_no + ']">';
            data += "</ul></div>";
        } else if (single_choice_que_type == "CommentTextbox") {
            data += "<div id='advanced" + page_no + que_no + "' class='tabcontent' style='display:none'>";
            data += "<ul><li class='s-title'><label class='left-label'>Help Tips </label><span><input  class='input-text question_help_comment' placeholder='Enter help tips for question' type='text' name='question_help_comment[" + page_no + "][" + que_no + "]'></span></li>";
            data += "<li class='s-required'><label class='left-label'>Is required  </label><span><input type='checkbox' name='is_required[" + page_no + "][" + que_no + "]' id='is_required' /></span></li>";
            data += '<li class="max_char"><label class="left-label">Max Size</label><input type="text" onkeypress="return validateNumbericValue(event,' + page_no + ',' + que_no + ');" placeholder="Maximum acceptable char" name="size_textbox[' + page_no + '][' + que_no + ']"></li>';
            data += '<li class="no_of_rows"><label class="left-label">Rows</label><input type="text" onkeypress="return validateNumbericValue(event,' + page_no + ',' + que_no + ');" placeholder="Rows" name="rows_commentbox[' + page_no + '][' + que_no + ']"></li>';
            data += '<li class="no_of_cols"><label class="left-label">Columns</label><input type="text" onkeypress="return validateNumbericValue(event,' + page_no + ',' + que_no + ');" placeholder="Columns" name="cols_commentbox[' + page_no + '][' + que_no + ']"></li>';
            data += "</ul></div>";
        }
        else if (single_choice_que_type == "Rating")
        {
            data += "<div id='advanced" + page_no + que_no + "' class='tabcontent' style='display:none'>";
            data += "<ul><li class='s-title'><label class='left-label'>Help Tips </label><span><input class='input-text question_help_comment' placeholder='Enter help tips for question' type='text' name='question_help_comment[" + page_no + "][" + que_no + "]'></span></li>";
            data += "<li class='s-required'><label class='left-label'>Is required</label><span><input type='checkbox' name='is_required[" + page_no + "][" + que_no + "]' id='is_required' /></span></li>";
            data += '<li class="no_of_stars"><label class="left-label">Star Numbers</label><select style="width:auto;" name="starno_rating[' + page_no + '][' + que_no + ']"><option value="0">Select</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option><option>10</option></select>';
            data += "</ul></div>";
        } else {
            data += "<div id='advanced" + page_no + que_no + "' class='tabcontent' style='display:none'>";
            data += "<ul><li class='s-title'><label class='left-label'>Help Tips </label><span><input  class='input-text question_help_comment' placeholder='Enter help tips for question' type='text' name='question_help_comment[" + page_no + "][" + que_no + "]'></span></li>";
            data += "<li class='s-required'><label class='left-label'>Is required</label><span><input type='checkbox' class='is_required' onclick='change_checkbox(this ," + page_no + "," + que_no + ")' name='is_required[" + page_no + "][" + que_no + "]' id='is_required" + page_no + que_no + "' /></span></li>";
            data += '<div class="row' + page_no + que_no + '" style="display:none;"><li class="s-required"><label class="left-label">Required Fields</label><div class="contact-info first"><span><input class="requiredfields" type="checkbox" name="cname[' + page_no + '][' + que_no + ']" checked>Name &nbsp;</span><span><input class="requiredfields" type="checkbox" name="email[' + page_no + '][' + que_no + ']" checked>Email Address&nbsp;</span><span><input class="requiredfields" type="checkbox" name="company[' + page_no + '][' + que_no + ']">Company&nbsp;</span><span><input class="requiredfields" type="checkbox" name="phone[' + page_no + '][' + que_no + ']" checked>Phone Number&nbsp;</span><span><input class="requiredfields" type="checkbox" name="address[' + page_no + '][' + que_no + ']">Address</span></div></li></div>';
            data += '<div class="row' + page_no + que_no + '" style="display:none;"><li class="s-required"><label class="left-label">&nbsp;</label><div class="contact-info"><span><input class="requiredfields" type="checkbox" name="address2[' + page_no + '][' + que_no + ']">Address 2 &nbsp;</span><span><input class="requiredfields" type="checkbox" name="city[' + page_no + '][' + que_no + ']">City/Town&nbsp;</span><span><input class="requiredfields" type="checkbox" name="state[' + page_no + '][' + que_no + ']">State/Province&nbsp;</span><span><input class="requiredfields" type="checkbox" name="zip[' + page_no + '][' + que_no + ']">Zip/Postal Code&nbsp;</span><span><input class="requiredfields" type="checkbox" name="country[' + page_no + '][' + que_no + ']">Country</span></div></li></div>';
            data += "</li></ul></div>";
        }
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += "<div id='pipe" + page_no + que_no + "' class='pipecontent' style='display:none'>";
            data += "<ul><li class='s-title'><label class='left-label'>Disable Piping</label><input type='checkbox' onclick='desablepiping_checkbox(this," + page_no + "," + que_no + ")' name='desable_piping_que[" + page_no + "][" + que_no + "]'/></label></li>";
            data += "<li class='s-title'><label class='left-label'>Sync Field</label><select class='fields_type' onchange='getfieldstype(this," + page_no + "," + que_no + ")' id='sync_field" + page_no + que_no + "' name='sync_fields[" + page_no + "][" + que_no + "]'>";
            var selected_module = $('#sync_module').val();
            data += '<option value="0">Select Fields</option>';
            $.each(module_fields[selected_module], function (key, value) {
                if (value != "") {
                    data += '<option value="' + key + '">' + value + '</option>';
                }
            });
            data += "</select><input type='hidden' id='sync_field_hidden" + page_no + que_no + "' value='0'></li>";
            data += '</div>';
        }
        data += "</div>";
        data += "</div>";
    }
    if (queType == "Video") {
        data += "<div class='survey-body' id='que_" + page_no + que_no + "'>";
        data += "<div class='s-row question' id='questionline" + page_no + "'>";
        data += "<div class='right-close-add-div' align='right'><a style='cursor:pointer;' onclick='addSection(this," + page_no + "," + que_no + ")'><i class='fa fa-pause fa-5 fa-rotate-90' title='Add Section' aria-hidden='true' style='font-size:14px;margin: 5px 3px;'></i></a><a id='que_remove_" + que_no + "' style='cursor:pointer;' onclick='removeQuestions(this," + page_no + ")'><i class='fa fa-remove' id='remove_que_" + que_no + "' title='Remove Question' style='font-size:14px;margin: 5px 3px;'></i></a></div>";
        data += "<div id='tabs" + page_no + que_no + "'>";
        data += "<div class='tab' style='margin-bottom: 20px;'>";
        data += '   <a class="advance_tab general_tab active" id="gen" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="gen">General</p></a>';
        data += '   <a class="advance_tab" id="adv" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="adv">Advanced Option</p></a>';
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += '   <a class="advance_tab pipe_tab" id="pipe" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="pipe">Piping</p></a>';
        }
        data += '</div>';
        data += "<div id='general" + page_no + que_no + "' class='tabcontent general_tab_content' style='display:block;'><ul class='question-section' id='question_table" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id[" + page_no + "][" + que_no + "]'  id='que_id" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id_del[" + page_no + "][" + que_no + "]' id='que_id_del" + page_no + que_no + "'>";
        data += "<li class='s-type'><label class='left-label'>Question Type  </label><span style='cursor:pointer;' class='change_request' id='change_" + page_no + que_no + "' ondblclick='drop_down(this," + page_no + "," + que_no + ")'>" + que_icon + " Video&nbsp;&nbsp;<a class='fa fa-pencil' aria-hidden='true'></a></span><span><input type='hidden' name='que_type[" + page_no + "][" + que_no + "]' id='que_type" + page_no + "" + que_no + "' value='Video'/></span> </li>";
        data += "<li class='s-title'> <label class='left-label'>Video URL<span class='required'>*</span></label><span><input placeholder='Enter URL For Video' id='que_title" + page_no + que_no + "' class='input-text que_title' type='text' name='que_title[" + page_no + "][" + que_no + "]'/></span><span class='survey-logo-selection'><img style='margin-left:10px' title='i.e. http://www.youtube.com/embed/L3_gx6Fx_b0' src='themes/default/images/helpInline.gif' alt='Information' class='inlineHelpTip' border='0'></span></li>";
        data += "</div>";
        data += "<div id='advanced" + page_no + que_no + "' class='tabcontent' style='display:none'>";
        data += "<ul><li class='s-desc'><label class='left-label'>Title </label><span><input  class='input-text title' placeholder='Enter Title For Video' type='text' name='question_help_comment[" + page_no + "][" + que_no + "]'></span></li>"
        data += "<li class='s-desc'><label class='left-label'>Description </label><span><textarea cols='40' rows='7' style='width: 31%; vertical-align: top;' placeholder='Enter Description For Video' name='video_desc[" + page_no + "][" + que_no + "]'></textarea></span></li>";
        data += "</ul></div>";
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += "<div id='pipe" + page_no + que_no + "' class='pipecontent' style='display:none'>";
            data += "<ul><li class='s-title'><label class='left-label'>Disable Piping</label><input type='checkbox' onclick='desablepiping_checkbox(this," + page_no + "," + que_no + ")' name='desable_piping_que[" + page_no + "][" + que_no + "]'/></label></li>";
            data += "<li class='s-title'><label class='left-label'>Sync Field</label><select class='fields_type' onchange='getfieldstype(this," + page_no + "," + que_no + ")' id='sync_field" + page_no + que_no + "' name='sync_fields[" + page_no + "][" + que_no + "]'>";
            var selected_module = $('#sync_module').val();
            data += '<option value="0">Select Fields</option>';
            $.each(module_fields[selected_module], function (key, value) {
                if (value != "") {
                    data += '<option value="' + key + '">' + value + '</option>';
                }
            });
            data += "</select><input type='hidden' id='sync_field_hidden" + page_no + que_no + "' value='0'></li>";
            data += '</div>';
        }
        data += "</div>";
        data += "</div>";
    } else if (queType == "Scale") {
        data += "<div class='survey-body' id='que_" + page_no + que_no + "'>";
        data += "<div class='s-row question' id='questionline" + page_no + "'>";
        data += "<div class='right-close-add-div' align='right'><a style='cursor:pointer;' onclick='addSection(this," + page_no + "," + que_no + ")'><i class='fa fa-pause fa-5 fa-rotate-90' title='Add Section' aria-hidden='true' style='font-size:14px;margin: 5px 3px;'></i></a><a id='que_remove_" + que_no + "' style='cursor:pointer;' onclick='removeQuestions(this," + page_no + ")'><i class='fa fa-remove' id='remove_que_" + que_no + "' title='Remove Question' style='font-size:14px;margin: 5px 3px;'></i></a></div>";
        data += "<div id='tabs" + page_no + que_no + "'>";
        data += "<div class='tab' style='margin-bottom: 20px;'>";
        data += '   <a class="advance_tab general_tab active" id="gen" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="gen">General</p></a>';
        data += '   <a class="advance_tab" id="adv" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="adv">Advanced Option</p></a>';
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += '   <a class="advance_tab pipe_tab" id="pipe" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="pipe">Piping</p></a>';
        }
        data += '</div>';
        data += "<div id='general" + page_no + que_no + "' class='tabcontent general_tab_content' style='display:block;'><ul class='question-section' id='question_table" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id[" + page_no + "][" + que_no + "]'  id='que_id" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id_del[" + page_no + "][" + que_no + "]'  id='que_id_del" + page_no + que_no + "'>";
        data += "<li class='s-type'><label class='left-label'>Question Type  </label><span style='cursor:pointer;' class='change_request' id='change_" + page_no + que_no + "' ondblclick='drop_down(this," + page_no + "," + que_no + ")'>" + que_icon + " Scale&nbsp;&nbsp;<a class='fa fa-pencil' aria-hidden='true'></a></span><span><input type='hidden' name='que_type[" + page_no + "][" + que_no + "]' id='que_type" + page_no + "" + que_no + "' value='Scale' /></span>  </li>";
        data += "<li class='s-title'><label class='left-label'>Question<span class='required'>*</span></label><span><input class='input-text que_title' id='que_title" + page_no + que_no + "' type='text' name='que_title[" + page_no + "][" + que_no + "]' /></span></li>";
        data += "</div>";
        data += "<div id='advanced" + page_no + que_no + "' class='tabcontent' style='display:none'>";
        data += "<ul><li class='s-desc'><label class='left-label'>Help Tips</label><span><input  class='input-text question_help_comment' placeholder='Enter help tips for question' type='text' name='question_help_comment[" + page_no + "][" + que_no + "]'></span></li>";
        data += "<li class='s-display'><label class='left-label'>Display Label</label><span><input  class='input-text' style='width: 10%;' placeholder='Left' type='text' name=left[" + page_no + "][" + que_no + "]>&nbsp;&nbsp;<input  class='input-text' placeholder='Middle' style='width: 10%;' type='text' name=middle[" + page_no + "][" + que_no + "]>&nbsp;&nbsp;<input  class='input-text' placeholder='Right' style='width: 10%;' type='text' name=right[" + page_no + "][" + que_no + "]></span></li>";
        data += "<li class='s-required'><label class='left-label'>Is required</label><span><input type='checkbox' name='is_required[" + page_no + "][" + que_no + "]' id='is_required' /></span></li>";
        data += "<li class='s-start'><label class='left-label'>Start & End Value</label><input  class='input-text start' style='width: 10%;' value='0' onkeypress='return validateNumbericValue(event," + page_no + "," + que_no + ");' placeholder='Start Value' type='text' name=start[" + page_no + "][" + que_no + "]>&nbsp; - &nbsp;<input class='input-text end' onkeypress='return validateNumbericValue(event," + page_no + "," + que_no + ");' placeholder='End Value' style='width: 10%;' type='text' name=end[" + page_no + "][" + que_no + "] value='10'>";
        data += "<li class='s-step'><label class='left-label'>Step Value</label><input class='input-text stepval' onkeypress='return validateNumbericValue(event," + page_no + "," + que_no + ");' placeholder='Step Value' style='width:10%;' type='text' name=step_value[" + page_no + "][" + que_no + "] value='1'>";
        data += "</ul></div>";
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += "<div id='pipe" + page_no + que_no + "' class='pipecontent' style='display:none'>";
            data += "<ul><li class='s-title'><label class='left-label'>Disable Piping</label><input type='checkbox' onclick='desablepiping_checkbox(this," + page_no + "," + que_no + ")' name='desable_piping_que[" + page_no + "][" + que_no + "]'/></label></li>";
            data += "<li class='s-title'><label class='left-label'>Sync Field</label><select class='fields_type' onchange='getfieldstype(this," + page_no + "," + que_no + ")' id='sync_field" + page_no + que_no + "' name='sync_fields[" + page_no + "][" + que_no + "]'>";
            var selected_module = $('#sync_module').val();
            data += '<option value="0">Select Fields</option>';
            $.each(module_fields[selected_module], function (key, value) {
                if (value != "") {
                    data += '<option value="' + key + '">' + value + '</option>';
                }
            });
            data += "</select><input type='hidden' id='sync_field_hidden" + page_no + que_no + "' value='0'></li>";
            data += '</div>';
        }
        data += "</div>";
        data += "</div>";
    } else if (queType == "Date") {
        data += "<div class='survey-body' id='que_" + page_no + que_no + "'>";
        data += "<div class='s-row question' id='questionline" + page_no + "'>";
        data += "<div class='right-close-add-div' align='right'><a style='cursor:pointer;' onclick='addSection(this," + page_no + "," + que_no + ")'><i class='fa fa-pause fa-5 fa-rotate-90' title='Add Section' aria-hidden='true' style='font-size:14px;margin: 5px 3px;'></i></a><a id='que_remove_" + que_no + "' style='cursor:pointer;' onclick='removeQuestions(this," + page_no + ")'><i class='fa fa-remove' id='remove_que_" + que_no + "' title='Remove Question' style='font-size:14px;margin: 5px 3px;'></i></a></div>";
        data += "<div id='tabs" + page_no + que_no + "'>";
        data += "<div class='tab' style='margin-bottom: 20px;'>";
        data += '   <a class="advance_tab general_tab active" id="gen" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="gen">General</p></a>';
        data += '   <a class="advance_tab" id="adv" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="adv">Advanced Option</p></a>';
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += '   <a class="advance_tab pipe_tab" id="pipe" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="pipe">Piping</p></a>';
        }
        data += '</div>';
        data += "<div id='general" + page_no + que_no + "' class='tabcontent general_tab_content' style='display:block;'><ul class='question-section' id='question_table" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id[" + page_no + "][" + que_no + "]'  id='que_id" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id_del[" + page_no + "][" + que_no + "]'  id='que_id_del" + page_no + que_no + "'>";
        data += "<li class='s-type'><label class='left-label'>Question Type  </label><span style='cursor:pointer;' class='change_request' id='change_" + page_no + que_no + "' ondblclick='drop_down(this," + page_no + "," + que_no + ")'>" + que_icon + " DateTime&nbsp;&nbsp;<a class='fa fa-pencil' aria-hidden='true'></a></span><span><input type='hidden' name='que_type[" + page_no + "][" + que_no + "]' id='que_type" + page_no + "" + que_no + "' value='Date'/></span></li>";
        data += "<li class='s-title'> <label class='left-label'>Question<span class='required'>*</span></label><span><input id='que_title" + page_no + que_no + "' class='input-text que_title' type='text' name='que_title[" + page_no + "][" + que_no + "]' /></span></li>";
        data += "</div>";
        data += "<div id='advanced" + page_no + que_no + "' class='tabcontent' style='display:none'>";
        data += "<ul><li class='s-desc'><label class='left-label'>Help Tips</label><span><input  class='input-text question_help_comment' placeholder='Enter help tips for question' type='text' name='question_help_comment[" + page_no + "][" + que_no + "]'></span></li>"
        data += "<li class='s-required'><label class='left-label'>Is required</label><span><input type='checkbox' name='is_required[" + page_no + "][" + que_no + "]' id='is_required' /></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>IsDateTime</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><input type='checkbox' name='is_datetime[" + page_no + "][" + que_no + "]' id='is_required' /></span></li>";
        data += "<li class='s-start'><label class='left-label'>Start Date</label><input class='input-text sdate' id='stdate" + page_no + que_no + "' style='width: 10%;' placeholder='Start Date' type='text' name=start_date_time[" + page_no + "][" + que_no + "]>&nbsp;<img id='startdate" + page_no + que_no + "' style='position: relative;top: 4px;padding-left: 4px;' src='themes/default/images/jscalendar.gif'/> &nbsp; - &nbsp;<input class ='input-text edate' id='edate" + page_no + que_no + "' placeholder='End Date' style='width: 10%;' type='text' name=end_date_time[" + page_no + "][" + que_no + "]>&nbsp;<img style='position: relative;top: 4px;padding-left: 4px;' src='themes/default/images/jscalendar.gif' id='enddate" + page_no + que_no + "''/> ";
        data += "</ul></div>";
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += "<div id='pipe" + page_no + que_no + "' class='pipecontent' style='display:none'>";
            data += "<ul><li class='s-title'><label class='left-label'>Disable Piping</label><input type='checkbox' onclick='desablepiping_checkbox(this," + page_no + "," + que_no + ")' name='desable_piping_que[" + page_no + "][" + que_no + "]'/></label></li>";
            data += "<li class='s-title'><label class='left-label'>Sync Field</label><select class='fields_type' onchange='getfieldstype(this," + page_no + "," + que_no + ")' id='sync_field" + page_no + que_no + "' name='sync_fields[" + page_no + "][" + que_no + "]'>";
            var selected_module = $('#sync_module').val();
            data += '<option value="0">Select Fields</option>';
            $.each(module_fields[selected_module], function (key, value) {
                if (value != "") {
                    data += '<option value="' + key + '">' + value + '</option>';
                }
            });
            data += "</select><input type='hidden' id='sync_field_hidden" + page_no + que_no + "' value='0'></li>";
            data += '</div>';
        }
        data += "</div>";
        data += "</div>";
    } else if (queType == "Image") {
        data += "<div class='survey-body' id='que_" + page_no + que_no + "'>";
        data += "<div class='s-row question' id='questionline" + page_no + "'>";
        data += "<div class='right-close-add-div' align='right'><a style='cursor:pointer;' onclick='addSection(this," + page_no + "," + que_no + ")'><i class='fa fa-pause fa-5 fa-rotate-90' title='Add Section' aria-hidden='true' style='font-size:14px;margin: 5px 3px;'></i></a><a id='que_remove_" + que_no + "' style='cursor:pointer;' onclick='removeQuestions(this," + page_no + ")'><i class='fa fa-remove' id='remove_que_" + que_no + "' title='Remove Question' style='font-size:14px;margin: 5px 3px;'></i></a></div>";
        data += "<div id='tabs" + page_no + que_no + "'>";
        data += "<div class='tab' style='margin-bottom: 20px;'>";
        data += '   <a class="advance_tab general_tab active" id="gen" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="gen">General</p></a>';
        data += '   <a class="advance_tab" id="adv" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="adv">Advanced Option</p></a>';
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += '   <a class="advance_tab pipe_tab" id="pipe" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="pipe">Piping</p></a>';
        }
        data += '</div>';
        data += "<div id='general" + page_no + que_no + "' class='tabcontent general_tab_content' style='display:block;'><ul class='question-section' id='question_table" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id[" + page_no + "][" + que_no + "]' value=''  id='que_id" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id_del[" + page_no + "][" + que_no + "]' value=''  id='que_id_del" + page_no + que_no + "'>";
        data += "<li class='s-type'><label class='left-label'>Question Type  </label><span style='cursor:pointer;' class='change_request' id='change_" + page_no + que_no + "' ondblclick='drop_down(this," + page_no + "," + que_no + ")'>" + que_icon + " Image&nbsp;&nbsp;<a class='fa fa-pencil' aria-hidden='true'></a></span><span><input type='hidden' name='que_type[" + page_no + "][" + que_no + "]' id='que_type" + page_no + "" + que_no + "' value='Image' /></span>  </li>";
        data += "<li class='s-title'><label class='left-label'>Image<span class='required'>*</span></label><span><input class='upload' type='radio' name='que_title[" + page_no + "][" + que_no + "]' onclick='change_radio(this," + page_no + "," + que_no + ")' value='upload' checked/>Upload Image&nbsp;</span><span><input class='url' type='radio' onclick='change_radio(this," + page_no + "," + que_no + ")' name='que_title[" + page_no + "][" + que_no + "]' value='img_url'/>Image Url</span></li>";
        data += "<li><label class='left-label'>&nbsp;</label><span class='upload" + page_no + que_no + " up'><input type='file' id='file" + page_no + que_no + "' onchange=validate_Imagesize(this.files," + page_no + "," + que_no + ") class='file_upload' name='img_url[" + page_no + "][" + que_no + "]' value=''></span><span class='url" + page_no + que_no + " ur' style='display:none;'><input class='input-text file_url' placeholder='Image Url' type='text' value='' name='img_url[" + page_no + "][" + que_no + "]'></span></li>";
        data += "</div>";
        data += "<div id='advanced" + page_no + que_no + "' class='tabcontent' style='display:none'>";
        data += "<ul><li class='s-desc'><label class='left-label'>Title</label><span><input class='input-text title' placeholder='Enter help Tips' type='text' name='question_help_comment[" + page_no + "][" + que_no + "]'></span></li>";
        data += "</ul></div>";
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += "<div id='pipe" + page_no + que_no + "' class='pipecontent' style='display:none'>";
            data += "<ul><li class='s-title'><label class='left-label'>Disable Piping</label><input type='checkbox' onclick='desablepiping_checkbox(this," + page_no + "," + que_no + ")' name='desable_piping_que[" + page_no + "][" + que_no + "]'/></label></li>";
            data += "<li class='s-title'><label class='left-label'>Sync Field</label><select class='fields_type' onchange='getfieldstype(this," + page_no + "," + que_no + ")' id='sync_field" + page_no + que_no + "' name='sync_fields[" + page_no + "][" + que_no + "]'>";
            var selected_module = $('#sync_module').val();
            data += '<option value="0">Select Fields</option>';
            $.each(module_fields[selected_module], function (key, value) {
                if (value != "") {
                    data += '<option value="' + key + '">' + value + '</option>';
                }
            });
            data += "</select><input type='hidden' id='sync_field_hidden" + page_no + que_no + "' value='0'></li>";
            data += '</div>';
        }
        data += "</div>";
        data += "</div>";
    } else if (queType == "Matrix") {
        data += "<div class='survey-body' id='que_" + page_no + que_no + "'>";
        data += "<div class='s-row question' id='questionline" + page_no + "'>";
        data += "<div class='right-close-add-div' align='right'><a style='cursor:pointer;' onclick='addSection(this," + page_no + "," + que_no + ")'><i class='fa fa-pause fa-5 fa-rotate-90' title='Add Section' aria-hidden='true' style='font-size:14px;margin: 5px 3px;'></i></a><a id='que_remove_" + que_no + "' style='cursor:pointer;' onclick='removeQuestions(this," + page_no + ")'><i class='fa fa-remove' id='remove_que_" + que_no + "' title='Remove Question' style='font-size:14px;margin: 5px 3px;'></i></a></div>";
        data += "<div id='tabs" + page_no + que_no + "'>";
        data += "<div class='tab' style='margin-bottom: 20px;'>";
        data += '   <a class="advance_tab general_tab active" id="gen" onclick=" change_tab(this, ' + page_no + ',' + que_no + ')"><p class="gen">General</p></a>';
        data += '   <a class="advance_tab" id="adv" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="adv">Advanced Option</p></a>';
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += '   <a class="advance_tab pipe_tab" id="pipe" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="pipe">Piping</p></a>';
        }
        data += '</div>';
        data += "<div id='general" + page_no + que_no + "' class='tabcontent general_tab_content' style='display:block;'><ul class='question-section' id='question_table" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id[" + page_no + "][" + que_no + "]'  id='que_id" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id_del[" + page_no + "][" + que_no + "]'  id='que_id_del" + page_no + que_no + "'>";
        data += "<input type='hidden' id='last_row_no_" + page_no + que_no + "' value='2' name='last_row_no" + page_no + "" + que_no + "'>";
        data += "<input type='hidden' id='last_col_no_" + page_no + que_no + "' value='2' name='last_col_no" + page_no + "" + que_no + "'>";
        data += "<li class='s-type'><label class='left-label'>Question Type  </label><span style='cursor:pointer;' class='change_request' id='change_" + page_no + que_no + "' ondblclick='drop_down(this," + page_no + "," + que_no + ")'>" + que_icon + " Matrix&nbsp;&nbsp;<a class='fa fa-pencil' aria-hidden='true'></a></span><span><input type='hidden' name='que_type[" + page_no + "][" + que_no + "]' id='que_type" + page_no + "" + que_no + "' value='Matrix' /></span>  </li>";
        data += "<li class='s-title'><label class='left-label'>Question<span class='required'>*</span></label><span><input class='input-text que_title' id='que_title" + page_no + que_no + "' type='text' name='que_title[" + page_no + "][" + que_no + "]' /></span></li>";
        data += "<li class='s-option matrix-body-row'>";
        var row = "row";
        data += "<ul class='answer-section matrix-row' id='rows" + page_no + "" + que_no + "'><li id='ans_op" + page_no + "" + que_no + "0'><label class='left-label'>Rows </label><span><input type='text' name=row[" + page_no + "][" + que_no + "][0] id='answer" + page_no + que_no + "'/></span><input type='button' value='&#10005;' name='btnRemoveOption' style='display:none;margin-left: 6px;height:25px;width:25px;' onclick='removerow_cols(" + page_no + "," + que_no + ",this,\"" + row + "\")'/></li>";
        data += "<li id='ans_op" + page_no + "" + que_no + "1'><label class='left-label'></label><span><input type='text' name=row[" + page_no + "][" + que_no + "][1] /></span>";
        data += "<input type='button' value='&#10005;' name='btnRemoveOption' style='display:none;margin-left: 6px;height:25px;width:25px;' onclick='removerow_cols(" + page_no + "," + que_no + ",this,\"" + row + "\")' /><input name='btnOptionAdd' type='button' value='&#43;' onclick='add_rowcols(" + page_no + "," + que_no + ",\"" + row + "\")' style='margin:10px;height:25px;width:25px;'/>";
        data += "</li></ul>";
        var col = "column";
        data += "<ul class='answer-section matrix-column' id='columns" + page_no + "" + que_no + "'><li id='ans_op" + page_no + "" + que_no + "0'><label class='left-label'>Columns </label><span><input type='text' name=col[" + page_no + "][" + que_no + "][0] id='answer" + page_no + "" + que_no + "'/></span><input type='button' value='&#10005;' name='btnRemoveOption' style='display:none;margin-left: 6px;height:25px;width:25px;' onclick='removerow_cols(" + page_no + "," + que_no + ",this,\"" + col + "\")'/></li>";
        data += "<li id='ans_op" + page_no + que_no + "1'><label class='left-label'></label><span><input type='text' name=col[" + page_no + "][" + que_no + "][1] /></span>";
        data += "<input type='button' value='&#10005;' name='btnRemoveOption' style='display:none;margin-left: 6px;height:25px;width:25px;' onclick='removerow_cols(" + page_no + "," + que_no + ",this,\"" + col + "\")' /><input name='btnOptionAdd' type='button' value='&#43;' onclick='add_rowcols(" + page_no + "," + que_no + ",\"" + col + "\")' style='margin:10px;height:25px;width:25px;'/>";
        data += "</li></ul>";
        data += "</div>";
        data += "<div id='advanced" + page_no + que_no + "' class='tabcontent' style='display:none'>";
        data += "<ul><li class='s-desc'><label class='left-label'>Help Tips</label><span><input  class='input-text question_help_comment' placeholder='Enter help tips for question' type='text' name='question_help_comment[" + page_no + "][" + que_no + "]'></span></li>";
        data += "<li class='s-required'><label class='left-label'>Is required</label><span><input type='checkbox' name='is_required[" + page_no + "][" + que_no + "]' id='is_required' /></span></li>";
        data += "<li class='s-display'><label class='left-label'>Display Option</label><span><input type='radio' value='Radio' name='display_type_matrix[" + page_no + "][" + que_no + "]' checked='checked'>&nbsp;&nbsp;&nbsp;<i class='fa fa-dot-circle-o'></i>&nbsp; Radio&nbsp;&nbsp;&nbsp;<span><input type='radio' name='display_type_matrix[" + page_no + "][" + que_no + "]' value='Checkbox'>&nbsp;&nbsp;&nbsp;<i class='fa fa-check-square-o'></i>&nbsp; CheckBox";
        data += "</ul></div>";
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += "<div id='pipe" + page_no + que_no + "' class='pipecontent' style='display:none'>";
            data += "<ul><li class='s-title'><label class='left-label'>Disable Piping</label><input type='checkbox' onclick='desablepiping_checkbox(this," + page_no + "," + que_no + ")' name='desable_piping_que[" + page_no + "][" + que_no + "]'/></label></li>";
            data += "<li class='s-title'><label class='left-label'>Sync Field</label><select class='fields_type' onchange='getfieldstype(this," + page_no + "," + que_no + ")' id='sync_field" + page_no + que_no + "' name='sync_fields[" + page_no + "][" + que_no + "]'>";
            var selected_module = $('#sync_module').val();
            data += '<option value="0">Select Fields</option>';
            $.each(module_fields[selected_module], function (key, value) {
                if (value != "") {
                    data += '<option value="' + key + '">' + value + '</option>';
                }
            });
            data += "</select><input type='hidden' id='sync_field_hidden" + page_no + que_no + "' value='0'></li>";
            data += '</div>';
        }
        data += "</div>";
        data += "</div>";
    }
    return data;
}
/**
 * double click on question type change
 *
 * @author     Original Author Biztech Co.
 * @param      integer - page_no,que_no
 * @param      String - queType,question_type
 * @param      array - ans_id
 * @return
 */
function Drop_DownQue(queType, page_no, que_no, question_type, ans_id, target_value_obj) {

    if (queType != "MultiSelectList" && queType != "Checkbox" && queType != "DrodownList" && queType != "RadioButton") {
        try {
            $('#ans_deleted').val(ans_id.join());
            target_value_obj = {};
        } catch (err) {
        }
    }
    if (confirm("Are You Sure To Change Question Type ?")) {
        var data = addQueHTML(queType, page_no, que_no);
        return data;
    } else {
        var survey_data = '';
        if (question_type == "Textbox") {
            survey_data += "<span style='cursor:pointer;' class='change_request' id='change_" + page_no + que_no + "' ondblclick='drop_down(this," + page_no + "," + que_no + ")'><i class='fa fa-file-text-o'></i>TextBox&nbsp;<a class='fa fa-pencil' aria-hidden='true'></a></span>";
        } else if (question_type == "CommentTextbox") {
            survey_data += "<span style='cursor:pointer;' class='change_request' id='change_" + page_no + que_no + "' ondblclick='drop_down(this," + page_no + "," + que_no + ")'><i class='fa fa-comments-o'></i>CommentTextBox&nbsp;<a class='fa fa-pencil' aria-hidden='true'></a></span>";
        } else if (question_type == "MultiSelectList") {
            survey_data += "<span style='cursor:pointer;' class='change_request' id='change_" + page_no + que_no + "' ondblclick='drop_down(this," + page_no + "," + que_no + ")'><i class='fa fa-list-ul'></i>MultiSelect List&nbsp;<a class='fa fa-pencil' aria-hidden='true'></a></span>";
        } else if (question_type == "Checkbox") {
            survey_data += "<span style='cursor:pointer;' class='change_request' id='change_" + page_no + que_no + "' ondblclick='drop_down(this," + page_no + "," + que_no + ")'><i class='fa  fa-check-square-o'></i>CheckBox&nbsp;<a class='fa fa-pencil' aria-hidden='true'></a></span>";
        } else if (question_type == "DrodownList") {
            survey_data += "<span style='cursor:pointer;' class='change_request' id='change_" + page_no + que_no + "' ondblclick='drop_down(this," + page_no + "," + que_no + ")'><i class='fa fa-chevron-down'></i>Dropdown List&nbsp;<a class='fa fa-pencil' aria-hidden='true'></a></span>";
        } else if (question_type == "RadioButton") {
            survey_data += "<span style='cursor:pointer;' class='change_request' id='change_" + page_no + que_no + "' ondblclick='drop_down(this," + page_no + "," + que_no + ")'><i class='fa fa-dot-circle-o'></i>Radio Button&nbsp;<a class='fa fa-pencil' aria-hidden='true'></a></span>";
        } else if (question_type == "ContactInformation") {
            survey_data += "<span style='cursor:pointer;' class='change_request' id='change_" + page_no + que_no + "' ondblclick='drop_down(this," + page_no + "," + que_no + ")'><i class='fa fa-list-alt'></i>Contact Information&nbsp;<a class='fa fa-pencil' aria-hidden='true'></a></span>";
        } else if (question_type == "Rating") {
            survey_data += "<span style='cursor:pointer;' class='change_request' id='change_" + page_no + que_no + "' ondblclick='drop_down(this," + page_no + "," + que_no + ")'><i class='fa fa-star'></i>Rating&nbsp;<a class='fa fa-pencil' aria-hidden='true'></a></span>";
        } else if (question_type == "date-time") {
            survey_data += "<span style='cursor:pointer;' class='change_request' id='change_" + page_no + que_no + "' ondblclick='drop_down(this," + page_no + "," + que_no + ")'><i class='fa fa-calendar'></i>DateTime&nbsp;<a class='fa fa-pencil' aria-hidden='true'></a></span>";
        } else if (question_type == "Image") {
            survey_data += "<span style='cursor:pointer;' class='change_request' id='change_" + page_no + que_no + "' ondblclick='drop_down(this," + page_no + "," + que_no + ")'><i class='fa fa-picture-o'></i>Image&nbsp;<a class='fa fa-pencil' aria-hidden='true'></a></span>";
        } else if (question_type == "Video") {
            survey_data += "<span style='cursor:pointer;' class='change_request' id='change_" + page_no + que_no + "' ondblclick='drop_down(this," + page_no + "," + que_no + ")'><i class='fa fa-video-camera'></i>Video&nbsp;<a class='fa fa-pencil' aria-hidden='true'></a></span>";
        } else if (question_type == "Scale") {
            survey_data += "<span style='cursor:pointer;' class='change_request' id='change_" + page_no + que_no + "' ondblclick='drop_down(this," + page_no + "," + que_no + ")'><i class='fa fa-arrows-h'></i>Scale&nbsp;<a class='fa fa-pencil' aria-hidden='true'></a></span>";
        } else if (question_type == "Matrix") {
            survey_data += "<span style='cursor:pointer;' class='change_request' id='change_" + page_no + que_no + "' ondblclick='drop_down(this," + page_no + "," + que_no + ")'><i class='fa fa-th'></i>Matrix&nbsp;<a class='fa fa-pencil' aria-hidden='true'></a></span>";
        }
        $('#edit_que_type_' + question_type + '_' + que_no).parent().find('select').remove();
        $('#general' + page_no + que_no).find('.left-label').eq(0).after(survey_data);
    }
}
/**
 * get data if the question is already
 *
 * @author     Original Author Biztech Co.
 * @param      integer - page_no,que_no
 * @param      String - el
 * @return
 */
function drop_down(el, page_no, que_no) {
    $(el).hide();
    var el_title = $(el).parent().parent().parent().find('.que_title').val();
    var el_help = $(el).parent().parent().parent().parent().find('.question_help_comment').val();
    var qn_id = $(el).parent().parent().parent().find('#que_id' + page_no + que_no).val();
    var ans = new Array();
    var cnt = 0;
    $.each($('#answer_div' + page_no + que_no).find('input[type=text]'), function () {
        ans[cnt] = $(this).val();
        cnt += 1;
    });
    var ans_id = new Array();
    var ansid_cnt = 0;
    $.each($(el).parent().parent().parent().find('input[id=ans_id]'), function () {
        ans_id[ansid_cnt] = $(this).val();
        ansid_cnt += 1
    });
    var questions = new Array();
    questions['que_type'] = $(el).parent().find('input[type=hidden]').val();
    var question_sequence = que_no;

    var data = dropDownEditQueType(el, questions, question_sequence, page_no, el_title, el_help, ans, qn_id, ans_id);
    $(el).parent().find("label.left-label").after(data);
}
/**
 * Display Drop Down list Of Question Type
 *
 * @author     Original Author Biztech Co.
 * @param      integer - page_no,que_no
 * @param      String - el
 * @return
 */
function dropDownEditQueType(el, questions, question_sequence, page_no, title, help, ans, qn_id, ans_id) {
    var survey_data = '';
    // set question type icons for each question dropdown to edit que type
    survey_data += '<select class="edit_que-type" id="edit_que_type_' + questions['que_type'] + '_' + question_sequence + '" onchange="conver_quetype(event,' + page_no + ',' + question_sequence + ',\'' + title + '\',\'' + help + '\',\'' + ans + '\',\'' + qn_id + '\',\'' + ans_id + '\')" style="font-family: \'FontAwesome\', Helvetica; ">';
    survey_data += '   <option value="Checkbox" ';
    if (questions['que_type'] == "Checkbox") {
        survey_data += 'selected';
    }
    survey_data += '>&#xf046; &nbsp; CheckBox</option>';
    survey_data += '   <option value="DrodownList" ';
    if (questions['que_type'] == "DrodownList") {
        survey_data += 'selected';
    }
    survey_data += '>&#xf078; &nbsp; DropdownList</option>';
    survey_data += '   <option value="RadioButton" ';
    if (questions['que_type'] == "RadioButton") {
        survey_data += 'selected';
    }
    survey_data += '>&#xf192; &nbsp; RadioButton</option>';
    survey_data += '   <option value="MultiSelectList" ';
    if (questions['que_type'] == "MultiSelectList") {
        survey_data += 'selected';
    }
    survey_data += '>&#xf0ca; &nbsp; MultiSelectList</option>';
    survey_data += '   <option value="Matrix" ';
    if (questions['que_type'] == "Matrix") {
        survey_data += 'selected';
    }
    survey_data += '>&#xf00a; &nbsp; Matrix </i></option>';
    survey_data += '   <option value="Date" ';
    if (questions['que_type'] == "Date") {
        survey_data += 'selected';
    }
    survey_data += '>&#xf073; &nbsp; DateTime </i></option>';
    survey_data += '   <option value="Textbox" ';
    if (questions['que_type'] == "Textbox") {
        survey_data += 'selected';
    }
    survey_data += '>&#xf0f6; &nbsp; TextBox</option>';
    survey_data += '   <option value="CommentTextbox" ';
    if (questions['que_type'] == "CommentTextbox") {
        survey_data += 'selected';
    }
    survey_data += '>&#xf0e6; &nbsp; Comment TextBox</option>';
    survey_data += '   <option value="Scale" ';
    if (questions['que_type'] == "Scale") {
        survey_data += 'selected';
    }
    survey_data += '>&#xf07e; &nbsp; Scale </i></option>';
    survey_data += '   <option value="Rating" ';
    if (questions['que_type'] == "Rating") {
        survey_data += 'selected';
    }
    survey_data += '>&#xf005; &nbsp; Rating </i></option>';
    survey_data += '   <option value="Image" ';
    if (questions['que_type'] == "Image") {
        survey_data += 'selected';
    }
    survey_data += '>&#xf1c5; &nbsp; Image </i></option>';
    survey_data += '   <option value="Video" ';
    if (questions['que_type'] == "Video") {
        survey_data += 'selected';
    }
    survey_data += '>&#xf03d; &nbsp; Video </i></option>';
    survey_data += '   <option value="ContactInformation" ';
    if (questions['que_type'] == "ContactInformation") {
        survey_data += 'selected';
    }
    survey_data += '>&#xf022; &nbsp; Contact Information</option>';
    survey_data += '</select>';
    return survey_data;
}
/**
 * after change question fillup data at blank question
 *
 * @author     Original Author Biztech Co.
 * @param      integer - page_no,que_no
 * @param      String - el,title, help, qn_id
 * @param      array - ans
 * @return
 */
function conver_quetype(el, page_no, que_no, title, help, ans, qn_id, ans_id) {

    try {
        var target_value = new Object();
        var target_value_obj_value = new Object();
        var action_value = '';
        var other_option = '';
        $.each($('.target_clear_all' + page_no + que_no), function () {
            action_value = $(this).prev().val();
            var answer_id = ((($(this).attr('class')).split(' '))[1]).split('_')[1];
            target_value[action_value] = $(this).val();
            target_value_obj_value[answer_id] = target_value;
            try {
                other_option = $(this).parent().find('.Other_Option').attr('class').split(' ')[0].split('_')[1];
            } catch (e) {
            }
            target_value = {};
        });
        var target_value_obj = target_value_obj_value;
    } catch (e) {
    }
    try {
        delete target_value_obj[other_option]
    } catch (e) {
    }
    var ans_detail = ans.split(',');
    var current_el_id = el.currentTarget.id;
    var current_que_type = current_el_id.split('_')[3];
    var current_que_id = current_el_id.split('_')[4];
    var queType = $('#edit_que_type_' + current_que_type + '_' + current_que_id).val();
    var data_que = Drop_DownQue(queType, page_no, que_no, current_que_type, ans_id, target_value_obj);
    var answer_id = ans_id.split(',');
    if (data_que != undefined) {
        $('#que_' + page_no + que_no).replaceWith(data_que);
        if (title != "") {
            $('#general' + page_no + que_no).find('input.que_title').val(title);
        } else {
            $('#general' + page_no + que_no).find('input.que_title').val('');
        }
        if (help != "undefined") {
            $('#advanced' + page_no + que_no).find('input.question_help_comment').val(help);
        } else {
            $('#advanced' + page_no + que_no).find('input.question_help_comment').val('');
        }
        if (qn_id != "") {
            $('#general' + page_no + que_no).find('#que_id' + page_no + que_no).val(qn_id);
            $('#general' + page_no + que_no).find('#que_id_del' + page_no + que_no).val(qn_id);
        } else {
            $('#general' + page_no + que_no).find('#que_id' + page_no + que_no).val('');
            $('#general' + page_no + que_no).find('#que_id_del' + page_no + que_no).val('');
        }
        if (queType == "MultiSelectList" || queType == "Checkbox" || queType == "DrodownList" || queType == "RadioButton") {
            $("#tabs" + page_no + que_no).find('#lgc').removeClass('disabled');
        }
        if (ans_detail.length >= 1) {
            if (ans_detail[0] != "") {
                var data = '';
                var length = Object.keys(ans_detail).length;
                var custom_len = 1;
                var showPlusBtn = 'display:none';
                var showremBtn = 'display:none';
                $.each(ans_detail, function (answerIndex, answerValue) {
                    if (custom_len == length) {
                        showPlusBtn = 'display:inline;';
                        showremBtn = 'display:inline;';
                    } else {
                        showPlusBtn = 'display:none;';
                        showremBtn = 'display:inline;';
                    }
                    var option = "";
                    if (custom_len == 1) {
                        option = "Option";
                    }
                    if (length > 2) {
                        if (answer_id[answerIndex]) {
                            data += "<li id='ans_op" + page_no + "" + que_no + "" + answerIndex + "' class='ans_op_class" + page_no + "" + que_no + "'><label class='left-label'>" + option + "</label><span><input type='text' class='answer" + page_no + que_no + "' name=answer[" + page_no + "][" + que_no + "][" + answerIndex + "] value='" + answerValue + "' /></span>&nbsp;<input type='number' name='score_dropdownlist[" + page_no + "][" + que_no + "][" + answerIndex + "]' value='" + custom_len + "' onblur='checkValue(this)' class='score_weight" + page_no + que_no + "' style='margin-left: 6px; text-align: center;max-width: 7%;height: 22px;display:none;'><input type='button' value='&#10005;' name='btnRemoveOption' style='" + showremBtn + " margin-left:6px;height:25px;width:25px;' onclick='removeOption(" + page_no + "," + que_no + ",this)' /><input name='btnOptionAdd' type='button' value='&#43;' onclick='editNewOption(this," + page_no + "," + que_no + ")' style='margin-left:10px;height:25px;width:25px;" + showPlusBtn + "'/>";
                            data += "<input type='hidden' name='ans_id[" + page_no + "][" + que_no + "][" + answerIndex + "]' id ='ans_id' style='width: 37.5%' value='" + answer_id[answerIndex] + "'/>";
                            data += "<input type='hidden' name='ans_id_del[" + page_no + "][" + que_no + "][" + answerIndex + "]' id='ans_id_del' style='width: 37.5%' value='" + answer_id[answerIndex] + "' />";
                        } else {
                            data += "<li id='ans_op" + page_no + "" + que_no + "" + answerIndex + "' class='ans_op_class" + page_no + "" + que_no + "'><label class='left-label'>" + option + "</label><span><input type='text' class='answer" + page_no + que_no + "' name=answer[" + page_no + "][" + que_no + "][" + answerIndex + "] value='" + answerValue + "' /></span>&nbsp;<input type='number' name='score_dropdownlist[" + page_no + "][" + que_no + "][" + answerIndex + "]' value='" + custom_len + "' onblur='checkValue(this)' class='score_weight" + page_no + que_no + "' style='margin-left: 6px; text-align: center;max-width: 7%;height: 22px;display:none;'><input type='button' value='&#10005;' name='btnRemoveOption' style='" + showremBtn + " margin-left:6px;height:25px;width:25px;' onclick='removeOption(" + page_no + "," + que_no + ",this)' />";
                            data += "<input type='hidden' name='ans_id[" + page_no + "][" + que_no + "][" + answerIndex + "]' id='ans_id' style='width: 37.5%' value=''/>";
                            data += "<input type='hidden' name='ans_id_del[" + page_no + "][" + que_no + "][" + answerIndex + "]' id='ans_id_del' style='width: 37.5%' value='' /><input name='btnOptionAdd' type='button' value='&#43;' onclick='editNewOption(this," + page_no + "," + que_no + ")' style='margin-left:10px;height:25px;width:25px; " + showPlusBtn + "'/>";
                        }
                    } else {
                        if (answer_id[answerIndex]) {
                            data += "<li id='ans_op" + page_no + "" + que_no + "" + answerIndex + "' class='ans_op_class" + page_no + "" + que_no + "'><label class='left-label'>" + option + "</label><span><input type='text' class='answer" + page_no + que_no + "' name=answer[" + page_no + "][" + que_no + "][" + answerIndex + "] value='" + answerValue + "' /></span>&nbsp;<input type='number' name='score_dropdownlist[" + page_no + "][" + que_no + "][" + answerIndex + "]' value='" + custom_len + "' onblur='checkValue(this)' class='score_weight" + page_no + que_no + "' style='margin-left: 6px; text-align: center;max-width: 7%;height: 22px;display:none;'><input type='button' value='&#10005;' name='btnRemoveOption' style='display:none; margin-left:6px;height:25px;width:25px;' onclick='removeOption(" + page_no + "," + que_no + ",this)' />";
                            data += "<input type='hidden' name='ans_id[" + page_no + "][" + que_no + "][" + answerIndex + "]'id='ans_id' style='width: 37.5%' value='" + answer_id[answerIndex] + "'/>";
                            data += "<input type='hidden' name='ans_id_del[" + page_no + "][" + que_no + "][" + answerIndex + "]' id='ans_id_del' style='width: 37.5%' value='" + answer_id[answerIndex] + "' /><input name='btnOptionAdd' type='button' value='&#43;' onclick='editNewOption(this," + page_no + "," + que_no + ")' style='margin-left:10px;height:25px;width:25px; " + showPlusBtn + "'/>";
                        } else {
                            data += "<li id='ans_op" + page_no + "" + que_no + "" + answerIndex + "' class='ans_op_class" + page_no + "" + que_no + "'><label class='left-label'>" + option + "</label><span><input type='text' class='answer" + page_no + que_no + "' name=answer[" + page_no + "][" + que_no + "][" + answerIndex + "] value='" + answerValue + "' /></span>&nbsp;<input type='number' name='score_dropdownlist[" + page_no + "][" + que_no + "][" + answerIndex + "]' value='" + custom_len + "' onblur='checkValue(this)' class='score_weight" + page_no + que_no + "' style='margin-left: 6px; text-align: center;max-width: 7%;height: 22px;display:none;'><input type='button' value='&#10005;' name='btnRemoveOption' style='display:none; margin-left:6px;height:25px;width:25px;' onclick='removeOption(" + page_no + "," + que_no + ",this)' />";
                            data += "<input type='hidden' name='ans_id[" + page_no + "][" + que_no + "][" + answerIndex + "]'id='ans_id' style='width: 37.5%' value=''/>";
                            data += "<input type='hidden' name='ans_id_del[" + page_no + "][" + que_no + "][" + answerIndex + "]' id='ans_id_del' style='width: 37.5%' value='' /><input name='btnOptionAdd' type='button' value='&#43;' onclick='editNewOption(this," + page_no + "," + que_no + ")' style='margin-left:10px;height:25px;width:25px; " + showPlusBtn + "'/>";
                        }
                    }
                    custom_len += 1;
                });
            }
            $('#answer_div' + page_no + que_no).html(data);

            var ans_idex = 0;
            var logic_detail = '';
            $.each(target_value_obj, function (key, value) {
                $.each(value, function (action, target) {
                    var logic_tr = '';
                    logic_tr += "<tr class='opid_" + key + "' id='con_value" + page_no + que_no + ans_idex + "'>";
                    logic_tr += '<input type="hidden" class="action_clear_all' + page_no + que_no + ' ansid_' + key + '" id="action' + page_no + que_no + ans_idex + '" value="' + action + '">';
                    logic_tr += '<input type="hidden" class="target_clear_all' + page_no + que_no + ' ansid_' + key + '" id="target' + page_no + que_no + ans_idex + '" value="' + target + '">';
                    logic_tr += " <td class='logic-table-first-column'><label style='color:#000;'></label></td><td class='condition'><select style='width: auto;cursor:pointer;' class='clear_all" + page_no + que_no + "' id='skipp_logic_function" + page_no + que_no + ans_idex + "' onchange='skipp_logic(this," + page_no + "," + que_no + "," + ans_idex + ")' name='option_value[" + page_no + "][" + que_no + "][" + ans_idex + "]'><option value='no_logic'>No Logic</option>";
                    if (action == "redirect_page") {
                        logic_tr += "<option value='redirect_page' selected>Redirect To Page</option>";
                    } else {
                        logic_tr += "<option value='redirect_page'>Redirect To Page</option>";
                    }
                    if (action == "end_page") {
                        logic_tr += "<option value='end_page' selected>End Of Survey</option>";
                    } else {
                        logic_tr += "<option value='end_page'>End Of Survey</option>";
                    }
                    if (action == "redirect_url") {
                        logic_tr += "<option value='redirect_url' selected>Redirect to Url</option>";
                    } else {
                        logic_tr += "<option value='redirect_url'>Redirect to Url</option>";
                    }
                    if (action == "show_hide_question") {
                        logic_tr += "<option value='show_hide_question' selected>Show/Hide Questions</option>";
                    } else {
                        logic_tr += "<option value='show_hide_question'>Show/Hide Questions</option>";
                    }
                    logic_tr += "</select></td><td>";
                    if (action == "redirect_page") {
                        logic_tr += '<select style="width: auto;" id="page_skipp_logic' + page_no + que_no + ans_idex + '" class="logic-page-select" name="page_skipp_logic[' + page_no + '][' + que_no + '][' + ans_idex + ']"><option value="' + target + '" selected></option></select>';
                    } else if (action == "redirect_url") {
                        logic_tr += '<input class="input-text logic-url-box" type="text" name="page_skipp_logic[' + page_no + '][' + que_no + '][' + ans_idex + ']" value="' + target + '">';
                    } else if (action == "show_hide_question") {
                        logic_tr += '<select style="width: auto;" class="show_hide_dd" id="show_hide_dd' + page_no + que_no + ans_idex + '" name="page_skipp_logic[' + page_no + '][' + que_no + '][' + ans_idex + '][]" multiple>';
                        var array = (target).split(',');
                        var length_of_array = ((target).split(',')).length;
                        while (length_of_array > 0) {
                            logic_tr += "<option value='" + array[length_of_array - 1] + "' selected></option>";
                            length_of_array -= 1;
                        }
                        logic_tr += "</select>"
                    }
                    logic_tr += "</td><td style='color:#000; text-align:center;'><a id='clear_" + page_no + que_no + ans_idex + "' onclick='clear_element(this," + page_no + "," + que_no + "," + ans_idex + ")' class='fa fa-remove' style='cursor:pointer; font-size:14px;margin: 5px 3px;'></a></td></tr>";
                    ans_idex += 1;
                    $("#logic_table" + page_no + que_no).find('tbody').append(logic_tr);
                });
            });
        }
    }
    if (queType == "Date") {
        $.ajax({
            url: 'index.php',
            data: {
                module: 'bc_survey',
                action: 'currentdateformat',
            },
            success: function (data, textStatus, jqXHR) {
                var formet = trim(data);
                Calendar.setup({
                    inputField: "stdate" + page_no + que_no,
                    onClose: function (cal) {
                        cal.hide();
                    },
                    form: "EditView",
                    ifFormat: formet,
                    daFormat: formet,
                    button: "startdate" + page_no + que_no,
                    singleClick: true,
                    dateStr: "",
                    startWeekday: "",
                    step: 1,
                    weekNumbers: false
                });
                Calendar.setup({
                    inputField: "edate" + page_no + que_no,
                    onClose: function (cal) {
                        cal.hide();
                    },
                    form: "EditView",
                    ifFormat: formet,
                    daFormat: formet,
                    button: "enddate" + page_no + que_no,
                    singleClick: true,
                    dateStr: "",
                    startWeekday: "",
                    step: 1,
                    weekNumbers: false
                });
            }
        });
    }
}
/**
 * while edit question
 *
 * @author     Original Author Biztech Co.
 * @param      integer - page_no,que_no
 * @param      array - queValue
 * @return
 */
//for edit question
function editQuestionHtml(queValue, page_no, que_no) {
    var data = '';
    var que_icon = '';
    var single_choice_que_type = ''; //store question for Textbox,CommentTextbox,etc
    var single_choice_que = '';
    var multi_choice_que_type = ''; // store question type
    var multi_choice_que = '';
    var multi_choice_type_value = {
        "MultiSelectList": 'MultiSelect List',
        "Checkbox": 'Checkbox',
        "DrodownList": 'Dropdown List',
        "RadioButton": 'Radio Button',
    };

    var single_choice_type_value = {
        "Textbox": 'Textbox',
        "CommentTextbox": 'Comment Textbox',
        "ContactInformation": 'Contact Information',
        "Rating": 'Rating',
    };
    if ("Textbox" === queValue.que_type) {
        que_icon = "<i class='fa fa-file-text-o' aria-hidden='true'></i>";
        single_choice_que_type = "Textbox";
        single_choice_que = true;
    } else if ("CommentTextbox" === queValue.que_type) {
        que_icon = "<i class='fa fa-comments-o' aria-hidden='true'></i>";
        single_choice_que_type = "CommentTextbox";
        single_choice_que = true;
    } else if ("ContactInformation" === queValue.que_type) {
        que_icon = "<i class='fa fa-list-alt' aria-hidden='true'></i>";
        single_choice_que_type = "ContactInformation";
        single_choice_que = true;
    } else if ("Rating" === queValue.que_type) {
        que_icon = "<i class='fa fa-star' aria-hidden='true'></i>";
        single_choice_que_type = "Rating";
        single_choice_que = true;
    }
//html for single choice question type
    if (single_choice_que == true) {
        data += "<div class='survey-body' id='que_" + page_no + que_no + "'>";
        data += "<div class='s-row question' id='questionline" + page_no + "'>";
        data += "<div class='right-close-add-div' align='right'><a style='cursor:pointer;' onclick='addSection(this," + page_no + "," + que_no + ")'><i class='fa fa-pause fa-5 fa-rotate-90' title='Add Section' aria-hidden='true' style='font-size:14px;margin: 5px 3px;'></i></a><a id='que_remove_" + que_no + "' style='cursor:pointer;' onclick='removeQuestions(this," + page_no + ")'><i class='fa fa-remove' id='remove_que_" + que_no + "' title='Remove Question' style='font-size:14px;margin: 5px 3px;'></i></a></div>";
        data += "<div id='tabs" + page_no + que_no + "'>";
        data += "<div class='tab' style='margin-bottom: 20px;'>";
        data += '   <a class="advance_tab general_tab active" id="gen" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="gen">General</p></a>';
        data += '   <a class="advance_tab" id="adv" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="adv">Advanced Option</p></a>';
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += '   <a class="advance_tab pipe_tab" id="pipe" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="pipe">Piping</p></a>';
        }
        data += '</div>';
        data += "<div id='general" + page_no + que_no + "' class='tabcontent general_tab_content' style='display:block;'><ul class='question-section' id='question_table" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id[" + page_no + "][" + que_no + "]' id='que_id" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id_del[" + page_no + "][" + que_no + "]' value='' id='que_id_del" + page_no + que_no + "'>";
        data += "<li class='s-type'><label class='left-label'>Question Type </label><span style='cursor:pointer;' class='change_request' id='change_" + page_no + que_no + "' ondblclick='drop_down(this," + page_no + "," + que_no + ")'>" + que_icon + single_choice_type_value[single_choice_que_type] + "&nbsp;&nbsp;<a class='fa fa-pencil' aria-hidden='true'></a></span><span><input type='hidden' name='que_type[" + page_no + "][" + que_no + "]' id='que_type" + page_no + "" + que_no + "' value='" + single_choice_que_type + "' /></span>  </li>";
        data += "<li class='s-title'> <label class='left-label'>Question<span class ='required'>*</span></label><span><input class='input-text que_title' id='que_title" + page_no + que_no + "' type='text' name='que_title[" + page_no + "][" + que_no + "]' value='" + queValue.que_title + "' /></span></li>";
        data += "</div>";
        if (single_choice_que_type == "Textbox") {
            data += "<ul class='question-section' id='question_table" + page_no + que_no + "'><div id='advanced" + page_no + que_no + "' class='tabcontent' style='display:none'>";
            data += "<li class='s-title'><label class='left-label'>Help Tips </label><span><input  class='input-text question_help_comment' type='text' name='question_help_comment[" + page_no + "][" + que_no + "]' value='" + queValue.question_help_comment + "' /></span></li>";
            if (queValue.is_required === "1") {
                data += "<li class='s-required'><label class='left-label'>Is required  </label><span><input type='checkbox' name='is_required[" + page_no + "][" + que_no + "]' id='is_required' checked/></span></li>";
            }
            else {
                data += "<li class='s-required'><label class='left-label'>Is required  </label><span><input type='checkbox' name='is_required[" + page_no + "][" + que_no + "]' id='is_required' /></span></li>";
            }
            data += "<input type='hidden' id='data_type_hidden" + page_no + que_no + "' name='data_type_hidden[" + page_no + "][" + que_no + "]' value='" + queValue.advance_type + "'/>"
            if (queValue.desable_piping == "0") {
                var disabled_select = "disabled";
            }
            if (queValue.advance_type == "Integer") {
                data += '<li class="data_type"><label class="left-label">DataType</label><select id="data_type' + page_no + que_no + '" class="datatype-textbox" onchange="textbox_dropdown(this,' + page_no + ',' + que_no + ')" name="datatype_textbox[' + page_no + '][' + que_no + ']" ' + disabled_select + '><option value="0">Select</option>';
                data += '<option value="Integer" selected>Number</option><option value="Float">Decimal</option><option value="Char">Char</option><option value="Email">Email</option>';
                data += '</select>';
                data += '<li class="max_char" style="display:none"><label class="left-label">Max Size</label><input type="text" onkeypress="return validateNumbericValue(event,' + page_no + ',' + que_no + ');" placeholder="Maximum acceptable char" name="size_textbox[' + page_no + '][' + que_no + ']" value="' + queValue.maxsize + '" class="inherit-width">';
            } else if (queValue.advance_type == "Float") {
                data += '<li class="data_type"><label class="left-label">DataType</label><select id="data_type' + page_no + que_no + '" class="datatype-textbox" onchange="textbox_dropdown(this,' + page_no + ',' + que_no + ')" name="datatype_textbox[' + page_no + '][' + que_no + ']" ' + disabled_select + '><option value="0">Select</option>';
                data += '<option value="Integer">Number</option><option value="Float" selected>Decimal</option><option value="Char">Char</option><option value="Email">Email</option>';
                data += '</select>';
                data += '<li class="max_char" style="display:none"><label class="left-label">Max Size</label><input type="text" onkeypress="return validateNumbericValue(event,' + page_no + ',' + que_no + ');" placeholder="Maximum acceptable char" name="size_textbox[' + page_no + '][' + que_no + ']" value="' + queValue.maxsize + '" class="inherit-width">';
            } else if (queValue.advance_type == "Char") {
                data += '<li class="data_type"><label class="left-label">DataType</label><select id="data_type' + page_no + que_no + '" class="datatype-textbox" onchange="textbox_dropdown(this,' + page_no + ',' + que_no + ')" name="datatype_textbox[' + page_no + '][' + que_no + ']" ' + disabled_select + '><option value="0">Select</option>';
                data += '<option value="Integer">Number</option><option value="Float">Decimal</option><option value="Char" selected>Char</option><option value="Email">Email</option>';
                data += '</select>';
                data += '<li class="max_char"><label class="left-label">Max Size</label><input type="text" onkeypress="return validateNumbericValue(event,' + page_no + ',' + que_no + ');" placeholder="Maximum acceptable char" name="size_textbox[' + page_no + '][' + que_no + ']" value="' + queValue.maxsize + '" class="inherit-width">';
            } else if (queValue.advance_type == "Email") {
                data += '<li class="data_type"><label class="left-label">DataType</label><select id="data_type' + page_no + que_no + '" class="datatype-textbox" onchange="textbox_dropdown(this,' + page_no + ',' + que_no + ')" name="datatype_textbox[' + page_no + '][' + que_no + ']" ' + disabled_select + '><option value="0">Select</option>';
                data += '<option value="Integer">Number</option><option value="Float">Decimal</option><option value="Char">Char</option><option value="Email" selected>Email</option>';
                data += '</select>';
                data += '<li class="max_char" style="display:none"><label class="left-label">Max Size</label><input type="text" onkeypress="return validateNumbericValue(event,' + page_no + ',' + que_no + ');" placeholder="Maximum acceptable char" name="size_textbox[' + page_no + '][' + que_no + ']" value="' + queValue.maxsize + '" class="inherit-width">';
            } else {
                data += '<li class="data_type"><label class="left-label">DataType</label><select id="data_type' + page_no + que_no + '" class="datatype-textbox" onchange="textbox_dropdown(this,' + page_no + ',' + que_no + ')" name="datatype_textbox[' + page_no + '][' + que_no + ']" ' + disabled_select + '><option value="0">Select</option>';
                data += '<option value="Integer">Number</option><option value="Float">Decimal</option><option value="Char">Char</option><option value="Email">Email</option>';
                data += '</select>';
                data += '<li class="max_char"><label class="left-label">Max Size</label><input type="text" onkeypress="return validateNumbericValue(event,' + page_no + ',' + que_no + ');" placeholder="Maximum acceptable char" name="size_textbox[' + page_no + '][' + que_no + ']" value="' + queValue.maxsize + '" class="inherit-width">';
            }

            if (queValue.advance_type == "Integer") {
                data += '<li class ="integer' + page_no + que_no + '"><label class="left-label">Min Value</label><input type="text" class="minint" onkeypress="return validateNumbericValue(event,' + page_no + ',' + que_no + ');" placeholder="Minimum value" name="min_integer[' + page_no + '][' + que_no + ']" value="' + queValue.min + '">';
                data += '<li class ="integer' + page_no + que_no + '"><label class="left-label">Max Value</label><input type="text" class="maxint" onkeypress="return validateNumbericValue(event,' + page_no + ',' + que_no + ');" placeholder="Maximum value" name="max_integer[' + page_no + '][' + que_no + ']" value="' + queValue.max + '">';
                data += '<li class ="float' + page_no + que_no + '" style="display:none;"><label class="left-label">Precision</label><input type="text" onkeypress="return validateNumbericValue(event,' + page_no + ',' + que_no + ');" placeholder="Precision" name="precision_textbox[' + page_no + '][' + que_no + ']">';
                data += '<li class ="float' + page_no + que_no + '" style="display:none;"><label class="left-label">Min Value</label><input type="text" class="minfloat" onkeypress="return validateDecimalValue(event,' + page_no + ',' + que_no + ');" placeholder="Minimum value" name="min_float[' + page_no + '][' + que_no + ']">';
                data += '<li class ="float' + page_no + que_no + '" style="display:none;"><label class="left-label">Max value</label><input type="text"class="maxfloat" onkeypress="return validateDecimalValue(event,' + page_no + ',' + que_no + ');" placeholder="Maximum value" name="max_float[' + page_no + '][' + que_no + ']">';
            } else if (queValue.advance_type == "Float") {
                data += '<li class ="float' + page_no + que_no + '" ><label class="left-label">Precision</label><input type="text" onkeypress="return validateNumbericValue(event,' + page_no + ',' + que_no + ');" placeholder="Precision" name="precision_textbox[' + page_no + '][' + que_no + ']" value="' + queValue.precision_value + '">';
                data += '<li class ="float' + page_no + que_no + '"><label class="left-label">Min Value</label><input type="text" class="minfloat" onkeypress="return validateDecimalValue(event,' + page_no + ',' + que_no + ');" placeholder="Minimum value" name="min_float[' + page_no + '][' + que_no + ']" value="' + queValue.min + '">';
                data += '<li class ="float' + page_no + que_no + '"><label class="left-label">Max value</label><input type="text" class="maxfloat" onkeypress="return validateDecimalValue(event,' + page_no + ',' + que_no + ');" placeholder="Maximum value" name="max_float[' + page_no + '][' + que_no + ']" value="' + queValue.max + '">';
                data += '<li class ="integer' + page_no + que_no + '" style="display:none;"><label class="left-label">Min Value</label><input class="minint" type="text" onkeypress="return validateNumbericValue(event,' + page_no + ',' + que_no + ');" placeholder="Minimum value" name="min_integer[' + page_no + '][' + que_no + ']">';
                data += '<li class ="integer' + page_no + que_no + '" style="display:none;"><label class="left-label">Max Value</label><input type="text" class="maxint" onkeypress="return validateNumbericValue(event,' + page_no + ',' + que_no + ');" placeholder="Maximum value" name="max_integer[' + page_no + '][' + que_no + ']">';
            } else {
                data += '<li class ="float' + page_no + que_no + '" style="display:none"><label class="left-label">Precision</label><input type="text" onkeypress="return validateNumbericValue(event,' + page_no + ',' + que_no + ');" placeholder="Precision" name="precision_textbox[' + page_no + '][' + que_no + ']">';
                data += '<li class ="float' + page_no + que_no + '" style="display:none"><label class="left-label">Min Value</label><input type="text" class="minfloat" onkeypress="return validateDecimalValue(event,' + page_no + ',' + que_no + ');" placeholder="Minimum value" name="min_float[' + page_no + '][' + que_no + ']">';
                data += '<li class ="float' + page_no + que_no + '"style="display:none"><label class="left-label">Max value</label><input type="text" class="maxfloat" onkeypress="return validateDecimalValue(event,' + page_no + ',' + que_no + ');" placeholder="Maximum value" name="max_float[' + page_no + '][' + que_no + ']">';
                data += '<li class ="integer' + page_no + que_no + '" style="display:none;"><label class="left-label">Min Value</label><input type="text" class="minint" placeholder="Minimum value" onkeypress="return validateNumbericValue(event,' + page_no + ',' + que_no + ');" name="min_integer[' + page_no + '][' + que_no + ']">';
                data += '<li class ="integer' + page_no + que_no + '" style="display:none;"><label class="left-label">Max Value</label><input type="text" class="maxint" onkeypress="return validateNumbericValue(event,' + page_no + ',' + que_no + ');" placeholder="Maximum value" name="max_integer[' + page_no + '][' + que_no + ']">';
            }
            data += "</div>";
            data += "</ul></div>";
        } else if (single_choice_que_type == "CommentTextbox") {
            data += "<div id='advanced" + page_no + que_no + "' class='tabcontent' style='display:none'>";
            data += "<ul><li class='s-title'><label class='left-label'>Help Tips </label><span><input class='input-text question_help_comment' placeholder='Enter help tips for question' type='text' name='question_help_comment[" + page_no + "][" + que_no + "]' value='" + queValue.question_help_comment + "'></span></li>";
            if (queValue.is_required === "1") {
                data += "<li class='s-required'><label class='left-label'>Is required  </label><span><input type='checkbox' name='is_required[" + page_no + "][" + que_no + "]' id='is_required' checked/></span></li>";
            }
            else {
                data += "<li class='s-required'><label class='left-label'>Is required  </label><span><input type='checkbox' name='is_required[" + page_no + "][" + que_no + "]' id='is_required' /></span></li>";
            }
            data += '<li class="max_char"><label class="left-label">Max Size</label><input type="text" onkeypress="return validateNumbericValue(event,' + page_no + ',' + que_no + ');" placeholder="Maximum acceptable char" name="size_textbox[' + page_no + '][' + que_no + ']" value=' + queValue.maxsize + '></li>';
            data += '<li class="no_of_rows"><label class="left-label">Rows</label><input type="text" onkeypress="return validateNumbericValue(event,' + page_no + ',' + que_no + ');" placeholder="Rows" name="rows_commentbox[' + page_no + '][' + que_no + ']" value=' + queValue.min + '></li>';
            data += '<li class="no_of_cols"><label class="left-label">Columns</label><input type="text" onkeypress="return validateNumbericValue(event,' + page_no + ',' + que_no + ');" placeholder="Columns" name="cols_commentbox[' + page_no + '][' + que_no + ']" value=' + queValue.max + '></li>';
            data += "</ul></div>";
        } else if (single_choice_que_type == "ContactInformation") {
            data += "<div id='advanced" + page_no + que_no + "' class='tabcontent' style='display:none'>";
            data += "<ul><li class='s-title'><label class='left-label'>Help Tips </label><span><input  class='input-text question_help_comment' placeholder='Enter help tips for question' type='text' name='question_help_comment[" + page_no + "][" + que_no + "]' value='" + queValue.question_help_comment + "'></span></li>";
            if (queValue.is_required === "1") {
                data += "<li class='s-required'><label class='left-label'>Is required  </label><span><input type='checkbox' class='is_required' onclick='change_checkbox(this ," + page_no + "," + que_no + ")' name='is_required[" + page_no + "][" + que_no + "]' id='is_required' checked/></span></li>";
                data += '<div class="row' + page_no + que_no + '"><li class="s-required"><label class="left-label">Required Fields</label>';
                var required_fields = queValue.advance_type.split(' ');
                if ($.inArray("Name", required_fields) != -1) {
                    data += '<div class="contact-info first"><span><input class="requiredfields" type="checkbox" name="cname[' + page_no + '][' + que_no + ']" checked>Name &nbsp;</span>';
                } else {
                    data += '<div class="contact-info first"><span><input class="requiredfields" type="checkbox" name="cname[' + page_no + '][' + que_no + ']">Name &nbsp;</span>';
                }
                if ($.inArray("Email", required_fields) != -1) {
                    data += '<span><input class="requiredfields" type = "checkbox" name = "email[' + page_no + '][' + que_no + ']" checked > Email Address &nbsp;</span>';
                } else {
                    data += '<span><input class="requiredfields" type = "checkbox" name = "email[' + page_no + '][' + que_no + ']"> Email Address &nbsp;</span>';
                }
                if ($.inArray("Company", required_fields) != -1) {
                    data += '<span><input class="requiredfields" type = "checkbox" name = "company[' + page_no + '][' + que_no + ']" checked> Company &nbsp;</span>';
                } else {
                    data += '<span><input class="requiredfields" type = "checkbox" name = "company[' + page_no + '][' + que_no + ']"> Company &nbsp;</span>';
                }
                if ($.inArray("Phone", required_fields) != -1) {
                    data += '<span><input class="requiredfields" type = "checkbox" name = "phone[' + page_no + '][' + que_no + ']" checked > Phone Number &nbsp;</span>';
                } else {
                    data += '<span><input class="requiredfields" type = "checkbox" name = "phone[' + page_no + '][' + que_no + ']"> Phone Number &nbsp;</span>';
                }
                if ($.inArray("Address", required_fields) != -1) {
                    data += '<span><input class="requiredfields" type = "checkbox" name = "address[' + page_no + '][' + que_no + ']" checked> Address </span></div></li></div>';
                } else {
                    data += '<span><input class="requiredfields" type = "checkbox" name = "address[' + page_no + '][' + que_no + ']"> Address </span></div></li></div>';
                }
                if ($.inArray("Address2", required_fields) != -1) {
                    data += '<div class="row' + page_no + que_no + '"><li class="s-required"><label class="left-label">&nbsp;</label><div class="contact-info"><span><input class="requiredfields" type="checkbox" name="address2[' + page_no + '][' + que_no + ']" checked>Address 2 &nbsp;</span>';
                } else {
                    data += '<div class="row' + page_no + que_no + '"><li class="s-required"><label class="left-label">&nbsp;</label><div class="contact-info"><span><input class="requiredfields" type="checkbox" name="address2[' + page_no + '][' + que_no + ']">Address 2 &nbsp;</span>';
                }
                if ($.inArray("City", required_fields) != -1) {
                    data += '<span><input class="requiredfields" type="checkbox" name="city[' + page_no + '][' + que_no + ']" checked>City/Town&nbsp;</span>';
                } else {
                    data += '<span><input class="requiredfields" type="checkbox" name="city[' + page_no + '][' + que_no + ']">City/Town&nbsp;</span>';
                }
                if ($.inArray("State", required_fields) != -1) {
                    data += '<span><input class="requiredfields" type="checkbox" name="state[' + page_no + '][' + que_no + ']" checked>State/Province&nbsp;</span>';
                } else {
                    data += '<span><input class="requiredfields" type="checkbox" name="state[' + page_no + '][' + que_no + ']">State/Province&nbsp;</span>';
                }
                if ($.inArray("Zip", required_fields) != -1) {
                    data += '<span><input class="requiredfields" type="checkbox" name="zip[' + page_no + '][' + que_no + ']" checked>Zip/Postal Code&nbsp;</span>';
                } else {
                    data += '<span><input class="requiredfields" type="checkbox" name="zip[' + page_no + '][' + que_no + ']">Zip/Postal Code&nbsp;</span>';
                }
                if ($.inArray("Country", required_fields) != -1) {
                    data += '<span><input class="requiredfields" type="checkbox" name="country[' + page_no + '][' + que_no + ']" checked>Country</span></div></li></div>';
                } else {
                    data += '<span><input class="requiredfields" type="checkbox" name="country[' + page_no + '][' + que_no + ']">Country</span></div></li></div>';
                }
            } else {
                data += "<li class='s-required'><label class='left-label'>Is required</label><span><input type='checkbox' class='is_required' onclick='change_checkbox(this ," + page_no + "," + que_no + ")' name='is_required[" + page_no + "][" + que_no + "]' id='is_required" + page_no + que_no + "' /></span></li>";
                data += '<div class="row' + page_no + que_no + '" style="display:none;"><li class="s-required"><label class="left-label">Required Fields</label><div class="contact-info first"><span><input class="requiredfields" type="checkbox" name="cname[' + page_no + '][' + que_no + ']" checked>Name &nbsp;</span><span><input class="requiredfields" type="checkbox" name="email[' + page_no + '][' + que_no + ']" checked>Email Address&nbsp;</span><span><input class="requiredfields" type="checkbox" name="company[' + page_no + '][' + que_no + ']">Company&nbsp;</span><span><input class="requiredfields" type="checkbox" name="phone[' + page_no + '][' + que_no + ']" checked>Phone Number&nbsp;</span><span><input class="requiredfields" type="checkbox" name="address[' + page_no + '][' + que_no + ']">Address</span></div></li></div>';
                data += '<div class="row' + page_no + que_no + '" style="display:none;"><li class="s-required"><label class="left-label">&nbsp;</label><div class="contact-info"><span><input class="requiredfields" type="checkbox" name="address2[' + page_no + '][' + que_no + ']">Address 2 &nbsp;</span><span><input class="requiredfields" type="checkbox" name="city[' + page_no + '][' + que_no + ']">City/Town&nbsp;</span><span><input class="requiredfields" type="checkbox" name="state[' + page_no + '][' + que_no + ']">State/Province&nbsp;</span><span><input class="requiredfields" type="checkbox" name="zip[' + page_no + '][' + que_no + ']">Zip/Postal Code&nbsp;</span><span><input class="requiredfields" type="checkbox" name="country[' + page_no + '][' + que_no + ']">Country</span></div></li></div>';
            }
            data += "</ul></div>";
        } else if (single_choice_que_type == "Rating") {
            data += "<div id='advanced" + page_no + que_no + "' class='tabcontent' style='display:none'>";
            data += "<ul><li class='s-title'><label class='left-label'>Help Tips </label><span><input class='input-text question_help_comment' placeholder='Enter help tips for question' type='text' name='question_help_comment[" + page_no + "][" + que_no + "]' value='" + queValue.question_help_comment + "'></span></li>";
            if (queValue.is_required === "1") {
                data += "<li class='s-required'><label class='left-label'>Is required  </label><span><input type='checkbox' name='is_required[" + page_no + "][" + que_no + "]' id='is_required' checked/></span></li>";
            } else {
                data += "<li class='s-required'><label class='left-label'>Is required  </label><span><input type='checkbox' name='is_required[" + page_no + "][" + que_no + "]' id='is_required' /></span></li>";
            }
            data += '<li class="no_of_stars"><label class="left-label">Star Numbers</label><select style="width:auto;" name="starno_rating[' + page_no + '][' + que_no + ']">';
            if (queValue.maxsize == 0) {
                data += '<option selected value="0">Select</option>';
            } else {
                data += '<option value="0">Select</option>';
            }
            if (queValue.maxsize == 1)
            {
                data += '<option selected>1</option>';
            } else {
                data += '<option>1</option>';
            }
            if (queValue.maxsize == 2) {
                data += '<option selected>2</option>';
            } else {
                data += '<option>2</option>';
            }
            if (queValue.maxsize == 3) {
                data += '<option selected>3</option>';
            } else {
                data += '<option>3</option>';
            }
            if (queValue.maxsize == 4) {
                data += '<option selected>4</option>';
            } else {
                data += '<option>4</option>';
            }
            if (queValue.maxsize == 5) {
                data += '<option selected>5</option>';
            } else {
                data += '<option>5</option>';
            }
            if (queValue.maxsize == 6) {
                data += '<option selected>6</option>';
            } else {
                data += '<option>6</option>';
            }
            if (queValue.maxsize == 7) {
                data += '<option selected>7</option>';
            } else {
                data += '<option>7</option>';
            }
            if (queValue.maxsize == 8) {
                data += '<option selected>8</option>';
            } else {
                data += '<option>8</option>';
            }
            if (queValue.maxsize == 9) {
                data += '<option selected>9</option>';
            } else {
                data += '<option>9</option>';
            }
            if (queValue.maxsize == 10) {
                data += '<option selected>10</option>';
            } else {
                data += '<option>10</option>';
            }
            data += "</select></ul></div>";
        }
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += "<div id='pipe" + page_no + que_no + "' class='pipecontent' style='display:none'>";
            if (queValue.desable_piping != "0" && typeof queValue.desable_piping != "undefined") {
                data += "<ul><li class='s-title'><label class='left-label'>Disable Piping</label><input type='checkbox' onclick='desablepiping_checkbox(this," + page_no + "," + que_no + ")' name='desable_piping_que[" + page_no + "][" + que_no + "]' checked/></label></li>";
                data += "<li class='s-title' style='display:none;'><label class='left-label'>Sync Field</label><select class='fields_type' onchange='getfieldstype(this," + page_no + "," + que_no + ")' id='sync_field" + page_no + que_no + "' name='sync_fields[" + page_no + "][" + que_no + "]'>";
                var selected_module = $('#sync_module').val();
                data += '<option value="0">Select Fields</option>';
                $.each(module_fields[selected_module], function (key, value) {
                    var selected = '';
                    if (queValue.sync_fields == key) {
                        selected = 'selected'
                    }
                    if (value != "") {
                        data += '<option value="' + key + '" ' + selected + '>' + value + '</option>';
                    }
                });
                data += "</select><input type='hidden' id='sync_field_hidden" + page_no + que_no + "' value='" + queValue.sync_fields + "'></li>";
            } else {
                data += "<ul><li class='s-title'><label class='left-label'>Disable Piping</label><input type='checkbox' onclick='desablepiping_checkbox(this," + page_no + "," + que_no + ")' name='desable_piping_que[" + page_no + "][" + que_no + "]'/></label></li>";
                data += "<li class='s-title'><label class='left-label'>Sync Field</label><select class='fields_type' onchange='getfieldstype(this," + page_no + "," + que_no + ")' id='sync_field" + page_no + que_no + "' name='sync_fields[" + page_no + "][" + que_no + "]'>";
                var selected_module = $('#sync_module').val();
                data += '<option value="0">Select Fields</option>';
                $.each(module_fields[selected_module], function (key, value) {
                    var selected = '';
                    if (queValue.sync_fields == key) {
                        selected = 'selected'
                    }
                    if (value != "") {
                        data += '<option value="' + key + '" ' + selected + '>' + value + '</option>';
                    }
                });
                data += "</select><input type='hidden' id='sync_field_hidden" + page_no + que_no + "' value='" + queValue.sync_fields + "'></li>";

            }
            data += '</ul></div>';
        }
        data += "</div>";
        data += "</div>";
    }

    if ("MultiSelectList" === queValue.que_type) {
        que_icon = "<i class='fa fa-list-ul' aria-hidden='true'></i>";
        multi_choice_que_type = "MultiSelectList";
        multi_choice_que = true;
    } else if ("Checkbox" === queValue.que_type) {
        que_icon = "<i class='fa fa-check-square-o' aria-hidden='true'></i>";
        multi_choice_que_type = "Checkbox";
        multi_choice_que = true;
    } else if ("DrodownList" === queValue.que_type) {
        que_icon = "<i class='fa fa-chevron-down' aria-hidden='true'></i>";
        multi_choice_que_type = "DrodownList";
        multi_choice_que = true;
    } else if ("RadioButton" === queValue.que_type) {
        que_icon = "<i class='fa fa-dot-circle-o' aria-hidden='true'></i>";
        multi_choice_que_type = "RadioButton";
        multi_choice_que = true;
    } else if (queValue.que_type === "Video") {
        que_icon = "<i class='fa fa-video-camera' aria-hidden='true'></i>";
    } else if (queValue.que_type === "Matrix") {
        que_icon = '<i class="fa fa-th" aria-hidden="true"></i>';
    } else if (queValue.que_type === "Scale") {
        que_icon = '<i class="fa fa-arrows-h" aria-hidden="true"></i>';
    } else if (queValue.que_type === "Image") {
        que_icon = '<i class="fa fa-picture-o" aria-hidden="true"></i>';
    } else if (queValue.que_type === "Date") {
        que_icon = '<i class="fa fa-calendar" aria-hidden ="true"></i>';
    }

    if (multi_choice_que == true) {
        data += "<div class='survey-body' id='que_" + page_no + que_no + "'>";
        data += "<div class='s-row question' id='questionline" + page_no + "'>";
        data += "<div class='right-close-add-div' align='right'><a style='cursor:pointer;' onclick='addSection(this," + page_no + "," + que_no + ")'><i class='fa fa-pause fa-5 fa-rotate-90' title='Add Section' aria-hidden='true' style='font-size:14px;margin: 5px 3px;'></i></a><a id='que_remove_" + que_no + "' style='cursor:pointer;' onclick='removeQuestions(this," + page_no + ")'><i class='fa fa-remove' id='remove_que_" + que_no + "' title='Remove Question' style='font-size:14px;margin: 5px 3px;'></i></a></div>";
        data += "<div id='tabs" + page_no + que_no + "'>";
        data += "<div class='tab' style='margin-bottom: 20px;'>"
        data += '   <a class="advance_tab general_tab active" id="gen" onclick="change_tab(this,' + page_no + ',' + que_no + ') "><p class="gen">General</p></a>';
        data += '   <a class="advance_tab" id="adv" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="adv">Advanced Option</p></a>';
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += '   <a class="advance_tab" id="lgc" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="lgc">Logic</p></a>';
            data += '   <a class="advance_tab pipe_tab" id="pipe" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="pipe">Piping</p></a>';
        }
        data += '</div>';
        data += "<div id='general" + page_no + que_no + "' class='tabcontent general_tab_content' style='display:block;'>";
        data += "<ul class='question-section' id='question_table" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id[" + page_no + "][" + que_no + "]'  id='que_id" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id_del[" + page_no + "][" + que_no + "]' value='' id='que_id_del" + page_no + que_no + "'>";
        data += "<li class='s-type'><label class='left-label'>Question Type  </label><span style='cursor:pointer;' class='change_request' id='change_" + page_no + que_no + "' ondblclick='drop_down(this," + page_no + "," + que_no + ")'>" + que_icon + multi_choice_type_value[multi_choice_que_type] + "&nbsp;&nbsp;<a class='fa fa-pencil' aria-hidden='true'></a></span><span><input type='hidden' name='que_type[" + page_no + "][" + que_no + "]' id='que_type" + page_no + "" + que_no + "' value='" + multi_choice_que_type + "' /></span>";
        if (queValue.enable_scoring == 1) {
            data += "<span style='margin-left: 300px;'>Enable Scoring <input type='checkbox' style='cursor:pointer;margin-left: 65px;' class='enable_score" + page_no + que_no + "' onclick='score_value_display(this," + page_no + "," + que_no + ");' name='enable_scoring_dropdownlist[" + page_no + "][" + que_no + "]' checked></span></li>";
        } else {
            data += "<span style='margin-left: 300px;'>Enable Scoring <input type='checkbox' style='cursor:pointer;margin-left: 65px;' class='enable_score" + page_no + que_no + "' onclick='score_value_display(this," + page_no + "," + que_no + ");' name='enable_scoring_dropdownlist[" + page_no + "][" + que_no + "]'></span></li>";
        }
        data += "<li class='s-title'> <label class='left-label'>Question<span class='required'>*</span></label><span><input class='input-text que_title' id='que_title" + page_no + que_no + "' type='text' name='que_title[" + page_no + "][" + que_no + "]' value='" + queValue.que_title + "' /></span></li>";
        data += "<li class='s-option'>";
        data += "<ul class='answer-section' id='answer_div" + page_no + "" + que_no + "' class='answer_div'>";
        var length = '';
        if (queValue.is_enable == "1") {
            length = Object.keys(queValue.answers).length - 1;
        } else {
            length = Object.keys(queValue.answers).length;
        }
        var custom_len = 1;
        var showPlusBtn = 'display:none';
        var showremBtn = 'display:none';
        if (queValue.enable_scoring == 1) {
            $.each(queValue.answers, function (answerIndex, answerValue) {

                if (custom_len == length) {
                    showPlusBtn = 'display:inline;';
                    showremBtn = 'display:inline;';
                } else {
                    showPlusBtn = 'display:none;';
                    showremBtn = 'display:inline;';
                }
                var option = "";
                if (custom_len == 1) {
                    option = "Option";
                }

                if (answerValue.option_type != 'other') {
                    if (length > 2) {
                        data += "<li id='ans_op" + page_no + "" + que_no + "" + (answerIndex - 1) + "' class='ans_op_class" + page_no + "" + que_no + "'><label class='left-label'>" + option + "</label><span><input type='text' class='answer" + page_no + "" + que_no + "' name=answer[" + page_no + "][" + que_no + "][" + (answerIndex - 1) + "] value='" + answerValue.name + "' /></span>&nbsp;<input type='number' name='score_dropdownlist[" + page_no + "][" + que_no + "][" + (answerIndex - 1) + "]' value='" + answerValue.score_value + "' onblur='checkValue(this)' class='score_weight" + page_no + que_no + "' style='margin-left: 6px; text-align: center;max-width: 7%;height: 22px;display:inline;'><input type='button' value='&#10005;' name='btnRemoveOption' style='" + showremBtn + " margin-left:6px;height:25px;width:25px;' onclick='removeOption(" + page_no + "," + que_no + ",this)' /><input name='btnOptionAdd' type='button' value='&#43;' onclick='editNewOption(this," + page_no + "," + que_no + ")' style='margin-left:10px;margin:10px;height:25px;width:25px;" + showPlusBtn + "'/>";
                        data += "<input type='hidden' name='ans_id[" + page_no + "][" + que_no + "][" + (answerIndex - 1) + "]' id ='ans_id' style='width: 37.5%' value='" + answerValue['id'] + "'/>";
                        data += "<input type='hidden' name='ans_id_del[" + page_no + "][" + que_no + "][" + (answerIndex - 1) + "]' id='ans_id_del' style='width: 37.5%' value='" + answerValue['id'] + "' />";
                    }
                    else
                    {
                        data += "<li id='ans_op" + page_no + "" + que_no + "" + (answerIndex - 1) + "' class='ans_op_class" + page_no + "" + que_no + "'><label class='left-label'>" + option + "</label><span><input type='text' class='answer" + page_no + "" + que_no + "' name=answer[" + page_no + "][" + que_no + "][" + (answerIndex - 1) + "] value='" + answerValue.name + "' /></span>&nbsp;<input type='number' name='score_dropdownlist[" + page_no + "][" + que_no + "][" + (answerIndex - 1) + "]' value='" + answerValue.score_value + "' onblur='checkValue(this)' class='score_weight" + page_no + que_no + "' style='margin-left: 6px; text-align: center;max-width: 7%;height: 22px;display:inline;'><input type='button' value='&#10005;' name='btnRemoveOption' style='display:none; margin-left:6px;height:25px;width:25px;' onclick='removeOption(" + page_no + "," + que_no + ",this)' />";
                        data += "<input type='hidden' name='ans_id[" + page_no + "][" + que_no + "][" + (answerIndex - 1) + "]'id='ans_id' style='width: 37.5%' value='" + answerValue['id'] + "'/>";
                        data += "<input type='hidden' name='ans_id_del[" + page_no + "][" + que_no + "][" + (answerIndex - 1) + "]' id='ans_id_del' style='width: 37.5%' value='" + answerValue['id'] + "' /><input name='btnOptionAdd' type='button' value='&#43;' onclick='editNewOption(this," + page_no + "," + que_no + ")' style='margin:10px;height:25px;width:25px; " + showPlusBtn + "'/>";
                    }
                }
                custom_len += 1;
            });
        } else {
            $.each(queValue.answers, function (answerIndex, answerValue) {

                if (custom_len == length) {
                    showPlusBtn = 'display:inline;';
                    showremBtn = 'display:inline;';
                } else {
                    showPlusBtn = 'display:none;';
                    showremBtn = 'display:inline;';
                }
                var option = "";
                if (custom_len == 1) {
                    option = "Option";
                }
                if (answerValue.option_type != 'other') {
                    if (length > 2) {
                        data += "<li id='ans_op" + page_no + "" + que_no + "" + (answerIndex - 1) + "' class='ans_op_class" + page_no + "" + que_no + "'><label class='left-label'>" + option + "</label><span><input type='text' class='answer" + page_no + "" + que_no + "' name=answer[" + page_no + "][" + que_no + "][" + (answerIndex - 1) + "] value='" + answerValue.name + "' /></span>&nbsp;<input type='number' name='score_dropdownlist[" + page_no + "][" + que_no + "][" + (answerIndex - 1) + "]' value='" + answerIndex + "' onblur='checkValue(this)' class='score_weight" + page_no + que_no + "' style='margin-left: 6px; text-align: center;max-width: 7%;height: 22px;display:none;'><input type='button' value='&#10005;' name='btnRemoveOption' style='" + showremBtn + " margin-left:6px;height:25px;width:25px;' onclick='removeOption(" + page_no + "," + que_no + ",this)' /><input name='btnOptionAdd' type='button' value='&#43;' onclick='editNewOption(this," + page_no + "," + que_no + ")' style='margin-left:10px;margin:10px;height:25px;width:25px;" + showPlusBtn + "'/>";
                        data += "<input type='hidden' name='ans_id[" + page_no + "][" + que_no + "][" + (answerIndex - 1) + "]' id ='ans_id' style='width: 37.5%' value='" + answerValue['id'] + "'/>";
                        data += "<input type='hidden' name='ans_id_del[" + page_no + "][" + que_no + "][" + (answerIndex - 1) + "]' id='ans_id_del' style='width: 37.5%' value='" + answerValue['id'] + "' />";
                    }
                    else
                    {
                        data += "<li id='ans_op" + page_no + "" + que_no + "" + (answerIndex - 1) + "' class='ans_op_class" + page_no + "" + que_no + "'><label class='left-label'>" + option + "</label><span><input type='text' class='answer" + page_no + "" + que_no + "' name=answer[" + page_no + "][" + que_no + "][" + (answerIndex - 1) + "] value='" + answerValue.name + "' /></span>&nbsp;<input type='number' name='score_dropdownlist[" + page_no + "][" + que_no + "][" + (answerIndex - 1) + "]' value='" + answerIndex + "' onblur='checkValue(this)' class='score_weight" + page_no + que_no + "' style='margin-left: 6px; text-align: center;max-width: 7%;height: 22px;display:none;'><input type='button' value='&#10005;' name='btnRemoveOption' style='display:none; margin-left:6px;height:25px;width:25px;' onclick='removeOption(" + page_no + "," + que_no + ",this)' />";
                        data += "<input type='hidden' name='ans_id[" + page_no + "][" + que_no + "][" + (answerIndex - 1) + "]'id='ans_id' style='width: 37.5%' value='" + answerValue['id'] + "'/>";
                        data += "<input type='hidden' name='ans_id_del[" + page_no + "][" + que_no + "][" + (answerIndex - 1) + "]' id='ans_id_del' style='width: 37.5%' value='" + answerValue['id'] + "' /><input name='btnOptionAdd' type='button' value='&#43;' onclick='editNewOption(this," + page_no + "," + que_no + ")' style='margin:10px;height:25px;width:25px; " + showPlusBtn + "'/>";
                    }
                }
                custom_len += 1;
            });
        }
        data += "</ul></ul>";

        if (queValue.is_enable == "1") {
            if (queValue.enable_scoring == 1) {
                $.each(queValue.answers, function (answerIndex, answerValue) {
                    if (answerValue.option_type == 'other') {
                        data += "<div class='warning_outer' style='padding: 0px 5px;'><div class='other_option'><input type='checkbox' onclick='click_other_option(this," + page_no + "," + que_no + ")' name='enable_option[" + page_no + "][" + que_no + "]' checked><label>&nbsp;&nbsp;Add other option textbox</label></div></div>";
                        data += "<ul class='other_option_div'><li><label class='left-label'>Other Option Label </label><input type='text' value='" + answerValue.name + "' onblur='other_option_value(this," + page_no + "," + que_no + ")'  name='answer_other[" + page_no + "][" + que_no + "]'/><span>&nbsp;<input type='number' name='score_dropdownlist_other[" + page_no + "][" + que_no + "]' value='" + answerValue.score_value + "' onblur='checkValue(this)' class='score_weight" + page_no + que_no + "' style='margin-left: 6px; text-align: center;max-width: 7%;height: 22px;'></span></li></ul>";
                        data += "<input type='hidden' name='ans_id_other[" + page_no + "][" + que_no + "]' id ='ans_id_other' style='width: 37.5%' value='" + answerValue.id + "'/>";
                    }
                });
            } else {
                $.each(queValue.answers, function (answerIndex, answerValue) {
                    if (answerValue.option_type == 'other') {
                        data += "<div class='warning_outer' style='padding: 0px 5px;'><div class='other_option'><input type='checkbox' onclick='click_other_option(this," + page_no + "," + que_no + ")' name='enable_option[" + page_no + "][" + que_no + "]' checked><label>&nbsp;&nbsp;Add other option textbox</label></div></div>";
                        data += "<ul class='other_option_div'><li><label class='left-label'>Other Option Label </label><input type='text' onblur='other_option_value(this," + page_no + "," + que_no + ")' value='" + answerValue.name + "'  name='answer_other[" + page_no + "][" + que_no + "]'/><span style='display:none;'>&nbsp;<input type='number' name='score_dropdownlist_other[" + page_no + "][" + que_no + "]' value='0' onblur='checkValue(this)' class='score_weight" + page_no + que_no + "' style='margin-left: 6px; text-align: center;max-width: 7%;height: 22px;'></span></li></ul>";
                        data += "<input type='hidden' name='ans_id_other[" + page_no + "][" + que_no + "]' id ='ans_id_other'  style='width: 37.5%' value='" + answerValue.id + "'/>";
                    }
                });
            }
        } else {
            data += "<div class='warning_outer' style='padding: 0px 5px;'><div class='other_option'><input type='checkbox' onclick='click_other_option(this," + page_no + "," + que_no + ")' name='enable_option[" + page_no + "][" + que_no + "]'><label>&nbsp;&nbsp;Add other option textbox</label></div></div>";
            data += "<ul class='other_option_div' style='display:none;'><li><label class='left-label'>Other Option Label </label><input type='text' onblur='other_option_value(this," + page_no + "," + que_no + ")' value='Other'  name='answer_other[" + page_no + "][" + que_no + "]'/><span style='display:none;'>&nbsp;<input type='number' name='score_dropdownlist_other[" + page_no + "][" + que_no + "]' value='0' onblur='checkValue(this)' class='score_weight" + page_no + que_no + "' style='margin-left: 6px; text-align: center;max-width: 7%;height: 22px;'></span></li></ul>";
        }
        data += "</div>";
        if (queValue.que_type == 'MultiSelectList' || queValue.que_type == 'DrodownList') {
            data += "<div id='advanced" + page_no + que_no + "' class='tabcontent' style='display:none'>";
            data += "<ul><li class='s-title'><label class='left-label'>Help Tips </label><span><input  class='input-text question_help_comment' type='text' name='question_help_comment[" + page_no + "][" + que_no + "]' value='" + queValue.question_help_comment + "' /></span></li>";
            if (queValue.is_required === "1") {
                data += "<li class='s-required'><label class='left-label'>Is required  </label><span><input type='checkbox' name='is_required[" + page_no + "][" + que_no + "]' id='is_required' checked/></span></li>";
            }
            else {
                data += "<li class='s-required'><label class='left-label'>Is required </label><span><input type='checkbox' name='is_required[" + page_no + "][" + que_no + "]' id='is_required' /></span></li>";
            }
            if (queValue.is_sort === "1") {
                data += '<li class="sortable"><label class="left-label">Sortable</label><input type="checkbox" name="is_sort_check-box[' + page_no + '][' + que_no + ']" checked>';
            } else {
                data += '<li class="sortable"><label class="left-label">Sortable</label><input type="checkbox" name="is_sort_check-box[' + page_no + '][' + que_no + ']">';
            }
            if (queValue.que_type == 'MultiSelectList') {
                data += '<li class="limit_dropdown"><label class="left-label">Selection Limit</label>';
                if (queValue.selection_limit) {
                    data += '<select style="width:auto;" id="limit_dropdown' + page_no + que_no + '" name="limit_dropdown[' + page_no + '][' + que_no + ']"><option value="0">Select</option>';
                    $.each(queValue.answers, function (ansindex, answer) {
                        if (queValue.selection_limit == ansindex) {
                            data += '<option value="' + queValue.selection_limit + '" selected>' + queValue.selection_limit + '</option>';
                        } else {
                            data += '<option value="' + ansindex + '">' + ansindex + '</option>';
                        }
                    });
                    data += '</select>';
                } else {
                    data += '<select style="width:auto;" id="limit_dropdown' + page_no + que_no + '" name="limit_dropdown[' + page_no + '][' + que_no + ']"><option value="0">Select</option></select>';
                }
            }
            data += "</div>";
        }
        if (queValue.que_type == 'RadioButton' || queValue.que_type == 'Checkbox') {
            data += "<div id='advanced" + page_no + que_no + "' class='tabcontent' style='display:none'>";
            data += "<ul><li class='s-title'><label class='left-label'>Help Tips </label><span><input  class='input-text question_help_comment' type='text' name='question_help_comment[" + page_no + "][" + que_no + "]' value='" + queValue.question_help_comment + "' /></span></li>";
            if (queValue.is_required === "1") {
                data += "<li class='s-required'><label class='left-label'>Is required  </label><span><input type='checkbox' name='is_required[" + page_no + "][" + que_no + "]' id='is_required' checked/></span></li>";
            }
            else {
                data += "<li class='s-required'><label class='left-label'>Is required </label><span><input type='checkbox' name='is_required[" + page_no + "][" + que_no + "]' id='is_required' /></span></li>";
            }
            if (queValue.is_sort === "1") {
                data += '<li class="sortable"><label class="left-label">Sortable</label><input type="checkbox" name="is_sort_check-box[' + page_no + '][' + que_no + ']" checked>';
            } else {
                data += '<li class="sortable"><label class="left-label">Sortable</label><input type="checkbox" name="is_sort_check-box[' + page_no + '][' + que_no + ']">';
            }
            if (queValue.advance_type == "Horizontal") {
                data += '<li class="display"><label class="left-label">Display</label><input type="radio" value="Vertical" name="display_radio_button[' + page_no + '][' + que_no + ']">&nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;Vertical&nbsp;&nbsp;&nbsp;<input type="radio" value="Horizontal" name="display_radio_button[' + page_no + '][' + que_no + ']" checked>&nbsp;&nbsp;<i class="fa fa-ellipsis-h"></i>&nbsp;&nbsp;Horizontal</li>';
            } else if (queValue.advance_type == "Vertical") {
                data += '<li class="display"><label class="left-label">Display</label><input type="radio" value="Vertical" name="display_radio_button[' + page_no + '][' + que_no + ']" checked>&nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;Vertical&nbsp;&nbsp;&nbsp;<input type="radio" value="Horizontal" name="display_radio_button[' + page_no + '][' + que_no + ']">&nbsp;&nbsp;<i class="fa fa-ellipsis-h"></i>&nbsp;&nbsp;Horizontal</li>';
            } else {
                data += '<li class="display"><label class="left-label">Display</label><input type="radio" value="Vertical" name="display_radio_button[' + page_no + '][' + que_no + ']" checked>&nbsp;&nbsp;<i class="fa fa-ellipsis-v"></i>&nbsp;&nbsp;Vertical&nbsp;&nbsp;&nbsp;<input type="radio" value="Horizontal" name="display_radio_button[' + page_no + '][' + que_no + ']">&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-ellipsis-h"></i>&nbsp;&nbsp;&nbsp;&nbsp;Horizontal</li>';
            }

            if (queValue.que_type == 'Checkbox') {
                data += '<li class="limit_dropdown"><label class="left-label">Selection Limit</label>';
                if (queValue.selection_limit) {
                    data += '<select style="width:auto;" id="limit_dropdown' + page_no + que_no + '" name="limit_dropdown[' + page_no + '][' + que_no + ']"><option value="0">Select</option>';
                    $.each(queValue.answers, function (ansindex, answer) {
                        if (queValue.selection_limit == ansindex) {
                            data += '<option value="' + queValue.selection_limit + '" selected>' + queValue.selection_limit + '</option>';
                        } else {
                            data += '<option value="' + ansindex + '">' + ansindex + '</option>';
                        }
                    });
                    data += '</select>';
                } else {
                    data += '<select style="width:auto;" id="limit_dropdown' + page_no + que_no + '" name="limit_dropdown[' + page_no + '][' + que_no + ']"><option value="0">Select</option></select>';
                }
            }
            data += "</ul></div>";
        }
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            if ($('input[name=record]').val() != '') {
                data += "<div id='logic_skipp" + page_no + que_no + "' class='tabcontent logic-data-table' style='display:none'>";
                data += "<div class='warning_outer' style='padding: 0px 5px; display:none;'><div class='warning'><b>CIRCULAR LOGIC &nbsp;:&nbsp;</b>This logic will cause the page to redirect to previous page, which will cause the respondent to go in an infinite loop if they keep picking that choice.</div></div><table cellspacing='20' style='width: auto;' id='logic_table" + page_no + que_no + "'>";
                data += "<tr><th class='logic-table-first-column' style='color:#000;'>If answer is ...<i class='fa fa-question-circle' style='color:#4e8ccf;' aria-hidden='true'></i></th><th style='color:#000;'>Then skip to ...<i class='fa fa-question-circle' style='color:#4e8ccf;' aria-hidden='true'> </th><th>&nbsp;</th><th style='text-align:center;'><a style='cursor:pointer;color:#000;' onclick='clear_all_element(this," + page_no + "," + que_no + ")'>Clear All</a></th></tr>";
                $.each(queValue.answers, function (ansindex, answer) {
                    var other = '';
                    if (answer.option_type == "other") {
                        other = 'Other_Option';
                    }
                    data += '<input type="hidden" class="action_clear_all' + page_no + que_no + ' ansid_' + answer.id + '" id="action' + page_no + que_no + (ansindex - 1) + '" value="' + answer.skip_action + '">';
                    data += '<input type="hidden" class="target_clear_all' + page_no + que_no + ' ansid_' + answer.id + '" id="target' + page_no + que_no + (ansindex - 1) + '" value="' + answer.skip_target + '">';
                    data += "<tr class='opid_" + answer.id + " " + other + "' id='con_value" + page_no + que_no + (ansindex - 1) + "'><td class='logic-table-first-column'><label style='color:#000;'>" + answer.name + "</label></td><td class='condition'><span><select style='width: auto;cursor:pointer;' class='clear_all" + page_no + que_no + "' id='skipp_logic_function" + page_no + que_no + (ansindex - 1) + "' onchange='skipp_logic(this," + page_no + "," + que_no + "," + (ansindex - 1) + ")' name='option_value[" + page_no + "][" + que_no + "][" + (ansindex - 1) + "]'><option value='no_logic'>No Logic</option>";
                    if (answer.skip_action == "redirect_page") {
                        data += "<option value='redirect_page' selected>Redirect To Page</option>";
                    } else {
                        data += "<option value='redirect_page'>Redirect To Page</option>";
                    }
                    if (answer.skip_action == "end_page") {
                        data += "<option value='end_page' selected>End Of Survey</option>";
                    } else {
                        data += "<option value='end_page'>End Of Survey</option>";
                    }
                    if (answer.skip_action == "redirect_url") {
                        data += "<option value='redirect_url' selected>Redirect to Url</option>";
                    } else {
                        data += "<option value='redirect_url'>Redirect to Url</option>";
                    }
                    if (answer.skip_action == "show_hide_question") {
                        data += "<option value='show_hide_question' selected>Show/Hide Questions</option>";
                    } else {
                        data += "<option value='show_hide_question'>Show/Hide Questions</option>";
                    }
                    data += "</select></td><td>";
                    if (answer.skip_action == "redirect_page") {
                        data += '<select style="width: auto;" id="page_skipp_logic' + page_no + que_no + (ansindex - 1) + '" class="logic-page-select" name="page_skipp_logic[' + page_no + '][' + que_no + '][' + (ansindex - 1) + ']"><option value="' + answer.skip_target + '" selected></option><select>';
                    } else if (answer.skip_action == "redirect_url") {
                        data += '<input class="input-text logic-url-box" type="text" name="page_skipp_logic[' + page_no + '][' + que_no + '][' + (ansindex - 1) + ']" value="' + answer.skip_target + '">';
                    } else if (answer.skip_action == "show_hide_question") {
                        data += '<select style="width: auto;" class="show_hide_dd" id="show_hide_dd' + page_no + que_no + (ansindex - 1) + '" name="page_skipp_logic[' + page_no + '][' + que_no + '][' + (ansindex - 1) + '][]" multiple>';
                        var array = (answer.skip_target).split(',');
                        var length_of_array = ((answer.skip_target).split(',')).length;
                        while (length_of_array > 0) {
                            data += "<option value='" + array[length_of_array - 1] + "' selected></option>";
                            length_of_array -= 1;
                        }
                    }
                    data += "</td><td style='color:#000;text-align:center;'><a id='clear_" + page_no + que_no + (ansindex - 1) + "' onclick='clear_element(this," + page_no + "," + que_no + "," + (ansindex - 1) + ")' class='fa fa-remove' style='cursor:pointer; font-size:14px;margin: 5px 3px;'></a></td></tr>";
                    //data += "<tr class='opid_" + answer.id + "' id='con_value" + page_no + que_no + indx + "'><td class='logic-table-first-column'><label style='color:#000'>" + $(this).parent().find('input[type=text]').val() + "</label></td><td class='condition'><span><select style='width: auto;cursor:pointer;' class='clear_all" + page_no + que_no + "' id='skipp_logic_function" + page_no + que_no + indx + "' onchange='skipp_logic(this," + page_no + "," + que_no + "," + indx + ")' name='option_value[" + page_no + "][" + que_no + "][" + indx + "]'><option value='no_logic'>No Logic</option><option value='redirect_page'>Redirect To Page</option><option value='end_page'>End Of Survey</option><option value='redirect_url'>Redirect to Url</option><option value='show_hide_question'>Show/Hide Questions</option></select></td><td></td><td style='color:#000;'><a id='clear_" + page_no + que_no + indx + "' onclick='clear_element(this," + page_no + "," + que_no + "," + indx + ")' style='cursor:pointer;'></a></td></tr>";
                });
                data += "</table></div>";
            }
        }
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += "<div id='pipe" + page_no + que_no + "' class='pipecontent' style='display:none'>";
            if (queValue.desable_piping != "0" && typeof queValue.desable_piping != "undefined") {
                data += "<ul><li class='s-title'><label class='left-label'>Disable Piping</label><input type='checkbox' onclick='desablepiping_checkbox(this," + page_no + "," + que_no + ")' name='desable_piping_que[" + page_no + "][" + que_no + "]' checked/></label></li>";
                data += "<li class='s-title' style='display:none;'><label class='left-label'>Sync Field</label><select class='fields_type' onchange='getfieldstype(this," + page_no + "," + que_no + ")' id='sync_field" + page_no + que_no + "' name='sync_fields[" + page_no + "][" + que_no + "]'>";
                var selected_module = $('#sync_module').val();
                data += '<option value="0">Select Fields</option>';
                $.each(module_fields[selected_module], function (key, value) {
                    var selected = '';
                    if (queValue.sync_fields == key) {
                        selected = 'selected'
                    }
                    if (value != "") {
                        data += '<option value="' + key + '" ' + selected + '>' + value + '</option>';
                    }
                });
                data += "</select><input type='hidden' id='sync_field_hidden" + page_no + que_no + "' value='" + queValue.sync_fields + "'></li>";
            } else {
                data += "<ul><li class='s-title'><label class='left-label'>Disable Piping</label><input type='checkbox' onclick='desablepiping_checkbox(this," + page_no + "," + que_no + ")' name='desable_piping_que[" + page_no + "][" + que_no + "]'/></label></li>";
                data += "<li class='s-title'><label class='left-label'>Sync Field</label><select class='fields_type' onchange='getfieldstype(this," + page_no + "," + que_no + ")' id='sync_field" + page_no + que_no + "' name='sync_fields[" + page_no + "][" + que_no + "]'>";
                var selected_module = $('#sync_module').val();
                data += '<option value="0">Select Fields</option>';
                $.each(module_fields[selected_module], function (key, value) {
                    var selected = '';
                    if (queValue.sync_fields == key) {
                        selected = 'selected'
                    }
                    if (value != "") {
                        data += '<option value="' + key + '" ' + selected + '>' + value + '</option>';
                    }
                });
                data += "</select><input type='hidden' id='sync_field_hidden" + page_no + que_no + "' value='" + queValue.sync_fields + "'></li>";

            }
            data += '</ul></div>';
        }
        data += "</div>";
        data += "<input type='hidden' id='last_ans_no_" + page_no + "" + que_no + "' value='2' name='last_ans_no" + page_no + "" + que_no + "'>";
        data += "</div>";
    }
//image question edit.

    if (queValue.que_type == "Image") {
        data += "<div class='survey-body' id='que_" + page_no + que_no + "'>";
        data += "<div class='s-row question' id='questionline" + page_no + "'>";
        data += "<div class='right-close-add-div' align='right'><a style='cursor:pointer;' onclick='addSection(this," + page_no + "," + que_no + ")'><i class='fa fa-pause fa-5 fa-rotate-90' title='Add Section' aria-hidden='true' style='font-size:14px;margin: 5px 3px;'></i></a><a id='que_remove_" + que_no + "' style='cursor:pointer;' onclick='removeQuestions(this," + page_no + ")'><i class='fa fa-remove' id='remove_que_" + que_no + "' title='Remove Question' style='font-size:14px;margin: 5px 3px;'></i></a></div>";
        data += "<div id='tabs" + page_no + que_no + "'>";
        data += "<div class='tab' style='margin-bottom: 20px;'>";
        data += ' <a class="advance_tab general_tab active" id="gen" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="gen">General</p></a>';
        data += ' <a class="advance_tab" id="adv" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="adv">Advanced Option</p></a>';
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += '   <a class="advance_tab pipe_tab" id="pipe" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="pipe">Piping</p></a>';
        }
        data += '</div>';
        data += "<div id='general" + page_no + que_no + "' class='tabcontent general_tab_content' style='display:block;'><ul class='question-section' id='question_table" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id[" + page_no + "][" + que_no + "]' value=''  id='que_id" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id_del[" + page_no + "][" + que_no + "]' value=''  id='que_id_del" + page_no + que_no + "'>";
        data += "<li class='s-type'><label class='left-label'>Question Type  </label><span style='cursor:pointer;' class='change_request' id='change_" + page_no + que_no + "' ondblclick='drop_down(this," + page_no + "," + que_no + ")'>" + que_icon + " Image&nbsp;&nbsp;<a class='fa fa-pencil' aria-hidden='true'></a></span><span><input type='hidden' name='que_type[" + page_no + "][" + que_no + "]' id='que_type" + page_no + "" + que_no + "' value='Image' /></span>  </li>";
        data += "<li class='s-title'><label class='left-label'>Image<span class='required'>*</span></label>";
        if (queValue.que_title == "upload") {
            data += "<span><input class='upload' type='radio' name='que_title[" + page_no + "][" + que_no + "]' onclick='change_radio(this," + page_no + "," + que_no + ")' value='upload' checked/>Upload Image&nbsp;</span>";
        } else {
            data += "<span><input class='upload' type='radio' name='que_title[" + page_no + "][" + que_no + "]' onclick='change_radio(this," + page_no + "," + que_no + ")' value='upload'/>Upload Image&nbsp;</span>";
        }
        if (queValue.que_title == "img_url") {
            data += "<span><input class='url' type='radio' onclick='change_radio(this," + page_no + "," + que_no + ")' name='que_title[" + page_no + "][" + que_no + "]' value='img_url' checked/>Image Url</span></li><br>";
        } else {
            data += "<span><input class='url' type='radio' onclick='change_radio(this," + page_no + "," + que_no + ")' name='que_title[" + page_no + "][" + que_no + "]' value='img_url'/>Image Url</span></li><br>";
        }
        //for already uploaded image
        if (queValue.que_title == "upload") {
            if (queValue.que_title == '') {
                data += "<label class='left-label'>&nbsp;</label></li><span class='upload" + page_no + que_no + " up'><input type='file' id='file" + page_no + que_no + "' onchange=validate_Imagesize(this.files," + page_no + "," + que_no + ") class='file_upload' name='img_url[" + page_no + "][" + que_no + "]'></span><span class='url" + page_no + que_no + " ur' style='display:none;'><input class='input-text file_url' placeholder='Image Url' type='text' name='img_url[" + page_no + "][" + que_no + "]'></span>";
            } else {
                var adv_type = queValue.advance_type;
                data += '<label class="left-label">&nbsp;</label></li><span class="upload' + page_no + que_no + ' up" style="display:none"><input type="file" id="file' + page_no + que_no + '" onchange=validate_Imagesize(this.files,' + page_no + ',' + que_no + ') class="file_upload" name="img_url[' + page_no + '][' + que_no + ']"></span><span class="img_src' + page_no + que_no + ' src"><img src="custom/include/Image_question/' + queValue.advance_type + '" height="200" width="300"><input type="hidden" name=img_src[' + page_no + '][' + que_no + '] value="' + queValue.advance_type + '"><input type="button" id="remove_img' + page_no + que_no + '" onclick="change_image(this,' + page_no + ',' + que_no + ',\'' + adv_type + '\')" value="Remove"/></span><span class="url' + page_no + que_no + ' ur" style="display:none;"><input class="input-text file_url" placeholder="Image Url" type="text" name="img_url[' + page_no + '][' + que_no + ']"></span>';
            }
        } else if (queValue.que_title == "img_url") {
            data += "<label class='left-label'>&nbsp;</label></li><span class='upload" + page_no + que_no + " up' style='display:none;'><input type='file' id='file" + page_no + que_no + "' onchange=validate_Imagesize(this.files," + page_no + "," + que_no + ") class='file_upload' name='img_url[" + page_no + "][" + que_no + "]'></span><span class='url" + page_no + que_no + " ur'><input class='input-text file_url' placeholder='Image Url'    type='text' name='img_url[" + page_no + "][" + que_no + "]' value='" + queValue.advance_type + "'></span>";
        }
        data += "</div>";
        data += "<div id='advanced" + page_no + que_no + "' class='tabcontent' style='display:none'>";
        data += "<ul><li class='s-desc'><label class='left-label'>Title</label><span><input class='input-text title' placeholder='Enter Image Title' type='text' name='question_help_comment[" + page_no + "][" + que_no + "]' value='" + queValue.question_help_comment + "'></span></li>";
        data += "</ul></div>";
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += "<div id='pipe" + page_no + que_no + "' class='pipecontent' style='display:none'>";
            if (queValue.desable_piping != "0" && typeof queValue.desable_piping != "undefined") {
                data += "<ul><li class='s-title'><label class='left-label'>Disable Piping</label><input type='checkbox' onclick='desablepiping_checkbox(this," + page_no + "," + que_no + ")' name='desable_piping_que[" + page_no + "][" + que_no + "]' checked/></label></li>";
                data += "<li class='s-title' style='display:none;'><label class='left-label'>Sync Field</label><select class='fields_type' onchange='getfieldstype(this," + page_no + "," + que_no + ")' id='sync_field" + page_no + que_no + "' name='sync_fields[" + page_no + "][" + que_no + "]'>";
                var selected_module = $('#sync_module').val();
                data += '<option value="0">Select Fields</option>';
                $.each(module_fields[selected_module], function (key, value) {
                    var selected = '';
                    if (queValue.sync_fields == key) {
                        selected = 'selected'
                    }
                    if (value != "") {
                        data += '<option value="' + key + '" ' + selected + '>' + value + '</option>';
                    }
                });
                data += "</select><input type='hidden' id='sync_field_hidden" + page_no + que_no + "' value='" + queValue.sync_fields + "'></li>";
            } else {
                data += "<ul><li class='s-title'><label class='left-label'>Disable Piping</label><input type='checkbox' onclick='desablepiping_checkbox(this," + page_no + "," + que_no + ")' name='desable_piping_que[" + page_no + "][" + que_no + "]'/></label></li>";
                data += "<li class='s-title'><label class='left-label'>Sync Field</label><select class='fields_type' onchange='getfieldstype(this," + page_no + "," + que_no + ")' id='sync_field" + page_no + que_no + "' name='sync_fields[" + page_no + "][" + que_no + "]'>";
                var selected_module = $('#sync_module').val();
                data += '<option value="0">Select Fields</option>';
                $.each(module_fields[selected_module], function (key, value) {
                    var selected = '';
                    if (queValue.sync_fields == key) {
                        selected = 'selected'
                    }
                    if (value != "") {
                        data += '<option value="' + key + '" ' + selected + '>' + value + '</option>';
                    }
                });
                data += "</select><input type='hidden' id='sync_field_hidden" + page_no + que_no + "' value='" + queValue.sync_fields + "'></li>";

            }
            data += '</ul></div>';
        }
        data += "</div>";
        data += "</div>";
    } else if (queValue.que_type == "Video") {
        data += "<div class='survey-body' id='que_" + page_no + que_no + "'>";
        data += "<div class='s-row question' id='questionline" + page_no + "'>";
        data += "<div class='right-close-add-div' align='right'><a style='cursor:pointer;' onclick='addSection(this," + page_no + "," + que_no + ")'><i class='fa fa-pause fa-5 fa-rotate-90' title='Add Section' aria-hidden='true' style='font-size:14px;margin: 5px 3px;'></i></a><a id='que_remove_" + que_no + "' style='cursor:pointer;' onclick='removeQuestions(this," + page_no + ")'><i class='fa fa-remove' id='remove_que_" + que_no + "' title='Remove Question' style='font-size:14px;margin: 5px 3px;'></i></a></div>";
        data += "<div id='tabs" + page_no + que_no + "'>";
        data += "<div class='tab' style='margin-bottom: 20px;'>"
        data += '   <a class="advance_tab general_tab active" id="gen" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="gen">General</p></a>';
        data += '  <a class="advance_tab" id="adv" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="adv">Advanced Option</p></a>';
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += '   <a class="advance_tab pipe_tab" id="pipe" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="pipe">Piping</p></a>';
        }
        data += '</div>';
        data += "<div id='general" + page_no + que_no + "' class='tabcontent general_tab_content' style ='display:block;'><ul class='question-section' id='question_table" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id[" + page_no + "][" + que_no + "]'  id='que_id" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id_del[" + page_no + "][" + que_no + "]'  id='que_id_del" + page_no + que_no + "'>";
        data += "<li class='s-type'><label class='left-label'>Question Type  </label><span style='cursor:pointer;' class='change_request' id='change_" + page_no + que_no + "' ondblclick='drop_down(this," + page_no + "," + que_no + ")'>" + que_icon + " Video&nbsp;&nbsp;<a class='fa fa-pencil' aria-hidden='true'></a></span><span><input type= 'hidden' name='que_type[" + page_no + "][" + que_no + "]' id='que_type" + page_no + "" + que_no + "' value='Video' /></span>  </li>";
        data += "<li class='s-title'> <label class='left-label'>Video URL<span class='required'>*</span></label><span><input placeholder='Enter URL For Video' class='input-text que_title' id='que_title" + page_no + que_no + "' type='text' name='que_title[" + page_no + "][" + que_no + "]' value='" + queValue.que_title + "'/></span><span class='survey-logo-selection'><img style='margin-left:10px' title='i.e. http://www.youtube.com/embed/L3_gx6Fx_b0' src='themes/default/images/helpInline.gif' alt='Information' class='inlineHelpTip' border='0'></span></li>";
        data += "</div>";
        data += "<div id='advanced" + page_no + que_no + "' class='tabcontent' style='display:none'>";
        data += "<ul><li class='s-desc'><label class='left-label'>Title </label><span><input class='input-text title' placeholder='Enter Title For Video' type='text' name='question_help_comment[" + page_no + "][" + que_no + "]' value='" + queValue.question_help_comment + "'></span></li>";
        data += "<li class ='s-desc'><label class='left-label'>Description </label><span><textarea cols='40' rows='7' style='width: 31%; vertical-align: top;' placeholder='Enter Description For Video' name='video_desc[" + page_no + "][" + que_no + "]'>" + queValue.descvideo + "</textarea></span></li>";
        data += "</ul></div>";
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += "<div id='pipe" + page_no + que_no + "' class='pipecontent' style='display:none'>";
            if (queValue.desable_piping != "0" && typeof queValue.desable_piping != "undefined") {
                data += "<ul><li class='s-title'><label class='left-label'>Disable Piping</label><input type='checkbox' onclick='desablepiping_checkbox(this," + page_no + "," + que_no + ")' name='desable_piping_que[" + page_no + "][" + que_no + "]' checked/></label></li>";
                data += "<li class='s-title' style='display:none;'><label class='left-label'>Sync Field</label><select class='fields_type' onchange='getfieldstype(this," + page_no + "," + que_no + ")' id='sync_field" + page_no + que_no + "' name='sync_fields[" + page_no + "][" + que_no + "]'>";
                var selected_module = $('#sync_module').val();
                data += '<option value="0">Select Fields</option>';
                $.each(module_fields[selected_module], function (key, value) {
                    var selected = '';
                    if (queValue.sync_fields == key) {
                        selected = 'selected'
                    }
                    if (value != "") {
                        data += '<option value="' + key + '" ' + selected + '>' + value + '</option>';
                    }
                });
                data += "</select><input type='hidden' id='sync_field_hidden" + page_no + que_no + "' value=''></li>";
            } else {
                data += "<ul><li class='s-title'><label class='left-label'>Disable Piping</label><input type='checkbox' onclick='desablepiping_checkbox(this," + page_no + "," + que_no + ")' name='desable_piping_que[" + page_no + "][" + que_no + "]'/></label></li>";
                data += "<li class='s-title'><label class='left-label'>Sync Field</label><select class='fields_type' onchange='getfieldstype(this," + page_no + "," + que_no + ")' id='sync_field" + page_no + que_no + "' name='sync_fields[" + page_no + "][" + que_no + "]'>";
                var selected_module = $('#sync_module').val();
                data += '<option value="0">Select Fields</option>';
                $.each(module_fields[selected_module], function (key, value) {
                    var selected = '';
                    if (queValue.sync_fields == key) {
                        selected = 'selected'
                    }
                    if (value != "") {
                        data += '<option value="' + key + '" ' + selected + '>' + value + '</option>';
                    }
                });
                data += "</select><input type='hidden' id='sync_field_hidden" + page_no + que_no + "' value='" + queValue.sync_fields + "'></li>";

            }
            data += '</ul></div>';
        }
        data += "</div>";
        data += "</div>";
    } else if (queValue.que_type == "Date") {

        data += "<div class='survey-body' id='que_" + page_no + que_no + "'>";
        data += "<div class='s-row question' id='questionl ine" + page_no + "'>";
        data += "<div class='right-close-add-div' align='right'><a style='cursor:pointer;' onclick='addSection(this," + page_no + "," + que_no + ")'><i class='fa fa-pause fa-5 fa-rotate-90' title='Add Section' aria-hidden='true' style='font-size:14px;margin: 5px 3px;'></i></a><a id='que_remove_" + que_no + "' style='cursor:pointer;' onclick='removeQuestions(this," + page_no + ")'><i class='fa fa-remove' id='remove_que_" + que_no + "' title='Remove Question' style='font-size:14px;margin: 5px 3px;'></i></a></div>";
        data += "<div id='tabs" + page_no + que_no + "'>";
        data += "<div class='tab' style='margin-bottom: 20px;'>";
        data += '<a class="advance_tab general_tab active" id="gen" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="gen">General</p></a>';
        data += '<a class="advance_tab" id="adv" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="adv">Advanced Option</p></a>';
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += '   <a class="advance_tab pipe_tab" id="pipe" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="pipe">Piping</p></a>';
        }
        data += '</div>';
        data += "<div id='general" + page_no + que_no + "' class='tabcontent general_tab_content' style='display:block;'><ul class='question-section' id='question_table" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id[" + page_no + "][" + que_no + "]'  id='que_id" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id_del[" + page_no + "][" + que_no + "]'  id='que_id_del" + page_no + que_no + "'>";
        data += "<li class='s-type'><label class='left-label'>Question Type  </label><span style='cursor:pointer;' class='change_request' id='change_" + page_no + que_no + "' ondblclick='drop_down(this," + page_no + "," + que_no + ")'>" + que_icon + " DateTime&nbsp;&nbsp;<a class='fa fa-pencil' aria-hidden='true'></a></span><span><input type='hidden' name='que_type[" + page_no + "][" + que_no + "]' id='que_type" + page_no + "" + que_no + "' value='Date'/></span>  </li>";
        data += "<li class='s-title'> <label class='left-label'>Question<span class='required'>*</span></label><span><input class='input-text que_title' id='que_title" + page_no + que_no + "' type='text' name='que_title[" + page_no + "][" + que_no + "]' value='" + queValue.que_title + "'/></span></li>";
        data += "</div>";
        data += "<div id='advanced" + page_no + que_no + "' class='tabcontent' style='display:none'>";
        data += "<ul><li class='s-desc'><label class='left-label'>Help Tips</label><span><input  class='input-text question_help_comment' placeholder='Enter help tips for question' type='text' nae='question_help_comment[" + page_no + "][" + que_no + "]' value='" + queValue.question_help_comment + "'></span></li>";
        if (queValue.is_required === "1") {
            data += "<li class='s-required'><label class='left-label'>Is required  </label><span><input type='checkbox' name='is_required[" + page_no + "][" + que_no + "]' id='is_required' checked/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        }
        else {
            data += "<li class='s-required'><label class='left-label'>Is required  </label><span><input type='checkbox' name='is_required[" + page_no + "][" + que_no + "]' id='is_required' />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        }
        if (queValue.is_datetime === "1") {
            data += "<label>IsDateTime</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><input type='checkbox' name='is_datetime[" + page_no + "][" + que_no + "]' id='is_required' checked/></span></li>";
        } else {
            data += "<label>IsDateTime</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><input type='checkbox' name='is_datetime[" + page_no + "][" + que_no + "]' id='is_required' /></span></li>";
        }
        data += "<li class='s-start'><label class='left-label'>Start Date</label><input class='input-text sdate' id='stdate" + page_no + que_no + "' style='width: 10%;' placeholder='Start Date' type='text' value='" + queValue.min + "' name=start_date_time[" + page_no + "][" + que_no + "]>&nbsp;<img id='startdate" + page_no + que_no + "' style='position: relative;top: 4px;padding-left: 4px;' src='themes/default/images/jscalendar.gif'/> &nbsp; - &nbsp;<input class ='input-text edate' value='" + queValue.max + "' id='edate" + page_no + que_no + "' placeholder='End Date' style='width: 10%;' type='text' name=end_date_time[" + page_no + "][" + que_no + "]>&nbsp;<img style='position: relative;top: 4px;padding-left: 4px;' src='themes/default/images/jscalendar.gif' id='enddate" + page_no + que_no + "''/> ";
        data += "</ul></div>";
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += "<div id='pipe" + page_no + que_no + "' class='pipecontent' style='display:none'>";
            if (queValue.desable_piping != "0" && typeof queValue.desable_piping != "undefined") {
                data += "<ul><li class='s-title'><label class='left-label'>Disable Piping</label><input type='checkbox' onclick='desablepiping_checkbox(this," + page_no + "," + que_no + ")' name='desable_piping_que[" + page_no + "][" + que_no + "]' checked/></label></li>";
                data += "<li class='s-title' style='display:none;'><label class='left-label'>Sync Field</label><select class='fields_type' onchange='getfieldstype(this," + page_no + "," + que_no + ")' id='sync_field" + page_no + que_no + "' name='sync_fields[" + page_no + "][" + que_no + "]'>";
                var selected_module = $('#sync_module').val();
                data += '<option value="0">Select Fields</option>';
                $.each(module_fields[selected_module], function (key, value) {
                    var selected = '';
                    if (queValue.sync_fields == key) {
                        selected = 'selected'
                    }
                    if (value != "") {
                        data += '<option value="' + key + '" ' + selected + '>' + value + '</option>';
                    }
                });
                data += "</select><input type='hidden' id='sync_field_hidden" + page_no + que_no + "' value=''></li>";
            } else {
                data += "<ul><li class='s-title'><label class='left-label'>Disable Piping</label><input type='checkbox' onclick='desablepiping_checkbox(this," + page_no + "," + que_no + ")' name='desable_piping_que[" + page_no + "][" + que_no + "]'/></label></li>";
                data += "<li class='s-title'><label class='left-label'>Sync Field</label><select class='fields_type' onchange='getfieldstype(this," + page_no + "," + que_no + ")' id='sync_field" + page_no + que_no + "' name='sync_fields[" + page_no + "][" + que_no + "]'>";
                var selected_module = $('#sync_module').val();
                data += '<option value="0">Select Fields</option>';
                $.each(module_fields[selected_module], function (key, value) {
                    var selected = '';
                    if (queValue.sync_fields == key) {
                        selected = 'selected'
                    }
                    if (value != "") {
                        data += '<option value="' + key + '" ' + selected + '>' + value + '</option>';
                    }
                });
                data += "</select><input type='hidden' id='sync_field_hidden" + page_no + que_no + "' value='" + queValue.sync_fields + "'></li>";

            }
            data += '</ul></div>';
        }
        data += "</div>";
        data += "</div>";
    } else if (queValue.que_type == "Scale") {
        data += "<div class='survey-body' id='que_" + page_no + que_no + "'>";
        data += "<div class='s-row question' id='questionline" + page_no + "'>";
        data += "<div class='right-close-add-div' align='right'><a style='cursor:pointer;' onclick='addSection(this," + page_no + "," + que_no + ")'><i class='fa fa-pause fa-5 fa-rotate-90' title='Add Section' aria-hidden='true' style='font-size:14px;margin: 5px 3px;'></i></a><a id='que_remove_" + que_no + "' style='cursor:pointer;' onclick='removeQuestions(this," + page_no + ")'><i class='fa fa-remove' id='remove_que_" + que_no + "' title='Remove Question' style='font-size:14px;margin: 5px 3px;'></i></a></div>";
        data += "<div id='tabs" + page_no + que_no + "'>";
        data += "<div class='tab' style='margin-bottom: 20px;'>";
        data += '   <a class="advance_tab general_tab active" id="gen" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="gen">General</p></a>';
        data += '   <a class="advance_tab" id="adv" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="adv">Advanced Option</p></a>';
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += '   <a class="advance_tab pipe_tab" id="pipe" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="pipe">Piping</p></a>';
        }
        data += '</div>';
        data += "<div id='general" + page_no + que_no + "' class='tabcontent general_tab_content' style='display:block;'><ul class='question-section' id='question_table" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id[" + page_no + "][" + que_no + "]'  id='que_id" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id_del[" + page_no + "][" + que_no + "]'  id='que_id_del" + page_no + que_no + "'>";
        data += "<li class='s-type'><label class='left-label'>Question Type  </label><span style='cursor:pointer;' class='change_request' id='change_" + page_no + que_no + "' ondblclick='drop_down(this," + page_no + "," + que_no + ")'>" + que_icon + " Scale&nbsp;&nbsp;<a class='fa fa-pencil' aria-hidden='true'></a></span><span><input type='hidden' name='que_type[" + page_no + "][" + que_no + "]' id='que_type" + page_no + "" + que_no + "' value='Scale' /></span>  </li>";
        data += "<li class='s-title'> <label class='left-label'>Question<span class='required'>*</span></label><span><input class='input-text que_title' id='que_title" + page_no + que_no + "' type='text' name='que_title[" + page_no + "][" + que_no + "]' value='" + queValue.que_title + "'/></span></li>";
        data += "</div>";
        data += "<div id='advanced" + page_no + que_no + "' class='tabcontent' style='display:none'>";
        data += "<ul><li class='s-desc'><label class='left-label'>Help Tips</label><span><input  class='input-text question_help_comment' placeholder='Enter help tips for question' type='text' name='question_help_comment[" + page_no + "][" + que_no + "]' value='" + queValue.question_help_comment + "'></span></li>";
        var label = queValue.advance_type.split("-");
        data += "<li class='s-display'><label class='left-label'>Display Label</label><span><input  class='input-text' style= 'width: 10%;' placeholder='Left' type='text' name='left[" + page_no + "][" + que_no + "]' value='" + label[0] + "'>&nbsp;&nbsp;<input  class='input-text' placeholder='Middle' style='width: 10%;' type='text' name=middle[" + page_no + "][" + que_no + "] value='" + label[1] + "'>&nbsp;&nbsp;<input  class='input-text' placeholder='Right' style='width: 10%;' type='text' name=right[" + page_no + "][" + que_no + "] value='" + label[2] + "'></span></li>";
        if (queValue.is_required === "1") {
            data += "<li class='s-required'><label class='left-label'>Is required  </label><span><input type='checkbox' name='is_required[" + page_no + "][" + que_no + "]' id='is_required' checked/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        } else {
            data += "<li class='s-required'><label class='left-label'>Is required  </label><span><input type='checkbox' name='is_required[" + page_no + "][" + que_no + "]' id='is_required' />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        }
        data += "<li class='s-start'><label class='left-label'>Start & End Value</label><input class='input-text start' style='width: 10%;' onkeypress='return validateNumbericValue(event," + page_no + "," + que_no + ");' placeholder='Start Value' type='text' name='start[" + page_no + "][" + que_no + "]' value='" + queValue.min + "'>&nbsp; - &nbsp;<input class='input-text end' onkeypress='return validateNumbericValue(event," + page_no + "," + que_no + ");' placeholder='End Value' style='width: 10%;' type='text' name='end[" + page_no + "][" + que_no + "]' value='" + queValue.max + "'>";
        data += "<li class='s-step'><label class='left-label'>Step Value</label><input class='input-text stepval' onkeypress='return validateNumbericValue(event," + page_no + "," + que_no + ");' placeholder='Step Value' style='width: 10%;' type='text' name=step_value[" + page_no + "][" + que_no + "] value='" + queValue.step_value + "'>";
        data += "</ul></div>";
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += "<div id='pipe" + page_no + que_no + "' class='pipecontent' style='display:none'>";
            if (queValue.desable_piping != "0" && typeof queValue.desable_piping != "undefined") {
                data += "<ul><li class='s-title'><label class='left-label'>Disable Piping</label><input type='checkbox' onclick='desablepiping_checkbox(this," + page_no + "," + que_no + ")' name='desable_piping_que[" + page_no + "][" + que_no + "]' checked/></label></li>";
                data += "<li class='s-title' style='display:none;'><label class='left-label'>Sync Field</label><select class='fields_type' onchange='getfieldstype(this," + page_no + "," + que_no + ")' id='sync_field" + page_no + que_no + "' name='sync_fields[" + page_no + "][" + que_no + "]'>";
                var selected_module = $('#sync_module').val();
                data += '<option value="0">Select Fields</option>';
                $.each(module_fields[selected_module], function (key, value) {
                    var selected = '';
                    if (queValue.sync_fields == key) {
                        selected = 'selected'
                    }
                    if (value != "") {
                        data += '<option value="' + key + '" ' + selected + '>' + value + '</option>';
                    }
                });
                data += "</select><input type='hidden' id='sync_field_hidden" + page_no + que_no + "' value='" + queValue.sync_fields + "'></li>";
            } else {
                data += "<ul><li class='s-title'><label class='left-label'>Disable Piping</label><input type='checkbox' onclick='desablepiping_checkbox(this," + page_no + "," + que_no + ")' name='desable_piping_que[" + page_no + "][" + que_no + "]'/></label></li>";
                data += "<li class='s-title'><label class='left-label'>Sync Field</label><select class='fields_type' onchange='getfieldstype(this," + page_no + "," + que_no + ")' id='sync_field" + page_no + que_no + "' name='sync_fields[" + page_no + "][" + que_no + "]'>";
                var selected_module = $('#sync_module').val();
                data += '<option value="0">Select Fields</option>';
                $.each(module_fields[selected_module], function (key, value) {
                    var selected = '';
                    if (queValue.sync_fields == key) {
                        selected = 'selected'
                    }
                    if (value != "") {
                        data += '<option value="' + key + '" ' + selected + '>' + value + '</option>';
                    }
                });
                data += "</select><input type='hidden' id='sync_field_hidden" + page_no + que_no + "' value='" + queValue.sync_fields + "'></li>";

            }
            data += '</ul></div>';
        }
        data += "</div>";
        data += "</div>";
    } else if (queValue.que_type == "Matrix") {
        data += "<div class='survey-body' id='que_" + page_no + que_no + "'>";
        data += "<div class='s-row question' id='questionline" + page_no + "'>";
        data += "<div class='right-close-add-div' align='right'><a style='cursor:pointer;' onclick='addSection(this," + page_no + "," + que_no + ")'><i class='fa fa-pause fa-5 fa-rotate-90' title='Add Section' aria-hidden='true' style='font-size:14px;margin: 5px 3px;'></i></a><a id='que_remove_" + que_no + "' style='cursor:pointer;' onclick='removeQuestions(this," + page_no + ")'><i class='fa fa-remove' id='remove_que_" + que_no + "' title='Remove Question' style='font-size:14px;margin: 5px 3px;'></i></a></div>";
        data += "<div id='tabs" + page_no + que_no + "'>";
        data += "<div class='tab' style='margin-bottom: 20px;'>";
        data += '   <a class="advance_tab general_tab active" id="gen" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="gen">General</p></a>';
        data += '   <a class="advance_tab" id="adv" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="adv">Advanced Option</p></a>';
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += '   <a class="advance_tab pipe_tab" id="pipe" onclick="change_tab(this,' + page_no + ',' + que_no + ')"><p class="pipe">Piping</p></a>';
        }
        data += '</div>';
        data += "<div id='general" + page_no + que_no + "' class='tabcontent general_tab_content' style='display:block;'><ul class='question-section' id='question_table" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id[" + page_no + "][" + que_no + "]'  id='que_id" + page_no + que_no + "'>";
        data += "<input type='hidden' name='que_id_del[" + page_no + "][" + que_no + "]'  id='que_id_del" + page_no + que_no + "'>";
        data += "<li class='s-type'><label class='left-label'>Question Type  </label><span style='cursor:pointer;' class='change_request' id='change_" + page_no + que_no + "' ondblclick='drop_down(this," + page_no + "," + que_no + ")'>" + que_icon + " Matrix&nbsp;&nbsp;<a class='fa fa-pencil' aria-hidden='true'></a></span><span><input type='hidden' name='que_type[" + page_no + "][" + que_no + "]' id='que_type" + page_no + "" + que_no + "' value='Matrix' /></span>  </li>";
        data += "<li class='s-title'><label class='left-label'>Question<span class='required'>*</span></label><span><input class='input-text que_title' id='que_title" + page_no + que_no + "' type='text' name='que_title[" + page_no + "][" + que_no + "]' value='" + queValue.que_title + "'/></span></li>";
        data += "<li class='s-option matrix-body-row'>";
        data += "<ul class='answer-section matrix-row' id='rows" + page_no + "" + que_no + "' class='answer_div'>";
        var length = Object.keys(queValue.matrix_row).length;
        var custom_len = 1;
        var showPlusBtn = 'display:none';
        var showremBtn = 'display:none';
        $.each(queValue.matrix_row, function (rowIndex, rowValue) {
            if (custom_len == length) {
                showPlusBtn = 'display:inline;';
                showremBtn = 'display:inline;';
            } else {
                showPlusBtn = 'display:none;';
                showremBtn = 'display:inline;';
            }
            var option = "";
            if (custom_len == 1) {
                option = "Rows";
            }
            var row = "row";
            if (length > 2) {
                data += "<li id='ans_op" + page_no + "" + que_no + "" + (rowIndex - 1) + "' class='ans_op_class" + page_no + "" + que_no + "'><label class='left-label'>" + option + "</label><span><input type='text' name=row[" + page_no + "][" + que_no + "][" + (rowIndex - 1) + "] value='" + rowValue + "' /></span><input type='button' value='&#10005;' name='btnRemoveOption' style='" + showremBtn + " margin-left:6px;height:25px;width:25px;' onclick='removerow_cols(" + page_no + "," + que_no + ",this,\"" + row + "\")' /><input name='btnOptionAdd' type='button' value='&#43;' onclick='edit_rowcols(" + page_no + "," + que_no + ",\"" + row + "\")' style='margin:10px;height:25px;width:25px;" + showPlusBtn + "'/>";
            }
            else {
                data += "<li id='ans_op" + page_no + "" + que_no + "" + (rowIndex - 1) + "' class='ans_op_class" + page_no + "" + que_no + "'><label class='left-label'>" + option + "</label><span><input type='text' name=row[" + page_no + "][" + que_no + "][" + (rowIndex - 1) + "] value='" + rowValue + "' /></span><input type='button' value='&#10005;' name='btnRemoveOption' style='display:none; margin-left:6px;height:25px;width:25px;' onclick='removerow_cols(" + page_no + "," + que_no + ",this,\"" + row + "\")' />";
                data += "<input name='btnOptionAdd' type='button' value='&#43;' onclick='edit_rowcols(" + page_no + "," + que_no + ",\"" + row + "\")' style='margin:10px;height:25px;width:25px; " + showPlusBtn + "'/>";
            }
            custom_len += 1;
        });
        data += "</ul><ul class='answer-section matrix-column' id='columns" + page_no + "" + que_no + "' class='answer_div'>";
        var col_length = Object.keys(queValue.matrix_col).length;
        var cstmcol_len = 1;
        $.each(queValue.matrix_col, function (colIndex, colValue) {

            if (cstmcol_len == col_length) {
                showPlusBtn = 'display:inline;';
                showremBtn = 'display:inline;';
            } else {
                showPlusBtn = 'display:none;';
                showremBtn = 'display:inline;';
            }
            var col_option = "";
            if (cstmcol_len == 1) {
                col_option = "Columns";
            }
            var col = "column";
            if (col_length > 2) {
                data += "<li id='ans_op" + page_no + "" + que_no + "" + (colIndex - 1) + "' class='ans_op_class" + page_no + "" + que_no + "'><label class='left-label'>" + col_option + "</label><span><input type='text' name=col[" + page_no + "][" + que_no + "][" + (colIndex - 1) + "] value='" + colValue + "' /></span><input type='button' value='&#10005;' name='btnRemoveOption' style='" + showremBtn + " margin-left:6px;height:25px;width:25px;' onclick='removerow_cols(" + page_no + "," + que_no + ",this,\"" + col + "\")' /><input name='btnOptionAdd' type='button' value='&#43;' onclick='edit_rowcols(" + page_no + "," + que_no + ",\"" + col + "\")' style='margin:10px;height:25px;width:25px;" + showPlusBtn + "'/>";
            }
            else {
                data += "<li id='ans_op" + page_no + "" + que_no + "" + (colIndex - 1) + "' class='ans_op_class" + page_no + "" + que_no + "'><label class='left-label'>" + col_option + "</label><span><input type='text' name=col[" + page_no + "][" + que_no + "][" + (colIndex - 1) + "] value='" + colValue + "' /></span><input type='button' value='&#10005;' name='btnRemoveOption' style='display:none; margin-left:6px;height:25px;width:25px;' onclick='removerow_cols(" + page_no + "," + que_no + ",this,\"" + col + "\")' />";
                data += "<input name='btnOptionAdd' type='button' value='&#43;' onclick='edit_rowcols(" + page_no + "," + que_no + ",\"" + col + "\")' style='margin:10px;height:25px;width:25px; " + showPlusBtn + "'/>";
            }
            cstmcol_len += 1;
        });
        data += "</ul></div>";
        data += "<div id='advanced" + page_no + que_no + "' class='tabcontent' style='display:none'>";
        data += "<ul><li class='s-desc'><label class='left-label'>Help Tips</label><span><input  class='input-text question_help_comment' placeholder='Enter help tips for question' type='text' name='question_help_comment[" + page_no + "][" + que_no + "]' value='" + queValue.question_help_comment + "'></span></li>";
        if (queValue.is_required === "1") {
            data += "<li class='s-required'><label class='left-label'>Is required  </label><span><input type='checkbox' name='is_required[" + page_no + "][" + que_no + "]' id='is_required' checked/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        }
        else {
            data += "<li class='s-required'><label class='left-label'>Is required  </label><span><input type='checkbox' name='is_required[" + page_no + "][" + que_no + "]' id='is_required' />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        }
        data += "<li class='s-display'><label class='left-label'>Display Option</label>";

        if (queValue.advance_type == "Radio") {
            data += "<span><input type='radio' name='display_type_matrix[" + page_no + "][" + que_no + "]' checked='checked' value='Radio''>&nbsp;&nbsp;&nbsp;<i class='fa fa-dot-circle-o'></i>&nbsp; Radio&nbsp;&nbsp;&nbsp;";
        } else {
            data += "<span><input type='radio' name='display_type_matrix[" + page_no + "][" + que_no + "]' value='Radio'>&nbsp;&nbsp;&nbsp;<i class='fa fa-dot-circle-o'></i>&nbsp; Radio&nbsp;&nbsp;&nbsp;";
        }
        if (queValue.advance_type == "Checkbox") {
            data += "<span><input type='radio' value='Checkbox' name='display_type_matrix[" + page_no + "][" + que_no + "]' checked='checked'>&nbsp;&nbsp;&nbsp;<i class='fa fa-check-square-o'></i>&nbsp; CheckBox";
        } else {
            data += "<span><input type='radio' value='Checkbox' name='display_type_matrix[" + page_no + "][" + que_no + "]'>&nbsp;&nbsp;&nbsp;<i class='fa fa-check-square-o'></i>&nbsp; CheckBox";
        }
        data += "</ul></div>";
        if ($('#EditView input[name=module]').val() != "bc_survey_template") {
            data += "<div id='pipe" + page_no + que_no + "' class='pipecontent' style='display:none'>";
            if (queValue.desable_piping != "0" && typeof queValue.desable_piping != "undefined") {
                data += "<ul><li class='s-title'><label class='left-label'>Disable Piping</label><input type='checkbox' onclick='desablepiping_checkbox(this," + page_no + "," + que_no + ")' name='desable_piping_que[" + page_no + "][" + que_no + "]' checked/></label></li>";
                data += "<li class='s-title' style='display:none;'><label class='left-label'>Sync Field</label><select class='fields_type' onchange='getfieldstype(this," + page_no + "," + que_no + ")' id='sync_field" + page_no + que_no + "' name='sync_fields[" + page_no + "][" + que_no + "]'>";
                var selected_module = $('#sync_module').val();
                data += '<option value="0">Select Fields</option>';
                $.each(module_fields[selected_module], function (key, value) {
                    var selected = '';
                    if (queValue.sync_fields == key) {
                        selected = 'selected'
                    }
                    if (value != "") {
                        data += '<option value="' + key + '" ' + selected + '>' + value + '</option>';
                    }
                });
                data += "</select><input type='hidden' id='sync_field_hidden" + page_no + que_no + "' value='" + queValue.sync_fields + "'></li>";
            } else {
                data += "<ul><li class='s-title'><label class='left-label'>Disable Piping</label><input type='checkbox' onclick='desablepiping_checkbox(this," + page_no + "," + que_no + ")' name='desable_piping_que[" + page_no + "][" + que_no + "]'/></label></li>";
                data += "<li class='s-title'><label class='left-label'>Sync Field</label><select class='fields_type' onchange='getfieldstype(this," + page_no + "," + que_no + ")' id='sync_field" + page_no + que_no + "' name='sync_fields[" + page_no + "][" + que_no + "]'>";
                var selected_module = $('#sync_module').val();
                data += '<option value="0">Select Fields</option>';
                $.each(module_fields[selected_module], function (key, value) {
                    var selected = '';
                    if (queValue.sync_fields == key) {
                        selected = 'selected'
                    }
                    if (value != "") {
                        data += '<option value="' + key + '" ' + selected + '>' + value + '</option>';
                    }
                });
                data += "</select><input type='hidden' id='sync_field_hidden" + page_no + que_no + "' value='" + queValue.sync_fields + "'></li>";

            }
            data += '</ul></div>';
        }
        data += "</div>";
        data += "</div>";
    } else if (queValue.que_type == "question_section") {
        data += '<div class="survey-body section_title" id="que_' + page_no + que_no + '"><div class="section row"><label style="align:right; width:12.5%;">Section Title: </label><input type="text" name="que_title[' + page_no + '][' + que_no + ']" id="section_title' + page_no + que_no + '" style="width:79%;" value="' + queValue.que_title + '" placeholder="(Required) Section Title"><input type="hidden" name="que_type[' + page_no + '][' + que_no + ']" value="question_section"/><a style="cursor:pointer;" id="remove_page_' + page_no + que_no + '" onclick="remove_section(this,' + page_no + ',' + que_no + ')"><i class="fa fa-remove" id="remove_section' + page_no + que_no + '" title="Remove Question" style="font-size:14px;margin: 5px 3px;"></i></a><span id="validate_name" class="error-msg"></span>';
        data += '<input type="hidden" name="que_id[' + page_no + '][' + que_no + ']" id="que_id' + page_no + que_no + '" value="' + queValue.que_id + '">';
        data += '<input type="hidden" name="que_id_del[' + page_no + '][' + que_no + ']" value="' + queValue.que_id_del + '" id="que_id_del' + page_no + que_no + '">';
        data += '</div></div>';
    }
    return data;
}
/**
 * remove Page
 *
 * @author     Original Author Biztech Co.
 * @param      array - event
 * @return
 */
//removing pages
function removePage(event) {

    var val = event.id;
    var current_page_count = $('.add-page-title').length;
    if (current_page_count > 1) {
        var page_no = val.substring(12);
        var confirm_value = confirm("Are you sure want to remove this page ?");
        if (confirm_value) {
            if ($('#page_deleted').val()) {
                $('#page_deleted').val($('#page_deleted').val() + ',' + $(event).parent().parent().parent().parent().find('#page_id_del').val());
            } else {
                $('#page_deleted').val($('div#page_' + page_no).find('#page_id_del').val());
            }
            $('div#page_' + page_no).remove();
        }
    } else {
        alert("You Must Set At Least One Page.");
    }
}
/**
 * remove questions
 *
 * @author     Original Author Biztech Co.
 * @param      array - event
 * @param      integer - page_no
 * @return
 */
//removing questions from page
function removeQuestions(event, page_no) {
    var val = event.id;
    var que_no = val.substring(11);
    var msg = "";
    if (($('#que_' + page_no + que_no).next().attr('class') == "add-que append_question" && $('#que_' + page_no + que_no).prev().attr('class') == "survey-body section_title") || ($('#que_' + page_no + que_no).prev().attr('class') == "survey-body section_title" && $('#que_' + page_no + que_no).next().attr('class') == "survey-body section_title")) {
        msg = "Removing this question will also remove its Section Header. Are you sure want to remove this question ?";
    } else {
        msg = "Are you sure want to remove this question ?"
    }
    if (confirm(msg)) {
        if (($('#que_' + page_no + que_no).next().attr('class') == "add-que append_question" && $('#que_' + page_no + que_no).prev().attr('class') == "survey-body section_title") || ($('#que_' + page_no + que_no).prev().attr('class') == "survey-body section_title" && $('#que_' + page_no + que_no).next().attr('class') == "survey-body section_title")) {
            $('#que_' + page_no + que_no).prev().remove();
        }
        if ($("#que_deleted").val()) {
            $("#que_deleted").val($("#que_deleted").val() + ',' + $(event).parent().parent().parent().parent().find('ul').find('#que_id_del' + page_no + que_no).val());
        } else {
            $("#que_deleted").val($('div#que_' + page_no + que_no).find('#que_id_del' + page_no + que_no).val());
        }
        $('div#que_' + page_no + que_no).remove();
    }
}
/**
 *add new page
 *
 * @author     Original Author Biztech Co.
 * @param      integer - page
 * @return
 */
//for add new pages
function addNewPage(page, survey_type) {
    var new_page_html = '';
    new_page_html += "<div class='add-page-title' id='page_" + page + "'>";
    if (survey_type == "poll") {
        new_page_html += "<div class='row pagetitle' style='min-height: 20px;background-color: #c5c5c5;'><label style='align:right; width:12.5%;'>Poll Question</label></div>";
    } else {
        new_page_html += "<div class='row pagetitle' style='min-height: 20px;background-color: #c5c5c5;'><label style='align:right; width:12.5%;'>Page Title: </label><input type='text' name='page_title[" + page + "]' id='page_title" + page + "' class='pagetitle_text' style='width:79%;' placeholder='(Required) Page Title'/><a style='margin-left:120px; cursor:pointer;' id='remove_page_" + page + "' onclick='removePage(this)'><i class='fa fa-remove' id='remove_page_" + page + "' title='Remove Question' style='font-size:14px;margin: 5px 3px;'></i></a><span id='validate_name' class='error-msg'></span></div>";
    }
    new_page_html += "<input type='hidden' name='que_no' value='0' id='last_que_no' />";
    new_page_html += "<input type='hidden' name='page_number[" + page + "]' id='page_number" + page + "' value='" + page + "'>";
    new_page_html += "<input type='hidden' name='page_id[" + page + "]' id='page_id" + page + "' style='width: 37.5%' />";
    new_page_html += "<input type='hidden' name='page_id_del[" + page + "]' id='page_id_del' style='width: 37.5%' >";
    //new_page_html += "<h4 style='display:inline;'>Page Title: </h4><input type='text' name='page_title[" + page + "]' id='page_title' style='width:60%; height: 40%; margin-bottom:10px;' placeholder='(Required) Page Title' /> <span id='validate_name' class='error-msg'></span>";
    new_page_html += " <input type='hidden' name='page_id[" + page + "]' id='page_id'  value=''>";
    if (survey_type != "poll") {
        new_page_html += "<div class='add-que append_question'><div class='add_question'>To add a question, simply drag it from the Page Component.</div></div>";
    } else {
        new_page_html += "<div class='survey-body' id='que_00'>";
        new_page_html += "<div class='s-row question' id='questionline0'>";
        new_page_html += "<ul class='question-section' id='question_table00'>";
        new_page_html += "<input type='hidden' name='que_id[0][0]'  id='que_id00'>";
        new_page_html += "<input type='hidden' name='survey_type' value='poll'>";
        new_page_html += "<li class='s-type'><label class='left-label'>Question Type </label><label><input type='radio' name='que_type[0][0]' value='RadioButton' checked><i class='fa fa-dot-circle-o' aria-hidden='true'></i>Radio Button</label>&nbsp;&nbsp;<label><input type='radio' name='que_type[0][0]' value='Checkbox'><i class='fa fa-check-square-o' aria-hidden='true'></i>Checkbox</label></li>";
        new_page_html += "<li class='s-title'> <label class='left-label'>Question<span class='required'>*</span> </label><span><input class='input-text que_title' type='text' id='que_title00' name='que_title[0][0]'></span></li>";
        new_page_html += "<li class='s-option'>";
        new_page_html += "<ul class='answer-section' id='answer_div00' class='answer_div'><li id='ans_op000'><label class='left-label'>Option </label><span><input type='text' name='answer[0][0][0]' class='answer00'/></span><input type='button' value='&#10005;' name='btnRemoveOption' style='display:none;margin-left: 12px;height:25px;width:25px;' onclick='removeOption(0,0,this)'/>";
        new_page_html += "<input type='hidden' name='ans_id[0][0][0]' id='ans_id' style='width: 37.5%' />";
        new_page_html += "<input type='hidden' name='ans_id_del[0][0][0]' id='ans_id_del' style='width: 37.5%' /></li>";
        new_page_html += "<li id='ans_op000'><label class='left-label'></label><span><input type='text' class='answer00' name=answer[0][0][1]/></span>";
        new_page_html += "<input type='hidden' name='ans_id[0][0][1]' id='ans_id' style='width: 37.5%' />";
        new_page_html += "<input type='hidden' name='ans_id_del[0][0][1]' id='ans_id_del' style='width: 37.5%' />&nbsp;";
        new_page_html += "<input type='button' value='&#10005;' name='btnRemoveOption' style='display:none;margin-left: 9px;height:25px;width:25px;' onclick='removeOption(0,0,this)' /><input name='btnOptionAdd' type='button' value='&#43;' onclick='addNewOption(this,0,0)' style='margin:10px;height:25px;width:25px;'/>";
        new_page_html += "</li>";
        new_page_html += "</ul>";
        new_page_html += "</ul>";
        new_page_html += "<input type='hidden' id='last_ans_no_00' value='2' name='last_ans_no00'>";
    }
    new_page_html += "</div>";
    return new_page_html;
}
/**
 * remove row columns
 *
 * @author     Original Author Biztech Co.
 * @param      string - type,currentElement
 * @param      integer - page_no,que_no
 * @return
 */
//row col remove
function removerow_cols(page_no, que_no, currentElement, type) {
    if (confirm("Are You Sure To Remove This Option?")) {
        if ($(currentElement).parent().is(':last-child'))
        {
            $(currentElement).parent().prev().find('input[name="btnOptionAdd"]').css('display', 'inline');
        }
        $(currentElement).parent().remove();
        if (type == "column") {
            var option_length = $('ul#columns' + page_no + que_no).find('li').length;
            if (option_length == 2) {
                $('ul#columns' + page_no + que_no).find('li').find('input[name="btnRemoveOption"]').css("display", "none");
            }
            $('ul#columns' + page_no + que_no).find('.left-label').eq(0).html('Columns');
        } else {
            var option_length = $('ul#rows' + page_no + que_no).find('li').length;
            if (option_length == 2) {
                $('ul#rows' + page_no + que_no).find('li').find('input[name="btnRemoveOption"]').css("display", "none");
            }
            $('ul#rows' + page_no + que_no).find('.left-label').eq(0).html('Rows');
        }
    }
}
/**
 * remove options
 *
 * @author     Original Author Biztech Co.
 * @param      string - currentElement
 * @param      integer - page_no,que_no
 * @return
 */
//Remove Multiple choice question options
function removeOption(page_no, que_no, currentElement) {
    if (confirm("Are You Sure To Remove This Option?")) {
        if ($("#ans_deleted").val()) {
            $("#ans_deleted").val($("#ans_deleted").val() + ',' + $(currentElement).closest('li').find('#ans_id_del').val());
        } else {
            $("#ans_deleted").val($(currentElement).closest('li').find('#ans_id_del').val());
        }

        if ($(currentElement).parent().is(':last-child'))
        {
            $(currentElement).parent().prev().find('input[name="btnOptionAdd"]').css('display', 'inline');
        }
        var el_id = $(currentElement).parent().find('#ans_id').val();
        $(currentElement).parents('#tabs' + page_no + que_no).find('.opid_' + el_id).remove();
        $(currentElement).parent().remove();
        var option_length = $('ul#answer_div' + page_no + que_no).find('li').length;
        if (option_length == 2) {
            $('ul#answer_div' + page_no + que_no).find('li').find('input[name="btnRemoveOption"]').css("display", "none");
        }
        $('ul#answer_div' + page_no + que_no).find('.left-label').eq(0).html('Option');
        $('#advanced' + page_no + que_no).find('.limit_dropdown').find('select').find('option:last').remove();
    }
}
/**
 * add row columns
 *
 * @author     Original Author Biztech Co.
 * @param      string - type
 * @param      integer - page_no,que_no
 * @return
 */
function add_rowcols(page_no, que_no, type) {
    var option_html = '';
    if (type == "column") {

        var ans_no = Number($('#last_col_no_' + page_no + que_no).val());
        var col = "column";
        option_html += "<li id='ans_op" + page_no + que_no + ans_no + "' class='ans_op_class" + page_no + que_no + "'><label class='left-label'></label><span><input type='text' name='col[" + page_no + "][" + que_no + "][" + ans_no + "]' id='answer" + page_no + que_no + ans_no + "'></span><input type='button' value='&#10005;' name='btnRemoveOption' style='display:inline;margin-left: 6px;height:25px;width:25px;' onclick='removerow_cols(" + page_no + "," + que_no + ",this,\"" + col + "\")' /> <input type='button' name='btnOptionAdd' value='&#43;' onclick='add_rowcols(" + page_no + "," + que_no + ",\"" + col + "\")' style='margin-left:14px;height:25px;width:25px;' />";
        option_html += "</li>";
        $('#columns' + page_no + que_no + ' ' + 'li:last').after(option_html);
        var option_length = ($('ul#columns' + page_no + que_no).find('li').length);
        if (option_length > 2) {
            $('ul#columns' + page_no + que_no).find('li').find('input[name="btnRemoveOption"]').css("display", "inline");
        }
//hiding the + button when clicking on that button
        $('#columns' + page_no + que_no + ' ' + 'li:nth-last-child(2)').find('input[name="btnOptionAdd"]').hide();
        $("#last_col_no_" + page_no + que_no).val(ans_no + 1); //increase value of answer options
    } else {

        var ans_no = Number($('#last_row_no_' + page_no + que_no).val());
        var row = "row";
        option_html += "<li id='ans_op" + page_no + que_no + ans_no + "' class='ans_op_class" + page_no + que_no + "'><label class='left-label'></label><span><input type='text' name='row[" + page_no + "][" + que_no + "][" + ans_no + "]' id='answer" + page_no + que_no + ans_no + "'></span>\n\
          <input type='button' value='&#10005;' name='btnRemoveOption' style='display:inline;margin-left:6px;height:25px;width:25px;' onclick='removerow_cols(" + page_no + "," + que_no + ",this,\"" + row + "\")' /> <input type='button' name='btnOptionAdd' value='&#43;' onclick='add_rowcols(" + page_no + "," + que_no + ",\"" + row + "\")' style='margin-left:14px;height:25px;width:25px;' />";
        option_html += "</li>";
        $('#rows' + page_no + que_no + ' ' + 'li:last').after(option_html);
        var option_length = ($('ul#rows' + page_no + que_no).find('li').length);
        if (option_length > 2) {
            $('ul#rows' + page_no + que_no).find('li').find('input[name="btnRemoveOption"]').css("display", "inline");
        }
//hiding the + button when clicking on that button
        $('#rows' + page_no + que_no + ' ' + 'li:nth-last-child(2)').find('input[name="btnOptionAdd"]').hide();
        $("#last_row_no_" + page_no + que_no).val(ans_no + 1);
    }
}
/**
 * add questions
 *
 * @author     Original Author Biztech Co.
 * @param      string - current_drop
 * @param      integer - page_no,que_no
 * @return
 */
//for identifies the type of question
function addQue(current_drop, page_no, que_no) {
    var data = '';
    if (current_drop.includes("Textbox") === true) {
        data = addQueHTML("Textbox", page_no, que_no);
    }
    if (current_drop.includes("CommentTextbox") === true) {
        data = addQueHTML("CommentTextbox", page_no, que_no);
    }
    if (current_drop.includes("MultiSelectList") === true) {
        data = addQueHTML("MultiSelectList", page_no, que_no);
    }
    if (current_drop.includes("Checkbox") === true) {
        data = addQueHTML("Checkbox", page_no, que_no);
    }
    if (current_drop.includes("DrodownList") === true) {
        data = addQueHTML("DrodownList", page_no, que_no);
    }
    if (current_drop.includes("RadioButton") === true) {
        data = addQueHTML("RadioButton", page_no, que_no);
    }
    if (current_drop.includes("ContactInformation") === true) {
        data = addQueHTML("ContactInformation", page_no, que_no);
    }
    if (current_drop.includes("Rating") === true) {
        data = addQueHTML("Rating", page_no, que_no);
    }
    if (current_drop.includes("Video") === true) {
        data = addQueHTML("Video", page_no, que_no);
    }
    if (current_drop.includes("Matrix") === true) {
        data = addQueHTML("Matrix", page_no, que_no);
    }
    if (current_drop.includes("Scale") === true) {
        data = addQueHTML("Scale", page_no, que_no);
    }
    if (current_drop.includes("Image") === true) {
        data = addQueHTML("Image", page_no, que_no);
    }
    if (current_drop.includes("Date") === true) {
        data = addQueHTML("Date", page_no, que_no);
    }
    return data;
}
/**
 * edit questions
 *
 * @author     Original Author Biztech Co.
 * @param      array - queValue
 * @param      integer - page_no,que_no
 * @return
 */
//for dragging question
function editQue(queValue, page_no, que_no) {
    var data = '';
    if (queValue.que_type === 'Textbox') {
        data = editQuestionHtml(queValue, page_no, que_no);
    }
    if (queValue.que_type === 'CommentTextbox') {
        data = editQuestionHtml(queValue, page_no, que_no);
    }
    if (queValue.que_type === 'MultiSelectList') {
        data = editQuestionHtml(queValue, page_no, que_no);
    }
    if (queValue.que_type === 'Checkbox') {
        data = editQuestionHtml(queValue, page_no, que_no);
    }
    if (queValue.que_type === 'DrodownList') {
        data = editQuestionHtml(queValue, page_no, que_no);
    }
    if (queValue.que_type === 'RadioButton') {
        data = editQuestionHtml(queValue, page_no, que_no);
    }
    if (queValue.que_type === 'ContactInformation') {
        data = editQuestionHtml(queValue, page_no, que_no);
    }
    if (queValue.que_type === 'Rating') {
        data = editQuestionHtml(queValue, page_no, que_no);
    }
    if (queValue.que_type === 'Image') {
        data = editQuestionHtml(queValue, page_no, que_no);
    }
    if (queValue.que_type === 'Video') {
        data = editQuestionHtml(queValue, page_no, que_no);
    }
    if (queValue.que_type === 'Date') {
        data = editQuestionHtml(queValue, page_no, que_no);
    }
    if (queValue.que_type === 'Matrix') {
        data = editQuestionHtml(queValue, page_no, que_no);
    }
    if (queValue.que_type === 'Scale') {
        data = editQuestionHtml(queValue, page_no, que_no);
    }
    if (queValue.que_type === 'question_section') {
        data = editQuestionHtml(queValue, page_no, que_no);
    }
    return data;
}
/**
 * add new option
 *
 * @author     Original Author Biztech Co.
 * @param      integer - page_no,que_no
 * @return
 */
// add option for multi choice question
function addNewOption(el, page_no, que_no) {
    var ans_no = Number($('#last_ans_no_' + page_no + que_no).val());
    var option_html = '';
    var display = '';
    if ($('.enable_score' + page_no + que_no).prop('checked') == true) {
        display = "display:inline;";
    } else {
        display = "display:none;";
    }
    var value = parseInt($(el).parent().find('.score_weight' + page_no + que_no).val()) + 1;
    option_html += "<li id='ans_op" + page_no + que_no + ans_no + "' class='ans_op_class" + page_no + que_no + "'><label class='left-label'></label><span><input type='text' name='answer[" + page_no + "][" + que_no + "][" + ans_no + "]' class='answer" + page_no + que_no + "' id='answer" + page_no + que_no + ans_no + "'></span>&nbsp;<input type='number' name='score_dropdownlist[" + page_no + "][" + que_no + "][" + ans_no + "]' value='" + value + "' onblur='checkValue(this)' class='score_weight" + page_no + que_no + "' style='margin-left: 6px; text-align: center;max-width: 7%;height: 22px;" + display + "'>\n\
                    <input type='button' value='&#10005;' name='btnRemoveOption' style='display:inline;margin-left:6px;height:25px;width:25px;' onclick='removeOption(" + page_no + "," + que_no + ",this)' /> <input type='button' name='btnOptionAdd' value='&#43;' onclick='addNewOption(this," + page_no + "," + que_no + ")' style='margin-left:14px;height:25px;width:25px;' />";
    option_html += "<input type='hidden' name='ans_id[" + page_no + "][" + que_no + "][" + ans_no + "]' id='ans_id' style='width: 37.5%' />";
    option_html += "<input type='hidden' name='ans_id_del[" + page_no + "][" + que_no + "][" + ans_no + "]' id='ans_id_del' style='width: 37.5%' />";
    option_html += "</li>";
    $('#answer_div' + page_no + que_no + ' ' + 'li:last').after(option_html);
    var option_length = ($('ul#answer_div' + page_no + que_no).find('li').length);
    if (option_length > 2) {
        $('ul#answer_div' + page_no + que_no).find('li').find('input[name="btnRemoveOption"]').css("display", "inline");
    }
    $('#answer_div' + page_no + que_no + ' ' + 'li:nth-last-child(2)').find('input[name="btnOptionAdd"]').hide(); //hiding the + button when clicking on that button
    $("#last_ans_no_" + page_no + que_no).val(ans_no + 1); //increase value of answer options
    var value = $('#advanced' + page_no + que_no).find('.limit_dropdown').find('select').find('option').length;
    $('#advanced' + page_no + que_no).find('.limit_dropdown').find('select').append('<option value="' + value + '">' + value + '</option>');
}
/**
 * edit row columns
 *
 * @author     Original Author Biztech Co.
 * @param      string - type
 * @param      integer - page_no,que_no
 * @return
 */
//for Matrix row col edit
function edit_rowcols(page_no, que_no, type) {
    if (type == "column") {
        var col = "column";
        var total_no_li = $('ul#columns' + page_no + que_no).find('li').length;
        var option_html = '';
        option_html += "<li id='ans_op" + page_no + que_no + total_no_li + "' class='ans_op_class" + page_no + que_no + "'><label class='left-label'></label><span><input type='text' name='col[" + page_no + "][" + que_no + "][" + total_no_li + "]' id='answer" + page_no + que_no + total_no_li + "'></span><input type='button' value='&#10005;' name='btnRemoveOption' style='display:inline; margin-left:6px;height:25px;width:25px;' onclick='removerow_cols(" + page_no + "," + que_no + ",this,\"" + col + "\")'/> <input type='button' name='btnOptionAdd' value='&#43;' onclick='edit_rowcols(" + page_no + "," + que_no + ",\"" + col + "\")' style='margin-left:14px;height:25px;width:25px;' />";
        option_html += "</li>";
        $('#columns' + page_no + que_no + ' ' + 'li:last').after(option_html);
        var option_length = ($('ul#columns' + page_no + que_no).find('li').length);
        if (option_length > 2) {
            $('ul#columns' + page_no + que_no).find('li').find('input[name="btnRemoveOption"]').css("display", "inline");
        }
        $('#columns' + page_no + que_no + ' ' + 'li:nth-last-child(2)').find('input[name="btnOptionAdd"]').hide(); //hiding the + button when clicking on that button
    } else {
        var row = "row";
        var total_no_li = $('ul#rows' + page_no + que_no).find('li').length;
        var option_html = '';
        option_html += "<li id='ans_op" + page_no + que_no + total_no_li + "' class='ans_op_class" + page_no + que_no + "'><label class='left-label'></label><span><input type='text' name='row[" + page_no + "][" + que_no + "][" + total_no_li + "]' id='answer" + page_no + que_no + total_no_li + "'></span>\n\<input type='button' value='&#10005;' name='btnRemoveOption' style='display:inline;margin-left:2px;height:25px;width:25px;' onclick='removerow_cols(" + page_no + "," + que_no + ",this,\"" + row + "\")' /> <input type='button' name='btnOptionAdd' value='&#43;' onclick='edit_rowcols(" + page_no + "," + que_no + ",\"" + row + "\")' style='margin-left:14px;height:25px;width:25px;' />";
        option_html += "</li>";
        $('#rows' + page_no + que_no + ' ' + 'li:last').after(option_html);
        var option_length = ($('ul#rows' + page_no + que_no).find('li').length);
        if (option_length > 2) {
            $('ul#rows' + page_no + que_no).find('li').find('input[name="btnRemoveOption"]').css("display", "inline");
        }
        $('#rows' + page_no + que_no + ' ' + 'li:nth-last-child(2)').find('input[name="btnOptionAdd"]').hide(); //hiding the + button when clicking on that button
    }
}
/**
 * edit new option
 *
 * @author     Original Author Biztech Co.
 * @param      integer - page_no,que_no
 * @return
 */
//editing muliti choice option
function editNewOption(el, page_no, que_no) {
    var total_no_li = $('ul#answer_div' + page_no + que_no).find('li').length;
    var cuu_optId_seq = $(el).parents('.ans_op_class' + page_no + que_no).attr('id').replace('ans_op' + page_no + que_no, '');
    cuu_optId_seq = parseInt(cuu_optId_seq) + 1;
    var option_html = '';
    var display = '';
    if ($('.enable_score' + page_no + que_no).prop('checked') == true) {
        display = "display:inline;";
    } else {
        display = "display:none;";
    }
    var value = parseInt($(el).parent().find('.score_weight' + page_no + que_no).val()) + 1;
    option_html += "<li id='ans_op" + page_no + que_no + cuu_optId_seq + "' class='ans_op_class" + page_no + que_no + "'><label class='left-label'></label><span><input type='text' name='answer[" + page_no + "][" + que_no + "][" + cuu_optId_seq + "]' class='answer" + page_no + que_no + "' id='answer" + page_no + que_no + cuu_optId_seq + "'></span>&nbsp;<input type='number' name='score_dropdownlist[" + page_no + "][" + que_no + "][" + cuu_optId_seq + "]' value='" + value + "' onblur='checkValue(this)' class='score_weight" + page_no + que_no + "' style='margin-left: 6px; text-align: center;max-width: 7%;height: 22px;" + display + "'>\n\
                    <input type='button' value='&#10005;' name='btnRemoveOption' style='display:inline; margin-left:3px;height:25px;width:25px;' onclick='removeOption(" + page_no + "," + que_no + ",this)' /> <input type='button' name='btnOptionAdd' value='&#43;' onclick='editNewOption(this," + page_no + "," + que_no + ")' style='margin-left:10px;height:25px;width:25px;' />";
    option_html += "<input type='hidden' name='ans_id[" + page_no + "][" + que_no + "][" + total_no_li + "]' id='ans_id' style='width: 37.5%' />";
    option_html += "<input type='hidden' name='ans_id_del[" + page_no + "][" + que_no + "][" + total_no_li + "]' id='ans_id_del' style='width: 37.5%' />";
    option_html += "</li>";
    $('#answer_div' + page_no + que_no + ' ' + 'li:last').after(option_html);
    var option_length = ($('ul#answer_div' + page_no + que_no).find('li').length);
    if (option_length > 2) {

        $('ul#answer_div' + page_no + que_no).find('li').find('input[name="btnRemoveOption"]').css("display", "inline");
    }
    $(el).hide();
    var value = $('#advanced' + page_no + que_no).find('.limit_dropdown').find('select').find('option').length;
    $('#advanced' + page_no + que_no).find('.limit_dropdown').find('select').append('<option value="' + value + '">' + value + '</option>');
    //$('#answer_div' + page_no + que_no + ' ' + 'li:nth-last-child(2)').find('input[name="btnOptionAdd"]').hide(); //hiding the + button when clicking on that button
}
/**
 * edit new Page
 *
 * @author     Original Author Biztech Co.
 * @param      array - pageValue
 * @return
 */
//for edit page record
function editNewPage(pageValue, cnt, questions) {
    var new_page_html = '';
    new_page_html += "<div class='add-page-title' id='page_" + (pageValue.page_number - 1) + "'>";
    if (cnt != 1) {
        new_page_html += "<div class='row pagetitle' style='min-height: 20px;background-color: #c5c5c5;'><label style='display:inline; width:12.5%;'>Page Title: </label><input type='text' class='pagetitle_text' name='page_title[" + (pageValue.page_number - 1) + "]' id='page_title" + (pageValue.page_number - 1) + "' style='width:79%; ' placeholder='(Required) Page Title' value='" + pageValue.page_title + "' /><a style='margin-left:120px;cursor:pointer;' id='remove_page_" + (pageValue.page_number - 1) + "' onclick='removePage(this)'><i class='fa fa-remove' id='remove_page_" + (pageValue.page_number - 1) + "' title='Remove Question' style='font-size:14px;margin: 5px 3px;'></i></a></div>";
    } else {
        new_page_html += "<div class='row pagetitle' style='min-height: 20px;background-color: #c5c5c5;'><label style='align:right; width:12.5%;'>Poll Question</label></div>";
    }
    new_page_html += "<input type='hidden' name='que_no' value='0' id='last_que_no" + (pageValue.page_number - 1) + "' />";
    new_page_html += "<input type='hidden' name='page_number[" + (pageValue.page_number - 1) + "]' class='page_number" + (pageValue.page_number - 1) + "' id='page_number" + (pageValue.page_number - 1) + "' value='" + (pageValue.page_number - 1) + "'>";
    new_page_html += "<input type='hidden' name='page_id[" + (pageValue.page_number - 1) + "]' id='page_id" + (pageValue.page_number - 1) + "' style='width: 37.5%' />";
    new_page_html += "<input type='hidden' name='page_id_del[" + (pageValue.page_number - 1) + "]' id='page_id_del" + (pageValue.page_number - 1) + "' style='width: 37.5%' >";

    if (cnt != 1) {
        new_page_html += "<div class='add-que' id='comment_" + (pageValue.page_number - 1) + "'><div class='add_question'>To add a question, simply drag it from the Page Component.</div></div>"
    } else {

        $.each(questions, function (que_index, que) {

            new_page_html += "<div class='survey-body' id='que_00'>";
            new_page_html += "<div class='s-row question' id='questionline0'>";
            new_page_html += "<ul class='question-section' id='question_table00'>";
            new_page_html += "<input type='hidden' name='que_id[0][0]' id='que_id00' value='" + que.que_id + "'>";
            new_page_html += "<input type='hidden' name='survey_type' value='poll'>";
            new_page_html += "<li class='s-type'><label class='left-label'>Question Type </label><label>";
            if (que.que_type == "RadioButton") {
                new_page_html += "<input type='radio' name='que_type[0][0]' value='RadioButton' checked><i class='fa fa-dot-circle-o' aria-hidden='true'></i>Radio Button</label>&nbsp;&nbsp;";
                new_page_html += "<label><input type='radio' name='que_type[0][0]' value='Checkbox'><i class='fa fa-check-square-o' aria-hidden='true'></i>Checkbox</label>";
            } else {
                new_page_html += "<input type='radio' name='que_type[0][0]' value='RadioButton' checked><i class='fa fa-dot-circle-o' aria-hidden='true'></i>Radio Button</label>&nbsp;&nbsp;";
                new_page_html += "<label><input type='radio' name='que_type[0][0]' value='Checkbox' checked><i class='fa fa-check-square-o' aria-hidden='true'></i>Checkbox</label>";
            }
            new_page_html += "</li><li class='s-title'> <label class='left-label'>Question<span class='required'>*</span> </label><span><input class='input-text que_title' type='text' id='que_title00' name='que_title[0][0]' value='" + que.que_title + "'></span></li>";
            new_page_html += "<li class='s-option'>";
            new_page_html += "<ul class='answer-section' id='answer_div00' class='answer_div'>";
            var length = '';
            length = Object.keys(que.answers).length;
            var custom_len = 1;
            var showPlusBtn = 'display:none';
            var showremBtn = 'display:none';
            $.each(que.answers, function (answerIndex, answerValue) {
                if (custom_len == length) {
                    showPlusBtn = 'display:inline;';
                    showremBtn = 'display:inline;';
                } else {
                    showPlusBtn = 'display:none;';
                    showremBtn = 'display:inline;';
                }
                var option = "";
                if (custom_len == 1) {
                    option = "Option";
                }
                if (length > 2)
                {
                    new_page_html += "<li id='ans_op00" + (answerIndex - 1) + "' class='ans_op_class00'><label class='left-label'>" + option + "</label><span><input type='text' class='answer00' name=answer[0][0][" + (answerIndex - 1) + "] value='" + answerValue.name + "' /></span>&nbsp;<input type='number' name='score_dropdownlist[0][0][" + (answerIndex - 1) + "]' value='" + answerIndex + "' onblur='checkValue(this)' class='score_weight11' style='margin-left: 6px; text-align: center;max-width: 7%;height: 22px;display:none;'><input type='button' value='&#10005;' name='btnRemoveOption' style='" + showremBtn + " margin-left:6px;height:25px;width:25px;' onclick='removeOption(0,0,this)' /><input name='btnOptionAdd' type='button' value='&#43;' onclick='editNewOption(this,0,0)' style='margin-left:10px;margin:10px;height:25px;width:25px;" + showPlusBtn + "'/>";
                    new_page_html += "<input type='hidden' name='ans_id[0][0][" + (answerIndex - 1) + "]' id ='ans_id' style='width: 37.5%' value='" + answerValue['id'] + "'/>";
                    new_page_html += "<input type='hidden' name='ans_id_del[0][0][" + (answerIndex - 1) + "]' id='ans_id_del' style='width: 37.5%' value='" + answerValue['id'] + "' />";
                }
                else
                {
                    new_page_html += "<li id='ans_op00" + (answerIndex - 1) + "' class='ans_op_class00'><label class='left-label'>" + option + "</label><span><input type='text' class='answer00' name=answer[0][0][" + (answerIndex - 1) + "] value='" + answerValue.name + "' /></span>&nbsp;<input type='number' name='score_dropdownlist[0][0][" + (answerIndex - 1) + "]' value='" + answerIndex + "' onblur='checkValue(this)' class='score_weight00' style='margin-left: 6px; text-align: center;max-width: 7%;height: 22px;display:none;'><input type='button' value='&#10005;' name='btnRemoveOption' style='display:none; margin-left:6px;height:25px;width:25px;' onclick='removeOption(0,0,this)' />";
                    new_page_html += "<input type='hidden' name='ans_id[0][0][" + (answerIndex - 1) + "]'id='ans_id' style='width: 37.5%' value='" + answerValue['id'] + "'/>";
                    new_page_html += "<input type='hidden' name='ans_id_del[0][0][" + (answerIndex - 1) + "]' id='ans_id_del' style='width: 37.5%' value='" + answerValue['id'] + "' /><input name='btnOptionAdd' type='button' value='&#43;' onclick='editNewOption(this,0,0)' style='margin:10px;height:25px;width:25px; " + showPlusBtn + "'/>";
                }
                custom_len += 1;
            });
            new_page_html += "</li>";
            new_page_html += "</ul>";
            new_page_html += "</ul>";
            new_page_html += "<input type='hidden' id='last_ans_no_00' value='2' name='last_ans_no00'>";
        });
    }
    new_page_html += "</div>";
    return new_page_html;
}
/**
 * set hidden fields value
 *
 * @author     Original Author Biztech Co.
 * @return
 */
//hidden fields for delete page,question and answer
$(window).load(function () {
    var data = '';
    data += "<input type='hidden' name='page_deleted' value='' id='page_deleted'>";
    data += "<input type='hidden' name='question_deleted' value='' id='que_deleted'> ";
    data += "<input type='hidden' name='answer_deleted' value='' id='ans_deleted'>";
    data += "<input type='hidden' name='sync_module' value='' id='sync_module_main'>";
    $('div#EditView_tabs').prepend(data);
});
/**
 * load pages in detail for survey and survey template module
 *
 * @author     Original Author Biztech Co.
 * @return
 */
function loadPagesDetailView() {
    var record_id = $('input[name="record"]').val();
    var page = getAttrValFromUrl('page');
    if (page == "null") {
        page = 1;
    }
    var module_name = $("#formDetailView input[name=module]").val();
    var action = '';
    var record_module = '';
    if (module_name == "bc_survey_template") {
        action = "detail_template";
    } else if (module_name == "bc_survey") {
        action = "detail_survey";
    }
    if (action == "detail_template") {
        record_module = record_id;
    } else if (action == "detail_survey") {
        record_module = {record: record_id};
    }
    if (record_module != '') {

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: {
                action: action,
                action_view: 'DetailView',
                module: module_name,
                record: record_id,
                page: page
            },
            success: function (data, textStatus, jqXHR) {
                var detaildata = JSON.parse(data);
                var pagData = detaildata.pageNumbersArr;
                delete detaildata.pageNumbersArr;
                var show = true;
                var show_style = "display:none";
                var count = 0;
                if (detaildata.survey_type == "poll") {
                    count = 1;
                }
                if (count == 1) {
                    $('#thanks_page').parent().parent().remove();
                    $('#welcome_page').parent().parent().remove();
                    $('#description').parent().parent().remove();
                }
                $.each(detaildata, function (page_indx, page) {
                    if (show == true) {
                        show_style = "display:block";
                        //pages for detail view
                        if (page_indx != 'module' && page_indx != 'survey_type') {
                            var pagedetail = '';
                            pagedetail = '<table  width="100%" id="TableQuestion' + page_indx + '" class="panel-inner" cellspacing="0">'
                            if (count != 1) {
                                pagedetail += '<tr >\n\
                                        <td width="10.5%" scope="col">\n\
                                            <strong>Page Title</strong>\n\
                                        </td>\n\
                                        <td  width="37.5%">\n\
                                            ' + page.page_title + '\n\
                                        </td>\n\
                                        <td width="10.5%" scope="col">\n\
                                            <strong>Page Number</strong>\n\
                                        </td>\n\
                                        <td  width="37.5%">\n\
                                            ' + page.page_number + '\n\
                                        </td></tr>';
                            }
                            pagedetail += '</table>';
                            $("div.survey-view-section").append(pagedetail);
                            loadQuestionDetailView(page_indx, page.page_questions, count);
                        }
                    }
                });
                $('#allow_redundant_answers').parent().parent().after("<tr><td><button type='button' id='get_share_button' style='width:200px !important; height:30px !important;  border-radius: 8px !important; ' onclick='getsharelink(this);'>Get Share Link</button></td><td colspan='3'><input type='text' name='get_share_link' id='get_share_link' style='width:500px; height:30px;' ></td></tr>");
                if (count != 1) {
                    $('#allow_redundant_answers').parent().parent().remove();
                }
                $("div.survey-view-section").append(pagData);
                var survey_id = $("[name=record]").val();
                $.ajax({
                    url: "index.php",
                    data: {
                        module: 'bc_survey',
                        action: 'generate_unique_survey_submit_id',
                        survey_id: survey_id,
                        status: 'DetailView'
                    },
                    success: function (result) {

                        if ($.trim(result) != "false") {
                            $("#get_share_link").val(result);

                        }
                    }
                });
            }
        });
    }
}
function getsharelink(value) {
    var survey_id = $("[name=record]").val();
    $.ajax({
        url: "index.php",
        data: {
            module: 'bc_survey',
            action: 'generate_unique_survey_submit_id',
            survey_id: survey_id,
        },
        success: function (result) {
            $("#get_share_link").val(result);


        }
    });

}
/**
 * load pages in detail for survey and survey template module
 *
 * @author     Original Author Biztech Co.
 * @param      integer - page_indx
 * @param      array - que
 * @return     array
 */
//load Quesstions in detail for template module
function loadQuestionDetailView(page_indx, que, count) {
    var question_detail = '';
    var required = "";
    var comment = "";
    $.each(que, function (qn_indx, question) {

        if (question.is_required == 1) {
            required = "Yes";
        } else {
            required = "No";
        }
        if (question.question_help_comment == '') {
            comment = "N/A";
        } else {
            comment = question.question_help_comment;
        }
        //Question DetailView
        if (question.que_type == "Question Section") {
            question_detail = '<tr class="first"><td width="37.5%" colspan="4"><table  width="100%" cellspacing="0">\n\
                                <tr>\n\
                                    <td width="1.7%" scope="col">\n\
                                        <strong>Section Title</strong>\n\
                                    </td>\n\
                                    <td width="37.5%">\n\
                                        ' + question.que_title + '\n\
                                    </td>\n\
                                </tr>';
        } else {
            question_detail = '<tr class="first"><td width="37.5%" colspan="4"><table  width="100%" class="panel-sub" cellspacing="0">\n\
                                <tr>\n\
                                    <td width="10.5%" scope="col">\n\
                                        <strong>Question</strong>\n\
                                    </td>\n\
                                    <td width="37.5%">\n\
                                        ' + question.que_title + '\n\
                                    </td>'
            if (count != 1) {
                question_detail += '<td width="10.5%" scope="col">\n\
                                       <strong>Help Tips</strong>\n\
                                    </td>\n\
                                    <td  width=37.5%">\n\
                                       ' + comment + '\n\
                                    </td>';
            }
            question_detail += '</tr><tr>\n\
                                    <td width="10.5%" scope="col">\n\
                                        <strong>Answer Type</strong>\n\
                                    </td>\n\
                                        <td width="37.5%">\n\
                                            ' + question.que_type + '\n\
                                        </td>';
            if (count != 1) {
                question_detail += '<td width="10.5%" scope="col">\n\
                                            <strong>Is required</strong>\n\
                                        </td>\n\
                                        <td width="37.5%">\n\
                                            ' + required + '\n\
                                        </td>';
            }
            question_detail += '</tr>';
            if (count != 1) {
                if ($('#enable_data_piping:checked').length && question.desable_piping == "0") {
                    question_detail += '<tr><td width="10.5%" scope="col">&nbsp;</td><td width="37.5%">&nbsp;</td>\n\
                                    <td width="10.5%" scope="col">\n\
                                    <strong>Sync Field</strong>\n\
                                    </td>\n\
                                    <td width="37.5%">\n\
                                            ' + question.sync_field + '\n\
                                        </td>\n\
                                    </tr>';
                }
            }
            question_detail += '</table>';
        }
        $("table#TableQuestion" + page_indx).append(question_detail);
        if (question.que_type == "Checkbox" || question.que_type == "Radio Button" || question.que_type == "Dropdown List" || question.que_type == "MultiSelect List") {
            loadAnsOptionDetailView(page_indx, question.answers);
        }
        if (question.que_type == "Matrix / Grid") {
            matrixDetailview(page_indx, question.matrix_row, question.matrix_col)
        }
        if (question.que_type == "Image") {
            ImageDetail(page_indx, question.que_title, question.advanced_type)
        }
    });
}
/**
 * Matrix Detailview
 *
 * @author     Original Author Biztech Co.
 * @param      integer - page_indx
 * @param      array - row,col
 * @return
 */
function matrixDetailview(page_indx, row, col) {
    var html1 = '';
    var html2 = '';
    html1 += '<tr><td width="10.5%" scope="col"><strong>Row</strong></td><td>';
    $.each(row, function (rowindx, row_value) {
        html1 += '<li style="padding: 5px;">' + row_value + '</li>';
    });
    html1 += '</td>';
    $("table#TableQuestion" + page_indx).find("table").last().append(html1);
    html2 += '<td width="10.5%" scope="col"><strong>Column</strong></td><td>';
    $.each(col, function (colindx, col_value) {
        html2 += '<li style="padding: 5px;">' + col_value + '</li>';
    });
    html2 += '</td></tr>';
    $("table#TableQuestion" + page_indx).find("table").find('tr:eq(2)').find('td').last().after(html2);
}
/**
 * Image DetailView
 *
 * @author     Original Author Biztech Co.
 * @param      integer - page_indx
 * @param      string - que_title,advanced_type
 * @return
 */
function ImageDetail(page_indx, que_title, advanced_type) {

    var html = '';
    html += '<tr><td width="10.5%" scope="col"><strong>Image</strong></td>';
    if (que_title == "upload") {
        html += '<td><img height="200" width="300" src=custom/include/Image_question/' + advanced_type + '></td>';
    } else {
        html += '<td><img height="200" width="300" src=' + advanced_type + '></td>';
    }
    $("table#TableQuestion" + page_indx).find("table").last().append(html);
}
/**
 * load Answer options in detail survey and Survey Template module
 *
 * @author     Original Author Biztech Co.
 * @param      integer - page_indx
 * @param      array - answer
 * @return
 */
function loadAnsOptionDetailView(page_indx, answer) {
//Ans Option DetailView
    var answer_detail = "<tr><td width='10.5%' scope='col'><strong> Option </strong></td><td width='37.5%'>";
    $.each(answer, function (ansindx, ans) {
        answer_detail += '<li style="padding: 5px;">' + ans.name + '</li>';
    });
    $("table#TableQuestion" + page_indx).find("table").last().append(answer_detail);
}
/**
 * Get attribute from url
 *
 * @author     Original Author Biztech Co.
 * @param      string - attr
 * @return
 */
//getting template id from url
function getAttrValFromUrl(attr) {
    var mycurrenturl = window.location.href;
    var uri_dec = decodeURIComponent(mycurrenturl);
    $.urlParam = function (name) {
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(uri_dec);
        if (results == null) {
            return null;
        } else {
            return results[1] || 0;
        }
    }
    var attr_val = decodeURIComponent($.urlParam(attr));
    return attr_val;
}
$('.expandLink').click(function () {

    var panel_no = $(this).find('img').attr('id').split('_')[1];
    if (panel_no == "2") {
        $('#detailpanel_3').removeClass('expanded').addClass('collapsed');
        $('#detailpanel_4').removeClass('expanded').addClass('collapsed');
    } else if (panel_no == "3") {
        $('#detailpanel_2').removeClass('expanded').addClass('collapsed');
        $('#detailpanel_4').removeClass('expanded').addClass('collapsed');
    } else if (panel_no == "4") {
        $('#detailpanel_2').removeClass('expanded').addClass('collapsed');
        $('#detailpanel_3').removeClass('expanded').addClass('collapsed');
    }
});
$('#enable_data_piping').click(function () {
    if ($('#enable_data_piping').prop('checked')) {
        $('#sync_module').parent().parent().show();
    } else {
        if (confirm('By disabling Data piping existing question\'s sync field will be reset. Are you sure that you want to proceed?')) {
            $('#sync_module').parent().parent().hide();
            $.each($('.pipe_tab'), function () {
                if ($(this).hasClass('active')) {
                    $(this).parent().parent().find('.general_tab_content').show();
                    $(this).parent().find('.general_tab').addClass('active');
                    $(this).removeClass('active');
                    $(this).parent().parent().find('.pipecontent').hide();
                }
                $(this).parent().parent().find('.datatype-textbox').removeAttr('disabled');
            });
            $.each($('.fields_type'), function (indx, value) {
                if (this.value != "0") {
                    var que_type = $(this).parent().parent().parent().parent().find('.s-type').find('input[type=hidden]').val();
                    if (que_type == "DrodownList" || que_type == "MultiSelectList" || que_type == "RadioButton") {
                        $.each($(this).parent().parent().parent().parent().find('.s-option').find('#ans_id'), function () {
                            if ($('#ans_deleted').val()) {
                                $("#ans_deleted").val($("#ans_deleted").val() + ',' + $(this).val());
                            } else {
                                $("#ans_deleted").val($(this).val());
                            }
                        });
                        var data_option = '';
                        var que_no = parseInt($(this).parent().parent().parent().parent().prev().find('a:last').attr('id').split('_')[2]);
                        var page_no = parseInt($(this).parents('.add-page-title').attr('id').split('_')[1]);
                        data_option += "<ul class='answer-section' id='answer_div" + page_no + "" + que_no + "' class='answer_div'><li id='ans_op" + page_no + "" + que_no + "0'><label class='left-label'>Option </label><span><input type='text' name='answer[" + page_no + "][" + que_no + "][0]' class='answer" + page_no + "" + que_no + "'/></span>&nbsp;<input type='number' name='score_dropdownlist[" + page_no + "][" + que_no + "][0]' value='1' onblur='checkValue(this)' class='score_weight" + page_no + que_no + "' style='margin-left: 6px; text-align: center;max-width: 7%;height: 22px;display:none;'><input type='button' value='&#10005;' name='btnRemoveOption' style='display:none;margin-left: 9px;height:25px;width:25px;' onclick='removeOption(" + page_no + "," + que_no + ",this)'/>";
                        data_option += "<input type='hidden' name='ans_id[" + page_no + "][" + que_no + "][0]' id='ans_id' style='width: 37.5%' />";
                        data_option += "<input type='hidden' name='ans_id_del[" + page_no + "][" + que_no + "][0]' id='ans_id_del' style='width: 37.5%' /></li>";
                        data_option += "<li id='ans_op" + page_no + "" + que_no + "1'><label class='left-label'></label><span><input type='text' class='answer" + page_no + "" + que_no + "' name=answer[" + page_no + "][" + que_no + "][1]/></span>&nbsp;<input type='number' name='score_dropdownlist[" + page_no + "][" + que_no + "][1]' value='2' onblur='checkValue(this)' class='score_weight" + page_no + que_no + "' style='margin-left: 6px; text-align: center;max-width: 7%;height: 22px;display:none;'>";
                        data_option += "<input type='hidden' name='ans_id[" + page_no + "][" + que_no + "][1]' id='ans_id' style='width: 37.5%' />";
                        data_option += "<input type='hidden' name='ans_id_del[" + page_no + "][" + que_no + "][1]' id='ans_id_del' style='width: 37.5%' />&nbsp;";
                        data_option += "<input type='button' value='&#10005;' name='btnRemoveOption' style='display:none;margin-left: 6px;height:25px;width:25px;' onclick='removeOption(" + page_no + "," + que_no + ",this)' /><input name='btnOptionAdd' type='button' value='&#43;' onclick='addNewOption(this," + page_no + "," + que_no + ")' style='margin:10px;height:25px;width:25px;'/>";
                        data_option += "</li>";
                        data_option += "</ul>";
                        $(this).parent().parent().parent().parent().find('.answer-section').replaceWith(data_option);
                    }
                    $(this).val('0');
                }
            });
        } else {
            $(this).attr('checked', true);
        }
    }
});
/**
 * drag and drop function call
 *
 * @author     Original Author Biztech Co.
 * @return
 */
$(function () {

    var survey_type = getAttrValFromUrl("type");
    if (survey_type == "poll") {
        $('.SurveyPage').hide();
        $('#detailpanel_2').hide();
        $('#detailpanel_3').hide();
        $('#left-nav').find('.component').html('<i class="fa fa-dashboard" style="font-size: 15px;" title="open" tabindex="-1"></i>&nbsp;&nbsp;Survey Theme');
        $('#left-nav').find('.tab-right').css('width', '100%');
        $('#left-nav').find('.list-group').hide();
        $('#left-nav').find('.custom_theme_inner').show();
        $('#Default_bc_survey_Subpanel').find('#description_label').parent().hide();
        $('#Default_bc_survey_Subpanel').find('#redirect_url_label').parent().hide();
        $('#Default_bc_survey_Subpanel').find('#allowed_resubmit_count_label').parent().hide();
        $('#Default_bc_survey_Subpanel').find('#is_progress_label').parent().remove();
    } else if ($('#EditView').attr('id') == "EditView" || survey_type == "survey") {
        $('#allow_redundant_answers').parent().parent().hide();
    }
    loadPagesDetailView();
    $('#sync_module').change(function () {
        if (confirm('By selecting this Sync Module existing question\'s sync field will be reset. Are you sure that you want to proceed?')) {
            var module_name = $('#sync_module').val();
            $.each($('.fields_type'), function (indx, value) {
                if (this.value != "0") {
                    var que_type = $(this).parent().parent().parent().parent().find('.s-type').find('input[type=hidden]').val()
                    if (que_type == "DrodownList" || que_type == "MultiSelectList" || que_type == "RadioButton") {
                        $.each($(this).parent().parent().parent().parent().find('.s-option').find('#ans_id'), function () {
                            if ($('#ans_deleted').val()) {
                                $("#ans_deleted").val($("#ans_deleted").val() + ',' + $(this).val());
                            } else {
                                $("#ans_deleted").val($(this).val());
                            }
                        });
                        var data_option = '';
                        var que_no = parseInt($(this).parent().parent().parent().parent().prev().find('a:last').attr('id').split('_')[2]);
                        var page_no = parseInt($(this).parents('.add-page-title').attr('id').split('_')[1]);
                        data_option += "<ul class='answer-section' id='answer_div" + page_no + "" + que_no + "' class='answer_div'><li id='ans_op" + page_no + "" + que_no + "0'><label class='left-label'>Option </label><span><input type='text' name='answer[" + page_no + "][" + que_no + "][0]' class='answer" + page_no + "" + que_no + "'/></span>&nbsp;<input type='number' name='score_dropdownlist[" + page_no + "][" + que_no + "][0]' value='1' onblur='checkValue(this)' class='score_weight" + page_no + que_no + "' style='margin-left: 6px; text-align: center;max-width: 7%;height: 22px;display:none;'><input type='button' value='&#10005;' name='btnRemoveOption' style='display:none;margin-left: 9px;height:25px;width:25px;' onclick='removeOption(" + page_no + "," + que_no + ",this)'/>";
                        data_option += "<input type='hidden' name='ans_id[" + page_no + "][" + que_no + "][0]' id='ans_id' style='width: 37.5%' />";
                        data_option += "<input type='hidden' name='ans_id_del[" + page_no + "][" + que_no + "][0]' id='ans_id_del' style='width: 37.5%' /></li>";
                        data_option += "<li id='ans_op" + page_no + "" + que_no + "1'><label class='left-label'></label><span><input type='text' class='answer" + page_no + "" + que_no + "' name=answer[" + page_no + "][" + que_no + "][1]/></span>&nbsp;<input type='number' name='score_dropdownlist[" + page_no + "][" + que_no + "][1]' value='2' onblur='checkValue(this)' class='score_weight" + page_no + que_no + "' style='margin-left: 6px; text-align: center;max-width: 7%;height: 22px;display:none;'>";
                        data_option += "<input type='hidden' name='ans_id[" + page_no + "][" + que_no + "][1]' id='ans_id' style='width: 37.5%' />";
                        data_option += "<input type='hidden' name='ans_id_del[" + page_no + "][" + que_no + "][1]' id='ans_id_del' style='width: 37.5%' />&nbsp;";
                        data_option += "<input type='button' value='&#10005;' name='btnRemoveOption' style='display:none;margin-left: 6px;height:25px;width:25px;' onclick='removeOption(" + page_no + "," + que_no + ",this)' /><input name='btnOptionAdd' type='button' value='&#43;' onclick='addNewOption(this," + page_no + "," + que_no + ")' style='margin:10px;height:25px;width:25px;'/>";
                        data_option += "</li>";
                        data_option += "</ul>";
                        $(this).parent().parent().parent().parent().find('.answer-section').replaceWith(data_option);
                    }
                }
            });
            var fields_data = '<option value="0">Select Fields</option>';
            $.each(module_fields[module_name], function (indx, value) {
                if (value != "") {
                    fields_data += "<option value='" + indx + "'>" + value + "</option>"
                }
            });
            $('.fields_type').html(fields_data);
            $('#sync_module_main').val(module_name);
        } else {
            $('#sync_module').val($('#sync_module_main').val());
        }
    });
    //pages become sortable
    $('div#right-nav').sortable({
        cancel: ".add-pages,h4,select,textarea,input,i,.pagetitle,.add-que",
        axis: "Y",
        items: "> div:not(.add-pages,textarea)",
        placeholder: "placeholder-pages",
        cursor: "move",
        sort: function (event, ui) {
            //when sorting page then page title textbox size becoming large
            $(ui.helper).find('#page_title').css({width: '721.059px', height: '16.667px'});
        },
        stop: function (event, ui) {

            var isTwiceSection = false;
            var sectionCounter = 0;
            var lastElement = '';
            // check recursively for question section applied only once for the question
            $.each($(ui.item).parents('.add-page-title').find('.survey-body'), function () {
                if ($(this).hasClass('section_title')) {
                    sectionCounter++;
                    if (sectionCounter == 2)
                    {
                        isTwiceSection = true;
                    }
                    lastElement = 'section_title';
                } else {
                    sectionCounter = 0;
                    lastElement = 'survey-body';
                }
            });
            // check last element must be a question not a  question section
            if (lastElement == 'section_title')
            {
                isTwiceSection = true;
            }
            if (($(ui.item).hasClass('section_title') && ($(ui.item).prev().hasClass('section_title') || $(ui.item).next().hasClass('section_title') || $(ui.item).next().attr('id') == 'placeholder')) || isTwiceSection) {

                $(this).sortable('cancel');
                alert('Section Header Block already exists or Section Header Block misplaced by this sorting. You can not drop section here.');
            }
        }
    });
    var template_id = getAttrValFromUrl('template_id');
    var prefill_type = getAttrValFromUrl('prefill_type');
    var record_id = $('input[name="record"]').val();
    var module_name = $("#EditView input[name=module]").val();
    var action = '';
    var record_module = '';
    if (module_name == "bc_survey_template") {
        action = "edit_template";
    } else if (module_name == "bc_survey") {
        action = "edit_survey";
    }
    if (action == "edit_template") {
        record_module = record_id;
    } else if (action == "edit_survey") {
        record_module = {record: record_id, template_id: template_id,prefill_type:prefill_type};
    }

//For Editing record of survey and survey template
    var count = 0;
    if (record_id != '' || template_id != 'null') {
        count = 1;
    } else {
        $('#sync_module').parent().parent().hide();
    }
    if (!$('#enable_data_piping:checked').length) {
        $('#sync_module').parent().parent().hide();
    }
    if (record_module != '' && count == 1) {
        $('#loading-image').show();
        $(".survey-form-body").css("opacity", 0.4);
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: {
                action: action,
                module: module_name,
                record: record_module
            },
            success: function (data, textStatus, jqXHR) {

                var jsonToString = JSON.parse(data);
                if (jsonToString.name) {
                    $("#name").val(jsonToString.name);
                }
                if (jsonToString.description) {
                    $("#description").val(jsonToString.description);
                }
                if (jsonToString.theme) {
                    $.each($('[name=survey_theme]'), function () {
                        if (this.value == jsonToString.theme) {
                            this.checked = 'checked';
                        }
                    });
                }
                var cnt = 0;
                if (jsonToString.survey_type == "poll") {
                    $('.SurveyPage').hide();
                    $('#detailpanel_2').hide();
                    $('#detailpanel_3').hide();
                    $('#left-nav').find('.component').html('<i class="fa fa-dashboard" style="font-size: 15px;" title="open" tabindex="-1"></i>&nbsp;&nbsp;Survey Theme');
                    $('#left-nav').find('.tab-right').css('width', '100%');
                    $('#left-nav').find('.list-group').hide();
                    $('#left-nav').find('.custom_theme_inner').show();
                    $('#Default_bc_survey_Subpanel').find('#description_label').parent().hide();
                    $('#Default_bc_survey_Subpanel').find('#redirect_url_label').parent().hide();
                    $('#Default_bc_survey_Subpanel').find('#allowed_resubmit_count_label').parent().hide();
                    $('#Default_bc_survey_Subpanel').find('#is_progress_label').parent().remove();
                    cnt = 1;
                } else {
                    $('#allow_redundant_answers').parent().parent().remove();
                }
                if (jsonToString.enable_data_piping == "0") {
                    $('#sync_module').parent().parent().hide();
                }
                var new_page_html = '';
                var que = '';
                $('#sync_module_main').val(jsonToString.sync_module);
                delete jsonToString.pageNumbersArr;
                $.each(jsonToString, function (pageIndex, pageValue) {
                    if (pageIndex != "name" && pageIndex != "description" && pageIndex != "theme" && pageIndex != "survey_type" && pageIndex != "sync_module" && pageIndex != "enable_data_piping") {
                        new_page_html = editNewPage(pageValue, cnt, pageValue.page_questions);
                        $('div.add-pages').before(new_page_html);
                        if (pageValue.page_questions != null) {

                            $.each(pageValue.page_questions, function (queIndex, queValue) {
                                if (cnt != 1) {
                                    que = editQue(queValue, (pageValue.page_number - 1), (queIndex - 1));
                                    if (queIndex == 1) {
                                        $('div#page_' + (pageValue.page_number - 1)).find("div.add-que").before(que);
                                        if (queValue.que_type == "DrodownList" || queValue.que_type == "MultiSelectList" || queValue.que_type == "RadioButton") {
                                            if (queValue.sync_fields) {
                                                $('.answer' + (pageValue.page_number - 1) + (queIndex - 1)).attr('readonly', true);
                                                $('#answer_div' + (pageValue.page_number - 1) + (queIndex - 1)).find('input[type=button]').hide();
                                                $('#tabs' + (pageValue.page_number - 1) + (queIndex - 1)).find('.warning_outer').hide();
                                            }
                                        }
                                        if (queValue.que_type == "Date") {
                                            var pg_no = pageValue.page_number - 1;
                                            var qn_no = (queIndex - 1);
                                            $.ajax({
                                                url: 'index.php',
                                                data: {
                                                    module: 'bc_survey',
                                                    action: 'currentdateformat',
                                                },
                                                success: function (data, textStatus, jqXHR) {
                                                    var formet = trim(data);
                                                    Calendar.setup({
                                                        inputField: "stdate" + pg_no + qn_no,
                                                        onClose: function (cal) {
                                                            cal.hide();
                                                        },
                                                        form: "EditView",
                                                        ifFormat: formet,
                                                        daFormat: formet,
                                                        button: "startdate" + pg_no + qn_no,
                                                        singleClick: true,
                                                        dateStr: "",
                                                        startWeekday: "",
                                                        step: 1,
                                                        weekNumbers: false
                                                    });
                                                    Calendar.setup({
                                                        inputField: "edate" + pg_no + qn_no,
                                                        onClose: function (cal) {
                                                            cal.hide();
                                                        },
                                                        form: "EditView",
                                                        ifFormat: formet,
                                                        daFormat: formet,
                                                        button: "enddate" + pg_no + qn_no,
                                                        singleClick: true,
                                                        dateStr: "",
                                                        startWeekday: "",
                                                        step: 1,
                                                        weekNumbers: false
                                                    });
                                                }
                                            });
                                        }
                                    }
                                    else {
                                        $('div#page_' + (pageValue.page_number - 1)).find("div.add-que").before(que);
                                        if (queValue.que_type == "DrodownList" || queValue.que_type == "MultiSelectList" || queValue.que_type == "RadioButton") {
                                            if (queValue.sync_fields) {
                                                $('#answer_div' + (pageValue.page_number - 1) + (queIndex - 1)).find('input[type=button]');
                                            }
                                        }
                                        if (queValue.que_type == "Date") {
                                            var pg_no = pageValue.page_number - 1;
                                            var qn_no = (queIndex - 1);
                                            $.ajax({
                                                url: 'index.php',
                                                data: {
                                                    module: 'bc_survey',
                                                    action: 'currentdateformat',
                                                },
                                                success: function (data, textStatus, jqXHR) {
                                                    var formet = trim(data);
                                                    Calendar.setup({
                                                        inputField: "stdate" + pg_no + qn_no,
                                                        onClose: function (cal) {
                                                            cal.hide();
                                                        },
                                                        form: "EditView",
                                                        ifFormat: formet,
                                                        daFormat: formet,
                                                        button: "startdate" + pg_no + qn_no,
                                                        singleClick: true,
                                                        dateStr: "",
                                                        startWeekday: "",
                                                        step: 1,
                                                        weekNumbers: false
                                                    });
                                                    Calendar.setup({
                                                        inputField: "edate" + pg_no + qn_no,
                                                        onClose: function (cal) {
                                                            cal.hide();
                                                        },
                                                        form: "EditView",
                                                        ifFormat: formet,
                                                        daFormat: formet,
                                                        button: "enddate" + pg_no + qn_no,
                                                        singleClick: true,
                                                        dateStr: "",
                                                        startWeekday: "",
                                                        step: 1,
                                                        weekNumbers: false
                                                    });
                                                }
                                            });
                                        }
                                    }
                                    $("#last_que_no" + (pageValue.page_number - 1)).val(parseInt(queIndex) + 1);
                                    $("div#page_" + (pageValue.page_number - 1)).find("ul#question_table" + (pageValue.page_number - 1) + (queIndex - 1)).find('#que_id' + (pageValue.page_number - 1) + (queIndex - 1)).attr('value', queValue['que_id']);
                                    $("div#page_" + (pageValue.page_number - 1)).find("ul#question_table" + (pageValue.page_number - 1) + (queIndex - 1)).find('#que_id_del' + (pageValue.page_number - 1) + (queIndex - 1)).attr('value', queValue['que_id']);
                                }
                            });
                        }
                        $("#last_page_no").val(parseInt(pageIndex) + 1);
                        $("div#page_" + (pageValue.page_number - 1)).find('#page_id' + (pageValue.page_number - 1)).attr("value", jsonToString[pageIndex]['page_id']);
                        $("div#page_" + (pageValue.page_number - 1)).find('#page_id_del' + (pageValue.page_number - 1)).attr("value", jsonToString[pageIndex]['page_id']);
                        $("div#page_" + (pageValue.page_number - 1)).find('.page_number' + (pageValue.page_number - 1)).attr("id", jsonToString[pageIndex]['page_id']);
                    }
                });
                //For Existing Pages making question draggable
                $('.add-page-title').droppable({
                    accept: '.Textbox,.CommentTextbox,.MultiSelectList,.Checkbox,.DrodownList,.RadioButton,.ContactInformation,.Rating, .Matrix,.Scale,.Image,.Video,.Date',
                    hoverClass: "droppable-hover",
                    drop: function (e, ui) {

                        $(this).addClass(ui.draggable);
                        $d = $(ui.draggable).clone();
                        var current_drop = $d.attr('class'); // class of current dropped element
                        //make call for add question into page
                        var value = $(this).attr("id")
                        var page_no_value = Number(value.substring(5));
                        var que_no = Number($(this).find('input[name="que_no"]').val());
                        $(this).find('input[name="que_no"]').val(que_no + 1);
                        data = addQue(current_drop, page_no_value, que_no);
                        //display forms on pages
                        $(this).find('.add-que').before(data);

                        if (current_drop == "Date ui-draggable") {
                            $.ajax({
                                url: 'index.php',
                                data: {
                                    module: 'bc_survey',
                                    action: 'currentdateformat',
                                },
                                success: function (data, textStatus, jqXHR) {
                                    var formet = trim(data);
                                    Calendar.setup({
                                        inputField: "stdate" + page_no_value + que_no,
                                        onClose: function (cal) {
                                            cal.hide();
                                        },
                                        form: "EditView",
                                        ifFormat: formet,
                                        daFormat: formet,
                                        button: "startdate" + page_no_value + que_no,
                                        singleClick: true,
                                        dateStr: "",
                                        startWeekday: "",
                                        step: 1,
                                        weekNumbers: false
                                    });
                                    Calendar.setup({
                                        inputField: "edate" + page_no_value + que_no,
                                        onClose: function (cal) {
                                            cal.hide();
                                        },
                                        form: "EditView",
                                        ifFormat: formet,
                                        daFormat: formet,
                                        button: "enddate" + page_no_value + que_no,
                                        singleClick: true,
                                        dateStr: "",
                                        startWeekday: "",
                                        step: 1,
                                        weekNumbers: false
                                    });
                                }
                            });
                        }
                    }
                }).sortable({
                    cancel: ".add-pages,h4,input,textarea,select,i,.pagetitle,.add-que",
                    placeholder: 'placeholder-question',
                    items: "> div:not(.pagetitle,h4,textarea,input,i,.add-que)",
                    axis: "Y",
                    cursor: "move",
                    stop: function (event, ui) {

                        var isTwiceSection = false;
                        var sectionCounter = 0;
                        var lastElement = '';
                        // check recursively for question section applied only once for the question
                        $.each($(ui.item).parents('.add-page-title').find('.survey-body'), function () {
                            if ($(this).hasClass('section_title')) {
                                sectionCounter++;
                                if (sectionCounter == 2)
                                {
                                    isTwiceSection = true;
                                }
                                lastElement = 'section_title';
                            } else {
                                sectionCounter = 0;
                                lastElement = 'survey-body';
                            }
                        });
                        // check last element must be a question not a  question section
                        if (lastElement == 'section_title')
                        {
                            isTwiceSection = true;
                        }
                        if (($(ui.item).hasClass('section_title') && ($(ui.item).prev().hasClass('section_title') || $(ui.item).next().hasClass('section_title') || $(ui.item).next().attr('id') == 'placeholder')) || isTwiceSection) {

                            $(this).sortable('cancel');
                            alert('Section Header Block already exists or Section Header Block misplaced by this sorting. You can not drop section here.');
                        }
                    }
                });
                //for pages and questions drag and drop in edit view
                $(".add-pages").droppable({
                    accept: '.new-page',
                    hoverClass: "droppable-hover",
                    drop: function (e, ui) {

                        $(this).addClass(ui.draggable);

                        var page = Number($('#last_page_no').val()) + 1;
                        $(this).before(addNewPage(page.survey_type));
                        $('#last_page_no').val(page);
                        $('.add-page-title').droppable({
                            accept: '.Textbox,.CommentTextbox,.MultiSelectList,.Checkbox,.DrodownList,.RadioButton,.ContactInformation,.Rating,.Video,.Matrix,.Scale,.Image,.Date',
                            hoverClass: "droppable-hover",
                            drop: function (e, ui) {
                                $(this).addClass(ui.draggable);
                                $d = $(ui.draggable).clone();
                                var current_drop = $d.attr('class'); // class of current dropped element
                                //make call for add question into page
                                var value = $(this).attr("id");
                                var page_no_value = Number(value.substring(5));
                                var que_no = Number($(this).find('input[name="que_no"]').val());
                                $(this).find('input[name="que_no"]').val(que_no + 1);
                                data = addQue(current_drop, page_no_value, que_no);
                                //display forms on questions
                                $(this).find('.add-que').before(data);

                                if (current_drop == "Date ui-draggable") {
                                    $.ajax({
                                        url: 'index.php',
                                        data: {
                                            module: 'bc_survey',
                                            action: 'currentdateformat',
                                        },
                                        success: function (data, textStatus, jqXHR) {
                                            var formet = trim(data);
                                            Calendar.setup({
                                                inputField: "stdate" + page_no_value + que_no,
                                                onClose: function (cal) {
                                                    cal.hide();
                                                },
                                                form: "EditView",
                                                ifFormat: formet,
                                                daFormat: formet,
                                                button: "startdate" + page_no_value + que_no,
                                                singleClick: true,
                                                dateStr: "",
                                                startWeekday: "",
                                                step: 1,
                                                weekNumbers: false
                                            });
                                            Calendar.setup({
                                                inputField: "edate" + page_no_value + que_no,
                                                onClose: function (cal) {
                                                    cal.hide();
                                                },
                                                form: "EditView",
                                                ifFormat: formet,
                                                daFormat: formet,
                                                button: "enddate" + page_no_value + que_no,
                                                singleClick: true,
                                                dateStr: "",
                                                startWeekday: "",
                                                step: 1,
                                                weekNumbers: false
                                            });
                                        }
                                    });
                                }
                            }
                        }).sortable({
                            cancel: ".add-pages,h4,textarea,select,input,i,.pagetitle,.add-que",
                            placeholder: 'placeholder-question',
                            items: "> div:not(.pagetitle,h4,textarea,input,i,.add-que)",
                            axis: "Y",
                            cursor: "move",
                            stop: function (event, ui) {

                                var isTwiceSection = false;
                                var sectionCounter = 0;
                                var lastElement = '';
                                // check recursively for question section applied only once for the question
                                $.each($(ui.item).parents('.add-page-title').find('.survey-body'), function () {
                                    if ($(this).hasClass('section_title')) {
                                        sectionCounter++;
                                        if (sectionCounter == 2)
                                        {
                                            isTwiceSection = true;
                                        }
                                        lastElement = 'section_title';
                                    } else {
                                        sectionCounter = 0;
                                        lastElement = 'survey-body';
                                    }
                                });
                                // check last element must be a question not a  question section
                                if (lastElement == 'section_title')
                                {
                                    isTwiceSection = true;
                                }
                                if (($(ui.item).hasClass('section_title') && ($(ui.item).prev().hasClass('section_title') || $(ui.item).next().hasClass('section_title') || $(ui.item).next().attr('id') == 'placeholder')) || isTwiceSection) {

                                    $(this).sortable('cancel');
                                    alert('Section Header Block already exists or Section Header Block misplaced by this sorting. You can not drop section here.');
                                }
                            }
                        });
                    }
                }).sortable({
                    cancel: ".add-pages,select,textarea,h4,input,i,.pagetitle,.add-que",
                    placeholder: "placeholder-pages",
                    axis: "Y",
                    cursor: "move",
                    sort: function (event, ui) {
                        $(ui.helper).find('#page_title').css({width: '721.059px', height: '16.667px'});
                    },
                    stop: function (event, ui) {

                        var isTwiceSection = false;
                        var sectionCounter = 0;
                        var lastElement = '';
                        // check recursively for question section applied only once for the question
                        $.each($(ui.item).parents('.add-page-title').find('.survey-body'), function () {
                            if ($(this).hasClass('section_title')) {
                                sectionCounter++;
                                if (sectionCounter == 2)
                                {
                                    isTwiceSection = true;
                                }
                                lastElement = 'section_title';
                            } else {
                                sectionCounter = 0;
                                lastElement = 'survey-body';
                            }
                        });
                        // check last element must be a question not a  question section
                        if (lastElement == 'section_title')
                        {
                            isTwiceSection = true;
                        }
                        if (($(ui.item).hasClass('section_title') && ($(ui.item).prev().hasClass('section_title') || $(ui.item).next().hasClass('section_title') || $(ui.item).next().attr('id') == 'placeholder')) || isTwiceSection) {

                            $(this).sortable('cancel');
                            alert('Section Header Block already exists or Section Header Block misplaced by this sorting. You can not drop section here.');
                        }
                    }
                });
            },
            complete: function (jqXHR, textStatus) {
                $('#loading-image').hide();
                $(".survey-form-body").css("opacity", 1);
            }
        });
    }

//make for drag question and pages

    $('.new-page,.Textbox,.CommentTextbox,.MultiSelectList,.Checkbox,.DrodownList,.RadioButton,.ContactInformation,.Rating,.Video,.Matrix,.Scale,.Image,.Date').draggable({
        revert: "invalid",
        helper: function (event) {
            return $(event.target).clone().css({
                width: $(event.target).width()
            });
        },
    });
    //only for creating new record then load default page
    if (template_id == 'null') {
        if (record_id == '') {
//getting value for last page no from hidden field
            var page = Number($('#last_page_no').val());
            //defalt page add
            $(".add-pages").before(addNewPage(page, survey_type));
        }
    }

//make droppable for first page
    $('.add-page-title').droppable({
        accept: '.Textbox,.CommentTextbox,.MultiSelectList,.Checkbox,.DrodownList,.RadioButton,.ContactInformation,.Rating,.Video,.Matrix,.Scale,.Image,.Date',
        hoverClass: "droppable-hover",
        drop: function (e, ui) {
            var value = $(this).attr("id")
            var page_no_value = Number(value.substring(5)); //getting page counter value
            var que_no = Number($(this).find('input[name="que_no"]').val()); //getting value of que no from hidden field
            $(this).find('input[name="que_no"]').val(que_no + 1);
            $(this).addClass(ui.draggable);
            $d = $(ui.draggable).clone();
            var current_drop = $d.attr('class'); // class of current dropped element
            data = addQue(current_drop, page_no_value, que_no);
            //display forms on pages
            $(this).find('.add-que').before(data);

            if (current_drop == "Date ui-draggable") {
                $.ajax({
                    url: 'index.php',
                    data: {
                        module: 'bc_survey',
                        action: 'currentdateformat',
                    },
                    success: function (data, textStatus, jqXHR) {
                        var formet = trim(data);
                        Calendar.setup({
                            inputField: "stdate" + page_no_value + que_no,
                            onClose: function (cal) {
                                cal.hide();
                            },
                            form: "EditView",
                            ifFormat: formet,
                            daFormat: formet,
                            button: "startdate" + page_no_value + que_no,
                            singleClick: true,
                            dateStr: "",
                            startWeekday: "",
                            step: 1,
                            weekNumbers: false
                        });
                        Calendar.setup({
                            inputField: "edate" + page_no_value + que_no,
                            onClose: function (cal) {
                                cal.hide();
                            },
                            form: "EditView",
                            ifFormat: formet,
                            daFormat: formet,
                            button: "enddate" + page_no_value + que_no,
                            singleClick: true,
                            dateStr: "",
                            startWeekday: "",
                            step: 1,
                            weekNumbers: false
                        });
                    }
                });
            }
        }
    }).sortable({
        cancel: ".add-pages,h4,select,textarea,input,i,.pagetitle,.add-que",
        placeholder: 'placeholder-question',
        items: "> div:not(.pagetitle,h4,input,textarea,i,.add-que)",
        axis: "Y",
        cursor: "move",
        stop: function (event, ui) {

            var isTwiceSection = false;
            var sectionCounter = 0;
            var lastElement = '';
            // check recursively for question section applied only once for the question
            $.each($(ui.item).parents('.add-page-title').find('.survey-body'), function () {
                if ($(this).hasClass('section_title')) {
                    sectionCounter++;
                    if (sectionCounter == 2)
                    {
                        isTwiceSection = true;
                    }
                    lastElement = 'section_title';
                } else {
                    sectionCounter = 0;
                    lastElement = 'survey-body';
                }
            });
            // check last element must be a question not a  question section
            if (lastElement == 'section_title')
            {
                isTwiceSection = true;
            }
            if (($(ui.item).hasClass('section_title') && ($(ui.item).prev().hasClass('section_title') || $(ui.item).next().hasClass('section_title') || $(ui.item).next().attr('id') == 'placeholder')) || isTwiceSection) {

                $(this).sortable('cancel');
                alert('Section Header Block already exists or Section Header Block misplaced by this sorting. You can not drop section here.');
            }
        }
    });
    //dragging new page
    $(".add-pages").droppable({
        accept: '.new-page',
        hoverClass: "droppable-hover",
        drop: function (e, ui) {

            $(this).addClass(ui.draggable);
            var page = Number($('#last_page_no').val());
            //default page add
            $(this).before(addNewPage(page + 1, survey_type));
            $('#last_page_no').val(page + 1);
            //after the default page dragging questions
            $('.add-page-title').droppable({
                accept: '.Textbox,.CommentTextbox,.MultiSelectList,.Checkbox,.DrodownList,.RadioButton,.ContactInformation,.Rating,.Video,.Matrix,.Scale,.Image,.Date',
                hoverClass: "droppable-hover",
                drop: function (e, ui) {

                    $(this).addClass(ui.draggable);
                    $d = $(ui.draggable).clone();
                    var current_drop = $d.attr('class'); // class of current dropped element
                    //make call for add question into page
                    var value = $(this).attr("id")
                    var page_no_value = Number(value.substring(5));
                    var que_no = Number($(this).find('input[name="que_no"]').val());
                    $(this).find('input[name="que_no"]').val(que_no + 1);
                    data = addQue(current_drop, page_no_value, que_no);
                    //display forms on pages
                    $(this).find('.add-que').before(data);
                    if (current_drop == "Date ui-draggable") {
                        $.ajax({
                            url: 'index.php',
                            data: {
                                module: 'bc_survey',
                                action: 'currentdateformat',
                            },
                            success: function (data, textStatus, jqXHR) {
                                var formet = trim(data);
                                Calendar.setup({
                                    inputField: "stdate" + page_no_value + que_no,
                                    onClose: function (cal) {
                                        cal.hide();
                                    },
                                    form: "EditView",
                                    ifFormat: formet,
                                    daFormat: formet,
                                    button: "startdate" + page_no_value + que_no,
                                    singleClick: true,
                                    dateStr: "",
                                    startWeekday: "",
                                    step: 1,
                                    weekNumbers: false
                                });
                                Calendar.setup({
                                    inputField: "edate" + page_no_value + que_no,
                                    onClose: function (cal) {
                                        cal.hide();
                                    },
                                    form: "EditView",
                                    ifFormat: formet,
                                    daFormat: formet,
                                    button: "enddate" + page_no_value + que_no,
                                    singleClick: true,
                                    dateStr: "",
                                    startWeekday: "",
                                    step: 1,
                                    weekNumbers: false
                                });
                            }
                        });
                    }
                }
            }).sortable({
                cancel: ".add-pages,h4,textarea,select,input,i,.pagetitle,.add-que",
                placeholder: 'placeholder-question',
                items: "> div:not(.pagetitle,h4,input,textarea,i,.add-que)",
                axis: "Y",
                cursor: "move",
                stop: function (event, ui) {

                    var isTwiceSection = false;
                    var sectionCounter = 0;
                    var lastElement = '';
                    // check recursively for question section applied only once for the question
                    $.each($(ui.item).parents('.add-page-title').find('.survey-body'), function () {
                        if ($(this).hasClass('section_title')) {
                            sectionCounter++;
                            if (sectionCounter == 2)
                            {
                                isTwiceSection = true;
                            }
                            lastElement = 'section_title';
                        } else {
                            sectionCounter = 0;
                            lastElement = 'survey-body';
                        }
                    });
                    // check last element must be a question not a  question section
                    if (lastElement == 'section_title')
                    {
                        isTwiceSection = true;
                    }
                    if (($(ui.item).hasClass('section_title') && ($(ui.item).prev().hasClass('section_title') || $(ui.item).next().hasClass('section_title') || $(ui.item).next().attr('id') == 'placeholder')) || isTwiceSection) {

                        $(this).sortable('cancel');
                        alert('Section Header Block already exists or Section Header Block misplaced by this sorting. You can not drop section here.');
                    }
                }
            });
        },
    }).sortable({
        cancel: ".add-pages,h4,textarea,select,input,i,.pagetitle,.add-que",
        placeholder: "placeholder-pages",
        axis: "Y",
        cursor: "move",
        sort: function (event, ui) {
            $(ui.helper).find('#page_title').css({width: '721.059px', height: '16.667px'});
        }
    });
    //add new page when click on plus button
    $('#plus-image').click(function () {

        var no_page = $('.add-page-title:last');
        var page_no = Number($(no_page).attr('id').split('_')[1]);
        var page_html = addNewPage(page_no + 1, survey_type);
        $(no_page).after(page_html);

        if (record_module.record == '' && record_module.template_id == 'null') {
            $('.add-page-title').droppable({
                accept: '.Textbox,.CommentTextbox,.MultiSelectList,.Checkbox,.DrodownList,.RadioButton,.ContactInformation,.Rating,.Video,.Matrix,.Scale,.Image,.Date',
                hoverClass: "droppable-hover",
                drop: function (e, ui) {

                    $(this).addClass(ui.draggable);
                    $d = $(ui.draggable).clone();
                    var current_drop = $d.attr('class'); // class of current dropped element
                    //make call for add question into page
                    var value = $(this).attr("id")
                    var page_no_value = Number(value.substring(5));
                    var que_no = Number($(this).find('input[name="que_no"]').val());
                    $(this).find('input[name="que_no"]').val(que_no + 1);
                    data = addQue(current_drop, page_no_value, que_no);
                    //display forms on pages
                    $(this).find('.add-que').before(data);

                    if (current_drop == "Date ui-draggable") {
                        $.ajax({
                            url: 'index.php',
                            data: {
                                module: 'bc_survey',
                                action: 'currentdateformat',
                            },
                            success: function (data, textStatus, jqXHR) {
                                var formet = trim(data);
                                Calendar.setup({
                                    inputField: "stdate" + page_no_value + que_no,
                                    onClose: function (cal) {
                                        cal.hide();
                                    },
                                    form: "EditView",
                                    ifFormat: formet,
                                    daFormat: formet,
                                    button: "startdate" + page_no_value + que_no,
                                    singleClick: true,
                                    dateStr: "",
                                    startWeekday: "",
                                    step: 1,
                                    weekNumbers: false
                                });
                                Calendar.setup({
                                    inputField: "edate" + page_no_value + que_no,
                                    onClose: function (cal) {
                                        cal.hide();
                                    },
                                    form: "EditView",
                                    ifFormat: formet,
                                    daFormat: formet,
                                    button: "enddate" + page_no_value + que_no,
                                    singleClick: true,
                                    dateStr: "",
                                    startWeekday: "",
                                    step: 1,
                                    weekNumbers: false
                                });
                            }
                        });
                    }
                }
            }).sortable({
                cancel: ".add-pages,h4,textarea,select,input,i,.pagetitle,.add-que",
                placeholder: 'placeholder-question',
                items: "> div:not(.pagetitle,h4,input,textarea,i,.add-que)",
                axis: "Y",
                cursor: "move",
                stop: function (event, ui) {

                    var isTwiceSection = false;
                    var sectionCounter = 0;
                    var lastElement = '';
                    // check recursively for question section applied only once for the question
                    $.each($(ui.item).parents('.add-page-title').find('.survey-body'), function () {
                        if ($(this).hasClass('section_title')) {
                            sectionCounter++;
                            if (sectionCounter == 2)
                            {
                                isTwiceSection = true;
                            }
                            lastElement = 'section_title';
                        } else {
                            sectionCounter = 0;
                            lastElement = 'survey-body';
                        }
                    });
                    // check last element must be a question not a  question section
                    if (lastElement == 'section_title')
                    {
                        isTwiceSection = true;
                    }
                    if (($(ui.item).hasClass('section_title') && ($(ui.item).prev().hasClass('section_title') || $(ui.item).next().hasClass('section_title') || $(ui.item).next().attr('id') == 'placeholder')) || isTwiceSection) {

                        $(this).sortable('cancel');
                        alert('Section Header Block already exists or Section Header Block misplaced by this sorting. You can not drop section here.');
                    }
                }
            });
        } else {
            $('.add-page-title').droppable({
                accept: '.Textbox,.CommentTextbox,.MultiSelectList,.Checkbox,.DrodownList,.RadioButton,.ContactInformation,.Rating,.Video,.Matrix,.Scale,.Image,.Date',
                hoverClass: "droppable-hover",
                drop: function (e, ui) {

                    $(this).addClass(ui.draggable);
                    $d = $(ui.draggable).clone();
                    var current_drop = $d.attr('class'); // class of current dropped element
                    //make call for add question into page
                    var value = $(this).attr("id")
                    var page_no_value = Number(value.substring(5));
                    var que_no = Number($(this).find('input[name="que_no"]').val());
                    $(this).find('input[name="que_no"]').val(que_no + 1);
                    data = addQue(current_drop, page_no_value, que_no);
                    //display forms on pages
                    $(this).find('.add-que').before(data);

                    if (current_drop == "Date ui-draggable") {
                        $.ajax({
                            url: 'index.php',
                            data: {
                                module: 'bc_survey',
                                action: 'currentdateformat',
                            },
                            success: function (data, textStatus, jqXHR) {
                                var formet = trim(data);
                                Calendar.setup({
                                    inputField: "stdate" + page_no_value + que_no,
                                    onClose: function (cal) {
                                        cal.hide();
                                    },
                                    form: "EditView",
                                    ifFormat: formet,
                                    daFormat: formet,
                                    button: "startdate" + page_no_value + que_no,
                                    singleClick: true,
                                    dateStr: "",
                                    startWeekday: "",
                                    step: 1,
                                    weekNumbers: false
                                });
                                Calendar.setup({
                                    inputField: "edate" + page_no_value + que_no,
                                    onClose: function (cal) {
                                        cal.hide();
                                    },
                                    form: "EditView",
                                    ifFormat: formet,
                                    daFormat: formet,
                                    button: "enddate" + page_no_value + que_no,
                                    singleClick: true,
                                    dateStr: "",
                                    startWeekday: "",
                                    step: 1,
                                    weekNumbers: false
                                });
                            }
                        });
                    }
                }
            }).sortable({
                cancel: ".add-pages,textarea,select,h4,input,i,.pagetitle,.add-que",
                placeholder: 'placeholder-question',
                items: "> div:not(.pagetitle,h4,input,textarea,i,.add-que)",
                axis: "Y",
                cursor: "move",
                stop: function (event, ui) {

                    var isTwiceSection = false;
                    var sectionCounter = 0;
                    var lastElement = '';
                    // check recursively for question section applied only once for the question
                    $.each($(ui.item).parents('.add-page-title').find('.survey-body'), function () {
                        if ($(this).hasClass('section_title')) {
                            sectionCounter++;
                            if (sectionCounter == 2)
                            {
                                isTwiceSection = true;
                            }
                            lastElement = 'section_title';
                        } else {
                            sectionCounter = 0;
                            lastElement = 'survey-body';
                        }
                    });
                    // check last element must be a question not a  question section
                    if (lastElement == 'section_title')
                    {
                        isTwiceSection = true;
                    }
                    if (($(ui.item).hasClass('section_title') && ($(ui.item).prev().hasClass('section_title') || $(ui.item).next().hasClass('section_title') || $(ui.item).next().attr('id') == 'placeholder')) || isTwiceSection) {
                        $(this).sortable('cancel');
                        alert('Section Header Block already exists or Section Header Block misplaced by this sorting. You can not drop section here.');
                    }
                }
            });
        }
    });
});