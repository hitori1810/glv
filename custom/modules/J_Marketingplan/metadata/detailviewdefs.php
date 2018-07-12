<?php
$module_name = 'J_Marketingplan';
$_object_name = 'j_marketingplan';
$viewdefs[$module_name] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'form' => 
      array (
      ),
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
            'name' => 'document_name',
            'label' => 'LBL_DOC_NAME',
          ),
          1 => 'status',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'category',
            'studio' => 'visible',
            'label' => 'LBL_CATEGORY',
          ),
          1 => 
          array (
            'name' => 'start_date',
            'label' => 'LBL_START_DATE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'currency_id',
            'studio' => 'visible',
            'label' => 'LBL_CURRENCY',
          ),
          1 => 
          array (
            'name' => 'end_date',
            'label' => 'LBL_END_DATE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'pr_number',
            'label' => 'LBL_PR_NUMBER',
          ),
          1 => 
          array (
            'name' => 'uploadfile',
            'displayParams' => 
            array (
              'link' => 'uploadfile',
              'id' => 'id',
            ),
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'budget',
            'label' => 'LBL_BUDGET',
          ),
          1 => 
          array (
            'name' => 'expected_cost',
            'label' => 'LBL_EXPECTED_COST',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'actual_cost',
            'label' => 'LBL_ACTUAL_COST',
          ),
          1 => 
          array (
            'name' => 'expected_revenue',
            'label' => 'LBL_EXPECTED_REVENUE',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'cost_enquiry',
            'label' => 'LBL_COST_ENQUIRY',
          ),
          1 => 
          array (
            'name' => 'expected_new_sale',
            'label' => 'LBL_EXPECTED_NEW_SALE',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'users_j_marketingplan_1_name',
          ),
          1 => 
          array (
            'name' => 'estimated_database',
            'label' => 'LBL_ESTIMATED_DATABASE',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'users_j_marketingplan_2_name',
          ),
          1 => 
          array (
            'name' => 'estimated_enquiries',
            'label' => 'LBL_ESTIMATED_ENQUIRIES',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'label' => 'LBL_DOC_DESCRIPTION',
          ),
        ),
      ),
      'lbl_detailview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
          ),
          1 => 
          array (
            'name' => 'date_entered',
            'comment' => 'Date record created',
            'studio' => 
            array (
              'portaleditview' => false,
            ),
            'label' => 'LBL_DATE_ENTERED',
          ),
        ),
        1 => 
        array (
          0 => 'team_name',
          1 => 
          array (
            'name' => 'date_modified',
            'comment' => 'Date record last modified',
            'studio' => 
            array (
              'portaleditview' => false,
            ),
            'label' => 'LBL_DATE_MODIFIED',
          ),
        ),
      ),
    ),
  ),
);
