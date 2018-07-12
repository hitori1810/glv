<?php
    require_once('custom/include/_helper/class_utils.php');      
    switch ($_POST['type']) {
        case 'addPublic':
            $result = addPubToSession($_POST['enroll_id'], $_POST['ss_id']);
            break;
        case 'addCorp':
            $result = addCorpToSession($_POST['student_id'], $_POST['ss_id']);
            break;
        case 'removeStudentFromSession':
            $result = removeStudentFromSession($_POST['student_id'], $_POST['ss_id']);
            break;
        case 'removeLeadFromDemo':
            $result = removeLeadFromDemo($_POST['lead_id'], $_POST['ss_id']);
            break;
            
    }
    if($result)
        echo json_encode(array("success" => "1"));   
    else
        echo json_encode(array("success" => "0"));       


    // ----------------------------------------------------------------------------------------------------------
?>
