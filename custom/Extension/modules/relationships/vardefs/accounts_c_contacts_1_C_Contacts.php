<?php
// created: 2017-08-09 23:33:27
$dictionary["C_Contacts"]["fields"]["accounts_c_contacts_1"] = array (
  'name' => 'accounts_c_contacts_1',
  'type' => 'link',
  'relationship' => 'accounts_c_contacts_1',
  'source' => 'non-db',
  'module' => 'Accounts',
  'bean_name' => 'Account',
  'side' => 'right',
  'vname' => 'LBL_ACCOUNTS_C_CONTACTS_1_FROM_ACCOUNTS_TITLE',
  'id_name' => 'accounts_c_contacts_1accounts_ida',
  'link-type' => 'one',
);
$dictionary["C_Contacts"]["fields"]["accounts_c_contacts_1_name"] = array (
  'name' => 'accounts_c_contacts_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_ACCOUNTS_C_CONTACTS_1_FROM_ACCOUNTS_TITLE',
  'save' => true,
  'id_name' => 'accounts_c_contacts_1accounts_ida',
  'link' => 'accounts_c_contacts_1',
  'table' => 'accounts',
  'module' => 'Accounts',
  'rname' => 'name',
);
$dictionary["C_Contacts"]["fields"]["accounts_c_contacts_1accounts_ida"] = array (
  'name' => 'accounts_c_contacts_1accounts_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_ACCOUNTS_C_CONTACTS_1_FROM_C_CONTACTS_TITLE_ID',
  'id_name' => 'accounts_c_contacts_1accounts_ida',
  'link' => 'accounts_c_contacts_1',
  'table' => 'accounts',
  'module' => 'Accounts',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
