<?php
// created: 2014-09-16 16:12:19
$dictionary["Contact"]["fields"]["c_memberships_contacts_2"] = array (
  'name' => 'c_memberships_contacts_2',
  'type' => 'link',
  'relationship' => 'c_memberships_contacts_2',
  'source' => 'non-db',
  'module' => 'C_Memberships',
  'bean_name' => 'C_Memberships',
  'vname' => 'LBL_C_MEMBERSHIPS_CONTACTS_2_FROM_C_MEMBERSHIPS_TITLE',
  'id_name' => 'c_memberships_contacts_2c_memberships_ida',
);
$dictionary["Contact"]["fields"]["c_memberships_contacts_2_name"] = array (
  'name' => 'c_memberships_contacts_2_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_C_MEMBERSHIPS_CONTACTS_2_FROM_C_MEMBERSHIPS_TITLE',
  'save' => true,
  'id_name' => 'c_memberships_contacts_2c_memberships_ida',
  'link' => 'c_memberships_contacts_2',
  'table' => 'c_memberships',
  'module' => 'C_Memberships',
  'rname' => 'name',
);
$dictionary["Contact"]["fields"]["c_memberships_contacts_2c_memberships_ida"] = array (
  'name' => 'c_memberships_contacts_2c_memberships_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_C_MEMBERSHIPS_CONTACTS_2_FROM_C_MEMBERSHIPS_TITLE_ID',
  'id_name' => 'c_memberships_contacts_2c_memberships_ida',
  'link' => 'c_memberships_contacts_2',
  'table' => 'c_memberships',
  'module' => 'C_Memberships',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
