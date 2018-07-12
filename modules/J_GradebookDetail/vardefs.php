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

$dictionary['J_GradebookDetail'] = array(
    'table'=>'j_gradebookdetail',
    'audited'=>true,
    'duplicate_merge'=>true,
    'fields'=>array (
        //add relationship between student and gradebook detail
        'student_name' => array(
            'required'  => true,
            'source'    => 'non-db',
            'name'      => 'student_name',
            'vname'     => 'LBL_STUDENT_NAME',
            'type'      => 'relate',
            'rname'     => 'name',
            'id_name'   => 'student_id',
            'join_name' => 'contacts',
            'link'      => 'student_j_gradebookdetail',
            'table'     => 'contacts',
            'isnull'    => 'true',
            'module'    => 'Contacts',
        ),
        'student_id' => array(
            'name'              => 'student_id',
            'rname'             => 'id',
            'vname'             => 'LBL_STUDENT_ID',
            'type'              => 'id',
            'table'             => 'contacts',
            'isnull'            => 'true',
            'module'            => 'Contacts',
            'dbType'            => 'id',
            'reportable'        => false,
            'massupdate'        => false,
            'duplicate_merge'   => 'disabled',
        ),
        'student_j_gradebookdetail' => array(
            'name'          => 'student_j_gradebookdetail',
            'type'          => 'link',
            'relationship'  => 'student_j_gradebookdetail',
            'module'        => 'Contacts',
            'bean_name'     => 'Contact',
            'source'        => 'non-db',
            'vname'         => 'LBL_STUDENT_NAME',
        ),
        //end
        //add relationship between gradebook and gradebook detail
        'gradebook_name' => array(
            'required'  => false,
            'source'    => 'non-db',
            'name'      => 'gradebook_name',
            'vname'     => 'LBL_GRADEBOOK_NAME',
            'type'      => 'relate',
            'rname'     => 'name',
            'id_name'   => 'gradebook_id',
            'join_name' => 'j_gradebook',
            'link'      => 'j_gradebook_j_gradebookdetail',
            'table'     => 'j_gradebook',
            'isnull'    => 'true',
            'module'    => 'J_Gradebook',
        ),
        'gradebook_id' => array(
            'name'              => 'gradebook_id',
            'rname'             => 'id',
            'vname'             => 'LBL_GRADEBOOK_ID',
            'type'              => 'id',
            'table'             => 'j_gradebook',
            'isnull'            => 'true',
            'module'            => 'J_Gradebook',
            'dbType'            => 'id',
            'reportable'        => false,
            'massupdate'        => false,
            'duplicate_merge'   => 'disabled',
        ),
        'j_gradebook_j_gradebookdetail' => array(
            'name'          => 'j_gradebook_j_gradebookdetail',
            'type'          => 'link',
            'relationship'  => 'j_gradebook_j_gradebookdetail',
            'module'        => 'J_Gradebook',
            'bean_name'     => 'J_Gradebook',
            'source'        => 'non-db',
            'vname'         => 'LBL_GRADEBOOK_NAME',
        ),
        //end

        'content' => array(
            'name' => 'content',
            'vname' => 'LBL_CONTENT',
            'type' => 'text',
        ),
        'final_result' =>
        array (
            'required' => false,
            'name' => 'final_result',
            'vname' => 'LBL_FINAL_RESULT',
            'type' => 'decimal',
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
            'len' => '10',
            'size' => '20',
            'enable_range_search' => false,
            'precision' => '2',
        ),
        // add relate field between j_class and gradebook detail
        'j_class_id' => array (
            'required' => false,
            'name' => 'j_class_id',
            'vname' => '',
            'type' => 'id',
            'massupdate' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => 0,
            'audited' => false,
            'reportable' => false,
            'len' => 36,
            'size' => '20',
        ),
        'j_class_relate_field' => array (
            'required' => false,
            'source' => 'non-db',
            'name' => 'j_class_relate_field',
            'vname' => 'LBL_J_CLASS_RELATE_FIELD',
            'type' => 'relate',
            'massupdate' => 0,
            'comments' => 'Field type is Relate to J_Class',
            'help' => 'Field type is Relate to J_Class',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '1',
            'audited' => true,
            'reportable' => true,
            'len' => '255',
            'size' => '20',
            'id_name' => 'j_class_id',
            'ext2' => 'J_Class',
            'module' => 'J_Class',
            'rname' => 'name',
            'quicksearch' => 'enabled',
            'studio' => 'visible',
        ),
        //end
        'date_input' => array(
            'name' => 'date_input',
            'vname' => 'LBL_DATE_INPUT',
            'type' => 'date',
            'display_default' => 'now',
        ),

        'certificate_type' => array(
            'name'  => 'certificate_type',
            'vname' => 'LBL_CERTIFICATE_TYPE',
            'type'  => 'varchar',
            'len'   => '100',
        ),
        'certificate_level' => array(
            'name'  => 'certificate_level',
            'vname' => 'LBL_CERTIFICATE_LEVEL',
            'type'  => 'varchar',
            'len'   => '10',
        ),

        'final_result_text' => array(
            'name' => 'final_result_text',
            'vname' => 'LBL_FINAL_RESULT',
            'type' => 'varchar',
            'source' => 'non-db',
        ),

        'teacher_id' =>
        array (
            'required' => false,
            'name' => 'teacher_id',
            'vname' => '',
            'type' => 'id',
            'massupdate' => 0,
            'comments' => '',
            'help' => '',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => 0,
            'audited' => false,
            'reportable' => false,
            'len' => 36,
            'size' => '20',
        ),
        'teacher_name' =>
        array (
            'required' => false,
            'source' => 'non-db',
            'name' => 'teacher_name',
            'vname' => 'LBL_TEACHER_NAME',
            'type' => 'relate',
            'massupdate' => 0,
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '1',
            'audited' => true,
            'reportable' => true,
            'len' => '255',
            'size' => '20',
            'id_name' => 'teacher_id',
            'ext2' => 'C_Teachers',
            'module' => 'C_Teachers',
            'rname' => 'full_teacher_name',
            'quicksearch' => 'enabled',
            'studio' => 'visible',
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
VardefManager::createVardef('J_GradebookDetail','J_GradebookDetail', array('basic','team_security','assignable'));