<?php 
 //WARNING: The contents of this file are auto-generated


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


// created: 2015-09-05 15:56:20
$dictionary["J_Payment"]["fields"]["j_coursefee_j_payment_1"] = array (
  'name' => 'j_coursefee_j_payment_1',
  'type' => 'link',
  'relationship' => 'j_coursefee_j_payment_1',
  'source' => 'non-db',
  'module' => 'J_Coursefee',
  'bean_name' => 'J_Coursefee',
  'side' => 'right',
  'vname' => 'LBL_J_COURSEFEE_J_PAYMENT_1_FROM_J_PAYMENT_TITLE',
  'id_name' => 'j_coursefee_j_payment_1j_coursefee_ida',
  'link-type' => 'one',
);
$dictionary["J_Payment"]["fields"]["j_coursefee_j_payment_1_name"] = array (
  'name' => 'j_coursefee_j_payment_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_COURSEFEE_J_PAYMENT_1_FROM_J_COURSEFEE_TITLE',
  'save' => true,
  'id_name' => 'j_coursefee_j_payment_1j_coursefee_ida',
  'link' => 'j_coursefee_j_payment_1',
  'table' => 'j_coursefee',
  'module' => 'J_Coursefee',
  'rname' => 'name',
);
$dictionary["J_Payment"]["fields"]["j_coursefee_j_payment_1j_coursefee_ida"] = array (
  'name' => 'j_coursefee_j_payment_1j_coursefee_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_COURSEFEE_J_PAYMENT_1_FROM_J_PAYMENT_TITLE_ID',
  'id_name' => 'j_coursefee_j_payment_1j_coursefee_ida',
  'link' => 'j_coursefee_j_payment_1',
  'table' => 'j_coursefee',
  'module' => 'J_Coursefee',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);


// created: 2015-09-04 09:23:24
$dictionary["J_Payment"]["fields"]["j_partnership_j_payment_1"] = array (
  'name' => 'j_partnership_j_payment_1',
  'type' => 'link',
  'relationship' => 'j_partnership_j_payment_1',
  'source' => 'non-db',
  'module' => 'J_Partnership',
  'bean_name' => 'J_Partnership',
  'side' => 'right',
  'vname' => 'LBL_J_PARTNERSHIP_J_PAYMENT_1_FROM_J_PAYMENT_TITLE',
  'id_name' => 'j_partnership_j_payment_1j_partnership_ida',
  'link-type' => 'one',
);
$dictionary["J_Payment"]["fields"]["j_partnership_j_payment_1_name"] = array (
  'name' => 'j_partnership_j_payment_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_PARTNERSHIP_J_PAYMENT_1_FROM_J_PARTNERSHIP_TITLE',
  'save' => true,
  'id_name' => 'j_partnership_j_payment_1j_partnership_ida',
  'link' => 'j_partnership_j_payment_1',
  'table' => 'j_partnership',
  'module' => 'J_Partnership',
  'rname' => 'name',
);
$dictionary["J_Payment"]["fields"]["j_partnership_j_payment_1j_partnership_ida"] = array (
  'name' => 'j_partnership_j_payment_1j_partnership_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_PARTNERSHIP_J_PAYMENT_1_FROM_J_PAYMENT_TITLE_ID',
  'id_name' => 'j_partnership_j_payment_1j_partnership_ida',
  'link' => 'j_partnership_j_payment_1',
  'table' => 'j_partnership',
  'module' => 'J_Partnership',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);


// created: 2015-08-06 10:50:14
$dictionary["J_Payment"]["fields"]["j_payment_j_discount_1"] = array (
  'name' => 'j_payment_j_discount_1',
  'type' => 'link',
  'relationship' => 'j_payment_j_discount_1',
  'source' => 'non-db',
  'module' => 'J_Discount',
  'bean_name' => 'J_Discount',
  'vname' => 'LBL_J_PAYMENT_J_DISCOUNT_1_FROM_J_DISCOUNT_TITLE',
  'id_name' => 'j_payment_j_discount_1j_discount_idb',
);


// created: 2015-07-28 09:30:06
$dictionary["J_Payment"]["fields"]["j_payment_j_inventory_1"] = array (
  'name' => 'j_payment_j_inventory_1',
  'type' => 'link',
  'relationship' => 'j_payment_j_inventory_1',
  'source' => 'non-db',
  'module' => 'J_Inventory',
  'bean_name' => 'J_Inventory',
  'vname' => 'LBL_J_PAYMENT_J_INVENTORY_1_FROM_J_INVENTORY_TITLE',
  'id_name' => 'j_payment_j_inventory_1j_inventory_idb',
);
$dictionary["J_Payment"]["fields"]["j_payment_j_inventory_1_name"] = array (
  'name' => 'j_payment_j_inventory_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_PAYMENT_J_INVENTORY_1_FROM_J_INVENTORY_TITLE',
  'save' => true,
  'id_name' => 'j_payment_j_inventory_1j_inventory_idb',
  'link' => 'j_payment_j_inventory_1',
  'table' => 'j_inventory',
  'module' => 'J_Inventory',
  'rname' => 'name',
);
$dictionary["J_Payment"]["fields"]["j_payment_j_inventory_1j_inventory_idb"] = array (
  'name' => 'j_payment_j_inventory_1j_inventory_idb',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_PAYMENT_J_INVENTORY_1_FROM_J_INVENTORY_TITLE_ID',
  'id_name' => 'j_payment_j_inventory_1j_inventory_idb',
  'link' => 'j_payment_j_inventory_1',
  'table' => 'j_inventory',
  'module' => 'J_Inventory',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);


// created: 2016-02-01 16:10:28
$dictionary["J_Payment"]["fields"]["j_payment_j_payment_1"] = array (
  'name' => 'j_payment_j_payment_1',
  'type' => 'link',
  'relationship' => 'j_payment_j_payment_1',
  'source' => 'non-db',
  'module' => 'J_Payment',
  'bean_name' => 'J_Payment',
  'vname' => 'LBL_J_PAYMENT_J_PAYMENT_1_FROM_J_PAYMENT_R_TITLE',
  'id_name' => 'j_payment_j_payment_1j_payment_idb',
  'link-type' => 'many',
  'side' => 'left',
);
$dictionary["J_Payment"]["fields"]["j_payment_j_payment_1_right"] = array (
  'name' => 'j_payment_j_payment_1_right',
  'type' => 'link',
  'relationship' => 'j_payment_j_payment_1',
  'source' => 'non-db',
  'module' => 'J_Payment',
  'bean_name' => 'J_Payment',
  'side' => 'right',
  'vname' => 'LBL_J_PAYMENT_J_PAYMENT_1_FROM_J_PAYMENT_L_TITLE',
  'id_name' => 'j_payment_j_payment_1j_payment_ida',
  'link-type' => 'one',
);
$dictionary["J_Payment"]["fields"]["j_payment_j_payment_1_name"] = array (
  'name' => 'j_payment_j_payment_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_PAYMENT_J_PAYMENT_1_FROM_J_PAYMENT_L_TITLE',
  'save' => true,
  'id_name' => 'j_payment_j_payment_1j_payment_ida',
  'link' => 'j_payment_j_payment_1_right',
  'table' => 'j_payment',
  'module' => 'J_Payment',
  'rname' => 'name',
);
$dictionary["J_Payment"]["fields"]["j_payment_j_payment_1j_payment_ida"] = array (
  'name' => 'j_payment_j_payment_1j_payment_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_PAYMENT_J_PAYMENT_1_FROM_J_PAYMENT_R_TITLE_ID',
  'id_name' => 'j_payment_j_payment_1j_payment_ida',
  'link' => 'j_payment_j_payment_1_right',
  'table' => 'j_payment',
  'module' => 'J_Payment',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);


 // created: 2016-06-03 07:20:57
$dictionary['J_Payment']['fields']['name']['importable']='true';
$dictionary['J_Payment']['fields']['name']['unified_search']=false;
$dictionary['J_Payment']['fields']['name']['calculated']=false;

 
?>