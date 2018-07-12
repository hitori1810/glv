<?php
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

