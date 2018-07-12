<?php
$viewdefs['Contacts'] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'hidden' => 
        array (
          0 => '<input type="hidden" name="opportunity_id" value="{$smarty.request.opportunity_id}">',
          1 => '<input type="hidden" name="lead_id" value="{$lead_id}">',
          2 => '<input type="hidden" name="case_id" value="{$smarty.request.case_id}">',
          3 => '<input type="hidden" name="bug_id" value="{$smarty.request.bug_id}">',
          4 => '<input type="hidden" name="email_id" value="{$smarty.request.email_id}">',
          5 => '<input type="hidden" name="inbound_email_id" value="{$smarty.request.inbound_email_id}">',
          6 => '<input type="hidden" name="assigned_user_id_2" value="{$assigned_user_id_2}">',
          7 => '<input type="hidden" id="contact_id" name value="{$fields.contact_id.value}">',
          8 => '<input type="hidden" id="team_type" name value="{$team_type}">',
          9 => '<input type="hidden" name="birthdate_2" value="{$birthdate_2}">',
          10 => '<input type="hidden" name="last_name_2" value="{$last_name_2}">',
          11 => '<input type="hidden" name="first_name_2" value="{$first_name_2}">',
          12 => '<input type="hidden" name="phone_mobile_2" value="{$phone_mobile_2}">',
        ),
      ),
      'maxColumns' => '2',
      'javascript' => '
            {sugar_getscript file="custom/include/javascripts/Select2/select2.min.js"}
            {sugar_getscript file="custom/modules/Contacts/js/editviews.js"}
            {sugar_getscript file="custom/include/javascripts/Multifield/jquery.multifield.js"}
            {sugar_getscript file="custom/modules/Contacts/js/pGenerator.jquery.js"}
            {sugar_getscript file="custom/include/javascripts/AutoComplete/src/js/textext.core.js"}
            {sugar_getscript file="custom/include/javascripts/AutoComplete/src/js/textext.plugin.autocomplete.js"}

            <link rel="stylesheet" href="{sugar_getjspath file=custom/include/javascripts/Select2/select2.css}"/>
            <link rel="stylesheet" href="{sugar_getjspath file=custom/include/javascripts/AutoComplete/src/css/textext.core.css}"/>
            <link rel="stylesheet" href="{sugar_getjspath file=custom/include/javascripts/AutoComplete/src/css/textext.plugin.autocomplete.css}"/>
            ',
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
        'LBL_PORTAL_INFORMATION' => 
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
      'syncDetailEditViews' => false,
    ),
    'panels' => 
    array (
      'lbl_contact_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'contact_id',
            'label' => 'LBL_CONTACT_ID',
            'customCode' => '<input type="text" class="input_readonly" name="contact_idd" id="contact_id" maxlength="255" value="{$fields.contact_id.value}" title="{$MOD.LBL_CONTACT_ID}" size="30" readonly>',
          ),
          1 => 'picture',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'full_student_name',
            'customLabel' => '{$MOD.LBL_FULL_NAME} <span class="required">*</span>',
            'customCode' => '    
                        {html_options name="salutation" id="salutation" options=$fields.salutation.options selected=$fields.salutation.value}&nbsp;
                        &nbsp;<input name="last_name" id="last_name" placeholder="{$MOD.LBL_LAST_NAME_PLACEHOLDER}" style="margin-right: 3px;" size="20" type="text"  value="{$fields.last_name.value}">
                        &nbsp;<input name="first_name" id="first_name" placeholder="{$MOD.LBL_FIRST_NAME_PLACEHOLDER}" style="width:120px !important; margin-right: 3px;" size="15" type="text" value="{$fields.first_name.value}">
                        <div id = "dialogDuplicationLocated"></div>',
          ),
          1 => 
          array (
            'name' => 'dob_date',
            'label' => 'LBL_BIRTHDATE',
            'type' => 'Dob',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'email1',
            'studio' => 'false',
            'label' => 'LBL_EMAIL_ADDRESS',
          ),
          1 => 
          array (
            'name' => 'gender',
            'studio' => 'visible',
            'label' => 'LBL_GENDER',
            'displayParams' => 
            array (
              'required' => true,
            ),
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'phone_mobile',
            'label' => 'LBL_MOBILE_PHONE',
          ),
        ),
        4 => 
        array (
          0 => 'facebook',
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'displayParams' => 
            array (
              'rows' => 4,
              'cols' => 55,
            ),
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'primary_address_street',
            'hideLabel' => true,
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'primary',
              'rows' => 2,
              'cols' => 30,
              'maxlength' => 150,
            ),
          ),
        ),
      ),
      'LBL_PANEL_COMPANY' => 
      array (
        0 => 
        array (
          0 => 'guardian_name',
          1 => 'other_mobile',
        ),
        1 => 
        array (
          0 => 'other_mobile',
          1 => 'email_parent_1',
        ),
        2 => 
        array (
          0 => 'guardian_name_2',
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
      'lbl_portal_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'portal_name',
            'customCode' => '<table border="0" cellspacing="0" cellpadding="0"><tr><td>
                        {if !empty($fields.portal_name.value)}
                        <input class="input_readonly" id="portal_name" name="portal_name" type="text" size="30" maxlength="{$fields.portal_name.len|default:\'30\'}" value="{$fields.portal_name.value}" autocomplete="off">
                        {else}
                        <input class="input_readonly" id="portal_name" name="portal_name" type="text" size="30" maxlength="{$fields.portal_name.len|default:\'30\'}" value="Auto-Generate" autocomplete="off">
                        {/if}
                        <input type="hidden" id="portal_name_existing" value="{$fields.portal_name.value}" autocomplete="off">
                        </td><tr><tr><td><input type="hidden" id="portal_name_verified" value="true"></td></tr></table>',
          ),
          1 => 'portal_active',
        ),
        1 => 
        array (
          0 => 'portal_active',
        ),
      ),
      'LBL_PANEL_ASSIGNMENT' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'contact_status',
            'customCode' => '{$STATUS}',
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
            'customLabel' => '{$MOD.LBL_FIRST_EC}',
            'displayParams' => 
            array (
              'required' => true,
            ),
          ),
          1 => 'team_name',
        ),
      ),
    ),
  ),
);
