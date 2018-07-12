<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class logicLoyalty {
    function beforeSaveLogic(&$bean, $event, $arguments){
        global $timdate;
        //Set Team
        $student = BeanFactory::getBean('Contacts', $bean->student_id);
        if(empty($bean->team_id)){
            $bean->team_id      = $GLOBALS['current_user']->team_id;
            $bean->team_set_id  = $bean->team_id;
        }

        //Changing the sign of a number
        $loyalty_in_list    = $GLOBALS['app_list_strings']['loyalty_in_list'];
        $loyalty_out_list   = $GLOBALS['app_list_strings']['loyalty_out_list'];
        if(in_array($bean->type, $loyalty_in_list))
            $bean->point = abs($bean->point);
        if(in_array($bean->type, $loyalty_out_list))
            $bean->point = -1 * abs($bean->point);

    }
    function addCode(&$bean, $event, $arguments){
        $code_field = 'name';
        if(empty($bean->$code_field)){
            //Get Prefix
            $res        = $GLOBALS['db']->query("SELECT contact_id FROM contacts WHERE id = '{$bean->student_id}'");
            $row        = $GLOBALS['db']->fetchByAssoc($res);
            $prefix     = $row['contact_id'].'/'.date('y');
            $year       = date('y',strtotime('+ 7hours'. (!empty($bean->date_entered) ? $bean->date_entered : $bean->fetched_row['date_entered'])));
            $table      = $bean->table_name;
            $sep        = '/';
            $first_pad  = '000';
            $padding    = 3;
            $query      = "SELECT $code_field FROM $table WHERE ( $code_field <> '' AND $code_field IS NOT NULL) AND id != '{$bean->id}' AND (LEFT($code_field, ".(strlen($prefix)+1).") = '#".$prefix."') ORDER BY RIGHT($code_field, $padding) DESC LIMIT 1";

            $result = $GLOBALS['db']->query($query);
            if($row = $GLOBALS['db']->fetchByAssoc($result))
                $last_code = $row[$code_field];
            else
                //no codes exist, generate default - PREFIX + CURRENT YEAR +  SEPARATOR + FIRST NUM
                $last_code = $prefix  . $sep  . $first_pad;


            $num = substr($last_code, -$padding, $padding);
            $num++;
            $pads = $padding - strlen($num);
            $new_code = $prefix . $sep;

            //preform the lead padding 0
            for($i=0; $i < $pads; $i++)
                $new_code .= "0";
            $new_code .= $num;

            //write to database - Logic: Before Save
            $bean->$code_field = "#".$new_code;
        }
    }
    function listViewColor(&$bean, $event, $arguments){
        if($bean->point < 0)
            $bean->point = "<b style='color: #BB1212'>".$bean->point."</b>";
        if($bean->point > 0)
            $bean->point = "<b style='color: #468931'>+".$bean->point."</b>";

        $loyalty_in_list    = $GLOBALS['app_list_strings']['loyalty_in_list'];
        $loyalty_out_list   = $GLOBALS['app_list_strings']['loyalty_out_list'];

        if(in_array($bean->type, $loyalty_in_list))
            $bean->type = '<span class="textbg_green">'.$bean->type.'</span>';
        if(in_array($bean->type, $loyalty_out_list))
            $bean->type = '<span class="textbg_blood">'.$bean->type.'</span>';
    }
}
?>