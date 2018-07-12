<?php
    global $moduleList, $current_language;
    $json = getJSONobj();
    $jsLang = '';
    
    for($i = 0; $i < count($moduleList); $i++){
        $moduleName = $moduleList[$i];
        if(!empty($moduleName)) {
            $moduleLanguage = return_module_language($current_language, $moduleName, true);
            $jsLang .= 'SUGAR.language.languages["'. $moduleName .'"] = '. $json->encode($moduleLanguage) .';';
        }
    }
    header('Content-Type: application/javascript');
    echo $jsLang;
?>
