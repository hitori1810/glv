<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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


$dictionary['KBDocumentKBTag'] = array('table' => 'kbdocuments_kbtags'
                               ,'fields' => array (
  'id' =>
  array (
    'name' => 'id',
    'vname' => 'LBL_KBDOCUMENTS_KBTAGS_ID',
    'type' => 'varchar',
    'len' => '36',
    'required'=>true,
    'reportable'=>false,
  ),
  'kbdocument_id' =>
  array (
    'name' => 'kbdocument_id',
    'vname' => 'LBL_KBDOCUMENT_ID',
    'type' => 'varchar',
    'len' => '36',
    'required'=>false,
    'reportable'=>false,
  ),
  'kbtag_id' =>
  array (
    'name' => 'kbtag_id',
    'vname' => 'LBL_TAG_ID',
    'type' => 'varchar',
    'len' => '36',
    'required'=>false,
    'reportable'=>false,
  ),
  'date_entered' => array (
	'name' => 'date_entered',
	'vname' => 'LBL_DATE_ENTERED',
	'type' => 'datetime',
	'comment' => 'Date record created'
  ),
  'created_by' =>
  array (
    'name' => 'created_by',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_CREATED',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id',
    'source'=>'db',
  ),
  'revision'=>
  array (
    'name' => 'revision',
    'vname' => 'LBL_REVISION',
    'type' => 'varchar',
    'len' => 100,
    'importable' => 'required',
  ),
  'deleted' =>
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'default' => 0,
    'reportable'=>false,
  ),
  'date_modified' =>
  array (
    'name' => 'date_modified',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'datetime',
  ),
'created_by_link' =>
  array (
    'name' => 'created_by_link',
    'type' => 'link',
    'relationship' => 'kbrevisions_created_by',
    'vname' => 'LBL_CREATED_BY_USER',
    'link_type' => 'one',
    'module'=>'Users',
    'bean_name'=>'User',
    'source'=>'non-db',
  ),
'created_by_name' =>
  array (
    'name' => 'created_by_name',
    'rname' => 'user_name',
    'db_concat_fields'=> array(0=>'last_name', 1=>'first_name'),
    'id_name' => 'created_by',
    'vname' => 'LBL_CREATED_BY_NAME',
    'type' => 'relate',
    'table' => 'users',
    'isnull' => 'true',
    'module' => 'Users',
    'dbType' => 'varchar',
    'link'=>'created_by_link',
    'len' => '255',
   	'source'=>'non-db',
  ),
),
'relationships'=>array(
   'kbrevisions_created_by' => array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
 	  		'rhs_module'=> 'KBDocumentKBTags', 'rhs_table'=> 'kbdocuments_kbtags', 'rhs_key' => 'created_by',
   			'relationship_type'=>'one-to-many'),

),
'indices' => array (
       array('name' =>'kbdocumentskbtagspk', 'type' =>'primary', 'fields'=>array('id')),
	   array('name' => 'idx_doc_id_tag_id', 'type' => 'index', 'fields' => array('kbdocument_id', 'kbtag_id')),
)
);

VardefManager::createVardef('KBDocumentKBTags','KBDocumentKBTag', array(
'team_security',
));
?>
