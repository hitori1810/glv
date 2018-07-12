<?php
// created: 2014-07-23 08:54:18
$dictionary["Contact"]["fields"]["contracts_contacts_1"] = array (
  'name' => 'contracts_contacts_1',
  'type' => 'link',
  'relationship' => 'contracts_contacts_1',
  'source' => 'non-db',
  'module' => 'Contracts',
  'bean_name' => 'Contract',
  'side' => 'right',
  'vname' => 'LBL_CONTRACTS_CONTACTS_1_FROM_CONTACTS_TITLE',
  'id_name' => 'contracts_contacts_1contracts_ida',
  'link-type' => 'one',
);
$dictionary["Contact"]["fields"]["contracts_contacts_1_name"] = array (
  'name' => 'contracts_contacts_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CONTRACTS_CONTACTS_1_FROM_CONTRACTS_TITLE',
  'save' => true,
  'id_name' => 'contracts_contacts_1contracts_ida',
  'link' => 'contracts_contacts_1',
  'table' => 'contracts',
  'module' => 'Contracts',
  'rname' => 'name',
);
$dictionary["Contact"]["fields"]["contracts_contacts_1contracts_ida"] = array (
  'name' => 'contracts_contacts_1contracts_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_CONTRACTS_CONTACTS_1_FROM_CONTACTS_TITLE_ID',
  'id_name' => 'contracts_contacts_1contracts_ida',
  'link' => 'contracts_contacts_1',
  'table' => 'contracts',
  'module' => 'Contracts',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
