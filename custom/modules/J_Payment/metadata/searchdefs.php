<?php
$module_name = 'J_Payment';
$searchdefs[$module_name] =
array (
  'layout' =>
  array (
    'basic_search' =>
    array (
      'name' =>
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'payment_type' =>
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_PAYMENT_TYPE',
        'width' => '10%',
        'default' => true,
        'name' => 'payment_type',
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
    ),
    'advanced_search' =>
    array (
      'name' =>
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'contacts_j_payment_1_name' =>
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_CONTACTS_J_PAYMENT_1_FROM_CONTACTS_TITLE',
        'id' => 'CONTACTS_J_PAYMENT_1CONTACTS_IDA',
        'width' => '10%',
        'default' => true,
        'name' => 'contacts_j_payment_1_name',
      ),
      'payment_type' =>
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_PAYMENT_TYPE',
        'width' => '10%',
        'default' => true,
        'name' => 'payment_type',
      ),
      'payment_date' =>
      array (
        'type' => 'date',
        'label' => 'LBL_PAYMENT_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'payment_date',
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
      'sale_type' =>
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_SALE_TYPE',
        'width' => '10%',
        'name' => 'sale_type',
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
      'assigned_user_id' =>
      array (
        'name' => 'assigned_user_id',
        'label' => ($GLOBALS['current_user']->team_type == 'Adult') ? 'LBL_FIRST_SM' :'LBL_ASSIGNED_TO',
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
      'remain_amount' =>
      array (
        'type' => 'currency',
        'default' => true,
        'label' => 'LBL_REMAIN_AMOUNT',
        'currency_format' => true,
        'width' => '10%',
        'name' => 'remain_amount',
      ),
      'remain_hours' =>
      array (
        'type' => 'decimal',
        'default' => true,
        'label' => 'LBL_REMAIN_HOURS',
        'width' => '10%',
        'name' => 'remain_hours',
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
      'current_user_only' =>
      array (
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'width' => '10%',
        'default' => true,
        'name' => 'current_user_only',
      ),
      'favorites_only' =>
      array (
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
        'width' => '10%',
        'default' => true,
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
