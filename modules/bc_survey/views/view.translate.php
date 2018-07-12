<?php

require_once('include/MVC/View/SugarView.php');
require_once("include/Sugar_Smarty.php");

class bc_SurveyViewTranslate extends SugarView {

    function __construct() {
        parent::SugarView();
    }

    function display() {
        global $app_list_strings, $sugar_config, $db;
        $survey_id = $_REQUEST['survey_id'];
        echo '<script src="custom/include/js/survey_js/jquery-1.10.2.js"></script>';
        echo '<link href="custom/include/css/survey_css/jquery-ui.css" rel="stylesheet">';
        echo "<link rel='stylesheet' href='custom/include/css/survey_css/general_report.css' type='text/css'>";
        $language = $app_list_strings['survey_language_dom'];
        $language_direction = $app_list_strings['text_direction_list'];
        $language_status = $app_list_strings['language_status_list'];
        $survey_obj = new bc_survey();
        $survey_obj->retrieve($survey_id);
        if ($survey_obj->supported_survey_language) {
            $supported_language = unencodeMultienum($survey_obj->supported_survey_language);
        }
        foreach ($language as $key => $value) {
            if ($survey_obj->default_survey_language != $key && !in_array($key, $supported_language)) {
                $option_language .= '<option value="' . $key . '">' . $value . '</option>';
            }
        }
        $query = "SELECT * FROM bc_survey_language WHERE bc_survey_id_c='{$survey_id}' AND deleted=0";
        $result = $db->query($query);
        $html = '';
        $option_default = '';
        while ($row = $db->fetchByAssoc($result)) {
            $html .= '<tr id="' . $row['survey_language'] . '" class="' . $row['id'] . '"><td style="text-align: center;">' . $app_list_strings['survey_language_dom'][$row['survey_language']] . '&nbsp;&nbsp;<a class="fa fa-pencil" aria-hidden="true" style="cursor: pointer;" onclick="edit_language(this)"></a></td>';
            if ($row['language_status'] == "enable") {
                $html .= '<td style="text-align: center;"><i class="fa fa-check" style="color:green; font-size:14px;" title="Enabled"></i></td>';
            } else {
                $html .= '<td style="text-align: center;"><i class="fa fa-ban" style="color:red;font-size:14px;" title="Disabled"></i></td>';
            }
            $html .= '<td style="text-align: center;">' . $app_list_strings['text_direction_list'][$row['text_direction']] . '</td>';
            if ($row['translated'] == 1) {
                $html .= '<td style="text-align: center;"><i class="fa fa-check" style="color:green; font-size:14px;" title="Enabled"></i></td>';
            } else {
                $html .= '<td style="text-align: center;"><i class="fa fa-times" style="color:red; font-size:14px;" title="No"></i></td>';
            }
            $html .= '<td style="text-align: center;"><input type="button" value="Translate Survey" onclick="change_tab(this)" id="translate_survey">&nbsp;&nbsp;&nbsp;<a class="delete_lang" title="Delete Language" onclick="delete_language(this)" href="javascript:void(0);"><img src="custom/include/images/trash.png" style="height: 22px;"></a></td>';
            $html .= '</tr>';
            if ($row['translated'] == 1 ) {
                if($survey_obj->default_survey_language == $row['survey_language']){
                    $selected = "selected";
                    if($row['language_status'] == "disable"){
                        $style="disply:none;";
                    }else{
                        $style="";
                    }
                }else{
                    $selected = '';
                }
                $option_default .= '<option style="'.$style.'" value="' . $row['survey_language'] . '"'.$selected.'>' . $app_list_strings['survey_language_dom'][$row['survey_language']] . '</option>';
            }
        }
        $default_language = $app_list_strings['survey_language_dom'][$sugar_config['default_language']];
        $default_key = $sugar_config['default_language'];

        foreach ($language_direction as $k => $directions) {
            $text_direction .= '<option value="' . $k . '">' . $directions . '</option>';
        }
        foreach ($language_status as $ke => $status) {
            $status_list .= '<option value="' . $ke . '">' . $status . '</option>';
        }
        $wlcome_page = $survey_obj->welcome_page;
        $thanks_page = $survey_obj->thanks_page;
        $hidden = '<input type="hidden" id="survey_language_id" value="">';
        $hidden .='<input type="hidden" id="translated_survey_language" value="">';
        $hidden .='<input type="hidden" id="copy_from_default" value="">';

        $sugarSmarty = new Sugar_Smarty();
        $sugarSmarty->assign('survey_id', $survey_id);
        $sugarSmarty->assign('survey_name', $survey_obj->name);
        $sugarSmarty->assign('retrive_record', $option_default);
        $sugarSmarty->assign('default_language', $default_language);
        $sugarSmarty->assign('records_tr', $html);
        $sugarSmarty->assign('hidden', $hidden);
        $sugarSmarty->assign('language', $option_language);
        $sugarSmarty->assign('default_key', $default_key);
        $sugarSmarty->assign('language_direction', $text_direction);
        $sugarSmarty->assign('status_list', $status_list);
        $sugarSmarty->assign('welcome_page', html_entity_decode($wlcome_page));
        $sugarSmarty->assign('thanks_page', html_entity_decode($thanks_page));
        $sugarSmarty->display('modules/bc_survey/tpl/translate.tpl');
    }

}
