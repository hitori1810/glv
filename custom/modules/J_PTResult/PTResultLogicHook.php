<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
class PTResultLogicHook{
    function updateStudentInfo(&$bean, $event, $arg) {
        if($bean->parent == "Leads") {
            $student = new Lead();
            $student->retrieve($bean->student_id);
            $bean->student_status = $student->status;

        }elseif($bean->parent == "Contacts") {
            $student = new Contact();
            $student->retrieve($bean->student_id);
            $bean->student_status = $student->contact_status;
        }
        $bean->student_name = $student->name;
        $bean->student_gender = $student->gender;
        $bean->student_birthdate = $GLOBALS['timedate']->to_db_date($student->birthdate,false);
        $bean->student_email = $student->email1;
        $bean->student_mobile = $student->phone_mobile;
        $bean->last_name = $student->last_name;
        $bean->first_name = $student->first_name;
        $bean->lead_source  = $student->lead_source;
        $bean->parent_name = $student->guardian_name;
        $bean->assigned_user_id = $student->assigned_user_id;
//        if($bean->date_entered == $bean->date_modified)
//            $bean->attended = 1;
    }
}
?>
