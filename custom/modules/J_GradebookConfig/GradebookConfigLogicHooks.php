<?php
class GradebookConfigLogicHooks{
    function setName(&$bean, $event, $arg) {
        if(empty($bean->name)){
            $teamCode = $GLOBALS['db']->getOne("SELECT short_name FROM teams WHERE id='{$bean->team_id}'");
            $bean->name = $teamCode.'--'.$bean->type;
            if(!empty($bean->minitest)) $bean->name .= '-'.$bean->minitest;    
        }
    }
}
?>
