<?php
    function getTeacher(){
        $options = array();
        $sql  = "SELECT id,full_teacher_name FROM c_teachers WHERE deleted = 0";
        $result = $GLOBALS['db']->query($sql);
        while($teacher = $GLOBALS['db']->fetchByAssoc($result)){
            $options[$teacher['id']] = $teacher['full_teacher_name'];
        }
        return $options;
    }
    function getRoom(){
        $options = array();
        $sql  = "SELECT id,name FROM c_rooms WHERE deleted = 0";
        $result = $GLOBALS['db']->query($sql);
        while($teacher = $GLOBALS['db']->fetchByAssoc($result)){
            $options[$teacher['id']] = $teacher['name'];
        }
        return $options;
    }
?>

