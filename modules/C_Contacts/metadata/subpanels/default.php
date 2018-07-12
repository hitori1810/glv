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


$module_name='C_Contacts';
$subpanel_layout = array(
	'top_buttons' => array(
		array('widget_class' => 'SubPanelTopCreateButton'),
		array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => $module_name),
	),

	'where' => '',

	'list_fields' => array(
		'name'=>array(
	 		'vname' => 'LBL_NAME',
			'widget_class' => 'SubPanelDetailViewLink',
	 		'width' => '20%',
		),
        'parent_type'=>array(
             'vname' => 'LBL_PARENT_TYPE',
             'width' => '20%',
        ),
        'mobile_phone'=>array(
             'vname' => 'LBL_MOBILE_PHONE',
             'width' => '20%',
        ),
        'address'=>array(
             'vname' => 'LBL_ADDRESS',
             'width' => '20%',
        ),
		'email1'=>array(
             'vname' => 'LBL_EMAIL_ADDRESS',
             'width' => '20%',
        ),
	),
);

?>