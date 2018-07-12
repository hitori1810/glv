<?php
# This module show an sms icon to phone numbers where a user can send sms
# Applicable to Detail View
# c/o tracy


function sms_phone($focus, $field, $value, $view) {  
  	global $beanList;
	$temp_field = $field;
	$phone_num = trim($focus->$temp_field); 
 	
	if($view == 'EditView' || $view == 'MassUpdate' || $view == 'QuickCreate') { //If this is EditView Or MassUpdate Include a field box 
	
		$phone_num = "<input type='text' name='{$field}' id='{$field}' value='{$phone_num}'>";
	
	} else { 
	
		if($focus->object_name == 'Case'){
				$ptype = 'Cases';
	        }else{
				$ptype = array_search($focus->object_name, $beanList);
	        }
		$pname = $focus->name;
		require_once("custom/modules/Administration/smsPhone/smsPhone.js.php");
		if (strlen(trim($phone_num))) 
 			$phone_num .= "&nbsp; <img style='border:none;cursor:pointer;' 
					title='Click to send an SMS. Opening the editor may take a moment. Please give it some time.'
					src='custom/modules/Administration/smsPhone/image/link_sms_icon.gif' 
					onclick=\"openAjaxPopup('" . urlencode($phone_num). "','{$ptype}','{$focus->id}','{$pname}');\">";
	}
	return $phone_num;
}

?>
