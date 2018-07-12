<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Professional Subscription
 * Agreement ("License") which can be viewed at
 * http://www.sugarcrm.com/crm/products/sugar-professional-eula.html
 * By installing or using this file, You have unconditionally agreed to the
 * terms and conditions of the License, and You may not use this file except in
 * compliance with the License.  Under the terms of the license, You shall not,
 * among other things: 1) sublicense, resell, rent, lease, redistribute, assign
 * or otherwise transfer Your rights to the Software, and 2) use the Software
 * for timesharing or service bureau purposes such as hosting the Software for
 * commercial gain and/or for the benefit of a third party.  Use of the Software
 * may be subject to applicable fees and any use of the Software without first
 * paying applicable fees is strictly prohibited.  You do not have the right to
 * remove SugarCRM copyrights from the source code or user interface.
 *
 * All copies of the Covered Code must include on each user interface screen:
 *  (i) the "Powered by SugarCRM" logo and
 *  (ii) the SugarCRM copyright notice
 * in the same form as they appear in the distribution.  See full license for
 * requirements.
 *
 * Your Warranty, Limitations of liability and Indemnity are expressly stated
 * in the License.  Please refer to the License for the specific language
 * governing these rights and limitations under the License.  Portions created
 * by SugarCRM are Copyright (C) 2004-2010 SugarCRM, Inc.; All Rights Reserved.
 ********************************************************************************/



require_once('include/generic/SugarWidgets/SugarWidget.php');
 
class SugarWidgetSubPanelSMSButton extends SugarWidgetSubPanelTopButton
{
    var $module;
	var $title;
	var $access_key;
	var $form_value;
	var $additional_form_fields;
	var $acl;

//TODO rename defines to layout defs and make it a member variable instead of passing it multiple layers with extra copying.
	
	/** Take the keys for the strings and look them up.  Module is literal, the rest are label keys
	*/
	function SugarWidgetSubPanelSMSButton($module='', $title='', $access_key='', $form_value='')
	{
		global $app_strings;

		if(is_array($module)) {
			// it is really the class details from the mapping
			$class_details = $module;
			
			// If keys were passed into the constructor, translate them from keys to values.
			if(!empty($class_details['module']))
				$this->module = $class_details['module'];
			if(!empty($class_details['title']))
				$this->title = $app_strings[$class_details['title']];
			if(!empty($class_details['access_key']))
				$this->access_key = $app_strings[$class_details['access_key']];
			if(!empty($class_details['form_value']))
				$this->form_value = translate($class_details['form_value'], $this->module);
			if(!empty($class_details['additional_form_fields']))
				$this->additional_form_fields = $class_details['additional_form_fields'];
			if(!empty($class_details['ACL'])){
				$this->acl = $class_details['ACL'];
			}
		} else {
			$this->module = $module;
		
			// If keys were passed into the constructor, translate them from keys to values.
			if(!empty($title))
				$this->title = $app_strings[$title];
			if(!empty($access_key))
				$this->access_key = $app_strings[$access_key];
			if(!empty($form_value))
				$this->form_value = translate($form_value, $module);
		}
	}
	
    function &_get_form($defines, $additionalFormFields = null) {
        global $app_strings;
        global $currentModule;
	
		return true;
       
    }

	/** This default function is used to create the HTML for a simple button */
	function display($defines, $additionalFormFields = null) {

		global $currentModule, $beanList, $app_strings;
		$button = false;
 
		$ptype = ($defines['focus']->object_name == 'Case') ? 'Cases' : array_search($defines['focus']->object_name, $beanList);
 		if (isset($ptype)) { 
			require_once("custom/modules/Administration/smsPhone/smsPhone.js.php"); 
			$onclick = "openAjaxPopup('','{$ptype}','" . $defines['focus']->id . "','" . $defines['focus']->name . "');";
			$button = "<input type='button' class='button' onclick=\"{$onclick}\" value='".$app_strings['LBL_COMPOSE_SMS']."'>";
				 
		}

		return $button;
	} 
	
	/**
	 * get_subpanel_relationship_name
	 * Get the relationship name based on the subapnel definition
	 * @param mixed $defines The subpanel definition
	 */
	function get_subpanel_relationship_name($defines) {
		 $relationship_name = '';
		 if(!empty($defines)) {
		 	$relationship_name = isset($defines['module']) ? $defines['module'] : '';
	     	$dataSource = $defines['subpanel_definition']->get_data_source_name(true);
         	if (!empty($dataSource)) {
				$relationship_name = $dataSource;
				//Try to set the relationship name to the real relationship, not the link.
				if (!empty($defines['subpanel_definition']->parent_bean->field_defs[$dataSource])
				 && !empty($defines['subpanel_definition']->parent_bean->field_defs[$dataSource]['relationship']))
				{
					$relationship_name = $defines['subpanel_definition']->parent_bean->field_defs[$dataSource]['relationship'];
				}
			}
		 }
		 return $relationship_name;
	}
	
}
?>
