<?php
$searchdefs['ProductTemplates'] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      0 => 'name',
    ),
    'advanced_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'code' => 
      array (
        'type' => 'varchar',
        'studio' => 'visible',
        'label' => 'LBL_CODE',
        'width' => '10%',
        'default' => true,
        'name' => 'code',
      ),
      'date_available' => 
      array (
        'name' => 'date_available',
        'default' => true,
        'width' => '10%',
      ),
      'date_modified' => 
      array (
        'type' => 'datetime',
        'label' => 'LBL_DATE_MODIFIED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_modified',
      ),
      'cost_price' => 
      array (
        'type' => 'currency',
        'label' => 'LBL_COST_PRICE',
        'currency_format' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'cost_price',
      ),
      'discount_price' => 
      array (
        'type' => 'currency',
        'label' => 'LBL_DISCOUNT_PRICE',
        'currency_format' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'discount_price',
      ),
      'status2' => 
      array (
        'type' => 'enum',
        'label' => 'LBL_STATUS_2',
        'width' => '10%',
        'default' => true,
        'name' => 'status2',
      ),
      'type_id' => 
      array (
        'name' => 'type_id',
        'label' => 'LBL_TYPE',
        'type' => 'multienum',
        'function' => 
        array (
          'name' => 'getProductTypes',
          'returns' => 'html',
          'include' => 'modules/ProductTemplates/ProductTemplate.php',
          'preserveFunctionValue' => true,
        ),
        'default' => true,
        'width' => '10%',
      ),
    ),
  ),
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'maxColumnsBasic' => '4',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
);
