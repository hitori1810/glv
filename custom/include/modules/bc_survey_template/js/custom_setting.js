/**
 * 
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
$(document).ready(function () {
    $('#addpage').click(function () {
        var page = parseInt($('#last_page_no').val());
        //  var que = $('#last_que_no_'+ page).val();
        var que = 0;
        addPageSection(page, que);
        $('#last_page_no').val(page + 1);
    });
});

function addPageSection(page_no, que_no) {
    $.ajax({
        url: "index.php?module=bc_survey_template&action=createPage",
        data: "page_number=" + page_no + "&que_number=" + que_no,
        success: function (result) {
            $("#EditView_tabs").append(result);
        }
    });
    // $('#last_ans_no'+que_no+page_no).val(0);
}

function remove_page(selector) {
    if (confirm("Are you sure you want to delete this page?") == true) {
        if ($('#page_deleted').val()) {
            $('#page_deleted').val($('#page_deleted').val() + ',' + $(selector).closest('div').parent('div').next('div').find('#page_id_del').val());
        } else {
            $('#page_deleted').val($(selector).closest('div').parent('div').next('div').find('#page_id_del').val());
        }
        $(selector).closest("div").parent("div").parent("div").parent("div").remove();
    }
}

function addQuestion(page, currentEle) {
    var que_no = parseInt($('#last_que_no_' + page).val()) + 1;
    var que_html = "<ul id='question_table" + que_no + "' class='qt_table'>\n\
        <li class='s-title'><label>Question<em>*</em></label><span><input type='text' name='que_title[" + page + "][" + que_no + "]' class='que_title' id='que_title" + page + que_no + "'></span> </li>\n\
        <li class='s-title'><label>Help Tips</label><span><input type='text' name='question_help_comment[" + page + "][" + que_no + "]' class='question_help_comment' id='question_help_comment" + page + que_no + "'></span> </li>\n\
        <li class='s-type'><label>Answer Type</label><span><select onfocus='javascript:window.selectedType = this.value;' class='input-text' name='que_type[" + page + "][" + que_no + "]' onchange='displayAnsOption(" + page + "," + que_no + ",this,window.selectedType);'><option value='Textbox'>Textbox</option><option value='CommentTextbox'>Comment Textbox</option>\n\
        <option value='Checkbox'>Checkbox</option><option value='RadioButton'>Radio Button</option><option value='DrodownList'> DrodownList </option>\n\
        <option value='MultiSelectList'>MultiSelectList</option><option value='ContactInformation'>Contact Information</option><option value='Rating'>Rating</option></select></span></select><span></li>\n\
        <li class='s-required' ><label>Is required</label><span><input type='checkbox' name='is_required[" + page + "][" + que_no + "]' id='is_required'></span></li>\n\
        <li class='s-add-que'><label>Questions</label><span><a class='remove-btn-1" + page + " remove-btn-1-design' href='javascript:void(0)' id='remove_que' onclick='removeQuestion(this," + page + "," + que_no + ");' title='Remove Question'>Remove</a>\n\
        <a class='add-btn" + page + " add-btn-design' href='javascript:void(0)'  id='add_que'" + page + " onclick='addQuestion(" + page + ",this);' title='Add Question'>Add Question</a></span></li>\n\
        <li class='s-option'><ul id='answer_div" + page + que_no + "'></ul></li></ul>";
    $('#questionline' + page).append(que_html);
    $('#que_title' + page + que_no).focus();
    $(currentEle).closest('ul').find('.remove-btn-1' + page).css('display', 'block');
    $(currentEle).closest('ul').find(".add-btn" + page).remove();
    var ans_hidden = "<input type='hidden' id='last_ans_no_" + que_no + page + "' value='0'>";
    $('#EditView_tabs').append(ans_hidden);
    $('#last_que_no_' + page).val(que_no);
    // $('#last_ans_no').val(0);
}
function removeQuestion(currentEle, page_no, que_no) {
    if (confirm("Are you sure you want to delete this question?") == true) {
        if ($("#que_deleted").val()) {
            $("#que_deleted").val($("#que_deleted").val() + ',' + $(currentEle).closest('ul').find('#que_id_del').val());
        } else {
            $("#que_deleted").val($(currentEle).closest('ul').find('#que_id_del').val());
        }
        var addque_length = $('.add-btn' + page_no).length;
        if ($(currentEle).closest('ul').find('.add-btn' + page_no).length == 0) {
            $(currentEle).closest('ul').remove();
            if ($('#questionline' + page_no).children('ul').length == 1) {
                $('#questionline' + page_no).children('ul').find('.remove-btn-1' + page_no).hide();
            }

        } else if (addque_length == 1) {
            var html = "<label>Questions</label><span><a class='remove-btn-1" + page_no + " remove-btn-1-design' href='javascript:void(0)' id='remove_que' onclick='removeQuestion(this," + page_no + "," + que_no + ");' title='Remove Question'>Remove</a>\n\
<a class='add-btn" + page_no + " add-btn-design' href='javascript:void(0)'  id='add_que'" + page_no + " onclick='addQuestion(" + page_no + ",this);' style='visible:visible' title='Add Question'>Add Question</a></span>";
            $(currentEle).closest('ul').prev('ul').children('.s-add-que').html(html);
            if ($(currentEle).closest('ul').prev('ul').attr('class') == 'qt_table1' && $('.qt_table').length == 1) {
                $(currentEle).closest('ul').prev('ul').find('.remove-btn-1' + page_no).hide();
            }
            $(currentEle).closest('ul').remove();
            if ($('#questionline' + page_no).children('ul').length == 1) {
                $('#questionline' + page_no).children('ul').find('.remove-btn-1' + page_no).hide();
            }

        }
    }
}

function displayAnsOption(page_no, que_no, element, prev) {
    if (confirm("Are you sure you want to change Question-type?") == true) {
        var ans_no = $('#last_ans_no_' + que_no + page_no).val();
        //var count = 1;
        var answer_options = "<li id='ans_op" + page_no + que_no + ans_no + "' class='ans_op_class" + page_no + que_no + "'><div class='f-left'><label>Option </label><span><input type='text' name='answer[" + page_no + "][" + que_no + "][" + ans_no + "]'></span></div>\n\
    <div class='f-right'><a class='add-ans-btn" + page_no + " add-btn-design' href='javascript:void(0)'  id='add_option" + page_no + que_no + "' onclick='addAnswerOption(" + page_no + "," + que_no + ",this);' title='Add Answer option'>Add Option</a>\n\
    <a class='remove-ans-btn-1" + page_no + " remove-btn-1-design' href='javascript:void(0)' id='remove_option' onclick='removeAnsOption(this," + page_no + "," + que_no + ");' style='display:none' title='Remove Answer option'>Remove</a></div></li>\n\
";
        var type_array = ['Checkbox', 'RadioButton', 'DrodownList', 'MultiSelectList'];
        if ($.inArray($(element).val(), type_array) != -1) {
            $(element).closest('div').find('#answer_div' + page_no + que_no).html(answer_options);
        } else {
            $(element).closest('div').find('#answer_div' + page_no + que_no).html('');
            $("#ans_validate").remove();
        }
    } else {
        $(element).find('option[value="' + prev + '"]').attr('selected', 'selected');
    }

}

function addAnswerOption(page_no, que_no, currentEle) {
    var ans_no = parseInt($('#last_ans_no_' + que_no + page_no).val()) + 1;
    //var count = $("#answer_div" + page_no + que_no).children('tr').length + 1;
    var option_html = "<li id='ans_op" + page_no + que_no + ans_no + "' class='ans_op_class" + page_no + que_no + "'><div class='f-left'><label>Option </label><span><input type='text' name='answer[" + page_no + "][" + que_no + "][" + ans_no + "]' id='answer" + page_no + que_no + ans_no + "'></span></div>\n\
                       <div class='f-right'><a class='remove-ans-btn-1" + page_no + " remove-btn-1-design' href='javascript:void(0)' id='remove_option' onclick='removeAnsOption(this," + page_no + "," + que_no + ");' title='Remove Answer option'>Remove</a>&nbsp;\n\
                        <a class='add-ans-btn" + page_no + " add-btn-design' href='javascript:void(0)'  id='add_option" + page_no + que_no + "' onclick='addAnswerOption(" + page_no + "," + que_no + ",this);' title='Add Answer option'>Add Option</a></div></li>";
    $('#answer_div' + page_no + que_no + ' ' + 'li:last').after(option_html);
    $('#answer' + page_no + que_no + ans_no).focus();
    $(currentEle).closest('li').find('.add-ans-btn' + page_no).hide();
    $(currentEle).closest('li').find('.remove-ans-btn-1' + page_no).show();
    $("#last_ans_no_" + que_no + page_no).val(ans_no);
}

function removeAnsOption(currentEle, page_no, que_no) {
    if (confirm("Are you sure you want to delete this answer option?") == true) {
        if ($("#ans_deleted").val()) {
            $("#ans_deleted").val($("#ans_deleted").val() + ',' + $(currentEle).closest('li').find('#ans_id_del').val());
        } else {
            $("#ans_deleted").val($(currentEle).closest('li').find('#ans_id_del').val());
        }
        if ($('.ans_op_class' + page_no + que_no).length == 2) {
            $(currentEle).closest('li').prev('li').find('.remove-ans-btn-1' + page_no).hide();
            $(currentEle).closest('li').prev('li').find('.add-ans-btn' + page_no).show();
        }
        if ($(currentEle).closest('li').find('.add-ans-btn' + page_no + ':hidden').length == 1) {
            $(currentEle).closest('li').remove();
        } else {
            $(currentEle).closest('li').prev('li').find('.add-ans-btn' + page_no).show();
        }
        $(currentEle).closest('li').remove();
        if ($('#answer_div' + page_no + que_no).children('li').length == 1) {
            $('#answer_div' + page_no + que_no).children('li').find('.remove-ans-btn-1' + page_no).hide();
        }
    }
}

function createSurvey(template_id, site_url) {
    window.location = "index.php?module=bc_survey&action=EditView&return_module=bc_survey&return_action=DetailView&template_id=" + template_id;
}

function cancelSurveyEdit(survey_id, module_name) {
    if (confirm("Are you sure you want to cancel?") == true) {
        SUGAR.ajaxUI.loadContent('index.php?action=DetailView&module=' + module_name + '&record=' + survey_id);
        return false;
    }
}

function changeDateFormat(date, dateFormat) {
    var d, m, y, dateArray;
    switch (dateFormat) {
        case 'Y-m-d':
            dateArray = date.split("-")
            d = dateArray[2];
            m = dateArray[1];
            y = dateArray[0];
            break;
        case 'm-d-Y':
            dateArray = date.split("-")
            d = dateArray[1];
            m = dateArray[0];
            y = dateArray[2];
            break;
        case 'd-m-Y':
            dateArray = date.split("-")
            d = dateArray[0];
            m = dateArray[1];
            y = dateArray[2];
            break;
        case 'Y/m/d':
            dateArray = date.split("/")
            d = dateArray[2];
            m = dateArray[1];
            y = dateArray[0];
            break;
        case 'm/d/Y':
            dateArray = date.split("/")
            d = dateArray[1];
            m = dateArray[0];
            y = dateArray[2];
            break;
        case 'd/m/Y':
            dateArray = date.split("/")
            d = dateArray[0];
            m = dateArray[1];
            y = dateArray[2];
            break;
        case 'Y.m.d':
            dateArray = date.split(".")
            d = dateArray[2];
            m = dateArray[1];
            y = dateArray[0];
            break;
        case 'd.m.Y':
            dateArray = date.split(".")
            d = dateArray[0];
            m = dateArray[1];
            y = dateArray[2];
            break;
        case 'm.d.Y':
            dateArray = date.split(".")
            d = dateArray[1];
            m = dateArray[0];
            y = dateArray[2];
            break;
    }
    return moment(y + '-' + m + '-' + d).format('YYYY/MM/DD');
}

function convertTo24Hour(time) {
    var hrs = Number(time.match(/^(\d+)/)[1]);
    var mnts = Number(time.match(/:(\d+)/)[1]);
    var format = time.match(/(am|pm){1,1}/gi);
    if (format == "pm" && hrs < 12)
        hrs = hrs + 12;
    if (format == "am" && hrs == 12)
        hrs = hrs - 12;
    var hours = hrs.toString();
    var minutes = mnts.toString();
    if (hrs < 10)
        hours = "0" + hours;
    if (mnts < 10)
        minutes = "0" + minutes;
    return (hours + ":" + minutes);
}