/**
 * Validation On Question , page and option
 * 
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */

/**
 * Validate Start Date And End Date
 *
 * @author     Original Author Biztech Co.
 * @param      date - start_date,end_date
 * @return     bool
 */
function checkDateValidation(start_date, end_date) {
    if ((start_date == "" && end_date != "") || (end_date == "" && start_date != "") || (start_date != "" && end_date != "" && start_date != undefined && end_date != undefined)) {
        var startformattedDate = new Date(start_date);
        var endformattedDate = new Date(end_date);
        var currentDate = new Date(currentdate);
    
        var s_date = startformattedDate.getDate();
        var s_month = startformattedDate.getMonth() + 1;
        var s_year = startformattedDate.getFullYear();
        var stdate = s_month + '/' + s_date + '/' + s_year;

        var e_date = endformattedDate.getDate();
        var e_month = endformattedDate.getMonth() + 1;
        var e_year = endformattedDate.getFullYear();
        var eddate = e_month + '/' + e_date + '/' + e_year;

        var c_date = currentDate.getDate();
        var c_month = currentDate.getMonth() + 1;
        var c_year = currentDate.getFullYear();
        var curdate = c_month + '/' + c_date + '/' + c_year;

        var flag = true;
        var st_date = Date.parse(stdate);
        var ed_date = Date.parse(eddate);
        var current_date = Date.parse(curdate);
        if (current_date > st_date) {
                if ($('#start_date_time_section').find('#validate_start_date').length == 0) {
                    $('#start_date_time_section').append("<span id='validate_start_date' class='error-msg'>&nbsp;&nbsp;Start date can not be less then today's date</span>");
                }
                flag = false;
            } else {
                $('#start_date_time_section').find('#validate_start_date').remove();
            }
        if (st_date > ed_date) {
                if ($('#end_date_time_section').find('#validate_start_date').length == 0) {
                    $('#end_date_time_section').append("<span id='validate_start_date' class='error-msg'>&nbsp;&nbsp;End date can not be less then Start date</span>");
            } else {
                $('#end_date_time_section').find('#validate_start_date').html("End date can not be less then Start date");
                }
                flag = false;
        } else if (current_date > ed_date) {
            if ($('#end_date_time_section').find('#validate_start_date').length == 0) {
                $('#end_date_time_section').append("<span id='validate_start_date' class='error-msg'>&nbsp;&nbsp;End date can not be less then today's date</span>");
            } else {
                $('#end_date_time_section').find('#validate_start_date').html("End date can not be less then today's date");
            }
            flag = false;
        } else {
                $('#end_date_time_section').find('#validate_start_date').remove();
            }
        }
    return flag;
}
function required_fieldsvalidation() {
    var flag = true;
    var _form = document.getElementById('EditView');
    _form.action.value = 'Save';
    flag = validate();
    var sync_fields = new Array();
    $.each($('.fields_type'), function (indx, value) {
        sync_fields[indx] = this.value;
    });
    var module_name = $('#sync_module').val();
    $.ajax({
        url: 'index.php',
        data: {
            module: 'bc_survey_questions',
            action: 'get_requiredfields',
            module_name: module_name,
        },
        success: function (result) {
            var required_field = JSON.parse(result);
            var count_required = 0;
            var field_name = new Array();
            if ($('#enable_data_piping:checked').length) {
            $.each(required_field, function (indx, value) {
                if ($.inArray(indx, sync_fields) == -1) {
                    field_name[count_required] = value['label'];
                    count_required += 1;
                }
            });
            }

            if ($('#enable_data_piping:checked').length) {
                if (count_required != 0) {
                    alert('Mandatory fields are not included in the survey. Please add madatory fields from the selected module : ' + field_name.join(','));
                    flag = false;
                }
            }
            var count_selected = 0;
            if ($('#enable_data_piping:checked').length) {
            $.each(required_field, function (indx, value) {
                if ($('.fields_type option[value=' + indx + ']:selected').length) {
                    if ($('.fields_type option[value=' + indx + ']:selected').parent().parent().parent().parent().parent().find('.s-required').find('#is_required:checked').length == 0) {
                        count_selected = 1
                    }
                }
            });
            if (count_selected != 0) {
                if (confirm('These are a required field, click "ok" to make these question(s) required.')) {
                    $.each(required_field, function (indx, value) {
                        $('.fields_type option[value=' + indx + ']:selected').parent().parent().parent().parent().parent().find('.s-required').find('#is_required').attr('checked', true);
                    });
                }
                flag = false;
            }
            }
            if (!flag) {
                return false;
            }
            else {
                 SUGAR.ajaxUI.submitForm(_form);
            }
        }
    });
}
/**
 * Validation At Admin Side
 *
 * @author     Original Author Biztech Co.
 * @param      String - el
 */
function validate() {
    var flag = true;
    $('.validation_error').remove();
    var start_date = $('#start_date_date').val();
    var end_date = $('#end_date_date').val();
    var flag_value = checkDateValidation(start_date, end_date);
    var que_type_array = ["Checkbox", "RadioButton", "DrodownList", "MultiSelectList", "Matrix"];
    flag = check_form("EditView");
    if (flag_value == false) {
        flag = false;
    }
    var re = /((((ht|f)tps?:\/\/)?[^\/\s]+\.(com|tv|co))(\/\S*)?)/;
    if ($('#redirect_url').val()) {
        if (($('#redirect_url').val() != "http://") && !re.test($('#redirect_url').val())) {
            if ($('#redirect_url').parent().find('.validation_error').length == 0) {
                $('#redirect_url').after("<span class='validation_error' style='color: red;font-size:12px;margin-top: 8px;display: inline-block;'>Error. Invalid URL. It should be in i.e. http://www.google.com format</span>")
            }
            flag = false;
        }
    }
    for (var tr = 0; tr < $('.add-page-title').length; tr++) {
        var page_title = $(":input[name^='page_title']");
        var page_questions = $(".que_title");
        var img_question = $(".file_upload");

        for (var i = 0; i < img_question.length; i++) {
            $(img_question[i]).parent().parent().parent().parent().prev().find('.gen').css("color", "#000");
            var question_type = $(img_question[i]).parent().parent().parent().find('.s-type').find('input[type=hidden]').val();
            var image = $(img_question[i]).parent().parent().parent().find('input:checked').val();
            if (image == "upload") {
                if (!$(img_question[i]).parent().parent().find('.src').find('input[type=hidden]').val()) {
                    if (!$(img_question[i]).parent().parent().find(".file_upload").val()) {
                        $(img_question[i]).parent().parent().parent().parent().prev().find('.gen').css("color", "red");
                        if ($(img_question[i]).parent().parent().find(".file_upload").parent().find('.validation_error').length == 0) {
                            $(img_question[i]).parent().parent().find(".file_upload").after("<span class='validation_error' style='color: red;font-size:12px;margin-top: 8px;display: inline-block;'>Please upload Image.</span>");
                        }
                        flag = false;
                    } else {
                        $(img_question[i]).parent().parent().find(".file_upload").parent().find('.validation_error').remove();
                    }
                }
            } else {
                if (!$(img_question[i]).parent().parent().find('.file_url').val()) {
                    $(img_question[i]).parent().parent().parent().parent().prev().find('.gen').css("color", "red");
                    if ($(img_question[i]).parent().parent().find('.file_url').parent().find('.validation_err').length == 0) {
                        $(img_question[i]).parent().parent().find('.file_url').after("<span class='validation_err' style='color: red;font-size:12px;margin-top: 8px;display: inline-block;'>Please enter Url For Image.</span>");
                    }
                    flag = false;
                } else {
                    $(img_question[i]).parent().parent().find('.file_url').parent().find('.validation_err').remove();
                }
            }
            if (question_type == "Image") {
                if ($(img_question[i]).parent().parent().parent().parent().parent().find('.title').val() == "") {
                    $(img_question[i]).parent().parent().parent().parent().parent().find('.adv').css('color', 'red');
                    if ($(img_question[i]).parent().parent().parent().parent().parent().find('.title').parent().find('.validation_error').length == 0) {
                        $(img_question[i]).parent().parent().parent().parent().parent().find('.title').parent().append("<span class='validation_error' style='color: red;font-size:12px;margin-top: 8px;display: inline-block;'>Enter Valid Image Title.</span>");
                    }
                    flag = false;
                } else {
                    $(img_question[i]).parent().parent().parent().parent().prev().find('p').css('color', '#000');
                }
            }
        }
        var que_option = $('.answer-section');
        // For validate every page has at least one question
        var count_que = $('.add-page-title')[tr];
        var no_que = $(count_que).find('.survey-body').length;
        if (no_que < 1) {

            if ($(count_que).find('.validation_error_que').length == 0 && page_title[tr].value.trim() != '') {
                if ($(count_que).find('h4').next().next().attr('class') == "validation_error") {
                    $(count_que).find('h4').next().next().after("<span class='validation_error_que' style='color: red;font-size:12px;margin-top: 8px;display: inline-block;'>Please set at least one question of every page.</span>");
                }
                else
                {
                    $(count_que).append("<span class='validation_error_que' style='color: red;font-size:12px;margin-top: 8px;display: inline-block;'>Please set at least one question of every page.</span>");
                }
            }

            flag = false;
        }
        else {
            $(count_que).find('.validation_error_que').remove();
        }
        //For Page Title Validation
        for (var i = 0; i < page_title.length; i++) {
            if (page_title[tr].value.trim() == '') {
                var parentDiv = $(page_title[tr]).parent();
                if ($(parentDiv).find('.validation_error').length == 0) {

                    $(page_title[tr]).parent().append("<span class='validation_error' style='color: red;font-size:12px;margin-top: 8px;display: inline-block; margin-left: 84px;'>Please enter your page title.</span>")
                    $(page_title[tr]).nextAll('.validation_error_que').remove();
                }
                flag = false;
            }
            else {
                $(page_title[tr]).parent().find('.validation_error').remove();
            }
        }
        //For Question Title Validation       
        for (var i = 0; i < page_questions.length; i++) {
            var parentQue = page_questions[i];
            var que_type = $(page_questions[i]).parent().parent().parent().find('.s-type').find('span').eq(1).find('input').attr('value');
            var sec_type = $(page_questions[i]).parent().find('input[type=hidden]').val();
            if (que_type == "Textbox") {
                var dataytpe = $(page_questions[i]).parent().parent().parent().parent().next().find('.datatype-textbox').val();
                if (dataytpe == "Integer") {

                    var min = $(page_questions[i]).parent().parent().parent().parent().next().find('.minint').val();
                    var max = $(page_questions[i]).parent().parent().parent().parent().next().find('.maxint').val();
                    if (parseInt(min) > parseInt(max)) {
                        if ($(page_questions[i]).parent().parent().parent().parent().next().find('.validation_error').length == 0) {
                            $(page_questions[i]).parent().parent().parent().parent().next().find('.maxint').after("<span class='validation_error' style='color: red;font-size:12px;margin-top: 8px;display: inline-block;'>Maximum value Should be greater then Minimum value</span>");
                        }
                        flag = false;
                        $(page_questions[i]).parent().parent().parent().parent().prev().find('.adv').css('color', 'red');
                    } else {
                        $(page_questions[i]).parent().parent().parent().parent().prev().find('.adv').css('color', '#000');
                        $(page_questions[i]).parent().parent().parent().parent().next().find('.validation_error').remove();
                    }
                } else if (dataytpe == "Float") {

                    var min = $(page_questions[i]).parent().parent().parent().parent().next().find('.minfloat').val();
                    var max = $(page_questions[i]).parent().parent().parent().parent().next().find('.maxfloat').val();
                    if (parseFloat(min) >= parseFloat(max)) {
                        if ($(page_questions[i]).parent().parent().parent().parent().next().find('.validation_error').length == 0) {
                            $(page_questions[i]).parent().parent().parent().parent().next().find('.maxfloat').after("<span class='validation_error' style='color: red;font-size:12px;margin-top: 8px;display: inline-block;'>Maximum value Should be greater then Minimum value</span>");
                        }
                        flag = false;
                        $(page_questions[i]).parent().parent().parent().parent().prev().find('.adv').css('color', 'red');
                    } else {
                        $(page_questions[i]).parent().parent().parent().parent().prev().find('.adv').css('color', '#000');
                        $(page_questions[i]).parent().parent().parent().parent().next().find('.validation_error').remove();
                    }
                }
                if (parentQue.value.trim() == '') {
                    if ($(parentQue).parent().find('.validation_error').length == 0) {
                        $(page_questions[i]).after("<span class='validation_error' style='color: red;font-size:12px;margin-top: 8px;display: inline-block;'>Please enter your question.</span>");
                    }
                    flag = false;
                    $(page_questions[i]).parent().parent().parent().parent().prev().find('.gen').css("color", "red");
                }
                else {
                    $(page_questions[i]).parent().parent().parent().parent().prev().find('.gen').css("color", "#000");
                    $(parentQue).parent().find('.validation_error').remove();
                }
            }
            if (que_type == "Scale") {
                var start = parseInt($(page_questions[i]).parent().parent().parent().parent().next().find('.start').val());
                var end = parseInt($(page_questions[i]).parent().parent().parent().parent().next().find('.end').val());
                if (isNaN(start) || isNaN(end)) {
                    if ($(page_questions[i]).parent().parent().parent().parent().next().find('.validation_error').length == 0) {
                        $(page_questions[i]).parent().parent().parent().parent().next().find('.end').after("<span class='validation_error' style='color: red;font-size:12px;margin-top: 8px;display: inline-block;'>The End Value must be greate then Start Value</span>");
                    }
                    flag = false;
                    $(page_questions[i]).parent().parent().parent().parent().prev().find('.adv').css('color', 'red');
                } else {
                    if (start > end) {
                        if ($(page_questions[i]).parent().parent().parent().parent().next().find('.validation_error').length == 0) {
                            $(page_questions[i]).parent().parent().parent().parent().next().find('.end').after("<span class='validation_error' style='color: red;font-size:12px;margin-top: 8px;display: inline-block;'>The End Value must be greate then Start Value</span>");
                        }
                        $(page_questions[i]).parent().parent().parent().parent().prev().find('.adv').css('color', 'red');
                        flag = false;
                    } else {
                        $(page_questions[i]).parent().parent().parent().parent().prev().find('.adv').css('color', '#000');
                        $(page_questions[i]).parent().parent().parent().parent().next().find('.validation_error').remove();
                    }
                    var diff = end - start;
                    var step_value = parseInt($(page_questions[i]).parent().parent().parent().parent().next().find('.stepval').val());
                    if (step_value >= diff) {
                        if ($(page_questions[i]).parent().parent().parent().parent().next().find('.validation_err').length == 0) {
                            $(page_questions[i]).parent().parent().parent().parent().next().find('.stepval').after("<span class='validation_err' style='color: red;font-size:12px;margin-top: 8px;display: inline-block;'>You may have to set step value less than " + diff + "</span>");
                        }
                        flag = false;
                        $(page_questions[i]).parent().parent().parent().parent().prev().find('.adv').css('color', 'red');
                    } else {
                        $(page_questions[i]).parent().parent().parent().parent().prev().find('.adv').css('color', '#000');
                        $(page_questions[i]).parent().parent().parent().parent().next().find('.validation_err').remove();
                    }
                }
                if (parentQue.value.trim() == '') {
                    if ($(parentQue).parent().find('.validation_error').length == 0) {
                        $(page_questions[i]).after("<span class='validation_error' style='color: red;font-size:12px;margin-top: 8px;display: inline-block;'>Please enter your question.</span>");
                    }
                    flag = false;
                    $(page_questions[i]).parent().parent().parent().parent().prev().find('.gen').css("color", "red");
                }
                else {
                    $(page_questions[i]).parent().parent().parent().parent().prev().find('.gen').css("color", "#000");
                    $(parentQue).parent().find('.validation_error').remove();
                }
            }
            if (que_type == "Date") {

                var st_date = ($(page_questions[i]).parent().parent().parent().parent().parent().find('.sdate').val());
                var e_date = ($(page_questions[i]).parent().parent().parent().parent().parent().find('.edate').val());

                if (st_date && e_date && e_date < st_date) {

                    if ($(page_questions[i]).parent().parent().parent().parent().parent().find('.validation_err').length == 0) {
                        $(page_questions[i]).parent().parent().parent().parent().parent().find('.edate').next().after("<span class='validation_err' style='color: red;font-size:12px;margin-top: 8px;display: inline-block;'>The date of this field must be after the date of Start Date Field</span>");
                    }
                    flag = false;
                    $(page_questions[i]).parent().parent().parent().parent().prev().find('p').css('color', 'red');
                } else {
                    $(page_questions[i]).parent().parent().parent().parent().prev().find('p').css('color', '#000');
                    $(page_questions[i]).parent().parent().parent().parent().parent().find('.validation_err').remove();
                }
                if (parentQue.value.trim() == '') {
                    if ($(parentQue).parent().find('.validation_error').length == 0) {
                        $(page_questions[i]).after("<span class='validation_error' style='color: red;font-size:12px;margin-top: 8px;display: inline-block;'>Please enter your question.</span>");
                    }
                    flag = false;
                    $(page_questions[i]).parent().parent().parent().parent().prev().find('.gen').css("color", "red");
                }
                else {
                    $(page_questions[i]).parent().parent().parent().parent().prev().find('.gen').css("color", "#000");
                    $(parentQue).parent().find('.validation_error').remove();
                }
            }
            if (que_type == "ContactInformation") {
                if ($(page_questions[i]).parent().parent().parent().parent().parent().find('.is_required').prop('checked') == true) {
                    var cnt = 0;
                    $.each($('.requiredfields'), function () {
                        if (this.checked == true) {
                            cnt += 1;
                        }
                    });
                    if (cnt < 1) {

                        if ($(parentQue).parent().parent().parent().parent().parent().find('.contact-info').eq(1).next().length == 0) {
                            $(page_questions[i]).parent().parent().parent().parent().parent().find('.contact-info').eq(1).after("<span class='validation_error' style='margin-left: 135px;color: red;font-size:12px;margin-top: 8px;display: inline-block;'>You may have to select atleast one field as required</span>")
                        }
                        flag = false;
                        $(page_questions[i]).parent().parent().parent().parent().prev().find('p').css('color', 'red');
                    }
                    else {
                        $(page_questions[i]).parent().parent().parent().parent().prev().find('p').css('color', '#000');
                        $(page_questions[i]).parent().parent().parent().parent().parent().find('.s-required').find('.validation_error').remove();
                    }
                }
                else {
                    $(page_questions[i]).parent().parent().parent().parent().prev().find('p').css('color', '#000');
                    $(page_questions[i]).parent().parent().parent().parent().parent().find('.s-required').find('.validation_error').remove();
                }

                if (parentQue.value.trim() == '') {
                    if ($(parentQue).parent().find('.validation_error').length == 0) {
                        $(page_questions[i]).after("<span class='validation_error' style='color: red;font-size:12px;margin-top: 8px;display: inline-block;'>Please enter your question.</span>");
                    }
                    flag = false;
                    $(page_questions[i]).parent().parent().parent().parent().prev().find('.gen').css("color", "red");
                }
                else {
                    $(page_questions[i]).parent().parent().parent().parent().prev().find('.gen').css("color", "#000");
                    $(parentQue).parent().find('.validation_error').remove();
                }
            } else if (que_type != "ContactInformation" && que_type != "Textbox" && que_type != "Scale" && que_type != "Date" && sec_type != "question_section") {

                if (parentQue.value.trim() == '') {
                    if ($(parentQue).parent().parent().find('.validation_error').length == 0) {
                        $(page_questions[i]).parent().parent().append("<span class='validation_error' style='color: red;font-size:12px;margin-top: 8px;display: inline-block;'>Please enter your question.</span>");
                    }
                    flag = false;
                    $(page_questions[i]).parent().parent().parent().parent().prev().find('.gen').css("color", "red");
                }
                else {
                    $(page_questions[i]).parent().parent().parent().parent().prev().find('.gen').css("color", "#000");
                    $(parentQue).parent().find('.validation_error').remove();
                }
            }
            if (que_type == "Checkbox" || que_type == "RadioButton" || que_type == "DrodownList" || que_type == "MultiSelectList") {
                var logic_action = $(page_questions[i]).parent().parent().parent().parent().parent().parent().find('.condition').find('select').find('option:selected');
                $(page_questions[i]).parent().parent().parent().parent().parent().parent().parent().find('.lgc').css("color", "#000");
                if ($('#EditView input[name=module]').val() != "bc_survey_template") {
                $.each(logic_action, function () {
                    if (this.value == "redirect_url") {
                        var url = $(this).parent().parent().parent().next().find('.logic-url-box').val();
                        $(this).parent().parent().parent().next().find('.logic-url-box').css('border-color', '#94c1e8');
                        if ($(this).parent().parent().parent().next().find('.logic-url-box').val() == "http://" || $(this).parent().parent().parent().next().find('.logic-url-box').val() == "" || $(this).parent().parent().parent().next().find('.logic-url-box').val() == "https://") {
                            $(page_questions[i]).parent().parent().parent().parent().parent().parent().parent().find('.lgc').css("color", "red");
                            $(this).parent().parent().parent().next().find('.logic-url-box').css('border-color', 'red');
                            flag = false;
                        } else if ((($('#redirect_url').val() != "http://") && !re.test(url)) || (($('#redirect_url').val() != "https://") && !re.test(url))) {
                            $(page_questions[i]).parent().parent().parent().parent().parent().parent().parent().find('.lgc').css("color", "red");
                            if ($(this).parent().parent().parent().next().find('.validation_error').length == 0) {
                                $(this).parent().parent().parent().next().append("<span class='validation_error' style='color: red;font-size:12px;margin-top: 8px;display: inline-block;'>Error. Invalid URL.</span>");
                                if ($(this).parent().parent().parent().next().find('i').length == 0) {
                                    $(this).parent().parent().parent().next().find('input[type=text]').after('<i class="fa fa-exclamation-circle" style="color: red;" title="It should be in i.e. http://www.google.com format."></i>')
                                }
                            }
                            flag = false;
                        }
                    } else if (this.value == "show_hide_question") {
                        if (!$(this).parent().parent().parent().next().find('.show_hide_dd').val()) {
                            $(page_questions[i]).parent().parent().parent().parent().parent().parent().parent().find('.lgc').css("color", "red");
                            if ($(this).parent().parent().parent().next().find('.validation_error').length == 0) {
                                $(this).parent().parent().parent().next().after("<span class='validation_error' style='color: red;font-size:12px;margin-top: 8px;display: inline-block;'>Select At Least One Question.</span>");
                            }
                            flag = false;
                        }
                    }
                });
            }
            }
            if (que_type == "Video") {
                if ($(page_questions[i]).parent().parent().parent().parent().parent().find('.title').val() == "") {

                    $(page_questions[i]).parent().parent().parent().parent().parent().find('.adv').css('color', 'red');
                    if ($(page_questions[i]).parent().parent().parent().parent().parent().find('.title').parent().find('.validation_error').length == 0) {
                        $(page_questions[i]).parent().parent().parent().parent().parent().find('.title').parent().append("<span class='validation_error' style='color: red;font-size:12px;margin-top: 8px;display: inline-block;'>Enter Valid Video Title.</span>");
                    }
                    flag = false;
                } else {
                    $(page_questions[i]).parent().parent().parent().parent().prev().find('p').css('color', '#000');
                }
            }
            if (sec_type == "question_section") {
                if ($(page_questions[i]).parent().find('.que_title').val() == "") {
                    if ($(page_questions[i]).parent().find('.validation_error').length == 0) {
                        $(page_questions[i]).parent().find('a').after("<span class='validation_error' style='color: red;font-size:12px;margin-top: 8px;display: inline-block; margin-left: 73px;'>Enter Question Section Title.</span>");
                    }
                    flag = false;
                }
            }
            if ($('#enable_data_piping:checked').length) {
                if ($(page_questions[i]).parent().parent().parent().parent().parent().find('.pipecontent').find('input[type=checkbox]:checked').length != 1) {
                    if ($(page_questions[i]).parent().parent().parent().parent().parent().find('.pipecontent').find('.fields_type').val() == "0") {
                        $(page_questions[i]).parent().parent().parent().parent().parent().parent().parent().find('.pipe').css("color", "red");
                        if ($(page_questions[i]).parent().parent().parent().parent().parent().find('.pipecontent').find('.validation_error').length == 0) {
                            $(page_questions[i]).parent().parent().parent().parent().parent().find('.pipecontent').find('.fields_type').after("<span class='validation_error' style='color: red;font-size:12px;margin-top: 8px;display: inline-block; margin-left: 73px;'>Select field or else select disable piping.</span>");
        }
                        flag = false;
                    }
                } else {
                    $(page_questions[i]).parent().parent().parent().parent().parent().parent().parent().find('.pipe').css("color", "#000");
                }
            }
        }
        //For Multiple Choice Question Validation   
        for (var i = 0; i < que_option.length; i++) {
            var que_type = $(que_option[i]).find('li').parent().parent().closest('ul').find('.s-type').find('span').eq(1).find('input').attr('value')
            if (!que_type) {
                que_type = $(que_option[i]).find('li').parent().parent().closest('ul').find('.s-type').find('input[type=radio]:checked').val();
            }
            var no_of_li = $(que_option[i]).find('li');

            var txtcount = 0;
            $.each(no_of_li, function (li_index, li_value) {
                if (que_type_array.indexOf(que_type) >= 0) {
                    if (que_type == "Checkbox" || que_type == "RadioButton" || que_type == "Matrix") {
                        if ($(li_value).find('input[type="text"]').val() == "") {
                            if ($(li_value).closest('ul').parent().parent().nextAll('.validation_error').length == 0) {
                                $(li_value).closest('ul').parent().parent().after("<span class='validation_error' style='color: red;font-size:12px;margin-top: -10px;margin-left:179px;margin-bottom:8px;display: inline-block;' id='ans_validate'>You must have to fill all the options.</span>");
                            }
                            flag = false;
                            $(li_value).closest('ul').parent().parent().parent().parent().parent().find('.gen').css("color", "red");
                        } else {
                            $(li_value).closest('ul').parent().parent().parent().parent().parent().find('.gen').css("color", "#000");
                        }
                    }
                    if (que_type == "DrodownList" || que_type == "MultiSelectList") {
                        if ($(li_value).find('input[type="text"]').val() == "") {
                            txtcount++;
                        }
                        if (txtcount > 1) {
                            if ($(li_value).closest('ul').parent().parent().nextAll('.validation_error').length == 0) {
                                $(li_value).closest('ul').parent().parent().after("<span class='validation_error' style='color: red;font-size:12px;margin-top: -10px;margin-left:179px; margin-bottom:8px;display: inline-block;' id='ans_validate'>You may remain only one option as blank. Please fill out other options.</span>");
                            }
                            flag = false;
                            $(li_value).closest('ul').parent().parent().parent().parent().parent().find('.gen').css("color", "red");
                        } else {
                            $(li_value).closest('ul').parent().parent().parent().parent().parent().find('.gen').css("color", "#000");
                            //$(li_value).closest('ul').parent().parent().next().remove();
                        }
                    }
                }
            });
        }
    }
    return flag;
    }

