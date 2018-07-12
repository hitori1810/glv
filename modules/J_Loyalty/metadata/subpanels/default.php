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


$module_name='J_Loyalty';
$subpanel_layout = array(
    'top_buttons' => array(
        array('widget_class' => 'SubPanelTopCreateButton'),
        array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => $module_name),
    ),

    'where' => '',

    'list_fields' => array(
        'name' =>
        array (
            'vname' => 'LBL_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '20%',
            'default' => true,
        ),
        'type' =>
        array (
            'vname' => 'LBL_TYPE',
            'width' => '10%',
        ),
        'point' =>
        array (
            'vname' => 'LBL_POINT',
            'width' => '10%',
            'default' => true,
        ),
        'input_date' =>
        array (
            'vname' => 'LBL_INPUT_DATE',
            'width' => '10%',
            'default' => true,
        ),
        'description' =>
        array (
            'vname' => 'LBL_DESCRIPTION',
            'width' => '15%',
            'default' => true,
        ),
        'created_by_name' =>
        array (
            'type' => 'relate',
            'link' => true,
            'vname' => 'LBL_CREATED',
            'id' => 'CREATED_BY',
            'width' => '10%',
            'default' => true,
            'widget_class' => 'SubPanelDetailViewLink',
            'target_module' => 'Users',
            'target_record_key' => 'created_by',
        ),
    ),
);

?>