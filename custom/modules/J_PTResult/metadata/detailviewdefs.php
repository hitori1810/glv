<?php
$module_name = 'J_PTResult';
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
          //0 => 'EDIT',
          //1 => 'DUPLICATE',
          //2 => 'DELETE',
          //3 => 'FIND_DUPLICATES',
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
        'LBL_DETAILVIEW_PANEL1' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_DETAILVIEW_PANEL2' => 
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
            'name' => 'contacts_j_ptresult_1_name',
          ),
          1 => 
          array (
            'name' => 'leads_j_ptresult_1_name',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'meetings_j_ptresult_1_name',
          ),
        ),
        3 => 
        array (
          0 => 'description',
        ),
      ),
      'lbl_detailview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'time_start',
            'label' => 'LBL_TIME_START',
          ),
          1 => 
          array (
            'name' => 'time_end',
            'label' => 'LBL_TIME_END',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'speaking',
            'label' => 'LBL_SPEAKING',
          ),
          1 => 
          array (
            'name' => 'writing',
            'label' => 'LBL_WRITING',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'listening',
            'label' => 'LBL_LISTENING',
          ),
          1 => 
          array (
            'name' => 'result',
            'label' => 'LBL_RESULT',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'attended',
            'label' => 'LBL_ATTENDED',
          ),
          1 => 
          array (
            'name' => 'pt_order',
            'label' => 'LBL_PT_ORDER',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'ec_note',
            'label' => 'LBL_EC_NOTE',
          ),
          1 => 
          array (
            'name' => 'teacher_comment',
            'label' => 'LBL_TEACHER_COMMENT',
          ),
        ),
      ),
      'lbl_detailview_panel2' => 
      array (
        0 => 
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
        1 => 
        array (
          0 => 'assigned_user_name',
          1 => 'team_name',
        ),
      ),
    ),
  ),
);
