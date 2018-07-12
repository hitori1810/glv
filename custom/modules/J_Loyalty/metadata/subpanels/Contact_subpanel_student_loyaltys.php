<?php
// created: 2017-10-10 11:10:08
$subpanel_layout['list_fields'] = array (
  'input_date' => 
  array (
    'vname' => 'LBL_INPUT_DATE',
    'width' => '7%',
    'default' => true,
    'link' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => NULL,
    'target_record_key' => NULL,
  ),
  'type' => 
  array (
    'vname' => 'LBL_TYPE',
    'width' => '7%',
    'default' => true,
  ),
  'point' => 
  array (
    'vname' => 'LBL_POINT',
    'width' => '6%',
    'default' => true,
    'type' => 'varchar',
  ),
  'description' => 
  array (
    'vname' => 'LBL_DESCRIPTION',
    'width' => '15%',
    'default' => true,
  ),
  'payment_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'vname' => 'LBL_PAYMENT_NAME',
    'id' => 'PAYMENT_ID',
    'width' => '9%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'J_Payment',
    'target_record_key' => 'payment_id',
  ),
  'created_by_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'vname' => 'LBL_CREATED',
    'id' => 'CREATED_BY',
    'width' => '8%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'Users',
    'target_record_key' => 'created_by',
  ),
  'date_entered' => 
  array (
    'type' => 'datetime',
    'studio' => 
    array (
      'portaleditview' => false,
    ),
    'vname' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => true,
  ),
  'team_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'studio' => 
    array (
      'portallistview' => false,
      'portaldetailview' => false,
      'portaleditview' => false,
    ),
    'vname' => 'LBL_TEAMS',
    'id' => 'TEAM_ID',
    'width' => '8%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'Teams',
    'target_record_key' => 'team_id',
  ),
);