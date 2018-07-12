<?php 
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point'); 
class AutoCode{
    function addCode(&$bean, $event, $arguments)
    {
        require_once("modules/C_ConfigID/AutoCodeHelper.php");
        $module_name    = $bean->module_name;
        $table          = $bean->table_name;
        $sql = "SELECT name, code_separator, code_field, is_reset, custom_table, add_date, date_format, zero_padding, first_num FROM c_configid WHERE custom_table = '{$table}' AND deleted = 0 ORDER BY date_entered DESC LIMIT 1;";
        $res = $GLOBALS['db']->query($sql);
        if($r = $GLOBALS['db']->fetchByAssoc($res)){
            if((!isset($bean->fetched_row[$r['code_field']]) || $bean->fetched_row[$r['code_field']]=='') ) {
                $bean->$r['code_field'] = AutoCodeHelper::createCode($module_name); 
            }
        }
    }
}
?>