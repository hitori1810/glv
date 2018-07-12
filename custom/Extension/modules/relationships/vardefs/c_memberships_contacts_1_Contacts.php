<?php
// created: 2014-09-16 16:03:36
$dictionary["Contact"]["fields"]["c_memberships_contacts_1"] = array (
  'name' => 'c_memberships_contacts_1',
  'type' => 'link',
  'relationship' => 'c_memberships_contacts_1',
  'source' => 'non-db',
  'module' => 'C_Memberships',
  'bean_name' => 'C_Memberships',
  'side' => 'right',
  'vname' => 'LBL_C_MEMBERSHIPS_CONTACTS_1_FROM_CONTACTS_TITLE',
  'id_name' => 'c_memberships_contacts_1c_memberships_ida',
  'link-type' => 'one',
);
$dictionary["Contact"]["fields"]["c_memberships_contacts_1_name"] = array (
  'name' => 'c_memberships_contacts_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_C_MEMBERSHIPS_CONTACTS_1_FROM_C_MEMBERSHIPS_TITLE',
  'save' => true,
  'id_name' => 'c_memberships_contacts_1c_memberships_ida',
  'link' => 'c_memberships_contacts_1',
  'table' => 'c_memberships',
  'module' => 'C_Memberships',
  'rname' => 'name',
);
$dictionary["Contact"]["fields"]["c_memberships_contacts_1c_memberships_ida"] = array (
  'name' => 'c_memberships_contacts_1c_memberships_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_C_MEMBERSHIPS_CONTACTS_1_FROM_CONTACTS_TITLE_ID',
  'id_name' => 'c_memberships_contacts_1c_memberships_ida',
  'link' => 'c_memberships_contacts_1',
  'table' => 'c_memberships',
  'module' => 'C_Memberships',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
