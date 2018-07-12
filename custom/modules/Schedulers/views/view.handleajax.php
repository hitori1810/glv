<?php
switch ($_POST['type']) {  
    case 'ajaxRunNow':
        echo ajaxRunNow($_POST['record']);
        break;
    default:
        echo false;
        die;             
}
die;

function ajaxRunNow($scheduerId){
    $sql = "
    SELECT job
    FROM schedulers
    WHERE id = '$scheduerId'
    ";
    $functionName = $GLOBALS['db']->getOne($sql);
    $functionName = str_replace("function::","", $functionName);

    if(empty($functionName)){
        return json_encode(array(
            "success"       => "0",   
            "error_label"   => "LBL_SHEDULER_NOT_FOUND",   
        )); 
    }
                              
    require_once('include/entryPoint.php');
    require_once('modules/Schedulers/_AddJobsHere.php');
    require_once('custom/modules/Schedulers/_AddJobsHere.php');

    global $current_user;
    $current_user->getSystemUser();

    $result = $functionName(); 

    if($result){
        return json_encode(array(
            "success"       => "1",     
        ));    
    }
    else{
        return json_encode(array(
            "success"       => "0",   
            "error_label"   => "LBL_SOMETHING_ERROR",   
        )); 
    }


}

