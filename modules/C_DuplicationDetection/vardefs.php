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

    $dictionary['C_DuplicationDetection'] = array(
        'table'=>'c_duplicationdetection',
        'audited'=>false,
        'duplicate_merge'=>true,
        'fields'=>array (
            'preventive_type' => 
            array (
                'required' => true,
                'name' => 'preventive_type',
                'vname' => 'LBL_PREVENTIVE_TYPE',
                'type' => 'enum',
                'options' => 'duplication_preventive_type_options',
                'comments' => 'Deternine which will be the taken action for the duplication',
            ),
            'target_module' => 
            array (
                'required' => true,
                'name' => 'target_module',
                'vname' => 'LBL_TARGET_MODULE',
                'type' => 'enum',
                'function' => 'getAvailableModules',
                'comments' => 'Target module that will apply duplication detection',
            ),
            'target_fields' => 
            array (
                'required' => true,
                'name' => 'target_fields',
                'vname' => 'LBL_TARGET_FIELDS',
                'type' => 'varchar',
                'comments' => 'Target fields that will apply duplication detection in the selected module',
            ),
            'is_active' => 
            array (
                'required' => true,
                'name' => 'is_active',
                'vname' => 'LBL_IS_ACTIVE',
                'type' => 'bool',
                'comments' => 'Boolean field that idicate wherether the selected module will be applied duplication detection',
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
    VardefManager::createVardef('C_DuplicationDetection','C_DuplicationDetection', array('basic','assignable'));