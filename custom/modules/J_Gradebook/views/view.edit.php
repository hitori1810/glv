<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class J_GradebookViewEdit extends ViewEdit
{
    public function __construct()
    {
        parent::ViewEdit();
        $this->useForSubpanel = true;
        $this->useModuleQuickCreateTemplate = true;
    }

    public function display()
    {
        if (!empty($_REQUEST['j_class_j_gradebook_1j_class_ida']))
            $thisClass = BeanFactory::getBean("J_Class", $_REQUEST['j_class_j_gradebook_1j_class_ida']);
        else
            $thisClass = BeanFactory::getBean("J_Class", $this->bean->j_class_j_gradebook_1j_class_ida);

        $this->ss->assign('kind_of_course', $thisClass->kind_of_course);

        // Validate team_type - Lap Nguyen
        $this->bean->team_id        = $thisClass->team_id;
        $this->bean->team_set_id    = $this->bean->team_id;

        $gradebook_type = array(
            'Progress' => 'Progress',
            'Commitment' => 'Commitment',
            'Overall' => 'Overall',
        );
        $this->ss->assign('gradebook_type', $gradebook_type);

        //Get config Process by Team
        $q1 = "SELECT id, minitest, name
        FROM j_gradebookconfig
        WHERE team_id = '{$this->bean->team_id}' AND koc_id = '{$thisClass->koc_id}' AND type = 'Progress' AND deleted = 0 AND minitest <> '' AND minitest IS NOT NULL
        ORDER BY CASE
        WHEN
        (minitest = ''
        OR minitest IS NULL)
        THEN
        0
        WHEN minitest = 'minitest1' THEN 1
        WHEN minitest = 'minitest2' THEN 2
        WHEN minitest = 'minitest3' THEN 3
        WHEN minitest = 'minitest4' THEN 4
        WHEN minitest = 'minitest5' THEN 5
        WHEN minitest = 'minitest6' THEN 6
        WHEN minitest = 'project1' THEN 7
        WHEN minitest = 'project2' THEN 8
        WHEN minitest = 'project3' THEN 9
        WHEN minitest = 'project4' THEN 10
        WHEN minitest = 'project5' THEN 11
        WHEN minitest = 'project6' THEN 12
        ELSE 13
        END ASC";
        $rs1 = $GLOBALS['db']->query($q1);
        $gradebook_mini = array('' => '-none-');
        while($row = $GLOBALS['db']->fetchByAssoc($rs1))
            $gradebook_mini[$row['minitest']] = $row['name'];

        $this->ss->assign('gradebook_mini', $gradebook_mini);
        parent::display();
    }

}