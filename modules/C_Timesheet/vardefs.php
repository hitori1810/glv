<?php
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

$dictionary['C_Timesheet'] = array(
    'table'=>'c_timesheet',
    'audited'=>false,
    'duplicate_merge'=>true,
    'fields'=>array (
        'add_date' => 
        array (
            'required' => false,
            'name' => 'add_date',
            'vname' => 'LBL_ADD_DATE',
            'type' => 'date',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'size' => '20',
            'enable_range_search' => false,
        ),
        'duration' => 
        array (
            'required' => false,
            'name' => 'duration',
            'vname' => 'LBL_DURATION',
            'type' => 'decimal',
            'len' => '10',
            'precision' => '2',
        ),
        //Custom Relationship JUNIOR. Aamin Hour - Meeting  By Lap Nguyen
        'meetings_link'=>array(
            'name' => 'meetings_link',
            'type' => 'link',
            'relationship' => 'c_timesheet_meeting',
            'module' => 'Meetings',
            'bean_name' => 'Meetings',
            'source' => 'non-db',
            'vname' => 'LBL_MEETING_NAME',
        ),
    ),
    'relationships'=>array (
    //Custom Relationship JUNIOR. Aamin Hour - Meeting  By Lap Nguyen
        'c_timesheet_meeting' => array(
            'lhs_module'        => 'C_Timesheet',
            'lhs_table'            => 'c_timesheet',
            'lhs_key'            => 'id',
            'rhs_module'        => 'Meetings',
            'rhs_table'            => 'meetings',
            'rhs_key'            => 'timesheet_id',
            'relationship_type'    => 'one-to-many',
        ),
    ),
    'optimistic_locking'=>true,
    'unified_search'=>true,
);
if (!class_exists('VardefManager')){
    require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('C_Timesheet','C_Timesheet', array('basic','team_security','assignable'));