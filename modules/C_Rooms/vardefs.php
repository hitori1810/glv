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

    $dictionary['C_Rooms'] = array(
        'table'=>'c_rooms',
        'audited'=>true,
        'duplicate_merge'=>true,
        'fields'=>array (
            'name' => 
            array (
                'name' => 'name',
                'vname' => 'LBL_NAME',
                'type' => 'name',
                'link' => true,
                'dbType' => 'varchar',
                'len' => '255',
                'unified_search' => false,
                'full_text_search' => 
                array (
                    'boost' => 3,
                ),
                'required' => false,
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'merge_filter' => 'disabled',
                'massupdate' => 0,
                'no_default' => false,
                'comments' => '',
                'help' => '',
                'duplicate_merge_dom_value' => '0',
                'audited' => false,
                'reportable' => true,
                'calculated' => false,
                'size' => '20',
            ),
            'location' => 
            array (
                'required' => false,
                'name' => 'location',
                'vname' => 'LBL_LOCATION',
                'type' => 'text',
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
                'studio' => 'visible',
                'rows' => '5',
                'cols' => '70',
            ),
            'capacity' => 
            array (
              'required' => false,
                'name' => 'capacity',
                'vname' => 'LBL_CAPACITY',
                'type' => 'enum',
                'massupdate' => 0,
                'default' => '20',
                'no_default' => false,
                'importable' => 'true',
                'reportable' => true,
                'len' => 10,
                'size' => '20',
                'options' => 'capacity_list',
                'studio' => 'visible',
            ),
            'room_type' => 
            array (
                'required' => false,
                'name' => 'room_type',
                'vname' => 'LBL_ROOM_TYPE',
                'type' => 'enum',
                'massupdate' => 0,
                'default' => 'Adult',
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
                'len' => 100,
                'size' => '20',
                'options' => 'type_rooms_list',
                'studio' => 'visible',
                'dependency' => false,
            ),
            //Add field - 16/07/2014 - by MTN
            'room_id' => 
            array (
                'required' => false,
                'name' => 'room_id',
                'vname' => 'LBL_ROOM_ID',
                'type' => 'varchar',
                'massupdate' => 0,
                'no_default' => false,
                'comments' => '',
                'help' => '',
                'importable' => 'false',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => false,
                'reportable' => true,
                'unified_search' => false,
                'merge_filter' => 'disabled',
                'calculated' => false,
                'len' => '100',
                'size' => '20',
            ),
            'status' => 
            array (
                'required' => false,
                'name' => 'status',
                'vname' => 'LBL_STATUS',
                'type' => 'enum',
                'massupdate' => 0,
                'no_default' => false,
                'comments' => '',
                'help' => '',
                'importable' => 'false',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => false,
                'reportable' => true,
                'unified_search' => false,
                'merge_filter' => 'disabled',
                'calculated' => false,
                'len' => 100,
                'size' => '20',
                'options' => 'room_status_list',
                'studio' => 'visible',
                'dependency' => false,
            ),
           //Custom Relationship JUNIOR. Room - Meeting  By Lap Nguyen
//            'ju_meetings'=>array(
//                'name' => 'ju_meetings',
//                'type' => 'link',
//                'relationship' => 'ju_rooms_meetings',
//                'module' => 'Meetings',
//                'bean_name' => 'Meetings',
//                'source' => 'non-db',
//                'vname' => 'LBL_JU_MEETING',
//            ),
        ),
        'relationships'=>array (
        //Custom Relationship JUNIOR. Room - Meeting  By Lap Nguyen
//            'ju_rooms_meetings' => array(
//                'lhs_module'        => 'C_Rooms',
//                'lhs_table'            => 'c_rooms',
//                'lhs_key'            => 'id',
//                'rhs_module'        => 'Meetings',
//                'rhs_table'            => 'meetings',
//                'rhs_key'            => 'ju_room_id',
//                'relationship_type'    => 'one-to-many',
//            ),
        ),
        'optimistic_locking'=>true,
        'unified_search'=>true,
    );
    if (!class_exists('VardefManager')){
        require_once('include/SugarObjects/VardefManager.php');
    }
    VardefManager::createVardef('C_Rooms','C_Rooms', array('basic','team_security','assignable'));