<?php
$popupMeta = array (
    'moduleMain' => 'ProductTemplates',
    'varName' => 'ProductTemplate',
    'orderBy' => 'product_templates.name',
    'whereClauses' => array (
  'name' => 'product_templates.name',
  'code' => 'producttemplates.code',
  'status2' => 'producttemplates.status2',
  'date_available' => 'producttemplates.date_available',
),
    'searchInputs' => array (
  0 => 'name',
  2 => 'code',
  3 => 'status2',
  4 => 'date_available',
),
    'searchdefs' => array (
  'code' => 
  array (
    'type' => 'varchar',
    'studio' => 'visible',
    'label' => 'LBL_CODE',
    'width' => '10%',
    'name' => 'code',
  ),
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'date_available' => 
  array (
    'type' => 'date',
    'label' => 'LBL_DATE_AVAILABLE',
    'width' => '10%',
    'name' => 'date_available',
  ),
  'status2' => 
  array (
    'type' => 'enum',
    'label' => 'LBL_STATUS_2',
    'width' => '10%',
    'name' => 'status2',
  ),
),
    'listviewdefs' => array (
  'NAME' => 
  array (
    'width' => '30',
    'label' => 'LBL_LIST_NAME',
    'link' => true,
    'default' => true,
    'name' => 'name',
  ),
  'TYPE_NAME' => 
  array (
    'width' => '10',
    'label' => 'LBL_LIST_TYPE',
    'sortable' => true,
    'default' => true,
    'name' => 'type_name',
  ),
  'CATEGORY_NAME' => 
  array (
    'width' => '10',
    'label' => 'LBL_LIST_CATEGORY',
    'sortable' => true,
    'default' => true,
    'name' => 'category_name',
  ),
  'STATUS' => 
  array (
    'width' => '10',
    'label' => 'LBL_LIST_STATUS',
    'default' => true,
    'name' => 'status',
  ),
  'QTY_IN_STOCK' => 
  array (
    'width' => '10',
    'label' => 'LBL_LIST_QTY_IN_STOCK',
    'default' => true,
    'name' => 'qty_in_stock',
  ),
  'COST_PRICE' => 
  array (
    'type' => 'currency',
    'label' => 'LBL_COST_PRICE',
    'currency_format' => true,
    'width' => '10',
    'default' => true,
    'name' => 'cost_price',
  ),
  'LIST_PRICE' => 
  array (
    'type' => 'currency',
    'label' => 'LBL_LIST_PRICE',
    'currency_format' => true,
    'width' => '10',
    'default' => true,
    'name' => 'list_price',
  ),
  'DISCOUNT_PRICE' => 
  array (
    'type' => 'currency',
    'label' => 'LBL_DISCOUNT_PRICE',
    'currency_format' => true,
    'width' => '10',
    'default' => true,
  ),
  'CURRENCY_ID' => 
  array (
    'width' => '5',
    'label' => 'LBL_CURRENCY_ID',
    'sortable' => true,
    'default' => false,
    'name' => 'currency_id',
  ),
),
);
