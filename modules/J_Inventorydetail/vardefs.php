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

$dictionary['J_Inventorydetail'] = array(
    'table'=>'j_inventorydetail',
    'audited'=>false,
    'duplicate_merge'=>true,
    'fields'=>array (
        'quantity' =>
        array (
            'required' => false,
            'name' => 'quantity',
            'vname' => 'LBL_QUANTITY',
            'type' => 'int',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => '50',
            'size' => '20',
            'enable_range_search' => false,
            'disable_num_format' => '',
            'min' => false,
            'max' => false,
        ),
        'price' =>
        array (
            'required' => false,
            'name' => 'price',
            'vname' => 'LBL_PRICE',
            'type' => 'currency',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => 26,
            'size' => '20',
            'enable_range_search' => false,
            'precision' => 6,
        ),
        'currency_id' =>
        array (
            'required' => false,
            'name' => 'currency_id',
            'vname' => 'LBL_CURRENCY',
            'type' => 'currency_id',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => 0,
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => 36,
            'size' => '20',
            'dbType' => 'id',
            'studio' => 'visible',
            'function' =>
            array (
                'name' => 'getCurrencyDropDown',
                'returns' => 'html',
            ),
        ),
        'amount' =>
        array (
            'required' => false,
            'name' => 'amount',
            'vname' => 'LBL_AMOUNT',
            'type' => 'currency',
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
            'len' => 26,
            'size' => '20',
            'enable_range_search' => false,
            'precision' => 6,
        ),
        //Add Relationship Inventory - Inventory detail
        'inventory_id' => array(
            'name' => 'inventory_id',
            'vname' => 'LBL_INVENTORY_ID',
            'type' => 'id',
            'required'=>false,
            'reportable'=>false,
            'comment' => ''
        ),
        'inventory_name' => array(
            'required' => true,
            'name' => 'inventory_name',
            'rname' => 'name',
            'id_name' => 'inventory_id',
            'vname' => 'LBL_INVENTORY_NAME',
            'type' => 'relate',
            'link' => 'j_inventory_link',
            'table' => 'j_inventory',
            'isnull' => 'true',
            'module' => 'J_Inventory',
            'dbType' => 'varchar',
            'reportable'=>true,
            'source' => 'non-db',
        ),
        'j_inventory_link' => array(
            'name' => 'j_inventory_link',
            'type' => 'link',
            'relationship' => 'inventory_inventorydetails',
            'link_type' => 'one',
            'side' => 'right',
            'source' => 'non-db',
            'vname' => 'LBL_INVENTORY_NAME',
        ),
        //END: Add Relationship Inventory - Inventory detail

        //Add Relationship Book - Inventory detail
        'book_id' => array(
            'name' => 'book_id',
            'vname' => 'LBL_BOOK_ID',
            'type' => 'id',
            'required'=>false,
            'reportable'=>false,
            'comment' => ''
        ),
        'book_name' => array(
            'required' => true,
            'name' => 'book_name',
            'rname' => 'name',
            'id_name' => 'book_id',
            'vname' => 'LBL_BOOK_NAME',
            'type' => 'relate',
            'link' => 'book_link',
            'table' => 'product_templates',
            'isnull' => 'true',
            'module' => 'ProductTemplates',
            'dbType' => 'varchar',
            'reportable'=>true,
            'source' => 'non-db',
        ),
        'book_link' => array(
            'name' => 'book_link',
            'type' => 'link',
            'relationship' => 'book_inventorydetails',
            'link_type' => 'one',
            'side' => 'right',
            'source' => 'non-db',
            'vname' => 'LBL_BOOK_NAME',
        ),
        //END: Add Relationship Book - Inventory detail
    ),
    'relationships'=>array (
    ),
    'optimistic_locking'=>true,
    'unified_search'=>true,
);
if (!class_exists('VardefManager')){
    require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('J_Inventorydetail','J_Inventorydetail', array('basic','team_security','assignable'));