<?php
    if($_POST['global_language']){
        GlobalLanguage::changeGlobalLanguage($_POST['global_language']);   
    } 
    
    /**
    * Class Change Global Language in Footer - by MTN - 06/05/2015
    */
    class GlobalLanguage{
        function changeGlobalLanguage($language){
            if(!empty($language)){
                $GLOBALS['current_language']  = $language;
                $_SESSION['authenticated_user_language'] = $language;
                $GLOBALS['mod_strings'] = return_module_language($language, "Users");
                $GLOBALS['app_strings'] = return_application_language($language);
                setcookie('login_language', $language); 
                echo 'true';
            }
            else{                            
                echo 'false';
            }
        }
    }
?>
