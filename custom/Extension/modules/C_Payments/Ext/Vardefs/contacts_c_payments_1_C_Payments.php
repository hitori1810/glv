<?php
// created: 2014-04-12 00:27:35
$dictionary["C_Payments"]["fields"]["contacts_c_payments_1"] = array (
  'name' => 'contacts_c_payments_1',
  'type' => 'link',
  'relationship' => 'contacts_c_payments_1',
  'source' => 'non-db',
  'module' => 'Contacts',
  'bean_name' => 'Contact',
  'side' => 'right',
  'vname' => 'LBL_STUDENT_NAME',
  'id_name' => 'contacts_c_payments_1contacts_ida',
  'link-type' => 'one',
);
$dictionary["C_Payments"]["fields"]["contacts_c_payments_1_name"] = array (
  'name' => 'contacts_c_payments_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_STUDENT_NAME',
  'save' => true,
  'id_name' => 'contacts_c_payments_1contacts_ida',
  'link' => 'contacts_c_payments_1',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["C_Payments"]["fields"]["contacts_c_payments_1contacts_ida"] = array (
  'name' => 'contacts_c_payments_1contacts_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_STUDENT_NAME',
  'id_name' => 'contacts_c_payments_1contacts_ida',
  'link' => 'contacts_c_payments_1',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
