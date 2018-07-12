<?php
// created: 2015-07-08 11:26:49
$dictionary["Case"]["fields"]["contacts_cases_1"] = array (
  'name' => 'contacts_cases_1',
  'type' => 'link',
  'relationship' => 'contacts_cases_1',
  'source' => 'non-db',
  'module' => 'Contacts',
  'bean_name' => 'Contact',
  'side' => 'right',
  'vname' => 'LBL_CONTACTS_CASES_1_FROM_CASES_TITLE',
  'id_name' => 'contacts_cases_1contacts_ida',
  'link-type' => 'one',
);
$dictionary["Case"]["fields"]["contacts_cases_1_name"] = array (
  'name' => 'contacts_cases_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CONTACTS_CASES_1_FROM_CONTACTS_TITLE',
  'save' => true,
  'id_name' => 'contacts_cases_1contacts_ida',
  'link' => 'contacts_cases_1',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["Case"]["fields"]["contacts_cases_1contacts_ida"] = array (
  'name' => 'contacts_cases_1contacts_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_CONTACTS_CASES_1_FROM_CASES_TITLE_ID',
  'id_name' => 'contacts_cases_1contacts_ida',
  'link' => 'contacts_cases_1',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
