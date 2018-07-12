<?php
$module_name = 'J_Marketingplan';
$viewdefs[$module_name] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'enctype' => 'multipart/form-data',
        'hidden' => 
        array (
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
      'javascript' => '{sugar_getscript file="include/javascript/popup_parent_helper.js"}
	{sugar_getscript file="cache/include/javascript/sugar_grp_jsolait.js"}
	{sugar_getscript file="modules/Documents/documents.js"}
    {sugar_getscript file="custom/modules/J_Marketingplan/js/editview.js"}',
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
          0 => 'document_name',
          1 => 
          array (
            'name' => 'status',
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
            'customCode' => '{html_options  style="width:26%;" name="status" id="status" options=$fields.status.options selected=$fields.status.value} ',
          ),
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
              'onchangeSetFileNameTo' => 'document_name',
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
            'customCode' => '<input type="text" style="width: 41%;" class="currency input_readonly" name="cost_enquiry" id="cost_enquiry" maxlength="255" value="{$fields.cost_enquiry.value}" title="{$MOD.LBL_COST_ENQUIRY}" readonly>',
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
          ),
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
              'required' => true,
            ),
          ),
        ),
      ),
    ),
  ),
);
