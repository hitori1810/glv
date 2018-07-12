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




$dictionary['project_relation'] = array(
	'table' => 'project_relation',
	'fields' => array(
		'id' => array(
			'name' => 'id',
			'vname' => 'LBL_ID',
			'required' => true,
			'type' => 'id',
		),
		'project_id' => array(
			'name' => 'project_id',
			'vname' => 'LBL_PROJECT_ID',
			'required' => true,
			'type' => 'id',
		),
		'relation_id' => array(
			'name' => 'relation_id',
			'vname' => 'LBL_PROJECT_NAME',
			'required' => true,
			'type' => 'id',
		),
		'relation_type' => array(
			'name' => 'relation_type',
			'vname' => 'LBL_PROJECT_NAME',
			'required' => true,
			'type' => 'enum',
			'options' => 'project_relation_type_options',
		),
		'deleted' => array(
			'name' => 'deleted',
			'vname' => 'LBL_DELETED',
			'type' => 'bool',
			'required' => true,
			'default' => '0',
		),
	    'date_modified' => array (
    		'name' => 'date_modified',
    		'vname' => 'LBL_DATE_MODIFIED',
    		'type' => 'datetime',
    		'required'=>true,
  		),
	),
	'indices' => array(
		array(
			'name' =>'proj_rel_pk',
			'type' =>'primary',
			'fields'=>array('id')
		),
	),

 	'relationships' => 
 		array ('projects_accounts' => array('lhs_module'=> 'Accounts', 'lhs_table'=> 'accounts', 'lhs_key' => 'id',
		'rhs_module'=> 'Project', 'rhs_table'=> 'project', 'rhs_key' => 'id',
		'relationship_type'=>'many-to-many',
		'join_table'=> 'project_relation', 'join_key_lhs'=>'relation_id', 'join_key_rhs'=>'project_id',
		'relationship_role_column'=>'relation_type','relationship_role_column_value'=>'Accounts'),
						  
		'projects_contacts' => array('lhs_module'=> 'Project', 'lhs_table'=> 'project', 'lhs_key' => 'id',
		'rhs_module'=> 'Contacts', 'rhs_table'=> 'contacts', 'rhs_key' => 'id',
		'relationship_type'=>'many-to-many',
		'join_table'=> 'project_relation', 'join_key_lhs'=>'project_id', 'join_key_rhs'=>'relation_id',
		'relationship_role_column'=>'relation_type','relationship_role_column_value'=>'Contacts'),							  

		'projects_opportunities' => array('lhs_module'=> 'Project', 'lhs_table'=> 'project', 'lhs_key' => 'id',
		'rhs_module'=> 'Opportunities', 'rhs_table'=> 'opportunities', 'rhs_key' => 'id',
		'relationship_type'=>'many-to-many',
		'join_table'=> 'project_relation', 'join_key_lhs'=>'project_id', 'join_key_rhs'=>'relation_id',
		'relationship_role_column'=>'relation_type','relationship_role_column_value'=>'Opportunities'),							  

		'projects_quotes' => array('lhs_module'=> 'Project', 'lhs_table'=> 'project', 'lhs_key' => 'id',
		'rhs_module'=> 'Quotes', 'rhs_table'=> 'quotes', 'rhs_key' => 'id',
		'relationship_type'=>'many-to-many',
		'join_table'=> 'project_relation', 'join_key_lhs'=>'project_id', 'join_key_rhs'=>'relation_id',
		'relationship_role_column'=>'relation_type','relationship_role_column_value'=>'Quotes'),							  
		
		),
);

?>
