<?php
switch ($_POST['type']) {
    case 'ajaxSaveConfigCategory':
        echo ajaxSaveConfigCategory();
        break;    
}    
die();

function ajaxSaveConfigCategory(){                                               
    $kocCategory        = convertTextToArray($_POST['koc_options']);
    $levelCategory      = convertTextToArray($_POST['level_options']);
    $moduleCategory     = convertTextToArray($_POST['module_options']);     
    $fullKocList        = convertTextToArray($_POST['pt_result_options']);         

    $admin = new Administration();  
    $admin->retrieveSettings();  
    $admin->saveSetting('custom', 'category_course', json_encode($kocCategory, JSON_UNESCAPED_UNICODE));
    $admin->saveSetting('custom', 'category_level', json_encode($levelCategory, JSON_UNESCAPED_UNICODE));
    $admin->saveSetting('custom', 'category_module', json_encode($moduleCategory, JSON_UNESCAPED_UNICODE));
    $admin->saveSetting('custom', 'category_pt_result', json_encode($fullKocList, JSON_UNESCAPED_UNICODE));

    return json_encode(array(
        "success" => 1,              
    ));      
}   

function convertTextToArray($text){
    $array = explode("\n",$text);
    $resultArr = array(
        '' => '-none-'
    );  

    foreach($array as $key => $value){
        $value = trim($value);
        if(!empty($value)){
            $resultArr[$value] = $value;    
        }
    }

    return $resultArr;
}

function generateExportContent($listStringName, $array){
    $output = "";  
    $output .= "
    \n\$app_list_strings['$listStringName'] = array(\n";         

    foreach($array as $key => $value){
        $output .= "\t'$key' => '$value',\n";        
    }

    if($listStringName == 'kind_of_course_list'){
        $output .= "\t'Other' => 'Other',\n";      
    }

    $output .= "\n);";   

    return $output;   
}

?>
