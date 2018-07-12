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


$module_name='J_Class';
$subpanel_layout = array(
    'top_buttons' => array(
        array('widget_class' => 'SubPanelTopCreateButton'),
        array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => $module_name),
    ),

    'where' => '',

    'list_fields' => array(
        'class_code' => 
        array (
            'type' => 'varchar',
            'vname' => 'LBL_CLASS_CODE',
            'width' => '10%',
            'default' => true,
        ),
        'class_type' => 
        array (
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'vname' => 'LBL_CLASS_TYPE',
            'width' => '10%',
        ),
        'name' => 
        array (
            'vname' => 'LBL_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '10%',
            'default' => true,
        ),
        'koc_name' => 
        array (
            'type' => 'relate',
            'link' => true,
            'vname' => 'LBL_KOC_NAME',
            'id' => 'KOC_ID',
            'width' => '10%',
            'default' => true,
            'widget_class' => 'SubPanelDetailViewLink',
            'target_module' => 'J_Kindofcourse',
            'target_record_key' => 'koc_id',
        ),
        'status' => 
        array (
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'vname' => 'LBL_STATUS',
            'width' => '10%',
        ),
        'start_date' => 
        array (
            'type' => 'date',
            'vname' => 'LBL_START_DATE',
            'width' => '10%',
            'default' => true,
        ),
        'end_date' => 
        array (
            'type' => 'date',
            'vname' => 'LBL_END_DATE',
            'width' => '10%',
            'default' => true,
        ),
        'assigned_user_name' => 
        array (
            'link' => true,
            'type' => 'relate',
            'vname' => 'LBL_ASSIGNED_TO_NAME',
            'id' => 'ASSIGNED_USER_ID',
            'width' => '10%',
            'default' => true,
            'widget_class' => 'SubPanelDetailViewLink',
            'target_module' => 'Users',
            'target_record_key' => 'assigned_user_id',
        ),
        'team_name' => 
        array (
            'type' => 'relate',
            'link' => true,
            'studio' => 
            array (
                'portallistview' => false,
                'portaldetailview' => false,
                'portaleditview' => false,
            ),
            'vname' => 'LBL_TEAMS',
            'id' => 'TEAM_ID',
            'width' => '10%',
            'default' => true,
            'widget_class' => 'SubPanelDetailViewLink',
            'target_module' => 'Teams',
            'target_record_key' => 'team_id',
        ),       

    ),
);

?>