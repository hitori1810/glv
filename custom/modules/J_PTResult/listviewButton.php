<?php 
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point'); 

class ListviewLogicHookPT { 

    /** 
    * Changing color of listview rows according to Status
    */ 
    function listViewButton(&$bean, $event, $arguments) {  
        //////////---Atlantic Junior -- Add Button Remove By Quyen.Cao----/////////
        if ($_REQUEST['module']=='Leads' || $_REQUEST['module']=='Contacts'){
            $bean->custom_button = '
            <div style="display: inline-flex;">
            <input type="button" class="remove_pt" class="button" id="'.$bean->id.'" value="'.$GLOBALS['mod_strings']['LBL_REMOVE'].'">
            </div>';
            $bean->custom_button_demo = '
            <div style="display: inline-flex;">
            <input type="button" class="remove_demo" class="button" id="'.$bean->id.'" value="'.$GLOBALS['mod_strings']['LBL_REMOVE'].'">
            </div>';
        }

        if($bean->speaking == '-')$bean->speaking = '';
        if($bean->writing == '-')$bean->writing = '';
        if($bean->listening == '-')$bean->listening = '';
        if($bean->result == '-')$bean->result = '';
        $sql = "SELECT DISTINCT
        IFNULL(l1.id, '') l1_id, IFNULL(l1.name, '') l1_name
        FROM
        j_ptresult
        INNER JOIN
        meetings_j_ptresult_1_c l1_1 ON j_ptresult.id = l1_1.meetings_j_ptresult_1j_ptresult_idb
        AND l1_1.deleted = 0
        INNER JOIN
        meetings l1 ON l1.id = l1_1.meetings_j_ptresult_1meetings_ida
        AND l1.deleted = 0
        WHERE
        (((j_ptresult.id = '{$bean->id}')))
        AND j_ptresult.deleted = 0";
        $rw =  $GLOBALS['db']->query($sql);
        $row = $GLOBALS['db']->fetchByAssoc($rw);
        $bean->meetings_j_ptresult_1_name = '<a href="index.php?module=Meetings&action=DetailView&record='.$row['l1_id'].'">'.$row['l1_name'].'</a>';

    } 
}
?>
