<?php
// created: 2017-01-17 14:27:54
$subpanel_layout['list_fields'] = array (
  'name' =>
  array (
    'vname' => 'LBL_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'width' => '10%',
    'default' => true,
  ),
  'discount_amount' =>
  array (
    'type' => 'currency',
    'default' => true,
    'vname' => 'LBL_DISCOUNT_AMOUNT',
    'currency_format' => true,
    'width' => '10%',
  ),
  'discount_percent' =>
  array (
    'type' => 'decimal',
    'default' => true,
    'vname' => 'LBL_DISCOUNT_PERCENT',
    'width' => '10%',
  ),
  'status' =>
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_STATUS',
    'width' => '10%',
  ),
  'used_time' =>
  array (
    'type' => 'int',
    'default' => true,
    'vname' => 'LBL_USED_TIME',
    'width' => '10%',
  ),
  'use_time' =>
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_USE_TIME',
    'width' => '10%',
  ),
  'foc_type' =>
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_FOC_TYPE',
    'width' => '10%',
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
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'Teams',
    'target_record_key' => 'team_id',
  ),
);