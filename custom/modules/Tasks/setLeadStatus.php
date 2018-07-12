<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class leadTaskStatus {
    function setLeadStatus(&$bean, $event, $arguments)
    {
        global $current_user;
        //Workflow tự động update Lead / Target
        if($bean->parent_type == 'Leads' || $bean->parent_type == 'Prospects'){
            $status =  $GLOBALS['db']->getOne("SELECT status FROM ".strtolower($bean->parent_type)." WHERE deleted=0 AND id='{$bean->parent_id}'");
            $in_array_status = array('New','Assigned', 'Recycled', 'Dead');
            if (in_array($status, $in_array_status)){
                $sql = "UPDATE ".strtolower($bean->parent_type)." SET status ='In Process', date_modified='{$bean->date_modified}', modified_user_id='{$bean->modified_user_id}' WHERE id='{$bean->parent_id}'";
                $rs = $GLOBALS['db']->query($sql);
            }
        }

        //Make Notification Task
        if((empty($bean->fetched_row)) || (!empty($bean->fetched_row) && $bean->fetched_row['assigned_user_id'] != $bean->assigned_user_id)) {
            //get the parent bean
            $parent_bean        = BeanFactory::getBean($bean->module_name, $bean->id);
            //initialize notification bean
            $notification_bean  = BeanFactory::getBean("Notifications");

            $notification_bean->name = $GLOBALS['app_list_strings']['parent_type_display'][$bean->module_name].": ".$bean->name." has been assigned to you.";
            //assigned user should be record assigned user
            $notification_bean->assigned_user_id    = $bean->assigned_user_id;
            $notification_bean->parent_id           = $bean->id;
            $notification_bean->parent_type         = $bean->module_name;
            $notification_bean->created_by          = $bean->modified_user_id;
            $GLOBALS['db']->query("DELETE FROM notifications WHERE parent_id='{$bean->id}'");
            //set is_read to no
            $notification_bean->is_read     = 0;
            //set the level of severity
            $notification_bean->severity    = "Info";
            $notification_bean->save();
        }
    }
}
?>
