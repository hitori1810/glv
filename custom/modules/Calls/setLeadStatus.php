<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class leadCallStatus {
    function setLeadStatus(&$bean, $event, $arguments){
        //Workflow tự động update Lead / Target
        if($bean->parent_type == 'Leads' || $bean->parent_type == 'Prospects'){
            $status =  $GLOBALS['db']->getOne("SELECT status FROM ".strtolower($bean->parent_type)." WHERE deleted=0 AND id='{$bean->parent_id}'");
            $in_array_status = array('New','Assigned', 'Recycled', 'Dead');
            if (in_array($status, $in_array_status)){
                $sql = "UPDATE ".strtolower($bean->parent_type)." SET status ='In Process', date_modified='{$bean->date_modified}', modified_user_id='{$bean->modified_user_id}' WHERE id='{$bean->parent_id}'";
                $rs = $GLOBALS['db']->query($sql);
            }

        }
    }
}
?>
