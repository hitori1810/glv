<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class logicMembership {
    function handleBeforeSave(&$bean, $event, $arguments){
        if(empty($bean->name)) {
            //Create Membership Code
            $first_pad  = '0000';
            $padding    = 4;
            $prefix     = date('ym',strtotime('+ 7hours'. (!empty($bean->date_entered) ? $bean->date_entered : $bean->fetched_row['date_entered'])));

            $query      = "SELECT name FROM c_memberships WHERE  (deleted = 0) AND (name <> '' AND name IS NOT NULL)  AND (LEFT(name, ".strlen($prefix).") = '$prefix') ORDER BY name DESC LIMIT 1";
            $result     = $GLOBALS['db']->query($query);
            if($row     = $GLOBALS['db']->fetchByAssoc($result))
                $last_code = $row['name'];
            else
                $last_code = $prefix . $first_pad;

            $num        = substr($last_code, -$padding + 3, $padding);
            $num++;
            $pads       = $padding - strlen($num);
            $new_code   = $prefix;

            //preform the lead padding 0
            for($i=0; $i < $pads; $i++)
                $new_code .= "0";
            $new_code .= $num;
            $milliseconds = round(microtime(true) * 1000);
            $bean->name = $new_code.substr($milliseconds , -3, 3);
        }
        $bean->team_id           = '1';
        $bean->team_set_id       = $bean->team_id;
    }
    function listViewColor(&$bean, $event, $arguments) {

        if($_REQUEST['action'] != 'Popup' || $_REQUEST['action'] == null){
            if($bean->type == 'Platinum')
                $bean->name = '<a href="index.php?module=C_Memberships&return_module=C_Memberships&action=DetailView&record='.$bean->id.'"><span class="textbg_black">'.$bean->name.'</span></a>';
            elseif($bean->type == 'Gold')
                $bean->name = '<a href="index.php?module=C_Memberships&return_module=C_Memberships&action=DetailView&record='.$bean->id.'"><span class="textbg_yellow">'.$bean->name.'</span></a>';
            elseif($bean->type == 'Blue')
                $bean->name = '<a href="index.php?module=C_Memberships&return_module=C_Memberships&action=DetailView&record='.$bean->id.'"><span class="textbg_bluelight">'.$bean->name.'</span></a>';
        }
    }
}
?>
