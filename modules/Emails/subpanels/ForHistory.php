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
	'where'				=> "",
	
	
	'fill_in_additional_fields'	=> true,
	'list_fields' => array(
		'object_image'=>array(
			'widget_class'			=> 'SubPanelIcon',
 		 	'width'					=> '2%',
		),
		'name' => array(
			 'vname'				=> 'LBL_LIST_SUBJECT',
			 'widget_class'			=> 'SubPanelDetailViewLink',
			 'width'				=> '15%',
             'parent_info'          => true
		),
		'status' => array(
			 'vname'				=> 'LBL_LIST_STATUS',
			 'width'				=> '15%',
		),
        'contact_id'=>array(
            'usage'=>'query_only',
    		'force_exists'=>true
        ),
//        'contact_name_owner'=>array(
//            'usage'=>'query_only',
//            'force_exists'=>true
//        ),  
//        'contact_name_mod'=>array(
//            'usage'=>'query_only',
//            'force_exists'=>true
//        ),  
		'date_modified' => array(
			'width'					=> '10%',
		),
		'date_entered'=>array(
			'vname' => 'LBL_LIST_DATE_ENTERED',
			'width' => '10%',
		),
		'assigned_user_name' => array (
			'name' => 'assigned_user_name',
			'vname' => 'LBL_LIST_ASSIGNED_TO_NAME',
		),
		'edit_button' => array(
			'widget_class'			=> 'SubPanelEditButton',
			 'width'				=> '2%',
		),
		'remove_button' => array(
			 'widget_class'			=> 'SubPanelRemoveButton',
			 'width'				=> '2%',
		),
		'filename' => array(
			'usage'					=> 'query_only',
			'force_exists'			=> true
		),
	), // end list_fields
);		
?>
