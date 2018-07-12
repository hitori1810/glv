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

$dictionary['SugarFeed'] = array(
	'table'=>'sugarfeed',
	'audited'=>false,
	'fields'=>array (
	 'name' => 
  array (
    'name' => 'name',
    'type' => 'name',
    'dbType' => 'varchar',
    'vname' => 'LBL_NAME',
    'len' => 255,
    'comment' => 'Name of the feed',
    'unified_search' => true,
    'full_text_search' => true,
    'audited' => true,
    'merge_filter' => 'selected',  //field will be enabled for merge and will be a part of the default search criteria..other valid values for this property are enabled and disabled, default value is disabled.
                            //property value is case insensitive.
  ),
   'description' => 
  array (
    'name' => 'description',
    'type' => 'name',
    'dbType' => 'varchar',
    'vname' => 'LBL_NAME',
    'len' => 255,
    'comment' => 'Name of the feed',
    'unified_search' => true,
    'full_text_search' => array('boost' => 1),
    'audited' => true,
    'merge_filter' => 'selected',  //field will be enabled for merge and will be a part of the default search criteria..other valid values for this property are enabled and disabled, default value is disabled.
                            //property value is case insensitive.
  ),
  
    'related_module' => 
  array (
    'name' => 'related_module',
    'type' => 'varchar',
    'vname' => 'LBL_NAME',
    'len' => 100,
    'comment' => 'related module',
    'unified_search' => true,
    'full_text_search' => array('boost' => 1),
    'audited' => false,
    'merge_filter' => 'selected',  //field will be enabled for merge and will be a part of the default search criteria..other valid values for this property are enabled and disabled, default value is disabled.
                            //property value is case insensitive.
  ),
   'related_id' => 
  array (
    'name' => 'related_id',
    'type' => 'id',
    'vname' => 'LBL_NAME',
    'len' => 36,
    'comment' => 'related module',
    'unified_search' => true,
    'audited' => false,
    'merge_filter' => 'selected',  //field will be enabled for merge and will be a part of the default search criteria..other valid values for this property are enabled and disabled, default value is disabled.
                            //property value is case insensitive.
  ),
  	 'link_url' => 
  array (
    'name' => 'link_url',
    'type' => 'varchar',
    'vname' => 'LBL_NAME',
    'len' => 255,
    'comment' => 'Name of the feed',
    'unified_search' => true,
    'full_text_search' => array('boost' => 1),
    'audited' => false,
    'merge_filter' => 'selected',  //field will be enabled for merge and will be a part of the default search criteria..other valid values for this property are enabled and disabled, default value is disabled.
                            //property value is case insensitive.
  ),
   	 'link_type' => 
  array (
    'name' => 'link_type',
    'type' => 'varchar',
    'vname' => 'LBL_NAME',
    'len' => 30,
    'comment' => 'Name of the feed',
    'unified_search' => true,
    'full_text_search' => array('boost' => 1),
    'audited' => false,
    'merge_filter' => 'selected',  //field will be enabled for merge and will be a part of the default search criteria..other valid values for this property are enabled and disabled, default value is disabled.
                            //property value is case insensitive.
  ),
	 
),
	'relationships'=>array (
    ),

    'indices' => array (
        array('name' => 'sgrfeed_date', 
              'type'=>'index',
              'fields'=>array('date_entered',
                              'deleted',
                  )),
        array('name' =>'idx_sgrfeed_rmod_rid_date',
              'type' =>'index',
              'fields'=>array('related_module', 'related_id', 'date_entered')
        ),
    ),

	'optimistic_lock'=>true,
);

VardefManager::createVardef('SugarFeed','SugarFeed', array('basic',
'team_security',
'assignable'));
