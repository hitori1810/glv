<?php
// created: 2015-10-20 14:44:48
$dictionary["Lead"]["fields"]["c_contacts_leads_1"] = array (
  'name' => 'c_contacts_leads_1',
  'type' => 'link',
  'relationship' => 'c_contacts_leads_1',
  'source' => 'non-db',
  'module' => 'C_Contacts',
  'bean_name' => 'C_Contacts',
  'side' => 'right',
  'vname' => 'LBL_C_CONTACTS_LEADS_1_FROM_LEADS_TITLE',
  'id_name' => 'c_contacts_leads_1c_contacts_ida',
  'link-type' => 'one',
);
$dictionary["Lead"]["fields"]["c_contacts_leads_1_name"] = array (
  'name' => 'c_contacts_leads_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_C_CONTACTS_LEADS_1_FROM_C_CONTACTS_TITLE',
  'save' => true,
  'id_name' => 'c_contacts_leads_1c_contacts_ida',
  'link' => 'c_contacts_leads_1',
  'table' => 'c_contacts',
  'module' => 'C_Contacts',
  'rname' => 'name',
);
$dictionary["Lead"]["fields"]["c_contacts_leads_1c_contacts_ida"] = array (
  'name' => 'c_contacts_leads_1c_contacts_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_C_CONTACTS_LEADS_1_FROM_LEADS_TITLE_ID',
  'id_name' => 'c_contacts_leads_1c_contacts_ida',
  'link' => 'c_contacts_leads_1',
  'table' => 'c_contacts',
  'module' => 'C_Contacts',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
