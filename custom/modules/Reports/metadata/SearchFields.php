<?php
// created: 2015-11-03 09:17:41
$searchFields['Reports'] = array (
  'name' => 
  array (
    'query_type' => 'default',
  ),
  'report_module' => 
  array (
    'query_type' => 'default',
    'db_field' => 
    array (
      0 => 'module',
    ),
  ),
  'assigned_user_id' => 
  array (
    'query_type' => 'default',
  ),
  'report_type' => 
  array (
    'query_type' => 'default',
  ),
  'current_user_only' => 
  array (
    'query_type' => 'default',
    'db_field' => 
    array (
      0 => 'assigned_user_id',
    ),
    'my_items' => true,
    'vname' => 'LBL_CURRENT_USER_FILTER',
    'type' => 'bool',
  ),
  'favorites_only' => 
  array (
    'query_type' => 'format',
    'operator' => 'subquery',
    'subquery' => 'SELECT sugarfavorites.record_id FROM sugarfavorites
			                    WHERE sugarfavorites.deleted=0
			                        and sugarfavorites.module = \'Reports\'
			                        and sugarfavorites.assigned_user_id = \'{0}\'',
    'db_field' => 
    array (
      0 => 'id',
    ),
  ),
);