<?php
// created: 2017-08-10 20:47:56
$dictionary["Contract"]["fields"]["c_contacts_contracts_1"] = array (
  'name' => 'c_contacts_contracts_1',
  'type' => 'link',
  'relationship' => 'c_contacts_contracts_1',
  'source' => 'non-db',
  'module' => 'C_Contacts',
  'bean_name' => 'C_Contacts',
  'side' => 'right',
  'vname' => 'LBL_C_CONTACTS_CONTRACTS_1_FROM_C_CONTACTS_TITLE',
  'id_name' => 'c_contacts_contracts_1c_contacts_ida',
  'link-type' => 'one',
);
$dictionary["Contract"]["fields"]["c_contacts_contracts_1_name"] = array (
  'name' => 'c_contacts_contracts_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_C_CONTACTS_CONTRACTS_1_FROM_C_CONTACTS_TITLE',
  'save' => true,
  'id_name' => 'c_contacts_contracts_1c_contacts_ida',
  'link' => 'c_contacts_contracts_1',
  'table' => 'c_contacts',
  'module' => 'C_Contacts',
  'rname' => 'name',
);
$dictionary["Contract"]["fields"]["c_contacts_contracts_1c_contacts_ida"] = array (
  'name' => 'c_contacts_contracts_1c_contacts_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_C_CONTACTS_CONTRACTS_1_FROM_CONTRACTS_TITLE_ID',
  'id_name' => 'c_contacts_contracts_1c_contacts_ida',
  'link' => 'c_contacts_contracts_1',
  'table' => 'c_contacts',
  'module' => 'C_Contacts',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
