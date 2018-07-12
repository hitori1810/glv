<?php

/*
 * By installing or using this file, you are confirming on behalf of the entity
 * subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
 * the SugarCRM Inc. Master Subscription Agreement ("MSA"), which is viewable at:
 * http://www.sugarcrm.com/master-subscription-agreement
 *
 * If Company is not bound by the MSA, then by installing or using this file
 * you are agreeing unconditionally that Company will be bound by the MSA and
 * certifying that you have authority to bind Company accordingly.
 *
 * Copyright (C) 2004-2014 SugarCRM Inc. All rights reserved.
 */

$dictionary['Notifications'] = array(
    'table' => 'notifications',
    'audited' => true,
    'fields' => array(
        'is_read' => array(
            'required' => false,
            'name' => 'is_read',
            'vname' => 'LBL_IS_READ',
            'type' => 'bool',
            'massupdate' => true,
            'comments' => '',
            'help' => '',
            'importable' => 'false',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => 0,
            'default' => 0,
            'reportable' => 1,
        ),
        'severity' => array(
            'len' => 15,
            'name' => 'severity',
            'options' => 'notifications_severity_list',
            'required' => true,
            'type' => 'enum',
            'massupdate' => false,
            'vname' => 'LBL_SEVERITY',
            'readonly' => true,
        ),
        'parent_name' =>
        array(
            'name' => 'parent_name',
            'parent_type' => 'record_type_display',
            'type_name' => 'parent_type',
            'id_name' => 'parent_id',
            'vname' => 'LBL_LIST_RELATED_TO',
            'type' => 'parent',
            'group' => 'parent_name',
            'source' => 'non-db',
            'options' => 'parent_type_display',
            'studio' => true,
            'massupdate' => false,
            'readonly' => true,
        ),
        'parent_type' =>
        array(
            'name' => 'parent_type',
            'vname' => 'LBL_PARENT_TYPE',
            'type' => 'parent_type',
            'dbType' => 'varchar',
            'group' => 'parent_name',
            'options' => 'parent_type_display',
            'len' => 100,
            'comment' => 'Module notification is associated with.',
            'studio' => array('searchview' => true, 'wirelesslistview' => true),
        ),
        'parent_id' =>
        array(
            'name' => 'parent_id',
            'vname' => 'LBL_PARENT_ID',
            'type' => 'id',
            'group' => 'parent_name',
            'reportable' => false,
            'comment' => 'ID of item indicated by parent_type.',
            'studio' => array('searchview' => false),
        ),
    ),
    'indices' => array(
        array(
            'name' => 'idx_notifications_my_unread_items',
            'type' => 'index',
            'fields' => array(
                'assigned_user_id',
                'is_read',
                'deleted',
            ),
        ),
    ),
    'relationships' => array(),
//    'optimistic_lock' => true,
);

require_once 'include/SugarObjects/VardefManager.php';
VardefManager::createVardef('Notifications', 'Notifications', array('basic', 'assignable'));

$dictionary['Notifications']['fields']['assigned_user_name']['massupdate'] = false;
$dictionary['Notifications']['fields']['assigned_user_id']['massupdate'] = false;
$dictionary['Notifications']['fields']['assigned_user_name']['readonly'] = true;
$dictionary['Notifications']['fields']['assigned_user_id']['readonly'] = true;
$dictionary['Notifications']['fields']['description']['readonly'] = true;
$dictionary['Notifications']['fields']['name']['readonly'] = true;

