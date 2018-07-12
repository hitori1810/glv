<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
class logicHolidays{
    function handleRemoveRelationship(&$bean, $event, $arguments){
        if($arguments['related_module'] == 'C_Teachers' ){
            $GLOBALS['db']->query("UPDATE holidays SET deleted = 1 WHERE id = '{$bean->id}'");
        }
    }
}
?>