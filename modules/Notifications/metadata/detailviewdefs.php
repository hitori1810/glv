<?php
$module_name = 'Notifications';
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
//          0 => 'EDIT',
//          1 => 'DUPLICATE',
//          2 => 'DELETE',
        ),
      ),
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
    ),
    'panels' =>
    array (
      'default' =>
      array (
        0 =>
        array (
          0 => 'name',
        ),
        1 =>
        array (
          0 =>
          array (
            'name' => 'parent_name',
            'studio' => true,
            'label' => 'LBL_LIST_RELATED_TO',
          ),
          1 =>
          array (
            'name' => 'is_read',
            'label' => 'LBL_IS_READ',
          ),
        ),
        2 =>
        array (
          0 => 'assigned_user_name',
          1 =>
          array (
            'name' => 'severity',
            'label' => 'LBL_SEVERITY',
          ),
        ),
        3 =>
        array (
          0 => 'description',
        ),
        4 =>
        array (
          0 =>
          array (
            'name' => 'date_entered',
            'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
            'label' => 'LBL_DATE_ENTERED',
          ),
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
