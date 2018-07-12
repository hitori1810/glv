<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2014-09-16 16:12:19
$dictionary["C_Memberships"]["fields"]["c_memberships_contacts_2"] = array (
  'name' => 'c_memberships_contacts_2',
  'type' => 'link',
  'relationship' => 'c_memberships_contacts_2',
  'source' => 'non-db',
  'module' => 'Contacts',
  'bean_name' => 'Contact',
  'vname' => 'LBL_C_MEMBERSHIPS_CONTACTS_2_FROM_CONTACTS_TITLE',
  'id_name' => 'c_memberships_contacts_2contacts_idb',
);
$dictionary["C_Memberships"]["fields"]["c_memberships_contacts_2_name"] = array (
  'name' => 'c_memberships_contacts_2_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_C_MEMBERSHIPS_CONTACTS_2_FROM_CONTACTS_TITLE',
  'save' => true,
  'id_name' => 'c_memberships_contacts_2contacts_idb',
  'link' => 'c_memberships_contacts_2',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["C_Memberships"]["fields"]["c_memberships_contacts_2contacts_idb"] = array (
  'name' => 'c_memberships_contacts_2contacts_idb',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_C_MEMBERSHIPS_CONTACTS_2_FROM_CONTACTS_TITLE_ID',
  'id_name' => 'c_memberships_contacts_2contacts_idb',
  'link' => 'c_memberships_contacts_2',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);


// created: 2014-10-01 21:30:36
$dictionary["C_Memberships"]["fields"]["c_memberships_leads_1"] = array (
  'name' => 'c_memberships_leads_1',
  'type' => 'link',
  'relationship' => 'c_memberships_leads_1',
  'source' => 'non-db',
  'module' => 'Leads',
  'bean_name' => 'Lead',
  'vname' => 'LBL_C_MEMBERSHIPS_LEADS_1_FROM_LEADS_TITLE',
  'id_name' => 'c_memberships_leads_1leads_idb',
);
$dictionary["C_Memberships"]["fields"]["c_memberships_leads_1_name"] = array (
  'name' => 'c_memberships_leads_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_C_MEMBERSHIPS_LEADS_1_FROM_LEADS_TITLE',
  'save' => true,
  'id_name' => 'c_memberships_leads_1leads_idb',
  'link' => 'c_memberships_leads_1',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["C_Memberships"]["fields"]["c_memberships_leads_1leads_idb"] = array (
  'name' => 'c_memberships_leads_1leads_idb',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_C_MEMBERSHIPS_LEADS_1_FROM_LEADS_TITLE_ID',
  'id_name' => 'c_memberships_leads_1leads_idb',
  'link' => 'c_memberships_leads_1',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);


  $dictionary["C_Memberships"]["fields"]["type"] = array (
        'name' => 'type',
        'vname' => 'LBL_TYPE',
        'type' => 'enum',
        'default' => '',
        'reportable' => true,
        'len' => 20,
        'size' => '20',
        'options' => 'membership_level_list',
    );


?>