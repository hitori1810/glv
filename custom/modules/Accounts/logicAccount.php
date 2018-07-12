<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
class logicAccount{
    function addCode(&$bean, $event, $arguments){
        $code_field = 'account_id';
        $bean->short_name = strtoupper($bean->short_name);
        if(empty($bean->$code_field)){
            //Get Prefix
            $res        = $GLOBALS['db']->query("SELECT code_prefix, team_type FROM teams WHERE id = '{$bean->team_id}'");
            $row        = $GLOBALS['db']->fetchByAssoc($res);
            $prefix     = $row['code_prefix'];
            $year       = date('y',strtotime('+ 7hours'. (!empty($bean->date_entered) ? $bean->date_entered : $bean->fetched_row['date_entered'])));
            $table      = $bean->table_name;
            $sep        = '-';
            $first_pad  = '0000';
            $padding    = 4;
            $query      = "SELECT $code_field FROM $table WHERE ( $code_field <> '' AND $code_field IS NOT NULL) AND id != '{$bean->id}' AND (LEFT($code_field, ".strlen($prefix.$year).") = '".$prefix.$year."') ORDER BY RIGHT($code_field, $padding) DESC LIMIT 1";

            $result = $GLOBALS['db']->query($query);
            if($row = $GLOBALS['db']->fetchByAssoc($result))
                $last_code = $row[$code_field];
            else
                //no codes exist, generate default - PREFIX + CURRENT YEAR +  SEPARATOR + FIRST NUM
                $last_code = $prefix . $year . $sep  . $first_pad;


            $num = substr($last_code, -$padding, $padding);
            $num++;
            $pads = $padding - strlen($num);
            $new_code = $prefix . $year . $sep;

            //preform the lead padding 0
            for($i=0; $i < $pads; $i++)
                $new_code .= "0";
            $new_code .= $num;

            //write to database - Logic: Before Save
            $bean->$code_field = $new_code;
        }
    }
}
?>
