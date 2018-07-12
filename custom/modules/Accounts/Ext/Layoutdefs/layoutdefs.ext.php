<?php 
 //WARNING: The contents of this file are auto-generated


 // created: 2017-08-09 23:33:27
$layout_defs["Accounts"]["subpanel_setup"]['accounts_c_contacts_1'] = array (
  'order' => 100,
  'module' => 'C_Contacts',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_ACCOUNTS_C_CONTACTS_1_FROM_C_CONTACTS_TITLE',
  'get_subpanel_data' => 'accounts_c_contacts_1',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);


 // created: 2014-04-12 00:29:43
$layout_defs["Accounts"]["subpanel_setup"]['accounts_c_invoices_1'] = array (
  'order' => 100,
  'module' => 'C_Invoices',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_ACCOUNTS_C_INVOICES_1_FROM_C_INVOICES_TITLE',
  'get_subpanel_data' => 'accounts_c_invoices_1',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);


 // created: 2014-04-12 00:31:10
$layout_defs["Accounts"]["subpanel_setup"]['accounts_c_payments_1'] = array (
  'order' => 100,
  'module' => 'C_Payments',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_ACCOUNTS_C_PAYMENTS_1_FROM_C_PAYMENTS_TITLE',
  'get_subpanel_data' => 'accounts_c_payments_1',
  'top_buttons' =>
  array (
    0 =>
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    1 =>
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);

$layout_defs["Accounts"]["subpanel_setup"]["account_payments"] = array (
    'order' => 100,
    'module' => 'J_Payment',
    'subpanel_name' => 'default',
    'title_key' => 'Payment',
    'sort_order' => 'asc',
    'sort_by' => 'payment_type',
    'get_subpanel_data' => 'payment_link',
    'top_buttons' =>
    array (
    ),
);


 // created: 2014-10-08 08:28:57
/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
$layout_defs["Accounts"]["subpanel_setup"]['bc_survey_accounts'] = array (
  'order' => 100,
  'module' => 'bc_survey',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_BC_SURVEY_ACCOUNTS_FROM_BC_SURVEY_TITLE',
  'get_subpanel_data' => 'bc_survey_accounts',
  'top_buttons' => array (),
);


 // created: 2014-10-08 08:28:57
/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
$layout_defs["Accounts"]["subpanel_setup"]['bc_survey_submission_accounts'] = array (
  'order' => 100,
  'module' => 'bc_survey_submission',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_BC_SURVEY_SUBMISSION_ACCOUNTS_FROM_BC_SURVEY_SUBMISSION_TITLE',
  'get_subpanel_data' => 'bc_survey_submission_accounts',
    'top_buttons' =>
        array (),
);


//auto-generated file DO NOT EDIT
/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
$layout_defs['Accounts']['subpanel_setup']['bc_survey_submission_accounts']['override_subpanel_name'] = 'Account_subpanel_bc_survey_submission_accounts';


//auto-generated file DO NOT EDIT
$layout_defs['Accounts']['subpanel_setup']['contracts']['override_subpanel_name'] = 'Account_subpanel_contracts';

?>