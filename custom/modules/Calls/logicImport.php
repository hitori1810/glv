<?php
class logicImportCall{
    //Import Payment
    function importCall(&$bean, $event, $arguments){
        global $timedate;
        if($_POST['module'] == 'Import'){
            $user_id = $GLOBALS['db']->getOne("SELECT id FROM users WHERE user_name = '{$bean->assigned_user_id}' AND deleted = 0");
            if(!empty($user_id))
                $bean->assigned_user_id = $user_id;

            $bean->created_by       = $bean->assigned_user_id; 
            $bean->modified_user_id = $bean->assigned_user_id; 
            $bean->date_entered     = $bean->date_start; 
            $bean->date_modified    = $bean->date_start; 

            //Get Student ID
            $parent_type = 'Contacts';
            $rs = $GLOBALS['db']->query("SELECT id, CONCAT(IFNULL(last_name, ''),' ',IFNULL(first_name, '')) name FROM contacts WHERE aims_id = '{$bean->student_aims_id}' AND deleted = 0");
            $student = $GLOBALS['db']->fetchByAssoc($rs);
            if(empty($student)){
                $parent_type = 'Leads';
                $rs = $GLOBALS['db']->query("SELECT id, CONCAT(IFNULL(last_name, ''),' ',IFNULL(first_name, '')) name FROM leads WHERE aims_id = '{$bean->student_aims_id}' AND deleted = 0");
                $student = $GLOBALS['db']->fetchByAssoc($rs);   
            }
            if(!empty($student)){
                $bean->parent_id    = $student['id'];
                $bean->parent_type  = $parent_type;

                if($bean->parent_type == 'Leads'){
                    $bean->load_relationship('leads');
                    $bean->leads->add($student['id']);
                }else{
                    $bean->load_relationship('contacts');
                    $bean->contacts->add($student['id']);    
                }

                $bean->duration_hours   = 0;
                $bean->duration_minutes = 15;
                $bean->direction        = 'Outbound';
                $bean->status           = 'Held'; 
                $bean->email_reminder_time = -1; 
                $bean->reminder_time       = -1;
                $bean->update_vcal    = false; 
                $bean->send_invites    = false; 
            }else{
                $bean->deleted = 1;
                $bean->email_reminder_time = -1; 
                $bean->reminder_time       = -1;
                $bean->update_vcal    = false;
                $bean->send_invites    = false; 
            }


        }
    }
}
?>
