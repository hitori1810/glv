<?php
// Load config   
if(empty($GLOBALS['app_list_strings']['kind_of_course_list'] )){
    $admin = new Administration();  
    $admin->retrieveSettings();    

    $app_list_strings['kind_of_course_list']       = json_decode(html_entity_decode($admin->settings['custom_category_course']),true, 512, JSON_UNESCAPED_UNICODE);        
    $app_list_strings['kind_of_course_list']['Other'] = 'Other';

    $app_list_strings['level_program_list']        = json_decode(html_entity_decode($admin->settings['custom_category_level']),true, 512, JSON_UNESCAPED_UNICODE);        
    $app_list_strings['module_program_list']       = json_decode(html_entity_decode($admin->settings['custom_category_module']),true, 512, JSON_UNESCAPED_UNICODE);        
    $app_list_strings['full_kind_of_course_list']  = json_decode(html_entity_decode($admin->settings['custom_category_pt_result']),true, 512, JSON_UNESCAPED_UNICODE); 
}

?>
