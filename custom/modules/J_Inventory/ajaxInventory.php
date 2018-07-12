<?php
    include_once("custom/include/utils/InventoryHelper.php");    


    switch ($_REQUEST['type']) {
        case "getToObjectOptions":
            echo getToObjectOptions($_REQUEST);
            break;

        case "getFromObjectOptions":
            echo getFromObjectOptions($_REQUEST);
            break;
    }
    die;

    function getToObjectOptions($data) {
        $options = array();
        $center_id = $data['center_id'] ;
        switch($data['from_object']) {
            case "Teams" :
            switch($data['object']) {
                case "Accounts":
                    $options = InventoryHelper::getCorpsOfCenters($center_id);                 
                    break;
                case "Contacts":
                    $options = InventoryHelper::getStudentsOfCenters($center_id);                 
                    break;
                case "Teams":                
                    $options = InventoryHelper::getCentersOfUser() ;
                    break;
                case "C_Teachers":
                    $options = InventoryHelper::getTeachers($center_id);                 
                    break;              
            }
            break;
            case "TeamsParent":
                $options = InventoryHelper::getCenters(" AND parent_id = '$center_id' ", true);                
                break;
            case "Accounts":
                $options1 = InventoryHelper::getCenters('', true) ;
                $options2 = InventoryHelper::getParentCenters('', true) ;
                $options = array_merge($options1,$options2);
                break;
        }       
        return get_select_options_with_id($options,$data['to_object_key']);
    }

    function getFromObjectOptions($data) {
        $options = array();
        $center_id = $data['center_id'] ;
        switch($data['object']) {              
            case "Teams":                
                $options = InventoryHelper::getCentersOfUser() ;
                break;
            case "TeamsParent":
                $options = InventoryHelper::getParentCenters('',true);                 
                break;              
        }            
        return get_select_options_with_id($options,'');
    }
?>
