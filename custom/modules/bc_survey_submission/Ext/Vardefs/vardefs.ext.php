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
// created: 2014-10-08 08:28:57
$dictionary["bc_survey_submission"]["fields"]["bc_submission_data_bc_survey_submission"] = array (
  'name' => 'bc_submission_data_bc_survey_submission',
  'type' => 'link',
  'relationship' => 'bc_submission_data_bc_survey_submission',
  'source' => 'non-db',
  'module' => 'bc_submission_data',
  'bean_name' => false,
  'side' => 'right',
  'vname' => 'LBL_BC_SUBMISSION_DATA_BC_SURVEY_SUBMISSION_FROM_BC_SUBMISSION_DATA_TITLE',
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
$dictionary["bc_survey_submission"]["fields"]["bc_survey_submission_accounts"] = array (
  'name' => 'bc_survey_submission_accounts',
  'type' => 'link',
  'relationship' => 'bc_survey_submission_accounts',
  'source' => 'non-db',
  'module' => 'Accounts',
  'bean_name' => 'Account',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_ACCOUNTS_FROM_ACCOUNTS_TITLE',
  'id_name' => 'bc_survey_submission_accountsaccounts_ida',
);
$dictionary["bc_survey_submission"]["fields"]["bc_survey_submission_accounts_name"] = array (
  'name' => 'bc_survey_submission_accounts_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_ACCOUNTS_FROM_ACCOUNTS_TITLE',
  'save' => true,
  'id_name' => 'bc_survey_submission_accountsaccounts_ida',
  'link' => 'bc_survey_submission_accounts',
  'table' => 'accounts',
  'module' => 'Accounts',
  'rname' => 'name',
);
$dictionary["bc_survey_submission"]["fields"]["bc_survey_submission_accountsaccounts_ida"]=array (
  'name' => 'bc_survey_submission_accountsaccounts_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_ACCOUNTS_FROM_BC_SURVEY_SUBMISSION_TITLE',
  'id_name' => 'bc_survey_submission_accountsaccounts_ida',
  'link' => 'bc_survey_submission_accounts',
  'table' => 'accounts',
  'module' => 'Accounts',
  'rname' => 'id',
  'reportable' => false,
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
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
$dictionary["bc_survey_submission"]["fields"]["bc_survey_submission_bc_survey"] = array (
  'name' => 'bc_survey_submission_bc_survey',
  'type' => 'link',
  'relationship' => 'bc_survey_submission_bc_survey',
  'source' => 'non-db',
  'module' => 'bc_survey',
  'bean_name' => false,
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_BC_SURVEY_FROM_BC_SURVEY_TITLE',
  'id_name' => 'bc_survey_submission_bc_surveybc_survey_ida',
);
$dictionary["bc_survey_submission"]["fields"]["bc_survey_submission_bc_survey_name"] = array (
  'name' => 'bc_survey_submission_bc_survey_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_BC_SURVEY_FROM_BC_SURVEY_TITLE',
  'save' => true,
  'id_name' => 'bc_survey_submission_bc_surveybc_survey_ida',
  'link' => 'bc_survey_submission_bc_survey',
  'table' => 'bc_survey',
  'module' => 'bc_survey',
  'rname' => 'name',
);
$dictionary["bc_survey_submission"]["fields"]["bc_survey_submission_bc_surveybc_survey_ida"]=array (
  'name' => 'bc_survey_submission_bc_surveybc_survey_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_BC_SURVEY_FROM_BC_SURVEY_SUBMISSION_TITLE',
  'id_name' => 'bc_survey_submission_bc_surveybc_survey_ida',
  'link' => 'bc_survey_submission_bc_survey',
  'table' => 'bc_survey',
  'module' => 'bc_survey',
  'rname' => 'id',
  'reportable' => false,
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
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
$dictionary["bc_survey_submission"]["fields"]["bc_survey_submission_contacts"] = array (
  'name' => 'bc_survey_submission_contacts',
  'type' => 'link',
  'relationship' => 'bc_survey_submission_contacts',
  'source' => 'non-db',
  'module' => 'Contacts',
  'bean_name' => 'Contact',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_CONTACTS_FROM_CONTACTS_TITLE',
  'id_name' => 'bc_survey_submission_contactscontacts_ida',
);
$dictionary["bc_survey_submission"]["fields"]["bc_survey_submission_contacts_name"] = array (
  'name' => 'bc_survey_submission_contacts_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_CONTACTS_FROM_CONTACTS_TITLE',
  'save' => true,
  'id_name' => 'bc_survey_submission_contactscontacts_ida',
  'link' => 'bc_survey_submission_contacts',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["bc_survey_submission"]["fields"]["bc_survey_submission_contactscontacts_ida"]=array (
  'name' => 'bc_survey_submission_contactscontacts_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_CONTACTS_FROM_BC_SURVEY_SUBMISSION_TITLE',
  'id_name' => 'bc_survey_submission_contactscontacts_ida',
  'link' => 'bc_survey_submission_contacts',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'id',
  'reportable' => false,
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
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
$dictionary["bc_survey_submission"]["fields"]["bc_survey_submission_leads"] = array (
  'name' => 'bc_survey_submission_leads',
  'type' => 'link',
  'relationship' => 'bc_survey_submission_leads',
  'source' => 'non-db',
  'module' => 'Leads',
  'bean_name' => 'Lead',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_LEADS_FROM_LEADS_TITLE',
  'id_name' => 'bc_survey_submission_leadsleads_ida',
);
$dictionary["bc_survey_submission"]["fields"]["bc_survey_submission_leads_name"] = array (
  'name' => 'bc_survey_submission_leads_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_LEADS_FROM_LEADS_TITLE',
  'save' => true,
  'id_name' => 'bc_survey_submission_leadsleads_ida',
  'link' => 'bc_survey_submission_leads',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["bc_survey_submission"]["fields"]["bc_survey_submission_leadsleads_ida"]=array (
  'name' => 'bc_survey_submission_leadsleads_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_LEADS_FROM_BC_SURVEY_SUBMISSION_TITLE',
  'id_name' => 'bc_survey_submission_leadsleads_ida',
  'link' => 'bc_survey_submission_leads',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'id',
  'reportable' => false,
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
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
$dictionary["bc_survey_submission"]["fields"]["bc_survey_submission_prospects"] = array (
  'name' => 'bc_survey_submission_prospects',
  'type' => 'link',
  'relationship' => 'bc_survey_submission_prospects',
  'source' => 'non-db',
  'module' => 'Prospects',
  'bean_name' => 'Prospect',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_PROSPECTS_FROM_PROSPECTS_TITLE',
  'id_name' => 'bc_survey_submission_prospectsprospects_ida',
);
$dictionary["bc_survey_submission"]["fields"]["bc_survey_submission_prospects_name"] = array (
  'name' => 'bc_survey_submission_prospects_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_PROSPECTS_FROM_PROSPECTS_TITLE',
  'save' => true,
  'id_name' => 'bc_survey_submission_prospectsprospects_ida',
  'link' => 'bc_survey_submission_prospects',
  'table' => 'prospects',
  'module' => 'Prospects',
  'rname' => 'account_name',
);
$dictionary["bc_survey_submission"]["fields"]["bc_survey_submission_prospectsprospects_ida"]=array (
  'name' => 'bc_survey_submission_prospectsprospects_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_PROSPECTS_FROM_BC_SURVEY_SUBMISSION_TITLE',
  'id_name' => 'bc_survey_submission_prospectsprospects_ida',
  'link' => 'bc_survey_submission_prospects',
  'table' => 'prospects',
  'module' => 'Prospects',
  'rname' => 'id',
  'reportable' => false,
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
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
$dictionary["bc_survey_submission"]["fields"]["bc_survey_submission_users"] = array (
  'name' => 'bc_survey_submission_users',
  'type' => 'link',
  'relationship' => 'bc_survey_submission_users',
  'source' => 'non-db',
  'module' => 'Users',
  'bean_name' => 'User',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_USERS_FROM_USERS_TITLE',
  'id_name' => 'bc_survey_submission_usersusers_ida',
);
$dictionary["bc_survey_submission"]["fields"]["bc_survey_submission_users_name"] = array (
  'name' => 'bc_survey_submission_users_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_USERS_FROM_USERS_TITLE',
  'save' => true,
  'id_name' => 'bc_survey_submission_usersusers_ida',
  'link' => 'bc_survey_submission_users',
  'table' => 'users',
  'module' => 'Users',
  'rname' => 'name',
);
$dictionary["bc_survey_submission"]["fields"]["bc_survey_submission_usersusers_ida"]=array (
  'name' => 'bc_survey_submission_usersusers_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_USERS_FROM_BC_SURVEY_SUBMISSION_TITLE',
  'id_name' => 'bc_survey_submission_usersusers_ida',
  'link' => 'bc_survey_submission_users',
  'table' => 'users',
  'module' => 'Users',
  'rname' => 'id',
  'reportable' => false,
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);


?>