<?php 
 //WARNING: The contents of this file are auto-generated


/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
$dictionary['Account']['fields']['email1']['required'] = true;


// created: 2017-08-09 23:33:27
$dictionary["Account"]["fields"]["accounts_c_contacts_1"] = array (
  'name' => 'accounts_c_contacts_1',
  'type' => 'link',
  'relationship' => 'accounts_c_contacts_1',
  'source' => 'non-db',
  'module' => 'C_Contacts',
  'bean_name' => 'C_Contacts',
  'vname' => 'LBL_ACCOUNTS_C_CONTACTS_1_FROM_C_CONTACTS_TITLE',
  'id_name' => 'accounts_c_contacts_1accounts_ida',
  'link-type' => 'many',
  'side' => 'left',
);


// created: 2014-04-12 00:29:43
$dictionary["Account"]["fields"]["accounts_c_invoices_1"] = array (
  'name' => 'accounts_c_invoices_1',
  'type' => 'link',
  'relationship' => 'accounts_c_invoices_1',
  'source' => 'non-db',
  'module' => 'C_Invoices',
  'bean_name' => 'C_Invoices',
  'vname' => 'LBL_ACCOUNTS_C_INVOICES_1_FROM_ACCOUNTS_TITLE',
  'id_name' => 'accounts_c_invoices_1accounts_ida',
  'link-type' => 'many',
  'side' => 'left',
);


// created: 2014-04-12 00:31:10
$dictionary["Account"]["fields"]["accounts_c_payments_1"] = array (
  'name' => 'accounts_c_payments_1',
  'type' => 'link',
  'relationship' => 'accounts_c_payments_1',
  'source' => 'non-db',
  'module' => 'C_Payments',
  'bean_name' => 'C_Payments',
  'vname' => 'LBL_ACCOUNTS_C_PAYMENTS_1_FROM_ACCOUNTS_TITLE',
  'id_name' => 'accounts_c_payments_1accounts_ida',
  'link-type' => 'many',
  'side' => 'left',
);


/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
// created: 2014-10-08 08:28:57
$dictionary["Account"]["fields"]["bc_survey_accounts"] = array (
  'name' => 'bc_survey_accounts',
  'type' => 'link',
  'relationship' => 'bc_survey_accounts',
  'source' => 'non-db',
  'module' => 'bc_survey',
  'bean_name' => false,
  'vname' => 'LBL_BC_SURVEY_ACCOUNTS_FROM_BC_SURVEY_TITLE',
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
$dictionary["Account"]["fields"]["bc_survey_submission_accounts"] = array (
  'name' => 'bc_survey_submission_accounts',
  'type' => 'link',
  'relationship' => 'bc_survey_submission_accounts',
  'source' => 'non-db',
  'module' => 'bc_survey_submission',
  'bean_name' => false,
  'side' => 'right',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_ACCOUNTS_FROM_BC_SURVEY_SUBMISSION_TITLE',
);


// Add field - 16/07/2014 - created by MTN
$dictionary['Account']['fields']['tax_code']=array (
	'required' => false,
	'name' => 'tax_code',
	'vname' => 'LBL_TAX_CODE',
	'type' => 'varchar',
	'massupdate' => 0,
	'no_default' => false,
	'comments' => '',
	'help' => '',
	'importable' => 'true',
	'duplicate_merge' => 'disabled',
	'duplicate_merge_dom_value' => '0',
	'audited' => false,
	'reportable' => true,
	'unified_search' => false,
	'merge_filter' => 'disabled',
	'calculated' => false,
	'len' => '100',
	'size' => '20',
);

$dictionary['Account']['fields']['bank_number']=array (
	'required' => false,
	'name' => 'bank_number',
	'vname' => 'LBL_BANK_NUMBER',
	'type' => 'varchar',
	'massupdate' => 0,
	'no_default' => false,
	'comments' => '',
	'help' => '',
	'importable' => 'true',
	'duplicate_merge' => 'disabled',
	'duplicate_merge_dom_value' => '0',
	'audited' => false,
	'reportable' => true,
	'unified_search' => false,
	'merge_filter' => 'disabled',
	'calculated' => false,
	'len' => '100',
	'size' => '20',
);

$dictionary['Account']['fields']['bank_name']=array (
	'required' => false,
	'name' => 'bank_name',
	'vname' => 'LBL_BANK_NAME',
	'type' => 'varchar',
	'massupdate' => 0,
	'no_default' => false,
	'comments' => '',
	'help' => '',
	'importable' => 'true',
	'duplicate_merge' => 'disabled',
	'duplicate_merge_dom_value' => '0',
	'audited' => false,
	'reportable' => true,
	'unified_search' => false,
	'merge_filter' => 'disabled',
	'calculated' => false,
	'len' => '100',
	'size' => '20',
);

$dictionary['Account']['fields']['account_id']=array (
	'required' => false,
	'name' => 'account_id',
	'vname' => 'LBL_ACCOUNT_ID',
	'type' => 'varchar',
	'massupdate' => 0,
	'no_default' => false,
	'comments' => '',
	'help' => '',
	'importable' => 'false',
	'duplicate_merge' => 'disabled',
	'duplicate_merge_dom_value' => '0',
	'audited' => false,
	'reportable' => true,
	'unified_search' => false,
	'merge_filter' => 'disabled',
	'calculated' => false,
	'len' => '100',
	'size' => '20',
);

$dictionary['Account']['fields']['account_status']=array (
	'required' => false,
	'name' => 'account_status',
	'vname' => 'LBL_ACCOUNT_STATUS',
	'type' => 'enum',
	'massupdate' => 0,
	'no_default' => false,
	'comments' => '',
	'help' => '',
	'importable' => 'false',
	'duplicate_merge' => 'disabled',
	'duplicate_merge_dom_value' => '0',
	'audited' => false,
	'reportable' => false,
	'unified_search' => false,
	'merge_filter' => 'disabled',
	'calculated' => false,
	'len' => 100,
	'size' => '20',
	'options' => 'account_status_list',
	'studio' => 'visible',
	'dependency' => false,
);
//add field type_of_account
$dictionary['Account']['fields']['type_of_account']=array (
	'required' => false,
	'name' => 'type_of_account',
	'vname' => 'LBL_ACCOUNT_TYPE',
	'type' => 'enum',
	'massupdate' => 1,
	'default' => 'Student',
	'no_default' => false,
	'comments' => '',
	'help' => '',
	'importable' => 'true',
	'duplicate_merge' => 'disabled',
	'duplicate_merge_dom_value' => '0',
	'audited' => true,
	'reportable' => true,
	'unified_search' => false,
	'merge_filter' => 'disabled',
	'calculated' => false,
	'len' => 100,
	'size' => '20',
	'options' => 'type_accounts_list',
	'studio' => 'visible',
	'dependency' => false,
);

//Add Relationship Account - Payment (Xuất hóa đơn Corporate)
$dictionary['Account']['relationships']['account_payments'] = array(
	'lhs_module' => 'Accounts',
	'lhs_table' => 'accounts',
	'lhs_key' => 'id',
	'rhs_module' => 'J_Payment',
	'rhs_table' => 'j_payment',
	'rhs_key' => 'account_id',
	'relationship_type' => 'one-to-many'
);
$dictionary['Account']['fields']['payment_link'] = array(
	'name' => 'payment_link',
	'type' => 'link',
	'relationship' => 'account_payments',
	'module' => 'J_Payment',
	'bean_name' => 'J_Payment',
	'source' => 'non-db',
	'vname' => 'LBL_PAYMENT_NAME',
);


?>