<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
class J_StudentSituationsViewEdit extends ViewEdit
{
    public function __construct()
    {
        parent::ViewEdit();
    }
    public function display(){

        // Dirty trick to clear cache, a must for EditView:
        //$th = new TemplateHandler();
        //$th->deleteTemplate('J_StudentSituations', 'DetailView');

        global $current_user, $timedate;

        //Generate move to class list
        if(!empty($this->bean->team_id) && ($this->bean->team_id != $current_user->team_id))
            $qTeam = "AND j_class.team_id = '{$this->bean->team_id}'";
        else
            $qTeam = "AND j_class.team_id = '{$current_user->team_id}'";

        $q1 = "SELECT DISTINCT
        IFNULL(j_class.id, '') primaryid,
        IFNULL(j_class.class_code, '') j_class_class_code,
        IFNULL(j_class.name, '') j_class_name,
        j_class.start_date j_class_start_date,
        j_class.end_date j_class_end_date,
        j_class.class_type class_type,
        j_class.hours j_class_hours,
        j_class.kind_of_course kind_of_course,
        IFNULL(j_class.description, '') j_class_description,
        IFNULL(j_class.short_schedule, '') j_class_short_schedule
        FROM
        j_class
        WHERE
        (((j_class.status IN ('Planning' , 'In Progress') AND j_class.end_date > '{$timedate->nowDbDate()}')))
        $qTeam
        OR (j_class.id = 'ab708c8d-96d0-6b47-8154-57f315b860bc')
        AND j_class.deleted = 0
        ORDER BY class_type ASC,j_class_start_date ASC";
        $rs1 = $GLOBALS['db']->query($q1);
        $classOptions = "<option value='' start_date='-none-' end_date='-none-' class_name='-none-' json_ss=''>- Select a Class -</option>";
        while($row = $GLOBALS['db']->fetchByAssoc($rs1) ) {
            $start_date 		=   $timedate->to_display_date($row['j_class_start_date'],true);
            $end_date   		=   $timedate->to_display_date($row['j_class_end_date'],true);
            $classOptions 		.= "<option value='{$row['primaryid']}' start_date='$start_date' end_date='$end_date' class_name='{$row['j_class_name']}' class_type='{$row['class_type']}' json_ss='{$row['j_class_short_schedule']}'>{$row['j_class_class_code']}</option>";
        }
        $this->ss->assign('classOptions', $classOptions);

        if(!empty($_GET['student_id'])){
            $student = BeanFactory::getBean('Contacts',$_GET['student_id']);
            $this->bean->student_name = $student->name;
            $this->bean->student_id = $student->id;
        }

        parent::display();
    }
}