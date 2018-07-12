{*
/**
 * EditView Of Survey Template Module And Survey Module 
 * 
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
*}
{literal}
    <script type="text/javascript" src="custom/include/js/survey_js/drag-drop.js"></script>
    <link rel="stylesheet" type="text/css" href="custom/include/css/font-awesome/css/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="custom/include/modules/bc_survey_template/css/survey.css" />      
    <script type="text/javascript" src="custom/include/js/survey_js/validation.js"></script>
    <script type="text/javascript" src="custom/include/js/survey_js/jquery.multiple.select.js"></script>
    <script type="text/javascript">
        //for scrollable left sidebar    
        $(function () {
            var $sidebar = $("#left-nav"),
                    $window = $(window),
                    offset = $sidebar.offset(),
                    topPadding = 0;

            $window.scroll(function () {
                if ($window.scrollTop() > offset.top) {
                    $sidebar.stop().css({
                        marginTop: $window.scrollTop() - offset.top + topPadding
                    });
                } else {
                    $sidebar.stop().css({
                        marginTop: 0
                    });
                }
            });
            if ($('#EditView').find("input[name='module']").val() == "bc_survey") {
                $(".component").show();
                $(".template").hide();
            } else {
                $(".template").show();
                $(".component").hide();
            }
            $(".survey_theme_image").click(function (event) {
                $(event.currentTarget).parent().find('input[type="radio"]').prop('checked', true);
            });
            $(".theme-label").click(function (event) {
                $(event.currentTarget).parent().find('input[type="radio"]').prop('checked', true);
            });
        });
    </script>
{/literal}   
<img src="themes/default/images/loading.gif" id="loading-image"  class="ajax-loader" style="display:none; left: 30%; top: 70%; position: absolute;"/>
<div class="upgraded-survey-layout">
    <div id="right-nav">
        <input type="hidden" name="page_no" value="0" id="last_page_no" />
        <input type="hidden" name="record_id" id="record_id" />
        <div class="add-pages">
            <div class="SurveyPage" tabindex="-1">
                <div class="add-survey-page">
                    <div align="center">
                        <p align="center">Add a Survey Page</p>
                        <a><i style="opacity:0.8; cursor: pointer" class="fa fa-plus fa-4x" id="plus-image"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="left-nav">
        <div class="component">
            <a class="advance_tab active tab-left" id="page" style="width: 43%;" onclick="change_pagecompo(this);"><i class="fa  fa-file-o" title="close" style="font-size: 15px;" tabindex="-1"></i> &nbsp;&nbsp;Page Component</a>
            <a class="advance_tab tab-right" id="theme" style="width: 43%;" onclick="change_pagecompo(this);"><i class="fa fa-dashboard" style="font-size: 15px;" title="open" tabindex="-1"></i>&nbsp;&nbsp;Survey Theme</a>
        </div>
        <div class="template" style="background-color: #c5c5c5; padding: 12px; font-weight: bold;">
            <center><i class="fa  fa-file-o" title="close" style="font-size: 15px;" tabindex="-1"></i> &nbsp; &nbsp;Page Component</center>
        </div>
        <div class="list-group">
            <div class="new-page">
                <div class="btn_icon"><i class="fa fa-file-o" aria-hidden="true"></i></div><div style="display: inline-block;margin-top: 7px;"><p>&nbsp; New Page</p></div>
            </div>
            <div>
                <div style="float:left; " class="Checkbox">
                    <div class="btn_icon"><i class="fa  fa-check-square-o" aria-hidden="true"></i></div><div style="display: inline-block;margin-top: 7px;"><p>&nbsp; CheckBox</p></div>
                </div>
                <div style="float:right; " class="DrodownList">
                    <div class="btn_icon"><i class="fa fa-chevron-down" aria-hidden="true"></i></div><div style="display: inline-block;margin-top: 7px;"><p>&nbsp; Dropdown List</p></div>
                </div>
            </div>
            <div>
                 <div style="float:left; " class="RadioButton">
                    <div class="btn_icon"><i class="fa fa-dot-circle-o" aria-hidden="true"></i></div><div style="display: inline-block;margin-top: 7px;"><p>&nbsp; Radio Button</p></div>
                </div>
                <div style="float:right; " class="MultiSelectList">
                    <div class="btn_icon"><i class="fa fa-list-ul" aria-hidden="true"></i></div><div style="display: inline-block;margin-top: 7px;"><p>&nbsp; MultiSelect List</p></div>
                </div>
            </div>
            <div>
                <div style="float:left; " class="Matrix">
                    <div class="btn_icon"><i class="fa fa-th" aria-hidden="true"></i></div><div style="display: inline-block;margin-top: 7px;"><p>&nbsp;Matrix / Grid</p></div>
                </div>
                <div style="float:right; " class="Date">
                    <div class="btn_icon"><i class="fa fa-calendar" aria-hidden="true"></i></div><div style="display: inline-block;margin-top: 7px;"><p>&nbsp;    DateTime </p></div>
                </div>
            </div>
            <div>
                <div style="float:left; " class="Textbox">
                    <div class="btn_icon"><i class="fa fa-file-text-o" aria-hidden="true"></i></div><div style="display: inline-block;margin-top: 7px;"><p>&nbsp; Textbox</p></div>
                </div>
                <div style="float:right; " class="CommentTextbox">
                    <div class="btn_icon"><i class="fa fa-comments-o" aria-hidden="true"></i></div><div style="display: inline-block;margin-top: 7px;"><p>&nbsp; Comment Textbox</p></div>
                </div>
            </div>
            <div>
                <div style="float:left; " class="Scale">
                    <div class="btn_icon"><i class="fa fa-arrows-h" aria-hidden="true"></i></div><div style="display: inline-block;margin-top: 7px;"><p>&nbsp;  Scale </p></div>
                </div>

                <div style="float:right; " class="Rating">
                    <div class="btn_icon"><i class="fa fa-star" aria-hidden="true"></i></div><div style="display: inline-block;margin-top: 7px;"><p>&nbsp; Rating</p></div>
                </div>
            </div>
            <div>
                <div style="float:left; " class="Image">
                    <div class="btn_icon"><i class="fa fa-picture-o" aria-hidden="true"></i></div><div style="display: inline-block;margin-top: 7px;"><p>&nbsp;  Image </p></div>
                </div>
                <div style="float:right; " class="Video">
                    <div class="btn_icon"><i class="fa fa-video-camera" aria-hidden="true"></i></div><div style="display: inline-block;margin-top: 7px;"><p>&nbsp;  Video </p></div>
                </div>
            </div>
            <div>
               <div style="float:left;" class="ContactInformation">
                   <div class="btn_icon"><i class="fa fa-list-alt" aria-hidden="true"></i></div><div style="display: inline-block;margin-top: 7px;"><p>&nbsp; Contact Information</p></div>
                </div>
            </div>

        </div>
        <div class="custom_theme_inner" style='display:none;'>
            <div class="accordion-inner" id='custom_theme_data'>
                <div class="theme_selection">
                    <div style="vertical-align: top; margin:5px;" class="SurveyTheme">
                        <div>
                            <input type="radio" name="survey_theme" value="theme1" checked="checked" aria-label="Survey Theme" class="theme-radio">
                            <label class="theme-label">Innovative</label>
                        </div>
                        <label class='survey_theme_image'  style="background: url(custom/include/survey-img/theme1-hover.png); width: 100%;  color:#fff; ">
                            <img src="custom/include/survey-img/theme1-hover.png" class="zoom">
                        </label>
                    </div>

                    <div style="vertical-align: top; margin:5px;" class="SurveyTheme">
                        <div>
                            <input type="radio" name="survey_theme" value="theme2" aria-label="Survey Theme" class="theme-radio">
                            <label class="theme-label">Ultimate</label>
                        </div>
                        <label class='survey_theme_image' style="background: url(custom/include/survey-img/theme2-hover.png);width: 100%;  color:white;">
                            <img src="custom/include/survey-img/theme2-hover.png" class="zoom">
                        </label>
                    </div>

                    <div style="vertical-align: top; margin:5px;" class="SurveyTheme">
                        <div>
                            <input type="radio" name="survey_theme" value="theme3" aria-label="Survey Theme" class="theme-radio">
                            <label class="theme-label">Incredible</label>
                        </div>
                        <label class='survey_theme_image' style="background: url(custom/include/survey-img/theme3-hover.png); width: 100%;  color:white;">
                            <img src="custom/include/survey-img/theme3-hover.png" class="zoom">
                        </label>
                    </div>

                    <div style="vertical-align: top; margin:5px;" class="SurveyTheme">
                        <div>
                            <input type="radio" name="survey_theme" value="theme4" aria-label="Survey Theme" class="theme-radio">
                            <label class="theme-label">Agile</label>
                        </div>
                        <label class='survey_theme_image' style="background:url(custom/include/survey-img/theme4-hover.png); width: 100%;  color:white;">
                            <img src="custom/include/survey-img/theme4-hover.png" class="zoom">
                        </label>
                    </div>

                    <div style="vertical-align: top; margin:5px;" class="SurveyTheme">
                        <div>
                            <input type="radio" name="survey_theme" value="theme5" aria-label="Survey Theme" class="theme-radio">
                            <label class="theme-label">Contemporary</label>
                        </div>
                        <label class='survey_theme_image' style="background: url(custom/include/survey-img/theme5-hover.png); width: 100%;  color:white;">
                            <img src="custom/include/survey-img/theme5-hover.png" class="zoom">
                        </label>
                    </div>

                    <div style="vertical-align: top; margin:5px;" class="SurveyTheme">
                        <div>
                            <input type="radio" name="survey_theme" value="theme6" aria-label="Survey Theme" class="theme-radio">
                            <label class="theme-label">Creative</label>
                        </div>
                        <label class='survey_theme_image' style="background: url(custom/include/survey-img/theme6-hover.png); width: 100%;  color:white;">
                            <img src="custom/include/survey-img/theme6-hover.png" class="zoom">
                        </label>
                    </div>

                    <div style="vertical-align: top; margin:5px;" class="SurveyTheme">
                        <div>
                            <input type="radio" name="survey_theme" value="theme7" aria-label="Survey Theme" class="theme-radio">
                            <label class="theme-label">Proffesional</label>
                        </div>
                        <label class='survey_theme_image' style="background: url(custom/include/survey-img/theme7-hover.png); width: 100%;  color:white;">
                            <img src="custom/include/survey-img/theme7-hover.png" class="zoom">
                        </label>
                    </div>

                    <div style="vertical-align: top; margin:5px;" class="SurveyTheme">
                        <div>
                            <input type="radio" name="survey_theme" value="theme8" aria-label="Survey Theme" class="theme-radio">
                            <label class="theme-label">Elegant</label>
                        </div>
                        <label class='survey_theme_image' style="background: url(custom/include/survey-img/theme8-hover.png); width: 100%;  color:white;">
                            <img src="custom/include/survey-img/theme8-hover.png" class="zoom">
                        </label>
                    </div>

                    <div style="vertical-align: top; margin:5px;" class="SurveyTheme">
                        <div>
                            <input type="radio" name="survey_theme" value="theme9" aria-label="Survey Theme" class="theme-radio">
                            <label class="theme-label">Automated</label>
                        </div>
                        <label class='survey_theme_image' style="background: url(custom/include/survey-img/theme9-hover.png); width: 100%;  color:white;">
                            <img src="custom/include/survey-img/theme9-hover.png" class="zoom">
                        </label>
                    </div>

                    <div style="vertical-align: top; margin:5px;" class="SurveyTheme">
                        <div>
                            <input type="radio" name="survey_theme" value="theme10" aria-label="Survey Theme" class="theme-radio">
                            <label class="theme-label">Exclusive</label>
                        </div>
                        <label class='survey_theme_image' style="background: url(custom/include/survey-img/theme10-hover.png); width: 100%;  color:white;">
                            <img src="custom/include/survey-img/theme10-hover.png" class="zoom">
                        </label>
                    </div>
                     <div></div>
                </div>
            </div>
        </div>
    </div>
</div>
