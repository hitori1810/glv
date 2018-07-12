<?php
// created: 2015-08-05 12:10:56
$dictionary["Contact"]["fields"]["j_school_contacts_1"] = array (
  'name' => 'j_school_contacts_1',
  'type' => 'link',
  'relationship' => 'j_school_contacts_1',
  'source' => 'non-db',
  'module' => 'J_School',
  'bean_name' => 'J_School',
  'side' => 'right',
  'vname' => 'LBL_J_SCHOOL_CONTACTS_1_FROM_CONTACTS_TITLE',
  'id_name' => 'j_school_contacts_1j_school_ida',
  'link-type' => 'one',
);
$dictionary["Contact"]["fields"]["j_school_contacts_1_name"] = array (
  'name' => 'j_school_contacts_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_SCHOOL_CONTACTS_1_FROM_J_SCHOOL_TITLE',
  'save' => true,
  'id_name' => 'j_school_contacts_1j_school_ida',
  'link' => 'j_school_contacts_1',
  'table' => 'j_school',
  'module' => 'J_School',
  'rname' => 'name',
);
$dictionary["Contact"]["fields"]["j_school_contacts_1j_school_ida"] = array (
  'name' => 'j_school_contacts_1j_school_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_SCHOOL_CONTACTS_1_FROM_CONTACTS_TITLE_ID',
  'id_name' => 'j_school_contacts_1j_school_ida',
  'link' => 'j_school_contacts_1',
  'table' => 'j_school',
  'module' => 'J_School',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
