<?php
// created: 2014-10-01 21:30:36
$dictionary["Lead"]["fields"]["c_memberships_leads_1"] = array (
  'name' => 'c_memberships_leads_1',
  'type' => 'link',
  'relationship' => 'c_memberships_leads_1',
  'source' => 'non-db',
  'module' => 'C_Memberships',
  'bean_name' => 'C_Memberships',
  'vname' => 'LBL_C_MEMBERSHIPS_LEADS_1_FROM_C_MEMBERSHIPS_TITLE',
  'id_name' => 'c_memberships_leads_1c_memberships_ida',
);
$dictionary["Lead"]["fields"]["c_memberships_leads_1_name"] = array (
  'name' => 'c_memberships_leads_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_C_MEMBERSHIPS_LEADS_1_FROM_C_MEMBERSHIPS_TITLE',
  'save' => true,
  'id_name' => 'c_memberships_leads_1c_memberships_ida',
  'link' => 'c_memberships_leads_1',
  'table' => 'c_memberships',
  'module' => 'C_Memberships',
  'rname' => 'name',
);
$dictionary["Lead"]["fields"]["c_memberships_leads_1c_memberships_ida"] = array (
  'name' => 'c_memberships_leads_1c_memberships_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_C_MEMBERSHIPS_LEADS_1_FROM_C_MEMBERSHIPS_TITLE_ID',
  'id_name' => 'c_memberships_leads_1c_memberships_ida',
  'link' => 'c_memberships_leads_1',
  'table' => 'c_memberships',
  'module' => 'C_Memberships',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
