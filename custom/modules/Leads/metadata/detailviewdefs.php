<?php
$viewdefs['Leads'] = 
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
          3 => 
          array (
            'customCode' => '{$btn_convert_2}',
          ),
        ),
        'headerTpl' => 'modules/Leads/tpls/DetailViewHeader.tpl',
      ),
      'maxColumns' => '2',
      'useTabs' => false,
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
      'javascript' => '
            {sugar_getscript file="custom/modules/Leads/js/addToPT.js"}
            {sugar_getscript file="modules/Leads/Lead.js"}
            {sugar_getscript file="custom/modules/Leads/js/DetailView.js"}
            ',
      'tabDefs' => 
      array (
        'LBL_CONTACT_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_PANEL_COMPANY' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_PANEL_ASSIGNMENT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' => 
    array (
      'lbl_contact_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'full_lead_name',
            'label' => 'LBL_FULL_NAME',
          ),
          1 => 
          array (
            'name' => 'picture',
            'comment' => 'Picture file',
            'label' => 'LBL_PICTURE_FILE',
          ),
        ),
        1 => 
        array (
          0 => 'birthdate',
          1 => 'gender',
        ),
        2 => 
        array (
          0 => 'email1',
        ),
        3 => 
        array (
          0 => 'phone_mobile',
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'facebook',
            'label' => 'LBL_FACEBOOK',
          ),
        ),
        5 => 
        array (
          0 => 'description',
          1 => 'description',
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'primary_address_street',
            'label' => 'LBL_PRIMARY_ADDRESS',
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'primary',
            ),
          ),
        ),
      ),
      'lbl_panel_company' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'guardian_name',
            'label' => 'LBL_GUARDIAN_NAME',
          ),
          1 => 'other_mobile',
        ),
        1 => 
        array (
          0 => 'other_mobile',
          1 => 'email_parent_1',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'guardian_name_2',
            'comment' => '',
            'label' => 'LBL_GUARDIAN_NAME_2',
          ),
          1 => 
          array (
            'name' => 'phone_other',
            'comment' => 'Other phone number for the contact',
            'label' => 'LBL_OTHER_PHONE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'phone_other',
            'comment' => 'Other phone number for the contact',
            'label' => 'LBL_OTHER_PHONE',
          ),
          1 => 'email_parent_2',
        ),
      ),
      'lbl_panel_assignment' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'status',
            'customCode' => '<span class="{$STATUS_CSS}"><b>{$STATUS}<b></span>  ',
          ),
        ),
        1 => 
        array (
          0 => 'status_description',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'assigned_user_name',
            'customCode' => '{$assigned_user_idQ}',
          ),
          1 => 'team_name',
        ),
      ),
    ),
  ),
);
