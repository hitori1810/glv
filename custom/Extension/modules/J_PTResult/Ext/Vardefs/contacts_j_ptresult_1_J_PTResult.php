<?php
// created: 2015-09-07 09:33:54
$dictionary["J_PTResult"]["fields"]["contacts_j_ptresult_1"] = array (
  'name' => 'contacts_j_ptresult_1',
  'type' => 'link',
  'relationship' => 'contacts_j_ptresult_1',
  'source' => 'non-db',
  'module' => 'Contacts',
  'bean_name' => 'Contact',
  'side' => 'right',
  'vname' => 'LBL_CONTACTS_J_PTRESULT_1_FROM_J_PTRESULT_TITLE',
  'id_name' => 'contacts_j_ptresult_1contacts_ida',
  'link-type' => 'one',
);
$dictionary["J_PTResult"]["fields"]["contacts_j_ptresult_1_name"] = array (
  'name' => 'contacts_j_ptresult_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CONTACTS_J_PTRESULT_1_FROM_CONTACTS_TITLE',
  'save' => true,
  'id_name' => 'contacts_j_ptresult_1contacts_ida',
  'link' => 'contacts_j_ptresult_1',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["J_PTResult"]["fields"]["contacts_j_ptresult_1contacts_ida"] = array (
  'name' => 'contacts_j_ptresult_1contacts_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_CONTACTS_J_PTRESULT_1_FROM_J_PTRESULT_TITLE_ID',
  'id_name' => 'contacts_j_ptresult_1contacts_ida',
  'link' => 'contacts_j_ptresult_1',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
