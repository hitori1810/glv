<?php
$module_name = 'J_Teachercontract';
$viewdefs[$module_name] = 
array (
  'QuickCreate' => 
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
        'LBL_EDITVIEW_PANEL1' => 
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
            'name' => 'no_contract',
            'label' => 'LBL_NO_CONTRACT',
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
            'label' => 'LBL_C_TEACHERS_J_TEACHERCONTRACT_1_FROM_C_TEACHERS_TITLE',
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
            'name' => 'less_non_working_hours',
            'label' => 'LBL_LESS_NON_WORKING_HOURS',
          ),
          1 => 
          array (
            'name' => 'working_hours_monthly',
            'label' => 'LBL_WORKING_HOURS_MONTHLY',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'teach_hours',
            'label' => 'LBL_TEACH_HOURS',
          ),
          1 => 
          array (
            'name' => 'admin_hours',
            'label' => 'LBL_ADMIN_HOURS',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'day_off',
            'studio' => 'visible',
            'label' => 'LBL_DAY_OFF',
          ),
        ),
        6 => 
        array (
          0 => 'description',
        ),
      ),
      'lbl_editview_panel1' => 
      array (
        0 => 
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
