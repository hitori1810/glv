<?php
class GradebookDetailLogicHooks{

    function showDetail(&$bean, $event, $arg) {
        if(strlen($bean->description) > 30) {
            $bean->description = "<span title = '{$bean->description}'>"
            .(substr($bean->description,0,25)."...")."</span>";

        }
        if(strpos($bean->gradebook_name, 'Overall') !== false) {

        }else{
             $bean->certificate_type = '';
             $bean->certificate_level = '';
        }
        //    $bean->final_result_text = "<span>". ($bean->final_result * 100) ."</span>";
    }
}
?>
