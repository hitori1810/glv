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

$dictionary['J_ConfigInvoiceNo'] = array(
    'table'=>'j_configinvoiceno',
    'audited'=>true,
    'duplicate_merge'=>true,
    'fields'=>array (
        'invoice_no_from' =>
        array (
            'required' => false,
            'name' => 'invoice_no_from',
            'vname' => 'LBL_INVOICE_NO_FORM',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => 'Invoice Number',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => '20',
            'size' => '20',
        ),
        'invoice_no_to' =>
        array (
            'required' => false,
            'name' => 'invoice_no_to',
            'vname' => 'LBL_INVOICE_NO_TO',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => 'Invoice Number',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => '20',
            'size' => '20',
        ),
        'invoice_no_current' =>
        array (
            'required' => false,
            'name' => 'invoice_no_current',
            'vname' => 'LBL_INVOICE_NO_CURRENT',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => 'Invoice Number',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => '20',
            'size' => '20',
        ),
        'serial_no' =>
        array (
            'required' => false,
            'name' => 'serial_no',
            'vname' => 'LBL_SERIAL_NO',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => 'Serial No',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => '20',
            'size' => '20',
        ),
        'is_using' =>
            array (
                'name' => 'is_using',
                'vname' => 'LBL_IS_USING',
                'type' => 'radioenum',
                'massupdate' => 0,
                'default' => 'Range 1',
                'reportable' => true,
                'len' => 20,
                'options' => 'is_using_list',
                'studio' => 'visible',
                'dbType' => 'enum',
            ),


        'invoice_no_from_2' =>
        array (
            'required' => false,
            'name' => 'invoice_no_from_2',
            'vname' => 'LBL_INVOICE_NO_FORM',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => 'Invoice Number',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => '20',
            'size' => '20',
        ),
        'invoice_no_to_2' =>
        array (
            'required' => false,
            'name' => 'invoice_no_to_2',
            'vname' => 'LBL_INVOICE_NO_TO',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => 'Invoice Number',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => '20',
            'size' => '20',
        ),
        'serial_no_2' =>
        array (
            'required' => false,
            'name' => 'serial_no_2',
            'vname' => 'LBL_SERIAL_NO',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => 'Serial No',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => '20',
            'size' => '20',
        ),

        'release_list' =>
        array (
            'name' => 'release_list',
            'vname' => 'LBL_RELEASE_LIST',
            'type' => 'text',
        ),
        'active' =>
        array (
            'name' => 'active',
            'vname' => 'LBL_ACTIVE',
            'type' => 'bool',
            'audited' => false,
            'default' => '1',
        ),
        'finish_printing' =>
        array (
            'name' => 'finish_printing',
            'vname' => 'LBL_FINISH_PRINTING',
            'type' => 'bool',
            'audited' => false,
            'default' => '1',
        ),
        'pmd_id_printing' =>
        array (
            'required' => false,
            'name' => 'pmd_id_printing',
            'vname' => '',
            'type' => 'id',
            'massupdate' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => 0,
            'audited' => true,
            'reportable' => false,
            'len' => 36,
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
VardefManager::createVardef('J_ConfigInvoiceNo','J_ConfigInvoiceNo', array('basic','team_security','assignable'));