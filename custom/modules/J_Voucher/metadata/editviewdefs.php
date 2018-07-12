<?php
$module_name = 'J_Voucher';
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
      'syncDetailEditViews' => false,
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
            'name' => 'status',
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
          ),
        ),
        1 =>
        array (
          0 =>
          array (
            'name' => 'discount_amount',
            'label' => 'LBL_DISCOUNT_AMOUNT',
          ),
          1 =>
          array (
            'name' => 'foc_type',
            'studio' => 'visible',
            'label' => 'LBL_FOC_TYPE',
          ),
        ),
        2 =>
        array (
          0 =>
          array (
            'name' => 'discount_percent',
            'label' => 'LBL_DISCOUNT_PERCENT',
          ),
          1 =>
          array (
            'name' => 'use_time',
            'studio' => 'visible',
            'label' => 'LBL_USE_TIME',
          ),
        ),
        3 =>
        array (
          0 =>
          array (
            'name' => 'amount_per_used',
            'label' => 'LBL_AMOUNT_PER_USED',
          ),
        ),
        4 =>
        array (
          0 =>
          array (
            'name' => 'student_name',
            'label' => 'LBL_STUDENT_NAME',
          ),
          1 =>
          array (
            'name' => 'start_date',
            'label' => 'LBL_START_DATE',
          ),
        ),
        5 =>
        array (
          0 =>
          array (
            'name' => 'description',
            'customCode' => '<textarea id="description" name="description" rows="4" cols="50" title="Description" tabindex="0">{$fields.description.value}</textarea>',
          ),
          1 =>
          array (
            'name' => 'end_date',
            'label' => 'LBL_END_DATE',
          ),
        ),
        6 =>
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
