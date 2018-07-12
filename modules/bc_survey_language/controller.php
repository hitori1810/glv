<?php
require_once('include/MVC/Controller/SugarController.php');
include_once 'custom/include/pagination.class.php';

class bc_survey_languageController extends SugarController {
    function action_updatetext_direction(){
        $oLanguage = new bc_survey_language();
        $language_id=$_REQUEST['language_id'];
        $oLanguage->retrieve($language_id);
        $direction =$_REQUEST['direction'];
        $status =$_REQUEST['status'];
        $survey_id = $_REQUEST['survey_id'];
        $language = $_REQUEST['language'];
        $oLanguage->text_direction=$direction;
        $oLanguage->survey_language=$language;
        $oLanguage->language_status=$status;
        $oLanguage->bc_survey_id_c=$survey_id;
        $oLanguage->translated=0;
        $oLanguage->save();
        echo "done";
        exit;
   }
   function action_retrive_language(){
       global $db;
       $query = "SELECT * FROM bc_survey_language WHERE bc_survey_id_c='{$_REQUEST['survey_id']}' AND survey_language='{$_REQUEST['language']}' AND deleted=0";
       $result=$db->query($query);
       $row=$db->fetchByAssoc($result);
       $array_lang['id']=$row['id'];
       $array_lang['direction']=$row['text_direction'];
       $array_lang['language_status']=$row['language_status'];
       $array_lang['survey_language']=$row['survey_language'];
       echo json_encode($array_lang);
       exit;
   }
   function action_save_language_translation() {
       $array = $_REQUEST['json_array'];

        $survey_id = $_REQUEST['survey_id'];
        $survey_lang_id = $_REQUEST['language_id'];
        $paramsToSave = $array;
      //  $GLOBALS['log']->fatal("This is the params : " . print_r($paramsToSave, 1));
        if (!empty($survey_lang_id) && $args['type'] != 'msg') { // edit record
            $oSurveyLang = BeanFactory::getBean('bc_survey_language', $survey_lang_id);
            $oSurveyLang->translated = 1;
            $oSurveyLang->save();
        }

        // Save language labels to custom file
        require_once('modules/ModuleBuilder/MB/ModuleBuilder.php');
        require_once('modules/ModuleBuilder/parsers/parser.dropdown.php');
        global $app_list_strings;
        $parser = new ParserDropDown();
        $params = array();
        $_REQUEST['view_package'] = 'studio'; //need this in parser.dropdown.php
        $_REQUEST['dropdown_lang'] = $oSurveyLang->survey_language;
        $params['view_package'] = $_REQUEST['view_package'];
        $params['dropdown_name'] = $survey_id; //replace with the dropdown name with survey id
        $params['dropdown_lang'] = $oSurveyLang->survey_language;
        $params['use_push'] = 'yes';
        $dropdwon_list = $app_list_strings[$params['dropdown_name']];

        foreach ($array as $key => $value) {
            if (($key == 'welcome_page' || $key == 'thanks_page') && !empty($value)) {
                $value = base64_encode($value);
            }
            if (!empty($key) && $key != 'undefined') {
                if (is_object($value)) {
                    $new_array = array();
                    foreach ($value as $array_key => $array_value) {
                        $drop_list[] = array($key . _ . $array_key, $array_value);
                    }
                } else {
                    $drop_list[] = array($key, $value);
                }
            }
        }
      //  $GLOBALS['log']->fatal("This is the params of dropdown : " . print_r($drop_list, 1));
        $params['list_value'] = json_encode($drop_list);
        $parser->saveDropDown($params);
        return $oSurveyLang->id;
    }
    function action_edit_survey_language(){
        global $app_list_strings;
        $default_survey_language = $_REQUEST['translated_survey'];
        $survey_id=$_REQUEST['survey_id'];
        $survey_translated_array = return_app_list_strings_language($default_survey_language)[$survey_id];
        echo json_encode($survey_translated_array);
        exit;
        
    }
}