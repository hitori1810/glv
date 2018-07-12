<?php
$module_name = 'J_Invoice';
$searchdefs[$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
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
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'serial_no' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_SERIAL_NO',
        'width' => '10%',
        'default' => true,
        'name' => 'serial_no',
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
      'invoice_date' => 
      array (
        'type' => 'date',
        'label' => 'LBL_INVOICE_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'invoice_date',
      ),
      'serial_no' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_SERIAL_NO',
        'width' => '10%',
        'default' => true,
        'name' => 'serial_no',
      ),
      'status' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_STATUS',
        'width' => '10%',
        'name' => 'status',
      ),
      'created_by' => 
      array (
        'type' => 'assigned_user_name',
        'label' => 'LBL_CREATED',
        'width' => '10%',
        'default' => true,
        'name' => 'created_by',
      ),
      'modified_user_id' => 
      array (
        'type' => 'assigned_user_name',
        'label' => 'LBL_MODIFIED',
        'width' => '10%',
        'default' => true,
        'name' => 'modified_user_id',
      ),
      'invoice_amount' => 
      array (
        'type' => 'currency',
        'default' => true,
        'label' => 'LBL_INVOICE_AMOUNT',
        'currency_format' => true,
        'width' => '10%',
        'name' => 'invoice_amount',
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
      'total_discount_amount' => 
      array (
        'type' => 'currency',
        'label' => 'LBL_DISCOUNT_AMOUNT',
        'currency_format' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'total_discount_amount',
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
      'payment_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_PAYMENT_NAME',
        'width' => '10%',
        'default' => true,
        'id' => 'PAYMENT_ID',
        'name' => 'payment_name',
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
        'default' => true,
        'width' => '10%',
      ),
      'date_modified' => 
      array (
        'type' => 'datetime',
        'studio' => 
        array (
          'portaleditview' => false,
        ),
        'label' => 'LBL_DATE_MODIFIED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_modified',
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
        'name' => 'date_entered',
      ),
      'favorites_only' => 
      array (
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
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
