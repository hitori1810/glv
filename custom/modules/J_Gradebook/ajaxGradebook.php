<?php
    require_once("custom/include/_helper/junior_gradebook_utils.php");  
    require_once("custom/modules/J_Gradebook/GradebookFunctions.php");
    switch ($_POST['process_type']) {
        case 'getGradebook':
            $result = json_encode(getGradebook($_POST['class_id']));
            echo $result;
            break;
        case 'getGradebookDetail':
            $result = json_encode(getGradebookDetail($_POST['gradebook_id'], $_POST['reloadconfig']));
            echo $result;
            break; 
        case 'inputMark':
            $result = saveInputMark($_POST);
            echo $result;
            break;   
        case 'showConfig':
            $result = showConfig($_POST['gradebook_id']);
            echo $result;
            break;
        //add by Lam Hai 22/7/2016
        case 'checkDuplicateTest':
            $result = checkDuplicateTest($_POST['gradebook_id'], $_POST['class_id'], $_POST['type'], $_POST['minitest']);
            echo $result;
            break;         
        case 'loadAttendance':
            echo loadAttendance();
            break;         
        case 'loadHomework':
            echo loadHomework();
            break;               
        case 'loadProject':
        case 'loadMinitest':
            echo loadProject();
            break;   
    }
    die;
?>
