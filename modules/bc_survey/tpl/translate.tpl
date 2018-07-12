<html>
    <head>
        {literal}
            <script src="include/javascript/tiny_mce/tiny_mce.js"></script>
            <script type="text/javascript">
                //retrive all languages in listview
                $(document).ready(function () {
                    $('.retrive_language').append('{/literal}{$records_tr}{literal}');
                });
                //default content load button click
                function load_default_content(el) {
                    if (confirm("Copy survey details and standard messaging for this language from an existing survey ? This will override your existing data. Are you sure that you want to proceed?")) {
                        $.each($('.copy_frm_default'), function () {
                            if($(this).next().find('input[type=text]').length != 0){
                            $(this).next().find('input[type=text]').val($(this).html());
                        }else if($(this).parent().find('textarea').length != 0){
                            $(this).parent().find('textarea').html($(this).html());
                        }else{
                            $(this).parent().next().find('textarea').html($(this).html());
                            $.each(tinyMCE.editors, function (index, editor) {
                            var idOf = editor.id;
                            var welcomePageContent=$("#welcome_page").val();
                            var thanksPageContent=$("#thanks_page").val();
                           if(idOf == 'welcome_page'){
                               editor.setContent(welcomePageContent);
                        }
                           if(idOf == 'thanks_page'){
                               editor.setContent(thanksPageContent);
                           }
                        });
                    }
                        });
                }
                }
                //tab change in translate language view
                function change_tab(el) {
                    if ($(el).attr('id') == "language") {
                        $('#translated_survey_language').val('');
                        $(el).addClass('active');
                        $(el).next().removeClass('active');
                        $('#language_tab_view').show();
                        $('#translate_tab_view').hide();
                        $('#translate').hide();
                        location.reload();
                    } else {


                        var direction = '';
                        var style = '';
                        if ($(".survey-auto-table").find(".allow_copy:checked").length) {
                            $('#copy_from_default').val('1');
                        }
                        if ($('#select_language_direction').val() == "right_to_left") {
                            style = "direction: rtl;"
                        }
                        if ($(el).attr('id') == "translate_survey") {
                            var language_id = $(el).parent().parent().attr('class');
                            $('#survey_language_id').val(language_id);
                            if ($(el).parent().prev().find('i').attr('title') == "No") {
                                $('#copy_from_default').val('0');
                            }
                            direction = $(el).parent().prev().prev().text();
                            if (direction == "Right to Left") {
                                style = "direction: rtl;"
                            }
                            var language = '';
                            if ($(el).parent().parent().attr('id') != undefined) {
                                language = $(el).parent().parent().attr('id');
                                $('#translated_survey_language').val(language);
                            } else {
                                language = $('#translated_survey_language').val();
                            }
                        }
                        if ($(el).find('#language_id').length != '0') {
                            var language_id = $("#language_id").val();
                            $('#survey_language_id').val(language_id);
                        }
                        $('#translate').show();
                        $('#translate').addClass('active');
                        $('#language').removeClass('active');
                        $('#language_tab_view').hide();
                        $('#translate_tab_view').show();
                        debugger;
                        var record_id = {record: '{/literal}{$survey_id}{literal}', template_id: ''}
                        $.ajax({
                            url: "index.php",
                            data: {
                                module: 'bc_survey',
                                action: 'edit_survey',
                                record: record_id,
                            },
                            success: function (data) {
                                var detail_array = JSON.parse(data);
                                var welcomePage = `{/literal}{$welcome_page}{literal}`;
                                var thnksPage = `{/literal}{$thanks_page}{literal}`;
                                   var type ='';
                                if(detail_array.survey_type == "poll"){
                                    type = "Poll";
                                }else{
                                    type = "Survey";
                                }
                                var html = '<h1>' + detail_array.name + ' Translated in ' + $('.retrive_language').find('#' + $('#translated_survey_language').val()).find('td:first').text() + '</h1>';
                                html += '<div class="report_table_title" style="float:left;"> ';
                                html += '<a id="survey_component" onclick="change_tab_translate(this)" class="report_title active">'+ type +' Content</a>';
                                html += '<a id ="message_button" onclick="change_tab_translate(this)" class="report_title"">Messages</a>';
                                html += '<a id ="advanced_mce" onclick="change_tab_translate(this)" class="report_title"">Advanced Contents</a>';
                                html += '</div>';
                                html += '<div style="float:right;"><input type="button" id="copy_default_content" Value="Load data from Default Language" onclick="load_default_content(this)"></div>';
                                html += '<div class="survey_component_content"><table class="individual_report_table retrive_language" style="width:100%;" cellpadding="10" CELLSPACING="10">';
                                html += '<tr class="thead"><th style="text-align: center;width:15%">Field</th><th style="text-align: center;width:45%">Default Text</th><th  style="text-align: center;width:40%">Translation</th></tr>';
                                html += '<tr id="survey_tr_id_{/literal}{$survey_id}{literal}"><td style="text-align: center;">'+ type +' Title</td><td class="copy_frm_default" style="text-align: center;">' + detail_array.name + '</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<input type="text" id="surveyid_{/literal}{$survey_id}{literal}" name="{/literal}{$survey_id}{literal}" value="' + detail_array.name + '" style="' + style + '"></td>';
                                } else {
                                    html += '<input type="text" id="surveyid_{/literal}{$survey_id}{literal}" name="{/literal}{$survey_id}{literal}" value="" style="' + style + '"></td>';
                                }
                                html += '</tr>';
                                if(detail_array.description){
                                html += '<tr id="survey_tr_id_{/literal}{$survey_id}{literal}"><td style="text-align: center;">Description</td><td class="copy_frm_default">' + detail_array.description + '</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<textarea id="surveyid_{/literal}{$survey_id}{literal}" style="width:92.5%" name="{/literal}{$survey_id}{literal}_description">'+detail_array.description+'</textarea></td>';
                                } else {
                                    html += '<textarea id="surveyid_{/literal}{$survey_id}{literal}" style="width:92.5%" name="{/literal}{$survey_id}{literal}_description"></textarea></td>';
                                }
                                html += '</tr>';
                                }
                                $.each(detail_array, function (page_index, page_detail) {
                                    if (page_index != "name" && page_index != "description" && page_index != "theme" && page_index != "survey_type" && page_index != "sync_module" && page_index != "enable_data_piping") {
                                        var page_title='';
                                        if(page_detail.page_title){
                                            page_title = page_detail.page_title
                                        }else{
                                            page_title = 'Page'
                                        }
                                        html += '<tr id="page_tr_id_' + page_detail.page_id + '"><td style="text-align: center;">Page Title</td><td class="copy_frm_default">' + page_title + '</td><td class="lang-content">';
                                        if ($('#copy_from_default').val() == "1") {
                                            html += '<input type="text" id="pageid_' + page_detail.page_id + '" name="' + page_detail.page_id + '" value="' + page_title + '" style="' + style + '"></td></tr>';
                                        } else {
                                            html += '<input type="text" id="pageid_' + page_detail.page_id + '" name="' + page_detail.page_id + '" style="' + style + '"></td></tr>';
                                        }
                                        $.each(page_detail.page_questions, function (que_index, question_detail) {

                                            if (question_detail.que_type != "question_section" && question_detail.que_type != "Image" && question_detail.que_type != "Video" && question_detail.que_type != "ContactInformation") {
                                                html += '<tr id="que_tr_id_' + question_detail.que_id + '"><td style="text-align: center;">Question Title (' + question_detail.que_type + ')</td><td class="copy_frm_default">' + question_detail.que_title + '</td><td class="lang-content">';
                                                if ($('#copy_from_default').val() == "1") {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_quetitle" value="' + question_detail.que_title + '" style="' + style + '"></td></tr>';
                                                } else {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_quetitle" value="" style="' + style + '"></td></tr>';
                                                }
                                            } else if (question_detail.que_type == "question_section") {
                                                html += '<tr id="que_tr_id_' + question_detail.que_id + '"><td style="text-align: center;">Question Section Header</td><td class="copy_frm_default">' + question_detail.que_title + '</td><td class="lang-content">';
                                                if ($('#copy_from_default').val() == "1") {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_quetitle" value="' + question_detail.que_title + '" style="' + style + '"></td></tr>';
                                                } else {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_quetitle" style="' + style + '"></td></tr>';
                                                }
                                            } else if (question_detail.que_type == "Image") {
                                                html += '<tr id="que_tr_id_' + question_detail.que_id + '"><td style="text-align: center;">Image (Title)</td><td class="copy_frm_default">' + question_detail.question_help_comment + '</td><td class="lang-content">';
                                                if ($('#copy_from_default').val() == "1") {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_quetitle" value="' + question_detail.question_help_comment + '" style="' + style + '"></td></tr>';
                                                } else {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_quetitle" style="' + style + '"></td></tr>';
                                                }
                                            } else if (question_detail.que_type == "Video") {
                                                html += '<tr id="que_tr_id_' + question_detail.que_id + '"><td style="text-align: center;">Video (Title)</td><td class="copy_frm_default">' + question_detail.question_help_comment + '</td><td class="lang-content">';
                                                if ($('#copy_from_default').val() == "1") {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_quetitle" value="' + question_detail.question_help_comment + '" style="' + style + '"></td></tr>';
                                                } else {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_quetitle" style="' + style + '"></td></tr>';
                                                }
                                                html += '<tr id="que_tr_id_' + question_detail.que_id + '"><td style="text-align: center;">Video (Discription)</td><td class="copy_frm_default">' + question_detail.descvideo + '</td><td class="lang-content">';
                                                if ($('#copy_from_default').val() == "1") {
                                                    html += '<textarea name="video_desc" style="' + style + '">' + question_detail.descvideo + '</textarea></td></tr>';
                                                } else {
                                                    html += '<textarea name="video_desc" style="' + style + '"></textarea></td></tr>';
                                                }
                                            }else if (question_detail.que_type == "ContactInformation") {
                                                html += '<tr id="que_tr_id_' + question_detail.que_id + '"><td style="text-align: center;">Contact Information (Title)</td><td class="copy_frm_default">' + question_detail.que_title + '</td><td class="lang-content">';
                                                if ($('#copy_from_default').val() == "1") {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_quetitle" value="' + question_detail.que_title + '" style="' + style + '"></td></tr>';
                                                } else {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_quetitle" style="' + style + '"></td></tr>';
                                            }
                                                 html += '<tr id="name_tr_id_' + question_detail.que_id + '"><td style="text-align: center;">Placeholder Label for Name</td><td class="copy_frm_default">Name</td><td class="lang-content">';
                                                if ($('#copy_from_default').val() == "1") {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_placeholder_label_Name" value="Name" style="' + style + '"></td></tr>';
                                                } else {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_placeholder_label_Name" style="' + style + '"></td></tr>';
                                                }
                                                 html += '<tr id="email_tr_id_' + question_detail.que_id + '"><td style="text-align: center;">Placeholder Label for Email Address</td><td class="copy_frm_default">Email Address</td><td class="lang-content">';
                                                if ($('#copy_from_default').val() == "1") {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_placeholder_label_Email Address" value="Email Address" style="' + style + '"></td></tr>';
                                                } else {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_placeholder_label_Email Address" style="' + style + '"></td></tr>';
                                                }
                                                html += '<tr id="phone_tr_id_' + question_detail.que_id + '"><td style="text-align: center;">Placeholder Label for Phone Number</td><td class="copy_frm_default">Phone Number</td><td class="lang-content">';
                                                if ($('#copy_from_default').val() == "1") {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_placeholder_label_Phone Number" value="Phone Number" style="' + style + '"></td></tr>';
                                                } else {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_placeholder_label_Phone Number" style="' + style + '"></td></tr>';
                                                }
                                                html += '<tr id="company_tr_id_' + question_detail.que_id + '"><td style="text-align: center;">Placeholder Label for Comapny</td><td class="copy_frm_default">Comapny</td><td class="lang-content">';
                                                if ($('#copy_from_default').val() == "1") {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_placeholder_label_Company" value="Comapny" style="' + style + '"></td></tr>';
                                                } else {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_placeholder_label_Company" style="' + style + '"></td></tr>';
                                                }
                                                html += '<tr id="address_tr_id_' + question_detail.que_id + '"><td style="text-align: center;">Placeholder Label for Address</td><td class="copy_frm_default">Address</td><td class="lang-content">';
                                                if ($('#copy_from_default').val() == "1") {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_placeholder_label_Address" value="Address" style="' + style + '"></td></tr>';
                                                } else {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_placeholder_label_Address" style="' + style + '"></td></tr>';
                                                }
                                                html += '<tr id="address2_tr_id_' + question_detail.que_id + '"><td style="text-align: center;">Placeholder Label for Address2</td><td class="copy_frm_default">Address2</td><td class="lang-content">';
                                                if ($('#copy_from_default').val() == "1") {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_placeholder_label_Address2" value="Address2" style="' + style + '"></td></tr>';
                                                } else {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_placeholder_label_Address2" style="' + style + '"></td></tr>';
                                                }
                                                 html += '<tr id="city_tr_id_' + question_detail.que_id + '"><td style="text-align: center;">Placeholder Label for City/Town</td><td class="copy_frm_default">City/Town</td><td class="lang-content">';
                                                if ($('#copy_from_default').val() == "1") {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_placeholder_label_City/Town" value="City/Town" style="' + style + '"></td></tr>';
                                                } else {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_placeholder_label_City/Town" style="' + style + '"></td></tr>';
                                                }
                                                  html += '<tr id="state_tr_id_' + question_detail.que_id + '"><td style="text-align: center;">Placeholder Label for State/Province</td><td class="copy_frm_default">State/Province</td><td class="lang-content">';
                                                if ($('#copy_from_default').val() == "1") {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_placeholder_label_State/Province" value="State/Province" style="' + style + '"></td></tr>';
                                                } else {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_placeholder_label_State/Province" style="' + style + '"></td></tr>';
                                                }
                                                  html += '<tr id="zip_tr_id_' + question_detail.que_id + '"><td style="text-align: center;">Placeholder Label for ZIP/Postal Code</td><td class="copy_frm_default">ZIP/Postal Code</td><td class="lang-content">';
                                                if ($('#copy_from_default').val() == "1") {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_placeholder_label_ZIP/Postal Code" value="ZIP/Postal Code" style="' + style + '"></td></tr>';
                                                } else {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_placeholder_label_ZIP/Postal Code" style="' + style + '"></td></tr>';
                                                }
                                                html += '<tr id="country_tr_id_' + question_detail.que_id + '"><td style="text-align: center;">Placeholder Label for Country</td><td class="copy_frm_default">Country</td><td class="lang-content">';
                                                if ($('#copy_from_default').val() == "1") {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_placeholder_label_Country" value="Country" style="' + style + '"></td></tr>';
                                                } else {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_placeholder_label_Country" style="' + style + '"></td></tr>';
                                                }
                                            }
                                            if (question_detail.question_help_comment) {
                                                html += '<tr id="help_tr_id_' + question_detail.que_id + '"><td style="text-align: center;">Help Title</td><td class="copy_frm_default">' + question_detail.question_help_comment + '</td><td class="lang-content">';
                                                if ($('#copy_from_default').val() == "1") {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_helptitle" value="' + question_detail.question_help_comment + '" style="' + style + '"></td></tr>';
                                                } else {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_helptitle" style="' + style + '"></td></tr>';
                                                }
                                            }
                                            if (question_detail.que_type == "Scale") {
                                                html += '<tr id="scale_tr_id_' + question_detail.que_id + '"><td style="text-align: center;">Label</td><td class="copy_frm_default">' + question_detail.advance_type + '</td><td class="lang-content">';
                                                if ($('#copy_from_default').val() == "1") {
                                                    var arr = question_detail.advance_type.split("-");
                                                    html += '<input type="text" name="' + question_detail.que_id + '_display_left" value="' + arr[0] + '" style="' + style + '">-<input type="text" name="' + question_detail.que_id + '_display_center" value="' + arr[1] + '" style="' + style + '">-<input type="text" name="' + question_detail.que_id + '_display_right" value="' + arr[2] + '" style="' + style + '"></td></tr>';
                                                } else {
                                                    html += '<input type="text" name="' + question_detail.que_id + '_display_left" style="' + style + '">-<input type="text" name="' + question_detail.que_id + '_display_center" style="' + style + '">-<input type="text" name="' + question_detail.que_id + '_display_right" style="' + style + '"></td></tr>';
                                                }
                                            }
                                            if (question_detail.que_type == "Matrix") {
                                                $.each(question_detail.matrix_col, function (colindx, column_name) {
                                                    html += '<tr id="col_id_' + colindx + '"><td style="text-align: center;">Column ' + colindx + '</td><td class="copy_frm_default">' + column_name + '</td><td class="lang-content">';
                                                    if ($('#copy_from_default').val() == "1") {
                                                        html += '<input type="text" name="' + question_detail.que_id + '_col' + colindx + '" value="' + column_name + '" style="' + style + '"></td></tr>';
                                                    } else {
                                                        html += '<input type="text" name="' + question_detail.que_id + '_col' + colindx + '" style="' + style + '"></td></tr>';
                                                    }
                                                });
                                                $.each(question_detail.matrix_row, function (rowindx, row_name) {
                                                    html += '<tr id="row_id_' + rowindx + '"><td style="text-align: center;">Row ' + rowindx + '</td><td class="copy_frm_default">' + row_name + '</td><td class="lang-content">';
                                                    if ($('#copy_from_default').val() == "1") {
                                                        html += '<input type="text" name="' + question_detail.que_id + '_row' + rowindx + '" value="' + row_name + '" style="' + style + '"></td></tr>';
                                                    } else {
                                                        html += '<input type="text" name="' + question_detail.que_id + '_row' + rowindx + '" style="' + style + '"></td></tr>';
                                                    }
                                                });
                                            }
                                            if (question_detail.que_type == "Checkbox" || question_detail.que_type == "MultiSelectList" || question_detail.que_type == "RadioButton" || question_detail.que_type == "DrodownList") {
                                                $.each(question_detail.answers, function (answer_index, answer) {

                                                    html += '<tr id="answer_tr_id_' + answer.id + '"><td style="text-align: center;">Option ' + answer_index + '</td><td class="copy_frm_default">' + answer.name + '</td><td class="lang-content">';
                                                    if ($('#copy_from_default').val() == "1") {
                                                        html += '<input type="text" name="' + answer.id + '" value="' + answer.name + '" style="' + style + '"></td></tr>';
                                                    } else {
                                                        html += '<input type="text" name="' + answer.id + '" style="' + style + '"></td></tr>';
                                                    }
                                                if(answer.option_type == 'other'){
                                                    html += '<tr id="other_tr_id_' + answer.id + '"><td style="text-align: center;">Placeholder Label for Other</td><td class="copy_frm_default">Other</td><td class="lang-content">';
                                                    if ($('#copy_from_default').val() == "1") {
                                                        html += '<input type="text" name="' + question_detail.que_id + '_other_placeholder_label" value="Other" style="' + style + '"></td></tr>';
                                                    } else {
                                                        html += '<input type="text" name="' + question_detail.que_id + '_other_placeholder_label" style="' + style + '"></td></tr>';
                                                    }
                                                     }
                                                });
                                            }
                                        });
                                    }
                                });
                                html += '</table>';
                                html += '<div style="float:left">&nbsp;</div><div class="lang-next" style="float:right"><input type="button" onclick="change_tab_translate(this)" value="Next" id="message_button"></div>';
                                html += '</div>';
                                html += '<div class="messages" style="display:none;"><table class="individual_report_table" style="width:100%;">'
                                html += '<tr class="thead"><th colspan = "3">'+ type +' Form Buttons</th></tr>';
                                html += '<tr class="thead"><th style="width: 15%;">Control</th><th style="width: 45%;">Default Text</th><th style="width: 40%;">Translation</th></tr>';
                                html += '<tr><td>Previous Button</td><td class="copy_frm_default">Prev</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<input type="text" name="prev_button" value="Prev" style="' + style + '"></td></tr>';
                                } else {
                                    html += '<input type="text" name="prev_button" value="" style="' + style + '"></td></tr>';
                                }
                                html += '<tr><td>Next Button</td><td class="copy_frm_default">Next</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<input type="text" name="next_button" value="Next" style="' + style + '"></td></tr>';
                                } else {
                                    html += '<input type="text" name="next_button" value="" style="' + style + '"></td></tr>';
                                }
                                html += '<tr><td>Submit Button</td><td class="copy_frm_default">Submit</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<input  type="text" name="submit_button" value="Submit" style="' + style + '"></td></tr>';
                                } else {
                                    html += '<input type="text" name="submit_button" value="" style="' + style + '"></td></tr>';
                                }
                                html += '<tr class="thead"><th colspan = "3">Validation Messages</th></tr>';
                                html += '<tr class="thead"><th>Control</th><th>Default Text</th><th>Translation</th></tr>';
                                html += '<tr><td>Required Message</td><td class="copy_frm_default">This question is mandatory, Please answer this question.</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<input type="text" name="required_msg" value="This question is mandatory, Please answer this question." style="' + style + '"></td></tr>';
                                } else {
                                    html += '<input type="text" name="required_msg" value="" style="' + style + '"></td></tr>';
                                }
                                html += '<tr><td>Invalid Email Address Messagen</td><td class="copy_frm_default">Please enter correct Email Address.</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<input type="text" name="invalid_email_msg" value="Please enter correct Email Address." style="' + style + '"></td></tr>';
                                } else {
                                    html += '<input type="text" name="invalid_email_msg" value="" style="' + style + '"></td></tr>';
                                }
                                html += '<tr><td>Invalid Phone Number Message</td><td class="copy_frm_default">Please enter proper Phone Number.</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<input type="text" name="invalid_phn_msg" value="Please enter proper Phone Number." style="' + style + '"></td></tr>';
                                } else {
                                    html += '<input type="text" name="invalid_phn_msg" value="" style="' + style + '"></td></tr>';
                                }
                                html += '<tr><td>Matrix answer required Message</td><td class="copy_frm_default">This question require one answer per row.</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<input type="text" name="matrix_required_msg" value="This question require one answer per row." style="' + style + '"></td></tr>';
                                } else {
                                    html += '<input type="text" name="matrix_required_msg" value="" style="' + style + '"></td></tr>';
                                }
                                html += '<tr><td>Option Selection Limit Message</td><td class="copy_frm_default">You must have to select atleast $min option(s).</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<input type="text" name="sel_limit_msg" value="You must have to select atleast $min option(s)." style="' + style + '">&nbsp;&nbsp;<i class="fa fa-exclamation-circle" title="$min required as input for this message" style="color:green;"></i></td></tr>';
                                } else {
                                    html += '<input type="text" name="sel_limit_msg" value="" style="' + style + '">&nbsp;&nbsp;<i class="fa fa-exclamation-circle" title="$min required as input for this message" style="color:green;"></i></td></tr>';
                                }
                                html += '<tr><td>Value Limit Message</td><td class="copy_frm_default">Please enter Value between $min-$max.</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<input type="text" name="limit_msg" value="Please enter Value between $min-$max." style="' + style + '">&nbsp;&nbsp;<i class="fa fa-exclamation-circle" title="$min and $max required as input for this message" style="color:green;"></i></td></tr>';
                                } else {
                                    html += '<input type="text" name="limit_msg" value="" style="' + style + '">&nbsp;&nbsp;<i class="fa fa-exclamation-circle" title="$min and $max required as input for this message" style="color:green;"></i></td></tr>';
                                }
                                html += '<tr><td>Min Value Limit Message</td><td class="copy_frm_default">Value can not be less then $min.</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<input type="text" name="limit_min_msg" value="Value can not be less then $min." style="' + style + '">&nbsp;&nbsp;<i class="fa fa-exclamation-circle" title="$min required as input for this message" style="color:green;"></i></td></tr>';
                                } else {
                                    html += '<input type="text" name="limit_min_msg" value="" style="' + style + '">&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:green;" title="$min required as input for this message"></i></td></tr>';
                                }
                                html += '<tr><td>Max Value Limit Message</td><td class="copy_frm_default">Value can not be more then $max.</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<input type="text" name="limit_max_msg" value="Value can not be more then $max." style="' + style + '">&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:green;" title="$max required as input for this message"></i></td></tr>';
                                } else {
                                    html += '<input type="text" name="limit_max_msg" value="" style="' + style + '">&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:green;" title="$max required as input for this message"></i></td></tr>';
                                }
                                html += '<tr><td>Precision Point Limit Message</td><td class="copy_frm_default">Please enter atleast $precision precision point.</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<input type="text" name="limit_precision_msg" value="Please enter atleast $precision precision point." style="' + style + '">&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:green;" title="$precision required as input for this message"></i></td></tr>';
                                } else {
                                    html += '<input type="text" name="limit_precision_msg" value="" style="' + style + '">&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:green;" title="$precision required as input for this message"></i></td></tr>';
                                }
                                html += '<tr><td>Maximum char length Message</td><td class="copy_frm_default">Maximum length $maxsize character.</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<input type="text" name="max_msg" value="Maximum length $maxsize character." style="' + style + '">&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:green;" title="$maxsize required as input for this message"></i></td></tr>';
                                } else {
                                    html += '<input type="text" name="max_msg" value="" style="' + style + '">&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:green;" title="$maxsize required as input for this message"></i></td></tr>';
                                }
                                html += '<tr><td>Date Range Message</td><td class="copy_frm_default">Date can be between $min to $max.</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<input type="text" name="range_msg" value="Date can be between $min to $max." style="' + style + '">&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:green;" title="$maxsize required as input for this message"></i></td></tr>';
                                } else {
                                    html += '<input type="text" name="range_msg" value="" style="' + style + '">&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:green;" title="$maxsize required as input for this message"></i></td></tr>';
                                }
                                html += '<tr><td>Start Date Message</td><td class="copy_frm_default">Please enter date after $min.</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<input type="text" name="start_date_msg" value="Please enter date after $min." style="' + style + '">&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:green;" title="$min required as input for this message"></i></td></tr>';
                                } else {
                                    html += '<input type="text" name="start_date_msg" value="" style="' + style + '">&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:green;" title="$min required as input for this message"></i></td></tr>';
                                }
                                html += '<tr><td>End Date Message</td><td class="copy_frm_default">Please enter date before $max.</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<input type="text" name="end_date_msg" value="Please enter date before $max." style="' + style + '">&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:green;" title="$max required as input for this message"></i></td></tr>';
                                } else {
                                    html += '<input type="text" name="end_date_msg" value="" style="' + style + '">&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:green;" title="$max required as input for this message"></i></td></tr>';
                                }

                                html += '<tr class="thead"><th colspan = "3">'+ type +' Form Messages</th></tr>';
                                html += '<tr class="thead"><th>Control</th><th>Default Text</th><th>Translation</th></tr>';
                                html += '<tr><td>Success '+ type +' Submission Message</td><td class="copy_frm_default">Your '+type+' has been submitted successfully and summary email send to your email address.</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<input type="text" name="survey_submission_msg" value="Your '+type+' has been submitted successfully and summary email send to your email address." style="' + style + '"></td></tr>';
                                } else {
                                    html += '<input type="text" name="survey_submission_msg" value="" style="' + style + '"></td></tr>';
                                }
                                html += '<tr><td>Already submitted Message</td><td class="copy_frm_default">You have already submitted this '+ type +'.</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<input type="text" name="already_sub_msg" value="You have already submitted this '+ type +'. " style="' + style + '"></td></tr>';
                                } else {
                                    html += '<input type="text" name="already_sub_msg" value="" style="' + style + '"></td></tr>';
                                }
                                 html += '<tr><td>Already submitted Message</td><td class="copy_frm_default">For request to admin to resubmit your '+ type +' &nbsp;</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<input type="text" name="req_msg" value="For request to admin to resubmit your '+ type +' " style="' + style + '"></td></tr>';
                                } else {
                                    html += '<input type="text" name="req_msg" value="" style="' + style + '"></td></tr>';
                                }
                                html += '<tr><td>Survey not started Message</td><td class="copy_frm_default">This survey has not started yet, Please try after $startDateTime</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<input type="text" name="survey_notstart_msg" value="This survey has not started yet, Please try after $startDateTime" style="' + style + '">&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:green;" title="$startDateTime required as input for this message"></i></td></tr>';
                                } else {
                                    html += '<input type="text" name="survey_notstart_msg" value="" style="' + style + '">&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:green;" title="$startDateTime required as input for this message"></i></td></tr>';
                                }
                                html += '<tr><td>'+ type +' expired Message</td><td class="copy_frm_default">Sorry... This '+type+' expired on $endDateTime</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<input type="text" name="survey_exp_msg" value="Sorry... This '+type+' expired on $endDateTime" style="' + style + '">&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:green;" title="$endDateTime required as input for this message"></i></td></tr>';
                                } else {
                                    html += '<input type="text" name="survey_exp_msg" value="" style="' + style + '">&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:green;" title="$endDateTime required as input for this message"></i></td></tr>';
                                }
                                html += '<tr><td>'+ type +' deactivated Message</td><td class="copy_frm_default">Sorry! This '+type+' has been deactivated by the owner. You can not attend it.</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<input type="text" name="survey_deleted_msg" value="Sorry! This '+type+' has been deactivated by the owner. You can not attend it." style="' + style + '"></td></tr>';
                                } else {
                                    html += '<input type="text" name="survey_deleted_msg" value="" style="' + style + '"></td></tr>';
                                }
                                html += '<tr><td>Recipient record deleted Message</td><td class="copy_frm_default">Sorry! This recipient record is deleted by the owner. You can not attend it.</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<input type="text" name="rec_deleted_msg" value="Sorry! This recipient record is deleted by the owner. You can not attend it." style="' + style + '"></td></tr>';
                                } else {
                                    html += '<input type="text" name="rec_deleted_msg" value="" style="' + style + '"></td></tr>';
                                }
                                html += '<tr><td>Resubmit request success Message</td><td class="copy_frm_default">Your request for re-submit '+type+' response is submitted successfully. You will be sent a confirmation email once admin approves your request.Thanks.</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<input type="text" name="resubmit_survey_msg" value="Your request for re-submit '+type+' response is submitted successfully. You will be sent a confirmation email once admin approves your request.Thanks." style="' + style + '"></td></tr>';
                                } else {
                                    html += '<input type="text" name="resubmit_survey_msg" value="" style="' + style + '"></td></tr>';
                                }
                                html += '<tr><td>Resubmit request fail Message</td><td class="copy_frm_default">Your request for re-submit '+type+' response is not submitted.</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<input type="text" name="resubmit_fail_msg" value="Your request for re-submit '+type+' response is not submitted." style="' + style + '"></td></tr>';
                                } else {
                                    html += '<input type="text" name="resubmit_fail_msg" value="" style="' + style + '"></td></tr>';
                                }
                                html += '<tr><td>Resubmit request already sent Message</td><td class="copy_frm_default">You have already requested for re-submit '+type+' response !</td><td class="lang-content">';
                                if ($('#copy_from_default').val() == "1") {
                                    html += '<input type="text" name="resubmit_already_msg" value="You have already requested for re-submit '+type+' response !" style="' + style + '"></td></tr>';
                                } else {
                                    html += '<input type="text" name="resubmit_already_msg" value="" style="' + style + '"></td></tr>';
                                }
                                html += '</table>';
                                html += '<div  class="lang-prev" style="float:left"><input type="button" onclick="change_tab_translate(this)" id="survey_component" value="Prev"></div><div class="lang-next" style="float:right"><input type="button" value="Next" onclick="change_tab_translate(this)" id="advanced_mce"></div>';
                                html += '</div>';
                                html += '<div class="advanced_content" style="display:none;">\n\
                                            <table class="individual_report_table" style="width: 100%;">\n\
                                             <tr class="thead"><th style="width:15%;">Control</th><th style="width:45%;">Default Text</th><th style="width:40%;">Translation</th></tr> \n\
                                            <tr><td>Welcome Page</td><td>';
                                if (welcomePage == '') {
                                    html += '<div>Not Set</div></td><td><div>Not Set</div></td></tr>';
                                }
                                else {
                                    html += `<div class="default-text copy_frm_default"  style="width:600px;overflow-x:auto;height:250px;overflow-y:auto;">{/literal}{$welcome_page}{literal}</div></td><td>`;
                                    if ($('#copy_from_default').val() == "1") {
                                        html += `<textarea id="welcome_page" name="welcome_page" rows="4" cols="60"  tabindex="0" >{/literal}{$welcome_page}{literal}</textarea>`;
                                    }
                                    else {
                                        html += '<textarea id="welcome_page" name="welcome_page" rows="4" cols="60"  tabindex="0" ></textarea>';
                                    }
                                }
                                html += '<tr><td>Thank You Page</td><td>';
                                if (thnksPage == '') {
                                    html += '<div>Not Set</td><td>';
                                    html += '<div>Not Set</div></td></tr>';
                                } else {
                                    html += `<div class="default-text copy_frm_default" style="width:600px;overflow-x:auto;height:250px;overflow-y:auto;">{/literal}{$thanks_page}{literal}</div></td><td>`;
                                    if ($('#copy_from_default').val() == "1") {
                                        html += `<textarea id="thanks_page" name="thanks_page" rows="4" cols="60"  tabindex="0" >{/literal}{$thanks_page}{literal}</textarea>`;
                                    } else {
                                        html += '<textarea id="thanks_page" name="thanks_page" rows="4" cols="60"  tabindex="0" ></textarea>';
                                    }
                                }
                                html += '</table>\n\
                                            <div class="lang-prev" style="float:left"><input type="button" onclick="change_tab_translate(this)" id="message_button" value="Prev"></div><div class="lang-save" style="float:right"><input type="button" value="Save" id="saveLanguagefile" onclick="save_language_translate(this)"></div>\n\
                                        </div>';
                                $('#translate_tab_view').html(html);
                                tinyMCE.init({"convert_urls": false, "valid_children": "+body[style]", "height": 300, "width": "50%", "theme": "advanced", "theme_advanced_toolbar_align": "left", "theme_advanced_toolbar_location": "top", "theme_advanced_buttons1": "code,separator,help,separator,bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,forecolor,backcolor,separator,formatselect", "theme_advanced_buttons2": "selectall,separator,search,separator,replace,separator,bullist,separator,numlist,separator,outdent,separator,indent,separator,undo,separator,redo,separator,link,separator,unlink,separator,anchor,separator,image,separator,hr,separator,insertdate,separator,inserttime", "theme_advanced_buttons3": "", "strict_loading_mode": true, "mode": "exact", "language": "en", "plugins": "advhr,insertdatetime,table,preview,paste,searchreplace,directionality", "elements": null, "extended_valid_elements": "style[dir|lang|media|title|type],hr[class|width|size|noshade],@[class|style]", "content_css": "include\/javascript\/tiny_mce\/themes\/advanced\/skins\/default\/content.css", "gecko_spellcheck": "true", "apply_source_formatting": false, "cleanup_on_startup": true, "relative_urls": false});
                                tinyMCE.execCommand('mceAddControl', false, "thanks_page");
                                tinyMCE.execCommand('mceAddControl', false, "welcome_page");
                                if ($(el).parent().prev().find('i').attr('title') == "Enabled") {

                                    var translated = $('#translated_survey_language').val();
                                    $.ajax({
                                        url: "index.php",
                                        data: {
                                            module: 'bc_survey_language',
                                            action: 'edit_survey_language',
                                            translated_survey: translated,
                                            survey_id: '{/literal}{$survey_id}{literal}',
                                        },
                                        success: function (detail_aaray) {

                                            var detail_language = JSON.parse(detail_aaray);
                                            $.each(detail_language, function (name, value) {

                                               if(name == "welcome_page" || name == "thanks_page"){
                                                    var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}

								var welcome=Base64.decode(value);
								var wlcm = $('<div/>').html(welcome).text();

                                                    $('.survey-form-body').find('[name=' + name + ']').text(wlcm);
                                                    $.each(tinyMCE.editors, function (index, editor) {
                            var idOf = editor.id;
                            var welcomePageContent=$("#welcome_page").val();
                            var thanksPageContent=$("#thanks_page").val();
                           if(idOf == 'welcome_page'){
                               editor.setContent(welcomePageContent);
                           }
                           if(idOf == 'thanks_page'){
                               editor.setContent(thanksPageContent);
                           }
                        });
                                                }else{
                                                    $('.survey-form-body').find('[name=' + name + ']').val(value);
                                                }
                                            });
                                        },
                                    });
                                }
                            },
                        });
                    }
                }
                function add_language_popup() {
                    $('body').append('<div id="backgroundpopup">&nbsp;</div>');
                    $('body').append('<form name="EditView" id="EditView"><div id="survey_main_div" style="width:30%;left: 35%;">' +
                            '<div id="survey_content">' +
                            '<div id="button_div">' +
                            '<table class="Add_Supported_language list view table footable-loaded footable default suite-model-table survey-auto-table" cellpadding="5px" cellspacing="5px" style="width: 100%; ">' +
                            '<thead><tr><th style="width: 100%;" colspan="2"><h2>Add Language</h2></th></tr></thead>' +
                            '<tbody>' +
                            '<tr><td></td><td></td></tr>' +
                            '<tr><td></td><td></td></tr>' +
                            '<tr><td class="lable">Select Language</td><td class="table-desc"><select id="select_language_dropdown" style="width:150px;"><option value="0">Select Language</option>{/literal}{$language}{literal}</select><span style="display:none;">&nbsp;&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:red;"></i></span></td></tr>' +
                            '<tr><td></td><td></td></tr>' +
                            '<tr><td></td><td></td></tr>' +
                            '<tr><td class="lable">Text Direction</td><td class="table-desc"><select id="select_language_direction" style="width:150px;">{/literal}{$language_direction}{literal}</select></span></td></tr>' +
                            '<tr><td></td><td></td></tr>' +
                            '<tr><td></td><td></td></tr>' +
                            '<tr><td class="lable" colspan="2"><input type="checkbox" class="allow_copy">&nbsp;Copy standard messaging for this language from an existing survey ?</td>' +
                            '</tr></tbody>' +
                            '<tr><td></td><td></td></tr>' +
                            '<tr><td></td><td></td></tr>' +
                            '<tfoot><tr><td style="width: 100%;" colspan="2">' +
                            '<input type="button" onclick="save_language(this)" value="Add Language">&nbsp;&nbsp;&nbsp;' +
                            '<input style="float:right" type="button" value="Cancel" onclick="close_survey_div()">' +
                            '</td></tr></tfoot>' +
                            '</table>' +
                            '</div>' +
                            '</div>' +
                            '<a onclick="close_survey_div();" href="javascript:close_survey_div();"></a>' +
                            '</div></form>');
                    $('#backgroundpopup').fadeIn();
                    $('#survey_main_div').fadeIn();
                    $.each($('#select_language_dropdown').find('option'), function () {
                        var lan_name = $(this).val();
                        if ($('#remove_' + lan_name).length == 1) {
                            $(this).remove();
                            $('#remove_' + lan_name).remove();
                        }
                    });
                    var rm_lang = $("#add_language_dropdown").val();
                    var language_name = $("#add_language_dropdown").attr('name');
                    if (language_name != undefined) {
                        $('#select_language_dropdown').append('<option value="' + rm_lang + '">' + language_name + '</option>');
                    }
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
                function save_language(el) {
                    if ($('#select_language_dropdown').val() != "0") {
                        var new_lang = $('#select_language_dropdown').val();
                        var direction = $('#select_language_direction').val();
                        $.ajax({
                            url: "index.php",
                            data: {
                                module: 'bc_survey',
                                action: 'save_new_language',
                                survey_id: '{/literal}{$survey_id}{literal}',
                                newlang: new_lang,
                                direction: direction,
                            },
                            success: function (result) {
                                if ($.trim(result) != "") {

                                    if (direction == "left_to_right") {
                                        $('.retrive_language').append('<tr id="' + new_lang + '"><td style="text-align: center;">' + $('[value=' + new_lang + ']').html() + '&nbsp;&nbsp;<a class="fa fa-pencil" aria-hidden="true" style="cursor: pointer;" onclick="edit_language(this)"></a><td style="text-align: center;"><i class="fa fa-check" style="color:green; font-size:14px;" title="Enabled"></i></td><td style="text-align: center;">Left to Right</td>' + '<td style="text-align: center;"><i class="fa fa-times" style="color:red; font-size:14px;" title="No"></i></td>' + '<td style="text-align: center;"><input type="button" value="Translate Survey" onclick="change_tab(this)" id="translate_survey">&nbsp;&nbsp;&nbsp;<a class="delete_lang" title="Delete Language" onclick="delete_language(this)" href="javascript:void(0);"><img src="custom/include/images/trash.png" style="height: 22px;"></a></td>' + '</tr>');
                                    } else {
                                        $('.retrive_language').append('<tr id="' + new_lang + '"><td style="text-align: center;">' + $('[value=' + new_lang + ']').html() + '&nbsp;&nbsp;<a class="fa fa-pencil" aria-hidden="true" style="cursor: pointer;" onclick="edit_language(this)"></a><td style="text-align: center;"><i class="fa fa-check" style="color:green; font-size:14px;" title="Enabled"></i></td><td style="text-align: center;">Right to Left</td>' + '<td style="text-align: center;"><i class="fa fa-times" style="color:red; font-size:14px;" title="No"></i></td>' + '<td style="text-align: center;"><input type="button" value="Translate Survey" onclick="change_tab(this)" id="translate_survey">&nbsp;&nbsp;&nbsp;<a class="delete_lang" title="Delete Language" onclick="delete_language(this)" href="javascript:void(0);"><img src="custom/include/images/trash.png" style="height: 22px;"></a></td>' + '</tr>');
                                    }
                                    $(el).append('<input type="hidden" id="language_id" value="' + $.trim(result) + '">');
                                    $('body').append('<input type="hidden" id="remove_' + new_lang + '">');
                                    //$('#default_language_dropdown').append('<option value="' + new_lang + '">' + $('[value=' + new_lang + ']').html() + '</option>')
                                    //$('#main').append('<input type="hidden" id="translated_survey_language" value="' + new_lang + '">');
                                    $('#translated_survey_language').val(new_lang);
                                    change_tab(el);
                                    close_survey_div();
                                }
                            }
                        });
                    } else {
                        $('#select_language_dropdown').parent().find('span').show();
                    }
                }
                function delete_language(el) {
                    if (confirm('Are you sure you want to remove this language?')) {
                        var rm_lang = $(el).parent().parent().attr('id');
                        var language_id = $(el).parent().parent().attr('class');
                        $.ajax({
                            url: "index.php",
                            data: {
                                module: 'bc_survey',
                                action: 'delete_language',
                                survey_id: '{/literal}{$survey_id}{literal}',
                                language_id: language_id,
                                remlang: rm_lang,
                            },
                            success: function () {
                                var language_name = $.trim($(el).parent().parent().find('td:first').html());
                                $('#default_language_dropdown').find('[value=' + rm_lang + ']').remove();
                                $('body').append('<input type="hidden" id="add_language_dropdown" name="' + language_name + '" value="' + rm_lang + '">');
                                $(el).parent().parent().remove();
                            },
                        });
                    }
                }

                function copytext(el) {
                    var defaultText = $(el).parent().parent().parent().find('.default-text').html();
                    $.each(tinyMCE.editors, function (index, editor) {
                        var idOf = editor.id;
                        var parentId = $(el).parent().parent().parent().find('#' + idOf);
                        if (parentId.length != '0') {
                            editor.setContent(defaultText);
                        }
                    });
                }
                function change_tab_translate(el) {
                    if ($(el).attr('id') == "survey_component") {
                        $('#survey_component').addClass('active');
                        $('#message_button').removeClass('active');
                        $('#advanced_mce').removeClass('active');
                        $('.survey_component_content').show();
                        $("#survey_thnks_page_button").remove();
                        $("#survey_welcome_page_button").remove();
                        $('.messages').hide();
                        $('.advanced_content').hide();
                    } else if ($(el).attr('id') == "message_button") {
                        $('#message_button').addClass('active');
                        $('#survey_component').removeClass('active');
                        $('#advanced_mce').removeClass('active');
                        $("#survey_thnks_page_button").remove();
                        $("#survey_welcome_page_button").remove();
                        $('.survey_component_content').hide();
                        $('.advanced_content').hide();
                        $('.messages').show();
                    } else {
                        $('#advanced_mce').addClass('active');
                        $('#message_button').removeClass('active');
                        $('#survey_component').removeClass('active');
                        $('.survey_component_content').hide();
                        $('.advanced_content').show();
                        if ($("#thanks_page").length != '0' && $("#survey_thnks_page_button").length == '0') {

                            $("#thanks_page").before("<div id='survey_thnks_page_button'><button type='button' onclick='copytext(this);'>Copy From Default</button></div>");
                        }
                        if ($("#welcome_page").length != '0' && $("#survey_welcome_page_button").length == '0' ) {

                            $("#welcome_page").before("<div id='survey_welcome_page_button'><button type='button' onclick='copytext(this);'>Copy From Default</button></div>");
                        }
                        $('.messages').hide();
                    }
                }
                function edit_language(el) {

                    var language = $(el).parent().parent().attr('id');
                    $.ajax({
                        url: 'index.php',
                        data: {
                            module: 'bc_survey_language',
                            action: 'retrive_language',
                            survey_id: '{/literal}{$survey_id}{literal}',
                            language: language,
                        },
                        success: function (result) {
                            var data = JSON.parse(result);
                            $('body').append('<div id="backgroundpopup">&nbsp;</div>');
                            $('body').append('<form name="EditView" id="EditView"><div id="survey_main_div" style="width:30%;left: 35%;">' +
                                    '<div id="survey_content">' +
                                    '<div id="button_div">' +
                                    '<input type="hidden" id="language_hidden_id" value="' + data.id + '">' +
                                    '<table class="Add_Supported_language list view table footable-loaded footable default suite-model-table survey-auto-table" cellpadding="5px" cellspacing="5px" style="width: 100%; ">' +
                                    '<thead><tr><th style="width: 100%;" colspan="2"><h2>Add Language</h2></th></tr></thead>' +
                                    '<tbody>' +
                                    '<tr><td></td><td></td></tr>' +
                                    '<tr><td></td><td></td></tr>' +
                                    '<tr><td class="lable">Language</td><td class="table-desc">' + $(el).parent().text() + '</td></tr>' +
                                    '<tr><td></td><td></td></tr>' +
                                    '<tr><td></td><td></td></tr>' +
                                    '<tr><td class="lable">Status</td><td class="table-desc"><select id="status_language" style="width:auto;">{/literal}{$status_list}{literal}</select></span></td></tr>' +
                                    '<tr><td></td><td></td></tr>' +
                                    '<tr><td></td><td></td></tr>' +
                                    '<tr><td class="lable">Text Direction</td><td class="table-desc"><select id="select_language_direction" style="width:auto;">{/literal}{$language_direction}{literal}</select></span></td></tr>' +
                                    '</tr></tbody>' +
                                    '<tr><td></td><td></td></tr>' +
                                    '<tr><td></td><td></td></tr>' +
                                    '<tfoot><tr><td style="width: 100%;" colspan="2">' +
                                    '<input type="button" onclick="update_language(this)" value="Update Language">&nbsp;&nbsp;&nbsp;' +
                                    '<input style="float:right" type="button" value="Cancel" onclick="close_survey_div()">' +
                                    '</td></tr></tfoot>' +
                                    '</table>' +
                                    '</div>' +
                                    '</div>' +
                                    '<a onclick="close_survey_div();" href="javascript:close_survey_div();"></a>' +
                                    '</div></form>');
                            $('#backgroundpopup').fadeIn();
                            $('#survey_main_div').fadeIn();
                            $('#main').append('<input type="hidden" id="language_hidden_name" value="' + data.survey_language + '">');
                            $.each($('#status_language').find('option'), function () {
                                if ($(this).val() == data.language_status) {
                                    $(this).attr('selected', true);
                                }
                            });
                            $.each($('#select_language_direction').find('option'), function () {
                                if ($(this).val() == data.direction) {
                                    $(this).attr('selected', true);
                                }
                            });
                        }
                    });
                }
                function update_language(el) {

                    var status = $('#status_language').val();
                    var direction = $('#select_language_direction').val();
                    var language_id = $('#language_hidden_id').val();
                    $.ajax({
                        url: 'index.php',
                        data: {
                            module: 'bc_survey_language',
                            action: 'updatetext_direction',
                            status: status,
                            direction: direction,
                            survey_id: '{/literal}{$survey_id}{literal}',
                            language_id: language_id,
                        },
                        success: function (result) {
                            if (status == "enable") {
                                $('#' + $('#language_hidden_name').val()).find('td:first').next().html('<i class="fa fa-check" style="color:green; font-size:14px;" title="Enabled"></i>');
                                $('#default_language_dropdown').find('[value=' + $('#language_hidden_name').val() + ']').show()
                            } else {
                                $('#' + $('#language_hidden_name').val()).find('td:first').next().html('<i class="fa fa-ban" style="color:red;font-size:14px;" title="Disabled"></i>');
                                $('#default_language_dropdown').find('[value=' + $('#language_hidden_name').val() + ']').hide()
                            }
                            if (direction == "right_to_left") {
                                $('#' + $('#language_hidden_name').val()).find('td:first').next().next().html('Right to Left');
                            } else {
                                $('#' + $('#language_hidden_name').val()).find('td:first').next().next().html('Left to Right');
                            }

                            $('#language_hidden_name').remove();
                            close_survey_div();
                        }
                    });
                }
                function save_language_translate(el) {
                    var count = 0;
                    var question_wise_data = new Object();
                    $('#welcome_page').text($('#welcome_page_ifr').contents().find("body").html());
                    $('#thanks_page').text($('#thanks_page_ifr').contents().find("body").html());
                    $.each($('.survey-form-body').find('input[type=text]'), function () {
                        question_wise_data[$(this).attr('name')] = this.value
                        if (this.value == '') {
                            count = 1;
                        }
                    });
                    $.each($('.survey-form-body').find('textarea'), function () {
                        question_wise_data[$(this).attr('name')] = this.value;
                        if (this.value == '') {
                            count = 1;
                        }
                    });

                    var language_id = $('#survey_language_id').val();
                    var msg = '';
                    if (count == 1) {
                        if(confirm("Form contains empty input as well. Are you sure you want to continue with default language?")){
                         $.ajax({
                        url: 'index.php',
                        type: "POST",
                        data: {
                            module: 'bc_survey_language',
                            action: 'save_language_translation',
                            survey_id: '{/literal}{$survey_id}{literal}',
                            json_array: question_wise_data,
                            language_id: language_id,
                        },
                        success: function (result) {
                                alert("Data Save Successfully.");
                                location.reload();
                        },
                    });
                    }
                    }else{
                        $.ajax({
                            url: 'index.php',
                            type: "POST",
                            data: {
                                module: 'bc_survey_language',
                                action: 'save_language_translation',
                                survey_id: '{/literal}{$survey_id}{literal}',
                                json_array: question_wise_data,
                                language_id: language_id,
                            },
                            success: function (result) {
                                alert("Data Save Successfully.");
                                location.reload();
                            },
                        });
                    }

                }
                function save_default_language_dropdown(el) {

                    var lan_name_short = $('#default_language_dropdown').val();
                    var language_name = $('#default_language_dropdown').find("option:selected").text();
                    if (confirm('Are You sure to Change Default language as ' + language_name)) {
                        $.ajax({
                            url: 'index.php',
                            data: {
                                module: 'bc_survey',
                                action: 'save_DefaultLanguage',
                                survey_id: '{/literal}{$survey_id}{literal}',
                                langauge_name: lan_name_short,
                            },
                            success: function (result) {
                                alert("Default Language is Successfully Change to " + language_name);
                            },
                        });
                    }
                }
            </script>
        {/literal}
        <link href="custom/include/css/survey_css/survey.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="custom/include/css/font-awesome/css/font-awesome.min.css"/>
    </head>
    <div class="survey-report-title"><div class="f-left"><label class="report_title">SURVEY TRANSLATION</label></div>

    <div class="f-right"> <a class="button back-to-survey" title="Back To Survey" id="transalte_survey" href="index.php?module=bc_survey&action=DetailView&record={$survey_id}">Back to Survey</a>
    </div>

</div>
    <div class="survey-form-body" style="min-height: 651px;">

        {$hidden}
        <div class="report_table_title">
            <a id="language" onclick="change_tab(this)" class="report_title active">Language</a>
            <a id ="translate" onclick="change_tab(this)" class="report_title" style="display:none;">Translate Survey</a>
        </div>
        <div class="report-tab-content">
            <div class="report_header">{$survey_name}</div>
        </div>
        <div id="language_tab_view" style="max-height: 500px; overflow-y: auto">
            <div style="float: left"><h1>Language</h1></div>

            <div style="float: right">Survey Default Language
                <select id="default_language_dropdown">
                    <option value="{$default_key}">{$default_language}</option>
                    {$retrive_record}
                </select>
                <input type="button" id="default_language_save" value="Save" onclick = "save_default_language_dropdown(this)"/>
            </div>
            <br><br>
            <input type="button" value="Add Language" onclick="add_language_popup()">
            <br><br><br><br>
            <table class="individual_report_table retrive_language" style="width:100%;" border="1" cellpadding="10" CELLSPACING="10">
                <tr class="head">
                    <th style="text-align: center;">Language</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">Text Direction</th>
                    <th style="text-align: center;">Translated</th>
                    <th style="text-align: center;">Action</th>
                </tr>
                <tr id="{$default_key}">
                    <td style="text-align: center;">{$default_language}</td>
                    <td style="text-align: center;"><i class="fa fa-check" style="color:green; font-size:14px;" title="Enabled"></i></td>
                    <td style="text-align: center;">Left to Right</td>
                    <td style="text-align: center;"><i class="fa fa-check" style="color:green; font-size:14px;" title="Yes"></i></td>
                    <td style="text-align: center;"></td>
                </tr>
            </table>
        </div>
        <div id="translate_tab_view" style="display:none;">
        </div>
    </div>
</html>