<?php
    $code = $_REQUEST['code'];
    $id = $_REQUEST['id'];

    $pk = BeanFactory::newBean('C_Memberships');
    if(empty($id)) { //when user create new record
        $q1 = 'SELECT name FROM c_memberships WHERE deleted = 0 AND name="'.$code.'"';
        $name = $GLOBALS['db']->getOne($q1);
    } else { //when user edit existed record (id record is existed)
        $q1 = 'SELECT name FROM c_memberships WHERE deleted = 0 AND id!="'.$id.'" AND name="'.$code.'"';
        $name = $GLOBALS['db']->getOne($q1);
    }
    if(!$name){
        echo json_encode(array(
            "success" => "1",
        ));
    }else{
        echo json_encode(array(
            "success" => "0",
        )); 
}