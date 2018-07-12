<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class before_save_teacher {
    function addCode(&$bean, $event, $arguments){
        $code_field = 'teacher_id';
        if(empty($bean->$code_field)){
        //Get Prefix
            $res        = $GLOBALS['db']->query("SELECT code_prefix, team_type FROM teams WHERE id = '{$bean->team_id}'");
            $row        = $GLOBALS['db']->fetchByAssoc($res);
            $prefix     = $row['code_prefix'];
            $table      = $bean->table_name;
            $sep        = '-';
            $first_pad  = '00000';
            $padding    = 5;
            $query      = "SELECT $code_field FROM $table WHERE ( $code_field <> '' AND $code_field IS NOT NULL) AND id != '{$bean->id}' AND (LEFT($code_field, ".strlen($prefix).") = '".$prefix."') ORDER BY RIGHT($code_field, $padding) DESC LIMIT 1";
            $result = $GLOBALS['db']->query($query);

            if($row = $GLOBALS['db']->fetchByAssoc($result))
                $last_code = $row[$code_field];
            else
                //no codes exist, generate default - PREFIX + CURRENT YEAR +  SEPARATOR + FIRST NUM
                $last_code = $prefix . $sep . $type . '00000';

            $num = substr($last_code, -$padding, $padding);
            $num++;
            $pads = $padding - strlen($num);
            $new_code = $prefix . $sep . $type;

            //preform the lead padding 0
            for($i=0; $i < $pads; $i++)
                $new_code .= "0";
            $new_code .= $num;
            $bean->$code_field = $new_code;
        }
    }

	function make_full_name(&$bean, $event, $arguments){
		$bean->full_teacher_name = $bean->first_name. ' '.$bean->last_name;
	}
}
?>
