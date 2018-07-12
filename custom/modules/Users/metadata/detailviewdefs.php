<?php
$viewdefs['Users'] = 
array (
  'DetailView' => 
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
      'form' => 
      array (
        'headerTpl' => 'modules/Users/tpls/DetailViewHeader.tpl',
        'footerTpl' => 'modules/Users/tpls/DetailViewFooter.tpl',
      ),
      'useTabs' => false,
      'tabDefs' => 
      array (
        'LBL_USER_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EMPLOYEE_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' => 
    array (
      'LBL_USER_INFORMATION' => 
      array (
        0 => 
        array (
          0 => 'full_name',
          1 => 'user_name',
        ),
        1 => 
        array (
          0 => 'status',
          1 => 
          array (
            'name' => 'UserType',
            'customCode' => '{$USER_TYPE_READONLY}',
          ),
        ),
        2 => 
        array (
          0 => 'picture',
          1 => 
          array (
            'name' => 'sign',
            'label' => 'LBL_PICTURE_SIGN_FILE',
          ),
        ),
      ),
      'LBL_EMPLOYEE_INFORMATION' => 
      array (
        0 => 
        array (
          0 => 'employee_status',
          1 => 'show_on_employees',
        ),
        1 => 
        array (
          0 => 'title',
          1 => 'phone_work',
        ),
        2 => 
        array (
          0 => 'department',
          1 => 'phone_mobile',
        ),
        3 => 
        array (
          0 => 'reports_to_name',
          1 => 'phone_other',
        ),
        4 => 
        array (
          1 => 'phone_fax',
        ),
        5 => 
        array (
          1 => 'phone_home',
        ),
        6 => 
        array (
          0 => 'messenger_type',
          1 => 'messenger_id',
        ),
        7 => 
        array (
          0 => 'address_street',
          1 => 'address_city',
        ),
        8 => 
        array (
          0 => 'address_state',
          1 => 'address_postalcode',
        ),
        9 => 
        array (
          0 => 'address_country',
        ),
        10 => 
        array (
          0 => 'description',
        ),
      ),
      'LBL_CTIUSER_PANEL' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'phoneextension_c',
          ),
          1 => 
          array (
            'name' => 'asteriskname_c',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'showclicktocall_c',
          ),
          1 => 
          array (
            'name' => 'dialout_prefix_c',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'dial_plan_c',
          ),
          1 => 
          array (
            'name' => 'call_notification_c',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'lastcall_c',
          ),
          1 => 
          array (
            'name' => 'relate_contact_c',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'schedulecall_c',
          ),
          1 => 
          array (
            'name' => 'taskcall_c',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'relate_account_c',
          ),
          1 => 
          array (
            'name' => 'create_account_c',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'create_lead_c',
          ),
          1 => 
          array (
            'name' => 'create_contact_c',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'call_transfer_c',
          ),
          1 => 
          array (
            'name' => 'call_hangup_c',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'create_case_c',
          ),
        ),
      ),
    ),
  ),
);
