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



$subpanel_layout = array(
	'top_buttons' => array(
        	array('widget_class'=>'SubPanelTopCreateButton'),
			array('widget_class'=>'SubPanelTopSelectButton', 'popup_module' => 'Meetings'),
		),

	'where' => '',


	'list_fields' => array(
    'name' =>
  array (
    'name' => 'name',
    'vname' => 'LBL_LIST_SUBJECT',
    'widget_class' => 'SubPanelDetailViewLink',
    'width' => '20%',
    'default' => true,
  ),
  'type' =>
  array (
    'type' => 'enum',
    'default' => true,
    'studio' =>
    array (
      'wireless_basic_search' => false,
    ),
    'vname' => 'LBL_SESSION_TYPE',
    'width' => '10%',
  ),
  'session_status' =>
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_SESSION_STATUS',
    'width' => '10%',
  ),
  'time_start_end' =>
  array (
    'name' => 'time_start_end',
    'vname' => 'Dates/Times',
    'width' => '12%',
    'default' => true,
    'sort_by' => 'date_start',
  ),
    'duration_cal' =>
  array (
    'type' => 'duration_cal',
    'vname' => 'LBL_DURATION',
    'width' => '10%',
    'default' => true,
  ),
  'teacher_name' =>
  array (
    'type' => 'relate',
    'link' => true,
    'vname' => 'LBL_TEACHER_NAME',
    'id' => 'TEACHER_ID',
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'C_Teachers',
    'target_record_key' => 'teacher_id',
  ),
  'room_name' =>
  array (
    'type' => 'relate',
    'link' => true,
    'vname' => 'LBL_ROOM_NAME',
    'id' => 'ROOM_ID',
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'C_Rooms',
    'target_record_key' => 'room_id',
  ),
  'recurring_source' =>
  array (
    'usage' => 'query_only',
  ),
        'date_start' =>
  array (
    'usage' => 'query_only',
  ),
  'date_end' =>
  array (
    'usage' => 'query_only',
  )
	),
);
?>
