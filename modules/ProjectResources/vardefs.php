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




$dictionary['ProjectResource'] = array ( 
	'table' => 'project_resources', 
	'fields' => array (
		'id' => array(
			'name' => 'id',
			'vname' => 'LBL_ID',
			'required' => true,
			'type' => 'id',
			'reportable'=>false,
			'comment' => 'Unique identifier'
		),
		'date_modified' => array(
			'name' => 'date_modified',
			'vname' => 'LBL_DATE_MODIFIED',
			'type' => 'datetime',
			'required' => true,
			'comment' => 'Date record last modified'
		),
		'modified_user_id' => array(
			'name' => 'modified_user_id',
			'rname' => 'user_name',
			'id_name' => 'modified_user_id',
			'vname' => 'LBL_MODIFIED_USER_ID',
			'type' => 'assigned_user_name',
			'table' => 'users',
			'isnull' => 'false',
			'dbType' => 'id',
			'reportable'=>true,
			'comment' => 'User who last modified record'
		),
		'created_by' => array(
			'name' => 'created_by',
			'rname' => 'user_name',
			'id_name' => 'modified_user_id',
			'vname' => 'LBL_CREATED_BY',
			'type' => 'assigned_user_name',
			'table' => 'users',
			'isnull' => 'false',
			'dbType' => 'id',
			'comment' => 'User who created record'
		),
  		array (
    		'name' => 'project_id',
    		'vname' => 'LBL_PROJECT_ID',
    		'reportable'=>false,
    		'dbtype' => 'id',
    		'type' => 'id',
		),
  		array (
    		'name' => 'resource_id',
    		'vname' => 'LBL_RESOURCE_ID',
    		'reportable'=>false,
    		'dbtype' => 'id',
    		'type' => 'id',
		),
  		array (
    		'name' => 'resource_type',
    		'vname' => 'LBL_RESOURCE_TYPE',
    		'reportable'=>false,
    		'type' => 'varchar',
    		'len' => '20',
		),
		'deleted' => array(
			'name' => 'deleted',
			'vname' => 'LBL_DELETED',
			'type' => 'bool',
			'required' => false,
			'default' => '0',
			'comment' => 'Record deletion indicator'
		),			
	),
    'indices' => array (
       	array('name' =>'project_resources_pk', 'type' =>'primary', 'fields'=>array('id')),
	),
);
?>
