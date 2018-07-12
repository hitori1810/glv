<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/*
* FormBase for Quick Create in Popup
* Author: Hieu Nguyen (Inherited from original AccountFormBase.php)
* Date: 21-01-2013
* Purpose: Enable to quick create in popup. 
*/

class FormBase{        

    function handleSave($prefix, $redirect = false, $useRequired = false){
        
	    require_once('include/formbase.php');

        // Get dynamic module name so that this can work for any module
        $module = $_POST['module'];
	    $focus = new $module();

	    if($useRequired &&  !checkRequired($prefix, array_keys($focus->required_fields))){
		    return null;
	    }
        
        // Auto assign value of each input to each field
	    $focus = populateFromPost($prefix, $focus);

	    if (isset($GLOBALS['check_notify'])) {
		    $check_notify = $GLOBALS['check_notify'];
	    }
	    else {
		    $check_notify = FALSE;
	    }

	    if(!$focus->ACLAccess('Save')){
		    ACLController::displayNoAccess(true);
		    sugar_cleanup(true);
	    }

        // Save
	    $focus->save($check_notify);
        $return_id = $focus->id;

        // Handle Redirect
	    if(isset($_POST['popup']) && $_POST['popup'] == 'true') {
		    $get = '&module=';
		    if(!empty($_POST['return_module'])) $get .= $_POST['return_module'];
		    else $get .= $module;
		    $get .= '&action=';
		    if(!empty($_POST['return_action'])) $get .= $_POST['return_action'];
		    else $get .= 'Popup';
		    if(!empty($_POST['return_id'])) $get .= '&return_id='.$_POST['return_id'];
		    if(!empty($_POST['popup'])) $get .= '&popup='.$_POST['popup'];
		    if(!empty($_POST['create'])) $get .= '&create='.$_POST['create'];
		    if(!empty($_POST['to_pdf'])) $get .= '&to_pdf='.$_POST['to_pdf'];
		    $get .= '&name=' . $focus->name;
		    $get .= '&query=true';
		    header("Location: index.php?$get");
		    return;
	    }
        
	    if($redirect){
		    handleRedirect($return_id,'C_Products');
	    }else{
		    return $focus;
	    }
    }
}
?>
