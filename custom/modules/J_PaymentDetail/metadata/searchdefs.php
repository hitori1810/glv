<?php
$module_name = 'J_PaymentDetail';
$searchdefs[$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'numeric_vat_no' => 
      array (
        'type' => 'int',
        'label' => 'LBL_INVOICE_NUMBER_INT',
        'width' => '10%',
        'default' => true,
        'name' => 'numeric_vat_no',
      ),
      'assigned_user_id' => 
      array (
        'name' => 'assigned_user_id',
        'label' => 'LBL_ASSIGNED_TO',
        'type' => 'enum',
        'function' => 
        array (
          'name' => 'get_user_array',
          'params' => 
          array (
            0 => false,
          ),
        ),
        'width' => '10%',
        'default' => true,
      ),
      'payment_date' => 
      array (
        'type' => 'date',
        'label' => 'LBL_PAYMENT_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'payment_date',
      ),
    ),
    'advanced_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'payment_date' => 
      array (
        'type' => 'date',
        'label' => 'LBL_PAYMENT_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'payment_date',
      ),
      'before_discount' => 
      array (
        'type' => 'currency',
        'label' => 'LBL_BEFORE_DISCOUNT',
        'currency_format' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'before_discount',
      ),
      'sponsor_amount' => 
      array (
        'type' => 'currency',
        'label' => 'LBL_SPONSOR_AMOUNT',
        'currency_format' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'sponsor_amount',
      ),
      'discount_amount' => 
      array (
        'type' => 'currency',
        'label' => 'LBL_DISCOUNT_AMOUNT',
        'currency_format' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'discount_amount',
      ),
      'payment_amount' => 
      array (
        'type' => 'currency',
        'default' => true,
        'label' => 'LBL_PAYMENT_AMOUNT',
        'currency_format' => true,
        'width' => '10%',
        'name' => 'payment_amount',
      ),
      'type' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_TYPE',
        'width' => '10%',
        'default' => true,
        'name' => 'type',
      ),
      'payment_method' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_PAYMENT_METHOD',
        'width' => '10%',
        'name' => 'payment_method',
      ),
      'status' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_STATUS',
        'width' => '10%',
        'default' => true,
        'name' => 'status',
      ),
      'numeric_vat_no' => 
      array (
        'type' => 'int',
        'label' => 'LBL_INVOICE_NUMBER_INT',
        'width' => '10%',
        'default' => true,
        'name' => 'numeric_vat_no',
      ),
      'payment_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_PAYMENT_NAME',
        'id' => 'PAYMENT_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'payment_name',
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
        'label' => 'LBL_TEAMS',
        'id' => 'TEAM_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'team_name',
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
