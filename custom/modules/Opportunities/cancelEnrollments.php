<?php
    //Update salestage is failure
    global $timedate;
    $date = $timedate->asDbDate($timedate->getNow()); 
    $time = $timedate->asDbTime($timedate->getNow()); 
    $sql = "UPDATE opportunities SET sales_stage='Failure', date_modified='$date $time', modified_user_id='".$GLOBALS['current_user']->id."' WHERE id='{$_REQUEST['oppid']}'";
    $result = $GLOBALS['db']->query($sql);
    if($_REQUEST['oppid']){
        echo json_encode(array("success" => "1"));   
    }else{
        echo json_encode(array("success" => "0"));   
    }
?>
