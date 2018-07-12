<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * By installing or using this file, you are confirming on behalf of the entity
 * subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
 * the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
 * http://www.sugarcrm.com/master-subscription-agreement
 *
 * If Company is not bound by the MSA, then by installing or using this file
 * you are agreeing unconditionally that Company will be bound by the MSA and
 * certifying that you have authority to bind Company accordingly.
 *
 * Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
 ********************************************************************************/



$subpanel_layout = array(
	'top_buttons' => array(
		array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Accounts'),
	),

	'where' => '',
	
	'fill_in_additional_fields'=>true,
	'list_fields' => array(
		'name'=>array(
	 		'vname' => 'LBL_LIST_NAME',
			'widget_class' => 'SubPanelDetailViewLink',
			'width' => '35%',
		),
		'assigned_user_name'=>array(
	 		'vname' => 'LBL_LIST_ASSIGNED_USER_ID',
			'widget_class' => 'SubPanelDetailViewLink',
	 		'module' => 'Users',
	 		'target_record_key' => 'assigned_user_id',
 		 	'target_module' => 'Users',
			'width' => '15%',
			 'sortable'=>false,	
		),
		'estimated_start_date' => array(
			'vname' => 'LBL_DATE_START',
			'width' => '22%',
			'sortable' => true,
		),
		'estimated_end_date' => array(
			'vname' => 'LBL_DATE_END',
			'width' => '22%',
			'sortable' => true,
		),	
		'edit_button'=>array(
			'vname' => 'LBL_EDIT_BUTTON',
			'widget_class' => 'SubPanelEditButton',
			'module' => 'Project',
		 	'width' => '3%',
		),
		'remove_button'=>array(
			'vname' => 'LBL_REMOVE',
			'widget_class' => 'SubPanelRemoveButton',
 		 	'module' => 'Project',
	 		'width' => '3%',
		),
	),
);

?>