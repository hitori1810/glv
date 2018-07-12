<?php
$listViewDefs['ProductTemplates'] =
array (
  'code' =>
  array (
    'type' => 'varchar',
    'label' => 'LBL_CODE',
    'width' => '10%',
    'default' => true,
  ),
  'name' =>
  array (
    'width' => '15%',
    'label' => 'LBL_LIST_NAME',
    'link' => true,
    'default' => true,
  ),
  'list_price' =>
  array (
    'type' => 'currency',
    'label' => 'LBL_LIST_PRICE',
    'currency_format' => true,
    'width' => '10%',
    'default' => true,
  ),
  'unit' =>
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_UNIT',
    'width' => '10%',
    'default' => true,
  ),
  'type_name' =>
  array (
    'width' => '7%',
    'label' => 'LBL_LIST_TYPE',
    'link' => false,
    'sortable' => true,
    'default' => true,
  ),
  'status2' =>
  array (
    'type' => 'enum',
    'label' => 'LBL_STATUS_2',
    'width' => '10%',
    'default' => true,
  ),
  'date_available' =>
  array (
    'type' => 'date',
    'label' => 'LBL_DATE_AVAILABLE',
    'width' => '10%',
    'default' => true,
  ),
  'description' =>
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '15%',
    'default' => true,
  ),
  'category_name' =>
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_CATEGORY',
    'link' => false,
    'sortable' => true,
    'default' => false,
  ),
  'vendor_part_num' =>
  array (
    'type' => 'varchar',
    'label' => 'LBL_VENDOR_PART_NUM',
    'width' => '10%',
    'default' => false,
  ),
);
