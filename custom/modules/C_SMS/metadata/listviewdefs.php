<?php
$module_name = 'C_SMS';
$listViewDefs[$module_name] = 
array (
  'name' => 
  array (
    'width' => '10%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'delivery_status' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_DELIVERY_STATUS',
    'width' => '10%',
  ),
  'parent_name' => 
  array (
    'type' => 'parent',
    'studio' => 'visible',
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
  'date_send' => 
  array (
    'type' => 'date',
    'label' => 'LBL_DATE_SEND',
    'width' => '10%',
    'default' => true,
  ),
  'phone_number' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_PHONE_NUMBER',
    'width' => '10%',
    'default' => true,
  ),
  'supplier' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_SUPPLIER',
    'width' => '7%',
    'default' => true,
  ),
  'description' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '25%',
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
  'assigned_user_name' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => false,
  ),
);
