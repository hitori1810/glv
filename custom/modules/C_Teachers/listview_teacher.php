<?php
    if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point'); 

    class ListviewTeacher { 

        function ListviewTeacher(&$bean, $event, $arguments) {
            if($bean->teacher_type=='TA'){
                $bean->teacher_id = '<span class="textbg_yellow">'.$bean->teacher_id.'</span>';
            } else{
                $bean->teacher_id = '<span class="textbg_blue">'.$bean->teacher_id.'</span>';
            }


            //Show Check box on Subpanel
            if ($_REQUEST['module']=='C_Classes'){
                $bean->checkbox = '<input type="checkbox" class="checkbox_teacher" name="checkbox_teacher" value="'.$bean->id.'">';
            }
        } 
    }
?>
