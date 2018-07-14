<?php
class C_ClassLogichook{
    //Save relationship
    function saveRelationship(&$bean, $event, $arguments){  
        $sql = "UPDATE c_grade_c_class_1_c SET deleted=1 WHERE c_grade_c_class_1c_class_idb='{$bean->id}'";
        $GLOBALS['db']->query($sql);     

        if (!empty($bean->grade)) {
            $bean->load_relationship('c_grade_c_class_1');
            $bean->c_grade_c_class_1->add($bean->grade);
        }   
    } 
}
?>
