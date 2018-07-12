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


$module_name='J_StudentSituations';
$subpanel_layout = array(
    'top_buttons' => array(
        array('widget_class' => 'SubPanelTopCreateButton'),
        array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => $module_name),
    ),

    'where' => '',

    'list_fields' => array(
        'name' => 
        array (
            'type' => 'name',
            'link' => true,
            'vname' => 'LBL_NAME',
            'width' => '10%',
            'default' => true,
            'widget_class' => 'SubPanelDetailViewLink',
            'target_module' => NULL,
            'target_record_key' => NULL,
        ),
        'ju_class_name' => 
        array (
            'type' => 'relate',
            'link' => true,
            'vname' => 'LBL_JU_CLASS_NAME',
            'id' => 'JU_CLASS_ID',
            'width' => '10%',
            'default' => true,
            'widget_class' => 'SubPanelDetailViewLink',
            'target_module' => 'J_Class',
            'target_record_key' => 'ju_class_id',
        ),
        'type' => 
        array (
            'type' => 'enum',
            'studio' => 'visible',
            'vname' => 'LBL_TYPE',
            'width' => '6%',
            'default' => true,
            'widget_class' => 'SubPanelDetailViewLink',
        ),

//        'payment_name' => 
//        array (
//            'type' => 'relate',
//            'link' => true,
//            'vname' => 'LBL_PAYMENT_NAME',
//            'id' => 'PAYMENT_ID',
//            'width' => '10%',
//            'default' => true,
//            'widget_class' => 'SubPanelDetailViewLink',
//            'target_module' => 'J_Payment',
//            'target_record_key' => 'payment_id',
//        ),
        'start_study' => 
        array (
            'type' => 'date',
            'vname' => 'LBL_START_STUDY',
            'width' => '7%',
            'default' => true,
        ),
        'end_study' => 
        array (
            'type' => 'date',
            'vname' => 'LBL_END_STUDY',
            'width' => '7%',
            'default' => true,
        ),
        'total_hour' => 
        array (
            'type' => 'varchar',
            'vname' => 'Total Hours',
            'width' => '7%',
            'default' => true,
        ),
        'total_amount' => 
        array (
            'type' => 'currency',
            'default' => true,
            'vname' => 'LBL_TOTAL_AMOUNT',
            'currency_format' => true,
            'width' => '10%',
        ),
//        'remain_hour' => 
//        array (
//            'type' => 'decimal',
//            'studio' => 'visible',
//            'vname' => 'LBL_REMAIN_HOUR',
//            'width' => '9%',
//            'default' => true,
//        ),
//        'remain_amount' => 
//        array (
//            'type' => 'decimal',
//            'studio' => 'visible',
//            'vname' => 'LBL_REMAIN_AMOUNT',
//            'width' => '9%',
//            'default' => true,
//        ),
//        'assigned_user_name' => 
//        array (
//            'link' => true,
//            'type' => 'relate',
//            'vname' => 'LBL_ASSIGNED_TO_NAME',
//            'id' => 'ASSIGNED_USER_ID',
//            'width' => '10%',
//            'default' => true,
//            'widget_class' => 'SubPanelDetailViewLink',
//            'target_module' => 'Users',
//            'target_record_key' => 'assigned_user_id',
//        ),
        'description' => 
        array (
            'type' => 'text',
            'vname' => 'LBL_DESCRIPTION',
            'sortable' => false,
            'width' => '10%',
            'default' => true,
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
    )
);
if (($GLOBALS['current_user']->team_type == 'Adult')){
    unset($subpanel_layout['list_fields']['total_amount']);
}

?>