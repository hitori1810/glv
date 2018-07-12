<?php
$module_name = 'C_Packages';
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
          0 => 
          array (
            'name' => 'c_programs_c_packages_1_name',
          ),
          1 => '',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'interval_package',
            'studio' => 'visible',
            'label' => 'LBL_INTERVAL_PACKAGE',
          ),
          1 => '',
        ),
        2 => 
        array (
          0 => 'name',
          1 => '',
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'price',
            'label' => 'LBL_PRICE',
          ),
          1 => '',
        ),
        4 => 
        array (
          0 => 'description',
        ),
        5 => 
        array (
          0 => 'assigned_user_name',
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'team_name',
            'displayParams' => 
            array (
              'display' => true,
            ),
          ),
          1 => '',
        ),
      ),
    ),
  ),
);
