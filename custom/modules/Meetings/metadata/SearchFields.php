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

$searchFields['Meetings'] = array (
  'name' =>
  array (
    'query_type' => 'default',
  ),
  'contact_name' =>
  array (
    'query_type' => 'default',
    'db_field' =>
    array (
      0 => 'contacts.first_name',
      1 => 'contacts.last_name',
    ),
  ),
  'date_start' =>
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
  'assigned_user_id' =>
  array (
    'query_type' => 'default',
  ),
  'status' =>
  array (
    'query_type' => 'default',
    'options' => 'meeting_status_dom',
    'template_var' => 'STATUS_FILTER',
  ),
  'favorites_only' =>
  array (
    'query_type' => 'format',
    'operator' => 'subquery',
    'subquery' => 'SELECT sugarfavorites.record_id FROM sugarfavorites
			                    WHERE sugarfavorites.deleted=0
			                        and sugarfavorites.module = \'Meetings\'
			                        and sugarfavorites.assigned_user_id = \'{0}\'',
    'db_field' =>
    array (
      0 => 'id',
    ),
  ),
  'open_only' =>
  array (
    'query_type' => 'default',
    'db_field' =>
    array (
      0 => 'status',
    ),
    'operator' => 'not in',
    'closed_values' =>
    array (
      0 => 'Held',
      1 => 'Not Held',
    ),
    'type' => 'bool',
  ),
  'range_date_entered' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_date_entered' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_date_entered' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_date_modified' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_date_modified' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_date_modified' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_date_start' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_date_start' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_date_start' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_date_end' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'start_range_date_end' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'end_range_date_end' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
    'is_date_field' => true,
  ),
  'range_duration_cal' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_duration_cal' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_duration_cal' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_delivery_hour' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_delivery_hour' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_delivery_hour' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_teaching_hour' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_teaching_hour' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_teaching_hour' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_first_duration' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_first_duration' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_first_duration' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_time_range' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_time_range' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_time_range' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'duration_hours' =>
  array (
    'query_type' => 'default',
  ),
  'duration_minutes' =>
  array (
    'query_type' => 'default',
  ),
  'duration_cal' =>
  array (
    'query_type' => 'default',
  ),
  'range_till_hour' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_till_hour' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_till_hour' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'range_aims_id' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'start_range_aims_id' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'end_range_aims_id' =>
  array (
    'query_type' => 'default',
    'enable_range_search' => true,
  ),
  'description' =>
  array (
    'query_type' => 'default',
  ),
);