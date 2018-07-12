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

