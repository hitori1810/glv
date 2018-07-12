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




global $current_user, $app;
// check if $app present - if in Studio/MB then loading a subpanel definition through the SubpanelDefinitions class 'requires' this file without an $app
if (isset($app) && isset($app->controller)){
	$projectId = $app->controller->record;
	
	$focus = new Project();
	$focus->retrieve($projectId);
	
	if (!$focus->isTemplate()){
		$subpanel_layout = array(
			'top_buttons' => array(
		        array('widget_class' => 'SubPanelTopCreateButton'),
					array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'ProjectTask'),
			),
		
			'where' => '',
		
		
			'list_fields' => array(
		        'name'=>array(
				 	'vname' => 'LBL_LIST_NAME',
					'widget_class' => 'SubPanelDetailViewLink',
					'width' => '20%',
				),
				'percent_complete'=>array(
				 	'vname' => 'LBL_LIST_PERCENT_COMPLETE',
					'width' => '20%',
				),
				'status'=>array(
				 	'vname' => 'LBL_LIST_STATUS',
					'width' => '20%',
				),
				'assigned_user_name'=>array(
				 	'vname' => 'LBL_LIST_ASSIGNED_USER_ID',
				 	'module' => 'Users',
					'width' => '20%',
				),
				'date_finish'=>array(
				 	'vname' => 'LBL_LIST_DATE_DUE',
					'width' => '20%',
				),
			),
		);
	}
	else{
		$subpanel_layout = array(
	
		'top_buttons' => array(
		),
	
		'where' => '',
	
	
		'list_fields' => array(
	        'name'=>array(
			 	'vname' => 'LBL_LIST_NAME',
				'widget_class' => 'SubPanelDetailViewLink',
				'width' => '70%',
			),
			'date_start'=>array(
			 	'vname' => 'LBL_DATE_START',
				'width' => '15%',
			),
	        'date_finish'=>array(
	            'vname' => 'LBL_DATE_FINISH',
	            'width' => '15%',
	        ),
		),
	);
	}
}
?>