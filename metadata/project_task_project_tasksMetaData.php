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




$dictionary['project_task_project_tasks'] = array(
	'table' => 'project_task_project_tasks',
	'fields' => array(
		'id' => array(
			'name' => 'id',
			'vname' => 'LBL_ID',
			'required' => true,
			'type' => 'id',
		),
		'project_task_id' => array(
			'name' => 'project_task_id',
			'vname' => 'LBL_PROJECT_TASK_ID',
			'required' => true,
			'type' => 'id',
		),
        'predecessor_project_task_id' => array(
            'name' => 'predecessor_project_task_id',
            'vname' => 'LBL_PROJECT_TASK_ID',
            'required' => true,
            'type' => 'id',
        ),        
		'deleted' => array(
			'name' => 'deleted',
			'vname' => 'LBL_DELETED',
			'type' => 'bool',
			'required' => false,
			'default' => '0',
		),
	),
	'indices' => array(
		array(
			'name' =>'proj_rel_pk',
			'type' =>'primary',
			'fields'=>array('id')
		),
	),

    'relationships' => array(
        'project_task_project_tasks' => array(
            'lhs_module'        => 'ProjectTasks2', 
            'lhs_table'         => 'project_tasks', 
            'lhs_key'           => 'id',
            'rhs_module'        => 'ProjectTasks2', 
            'rhs_table'         => 'project_tasks', 
            'rhs_key'           => 'id',
            'relationship_type' => 'many-to-many',
            'join_table'        => 'project_task_project_tasks', 
            'join_key_lhs'      => 'project_task_id', 
            'join_key_rhs'      => 'predecessor_project_task_id',
        ),
    ),
);

?>
