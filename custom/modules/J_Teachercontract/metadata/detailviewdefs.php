<?php
$module_name = 'J_Teachercontract';
$viewdefs[$module_name] =
array (
  'DetailView' =>
  array (
    'templateMeta' =>
    array (
      'form' =>
      array (
        'buttons' =>
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 'FIND_DUPLICATES',
        ),
      ),
      'maxColumns' => '2',
      'javascript' => '
            {sugar_getscript file="custom/modules/J_Teachercontract/js/detailview.js"}',
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
        'LBL_DETAILVIEW_PANEL1' =>
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' =>
    array (
      'default' =>
      array (
        0 =>
        array (
          0 =>
          array (
            'name' => 'name',
            'label' => 'LBL_NO_CONTRACT',
            'customCode' => '{$name}',
          ),
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
            'name' => 'c_teachers_j_teachercontract_1_name',
          ),
          1 =>
          array (
            'name' => 'contract_date',
            'label' => 'LBL_CONTRACT_DATE',
          ),
        ),
        2 =>
        array (
          0 =>
          array (
            'name' => 'contract_type',
            'studio' => 'visible',
            'label' => 'LBL_CONTRACT_TYPE',
          ),
          1 =>
          array (
            'name' => 'contract_until',
            'label' => 'LBL_CONTRACT_UNTIL',
          ),
        ),
        3 =>
        array (
          0 =>
          array (
            'name' => 'working_hours_monthly',
            'label' => 'LBL_WORKING_HOURS_MONTHLY',
          ),
          1 => ''
        ),
        4 =>
        array (
          0 =>
          array (
            'name' => 'day_off',
            'studio' => 'visible',
            'label' => 'LBL_DAY_OFF',
            'customCode' => '{$DAYOFF}',
          ),
        ),
        5 =>
        array (
          0 => 'description',
        ),
      ),
      'lbl_detailview_panel1' =>
      array (
        0 =>
        array (
          0 => 'assigned_user_name',
          1 =>
          array (
            'name' => 'date_entered',
            'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
            'label' => 'LBL_DATE_ENTERED',
          ),
        ),
        1 =>
        array (
          0 => 'team_name',
          1 =>
          array (
            'name' => 'date_modified',
            'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
            'label' => 'LBL_DATE_MODIFIED',
          ),
        ),
      ),
    ),
  ),
);
