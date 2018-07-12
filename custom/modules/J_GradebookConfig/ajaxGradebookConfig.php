<?php
include_once("custom/modules/J_GradebookConfig/GradebookConfigFunctions.php");
include_once("custom/include/_helper/junior_gradebook_utils.php");
switch ($_POST['process_type']) {
    case 'getKOCOfCenter':
        $result = getKOCOfCenter($_POST['center_id']);
        echo $result;
        break;    
    case 'getTypeOfKOC':
        $result = getTypeOfKOC($_POST);
        echo $result;
        break;
    case 'getConfigContent' :
        echo getConfigContent($_POST);               
        break;
    case 'saveConfig':
        $result = saveConfig($_POST);
        echo $result;
        break;     
}
die;
?>