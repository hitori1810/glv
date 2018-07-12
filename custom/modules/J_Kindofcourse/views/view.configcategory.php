<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class J_KindofcourseViewConfigCategory extends SugarView{   
    public function display(){                                 
        global $mod_strings, $app_strings, $app_list_strings, $current_user; 
                      
        if(!ACLController::checkAccess('J_Kindofcourse', 'import', true)){
            die($app_strings['LBL_EMAIL_DELETE_ERROR_DESC']);    
        }                                

        // Load config 
        $kocCategory        = $app_list_strings['kind_of_course_list'];
        $levelCategory      = $app_list_strings['level_program_list'];
        $moduleCategory     = $app_list_strings['module_program_list'];
        $fullKocList        = $app_list_strings['full_kind_of_course_list'];

        //unset none option
        unset($kocCategory['']);
        unset($kocCategory['Other']);
        unset($levelCategory['']);
        unset($moduleCategory['']);
        unset($fullKocList['']);       
        
        $smarty = new Sugar_Smarty();
        $smarty->assign('APP', $app_strings);  
        $smarty->assign('MOD', $mod_strings);
                          
        $smarty->assign('KOC_OPTIONS', $this->convertToTextArea($kocCategory));
        $smarty->assign('LEVEL_OPTIONS', $this->convertToTextArea($levelCategory));
        $smarty->assign('MODULE_OPTIONS', $this->convertToTextArea($moduleCategory));  
        $smarty->assign('PT_RESULT_OPTIONS', $this->convertToTextArea($fullKocList));   
        
        
        
          
        echo $smarty->fetch("custom/modules/J_Kindofcourse/tpls/ConfigCategory.tpl");
    }
    
    function convertToTextArea($input){
        $str = "";
        
        foreach($input as $value){ 
            $str .= $value;    
            $str .= "\r\n";    
        }
        
        return $str;
    }
}