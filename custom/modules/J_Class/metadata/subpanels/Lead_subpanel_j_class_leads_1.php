<?php
// created: 2015-10-19 10:59:37
$subpanel_layout['list_fields'] = array (
  'class_code' => 
  array (
    'type' => 'varchar',
    'vname' => 'LBL_CLASS_CODE',
    'width' => '15%',
    'default' => true,
  ),
  'name' => 
  array (
    'vname' => 'LBL_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'width' => '15%',
    'default' => true,
  ),
  'class_type' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_CLASS_TYPE',
    'width' => '10%',
  ),
  'start_date' => 
  array (
    'type' => 'date',
    'vname' => 'LBL_START_DATE',
    'width' => '10%',
    'default' => true,
  ),
);