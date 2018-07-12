<?php
$module_name = 'Notifications';
$listViewDefs[$module_name] = 
array (
  'is_read' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_IS_READ',
    'width' => '4%',
  ),
  'name' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'parent_name' => 
  array (
    'type' => 'parent',
    'studio' => true,
    'label' => 'LBL_LIST_RELATED_TO',
    'link' => true,
    'sortable' => false,
    'ACLTag' => 'PARENT',
    'dynamic_module' => 'PARENT_TYPE',
    'id' => 'PARENT_ID',
    'related_fields' => 
    array (
      0 => 'parent_id',
      1 => 'parent_type',
    ),
    'width' => '10%',
    'default' => true,
  ),
  'assigned_user_name' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'default' => true,
  ),
  'date_entered' => 
  array (
    'type' => 'datetime',
    'studio' => 
    array (
      'portaleditview' => false,
    ),
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => true,
  ),
  'created_by_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CREATED',
    'id' => 'CREATED_BY',
    'width' => '10%',
    'default' => true,
  ),
  'parent_type' => 
  array (
    'type' => 'parent_type',
    'studio' => 
    array (
      'searchview' => false,
      'wirelesslistview' => false,
    ),
    'label' => 'LBL_PARENT_TYPE',
    'width' => '5%',
    'default' => false,
  ),
  'severity' => 
  array (
    'type' => 'enum',
    'label' => 'LBL_SEVERITY',
    'width' => '5%',
    'default' => false,
  ),
  'description' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'team_name' => 
  array (
    'width' => '9%',
    'label' => 'LBL_TEAM',
    'default' => false,
  ),
);
