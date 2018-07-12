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
		array('widget_class' => 'SubPanelTopCreateButton'),
		array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'Opportunities'),
	),

	'where' => '',
	
	

	'list_fields' => array(
		'name' => 
        array (
            'name' => 'name',
            'vname' => 'LBL_LIST_OPPORTUNITY_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '25%',
            'default' => true,
        ),
        'sales_stage' => 
        array (
            'name' => 'sales_stage',
            'vname' => 'LBL_LIST_SALES_STAGE',
            'width' => '20%',
            'default' => true,
        ),
        'date_closed' => 
        array (
            'name' => 'date_closed',
            'vname' => 'LBL_DATE_CLOSED',
            'width' => '15%',
            'default' => false,
        ),
        'total_in_invoice' => 
        array (
            'name' => 'total_in_invoice',
            'vname' => 'LBL_TOTAL_IN_INVOICE',
            'width' => '20%',
            'default' => false,
        ),
        'date_modified'=>array(
            'vname' => 'LBL_DATE_MODIFIED',
            'width' => '20%',
        ),
	),
);

?>