<?php
// created: 2015-07-14 16:44:03
$dictionary["J_Payment"]["fields"]["contacts_j_payment_1"] = array (
  'name' => 'contacts_j_payment_1',
  'type' => 'link',
  'relationship' => 'contacts_j_payment_1',
  'source' => 'non-db',
  'module' => 'Contacts',
  'bean_name' => 'Contact',
  'side' => 'right',
  'vname' => 'LBL_CONTACTS_J_PAYMENT_1_FROM_J_PAYMENT_TITLE',
  'id_name' => 'contacts_j_payment_1contacts_ida',
  'link-type' => 'one',
);
$dictionary["J_Payment"]["fields"]["contacts_j_payment_1_name"] = array (
  'name' => 'contacts_j_payment_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CONTACTS_J_PAYMENT_1_FROM_CONTACTS_TITLE',
  'save' => true,
  'id_name' => 'contacts_j_payment_1contacts_ida',
  'link' => 'contacts_j_payment_1',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["J_Payment"]["fields"]["contacts_j_payment_1contacts_ida"] = array (
  'name' => 'contacts_j_payment_1contacts_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_CONTACTS_J_PAYMENT_1_FROM_J_PAYMENT_TITLE_ID',
  'id_name' => 'contacts_j_payment_1contacts_ida',
  'link' => 'contacts_j_payment_1',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
