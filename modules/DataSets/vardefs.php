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

$dictionary['DataSet'] = array('table' => 'data_sets'
                               ,'fields' => array (
  'id' =>
  array (
    'name' => 'id',
    'vname' => 'LBL_NAME',
    'type' => 'id',
    'required' => true,
    'reportable'=>false,
  ),
   'deleted' =>
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'required' => true,
    'default' => '0',
    'reportable'=>false,
  ),
   'date_entered' =>
  array (
    'name' => 'date_entered',
    'vname' => 'LBL_DATE_ENTERED',
    'type' => 'datetime',
    'required' => true,
  ),
  'date_modified' =>
  array (
    'name' => 'date_modified',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'datetime',
    'required' => true,
  ),
    'modified_user_id' =>
  array (
    'name' => 'modified_user_id',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id',
    'reportable'=>true,
  ),
  'created_by' =>
  array (
    'name' => 'created_by',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id'
  ),
  'parent_id' =>
  array (
    'name' => 'parent_id',
    'type' => 'id',
    'reportable'=>false,
  ),

 'parent_name' =>
 array (
	    'name' => 'parent_name',
	    'vname' => 'LBL_PARENT_DATASET',
	    'type' => 'relate',
	    'reportable'=>false,
	    'source'=>'non-db',
	    'table' => 'data_sets',
	    'id_name' => 'parent_id',
	    'module'=> 'DataSets',
	    'duplicate_merge'=>'disabled',
	    'comment' => 'Parent data sets for the data set (Meta-data only)',
 ),


  'report_id' =>
  array (
    'name' => 'report_id',
    'type' => 'id',
    'required'=>false,
    'reportable'=>false,
  ),

 'report_name' =>
 array (
	    'name' => 'report_name',
	    'vname' => 'LBL_REPORT_NAME',
	    'type' => 'varchar',
	    'reportable'=>false,
	    'source'=>'non-db',
	    'duplicate_merge'=>'disabled',
	    'comment' => 'Custom Queries for the data sets (Meta-data only)',
 ),

  'query_id' =>
  array (
    'name' => 'query_id',
    'vname' => 'LBL_QUERY_NAME',
    'type' => 'id',
    'required'=>true,
    'importable' => 'required',
  ),

 'query_name' =>
 array (
	    'name' => 'query_name',
	    'vname' => 'LBL_QUERY_NAME',
	    'type' => 'relate',
	    'reportable'=>false,
	    'required'=>true,
	    'source'=>'non-db',
	    'table' => 'custom_queries',
	    'id_name' => 'query_id',
	    'module'=>'CustomQueries',
	    'duplicate_merge'=>'disabled',
	    'comment' => 'Custom Queries for the data sets (Meta-data only)',
 ),

'child_name' =>
array (
        'name' => 'child_name',
        'vname' => 'LBL_CHILD_NAME',
        'source'=>'non-db',
        'type' => 'relate',
),

  'name' =>
  array (
    'name' => 'name',
    'vname' => 'LBL_NAME',
    'type' => 'varchar',
    'len' => '50',
    'importable' => 'required',
  ),
     'list_order_y' =>
  array (
    'name' => 'list_order_y',
    'vname' => 'LBL_LISTORDER_Y',
    'type' => 'int',
    'len' => '3',
    'default' => '0',
    'importable' => 'required',
  ),

 'exportable' =>
  array (
    'name' => 'exportable',
    'vname' => 'LBL_EXPORTABLE',
    'dbType' => 'varchar',
    'type' => 'bool',
    'len' => '3',
    'default' => '0',
  ),

  'header' =>
  array (
    'name' => 'header',
    'vname' => 'LBL_HEADER',
    'dbType' => 'varchar',
    'type' => 'bool',
    'len' => '3',
    'default' => '0',
  ),
  'description' =>
  array (
    'name' => 'description',
    'vname' => 'LBL_DESCRIPTION',
    'type' => 'text',
  ),
   'table_width' =>
  array (
    'name' => 'table_width',
    'vname' => 'LBL_TABLE_WIDTH',
    'type' => 'varchar',
    'dbType' => 'varchar',
    'len' => '3',
    'default' => '0',
  ),
     'font_size' =>
  array (
    'name' => 'font_size',
    'vname' => 'LBL_FONT_SIZE',
    'type' => 'enum',
    'options' => 'font_size_dom',
    'len'=>8,
    'default' => '0',
  ),
  'output_default' =>
  array (
    'name' => 'output_default',
    'vname' => 'LBL_OUTPUT_DEFAULT',
    'type' => 'enum',
    'options' => 'dataset_output_default_dom',
    'len' => 100,
  ),
       'prespace_y' =>
  array (
    'name' => 'prespace_y',
    'vname' => 'LBL_PRESPACE_Y',
    'type' => 'bool',
    'dbType' => 'varchar',
    'len' => '3',
    'default' => '0',
  ),
       'use_prev_header' =>
  array (
    'name' => 'use_prev_header',
    'vname' => 'LBL_USE_PREV_HEADER',
    'type' => 'bool',
    'dbType' => 'varchar',
    'len' => '3',
    'default' => '0',
  ),
    'header_back_color' =>
  array (
    'name' => 'header_back_color',
    'vname' => 'LBL_HEADER_BACK_COLOR',
    'type' => 'enum',
    'options' => 'report_color_dom',
    'len' => 100,
  ),
      'body_back_color' =>
  array (
    'name' => 'body_back_color',
    'vname' => 'LBL_BODY_BACK_COLOR',
    'type' => 'enum',
    'options' => 'report_color_dom',
    'len' => 100,
  ),
        'header_text_color' =>
  array (
    'name' => 'header_text_color',
    'vname' => 'LBL_HEADER_TEXT_COLOR',
    'type' => 'enum',
    'options' => 'report_color_dom',
    'len' => 100,
  ),
   'body_text_color' =>
  array (
    'name' => 'body_text_color',
    'vname' => 'LBL_BODY_TEXT_COLOR',
    'type' => 'enum',
    'options' => 'report_color_dom',
    'len' => 100,
  ),
  'table_width_type' =>
  array (
    'name' => 'table_width_type',
    'vname' => 'LBL_TABLE_WIDTH_TYPE',
    'type' => 'enum',
    'options' => 'width_type_dom',
    'len'=>3,
  ),
      'custom_layout' =>
  array (
    'name' => 'custom_layout',
    'vname' => 'LBL_CUSTOM_LAYOUT',
    'type' => 'enum',
    'options' => 'custom_layout_dom',
    'len'=>10,
  ),

),
'acls' => array('SugarACLAdminOnly' => array('allowUserRead' => true)),
'indices' => array (
       array('name' =>'dataset_k', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_dataset', 'type'=>'index', 'fields'=>array('name','deleted')),
                                                      )
                            );

VardefManager::createVardef('DataSets','DataSet', array(
'team_security',
));
?>