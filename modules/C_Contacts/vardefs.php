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

    $dictionary['C_Contacts'] = array(
        'table'=>'c_contacts',
        'audited'=>true,
        'duplicate_merge'=>true,
        'fields'=>array (
            'address' => 
            array (
                'name' => 'address',
                'vname' => 'LBL_ADDRESS',
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
                'rows' => '2',
                'cols' => '20',
            ),
            'mobile_phone' => 
            array (
                'required' => false,
                'name' => 'mobile_phone',
                'vname' => 'LBL_MOBILE_PHONE',
                'type' => 'phone',
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
                'len' => '100',
                'size' => '20',
                'dbType' => 'varchar',
            ),
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
                'required' => true,
                'importable' => 'required',
                'duplicate_merge' => 'enabled',
                'merge_filter' => 'selected',
                'massupdate' => 0,
                'no_default' => false,
                'comments' => '',
                'help' => '',
                'duplicate_merge_dom_value' => '3',
                'audited' => false,
                'reportable' => true,
                'calculated' => false,
                'size' => '20',
            ),
            'parent_type' => 
            array (
                'required' => false,
                'name' => 'parent_type',
                'vname' => 'LBL_PARENT_TYPE',
                'type' => 'varchar',
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
                'len' => '100',
                'size' => '20',
            ),
            //Custom Email - By Lap Nguyen
            'email1' => 
            array (
                'name' => 'email1',
                'vname' => 'LBL_EMAIL_ADDRESS',
                'type' => 'varchar',
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
                'len' => '100',
                'size' => '20',
            ),

        ),
        'relationships'=>array (
        ),
        'optimistic_locking'=>true,
        'unified_search'=>true,
    );
    if (!class_exists('VardefManager')){
        require_once('include/SugarObjects/VardefManager.php');
    }
    VardefManager::createVardef('C_Contacts','C_Contacts', array('basic','team_security','assignable'));