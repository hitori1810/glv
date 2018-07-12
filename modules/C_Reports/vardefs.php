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

    $dictionary['C_Reports'] = array(
        'table'=>'c_reports',
        'audited'=>false,
        'duplicate_merge'=>true,
        'fields'=>array (
            'start_date' => 
            array (
                'required' => false,
                'name' => 'start_date',
                'vname' => 'LBL_START_DATE',
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
            'period' => 
            array (
                'required' => false,
                'name' => 'period',
                'vname' => 'LBL_PERIOD',
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
                'len' => 100,
                'size' => '20',
                'studio' => 'visible',
                'dependency' => false,
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
    VardefManager::createVardef('C_Reports','C_Reports', array('basic','team_security','assignable'));