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

$dictionary['C_Memberships'] = array(
    'table'=>'c_memberships',
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
            'len' => '150',
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
        'name_on_card' =>
        array (
            'required' => false,
            'name' => 'name_on_card',
            'vname' => 'LBL_NAME_ON_CARD',
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
            'len' => '255',
            'size' => '20',
        ),
        'upgrade_date' =>
        array (
            'audited' => false,
            'required' => true,
            'name' => 'upgrade_date',
            'vname' => 'LBL_UPGRADE_DATE',
            'type' => 'date',
            'massupdate' => 0,
            'no_default' => false,
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
            'display_default' => 'now',
        ),
        'picture' =>
        array (
            'name' => 'picture',
            'vname' => 'LBL_PICTURE_FILE',
            'type' => 'image',
            'dbtype' => 'varchar',
            'comment' => 'Picture file',
            'len' => 255,
            'width' => '120',
            'height' => '',
            'border' => '',
            'source'    => 'non-db',
        ),

         //Add Relationship Student - Membership
        'student_id' => array(
            'name' => 'student_id',
            'vname' => 'LBL_STUDENT_ID',
            'type' => 'id',
            'required'=>false,
            'reportable'=>false,
            'comment' => ''
        ),
        'student_name' => array(
            'required' => true,
            'name' => 'student_name',
            'rname' => 'name',
            'id_name' => 'student_id',
            'vname' => 'LBL_STUDENT_NAME',
            'type' => 'relate',
            'link' => 'student_link',
            'table' => 'contacts',
            'isnull' => 'true',
            'module' => 'Contacts',
            'dbType' => 'varchar',
            'len' => 'id',
            'reportable'=>true,
            'source' => 'non-db',
        ),
        'student_link' => array(
            'name' => 'student_link',
            'type' => 'link',
            'relationship' => 'student_membership',
            'link_type' => 'one',
            'side' => 'right',
            'source' => 'non-db',
            'vname' => 'LBL_STUDENT_NAME',
        ),
        //END: Add Relationship Student - Loyalty
    ),
    'relationships'=>array (
    ),
    'optimistic_locking'=>true,
    'unified_search'=>true,
);
if (!class_exists('VardefManager')){
    require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('C_Memberships','C_Memberships', array('basic','team_security','assignable'));