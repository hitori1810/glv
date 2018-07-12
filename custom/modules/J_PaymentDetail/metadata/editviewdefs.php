<?php
$module_name = 'J_PaymentDetail';
$viewdefs[$module_name] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => false,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 'name',
          1 => 
          array (
            'name' => 'payment_date',
            'label' => 'LBL_PAYMENT_DATE',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'payment_name',
            'label' => 'LBL_PAYMENT_NAME',
          ),
          1 => 'status',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'before_discount',
            'label' => 'LBL_BEFORE_DISCOUNT',
          ),
          1 => 
          array (
            'name' => 'payment_no',
            'label' => 'LBL_PAYMENT_NO',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'discount_amount',
            'label' => 'LBL_DISCOUNT_AMOUNT',
          ),
          1 => 
          array (
            'name' => 'type',
            'studio' => 'visible',
            'label' => 'LBL_TYPE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'sponsor_amount',
            'label' => 'LBL_SPONSOR_AMOUNT',
          ),
          1 => 'is_discount',
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'payment_amount',
            'label' => 'LBL_PAYMENT_AMOUNT',
          ),
          1 => 
          array (
            'name' => 'payment_method',
            'studio' => 'visible',
            'label' => 'LBL_PAYMENT_METHOD',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'method_note',
          ),
          1 => 
          array (
            'name' => 'card_type',
            'customCode' => '{$card_type}',
            'label' => 'LBL_CARD_TYPE',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'invoice_name',
            'label' => 'LBL_INVOICE_NUMBER',
          ),
          1 => 
          array (
            'name' => 'serial_no',
            'label' => 'LBL_SERIAL_NO',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'description',
          ),
        ),
        9 => 
        array (
          0 => 'assigned_user_name',
          1 => 
          array (
            'name' => 'team_name',
            'displayParams' => 
            array (
              'display' => true,
            ),
          ),
        ),
      ),
    ),
  ),
);
