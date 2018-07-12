<?php
// created: 2015-08-05 12:09:35
$dictionary["Lead"]["fields"]["j_school_leads_1"] = array (
  'name' => 'j_school_leads_1',
  'type' => 'link',
  'relationship' => 'j_school_leads_1',
  'source' => 'non-db',
  'module' => 'J_School',
  'bean_name' => 'J_School',
  'side' => 'right',
  'vname' => 'LBL_J_SCHOOL_LEADS_1_FROM_LEADS_TITLE',
  'id_name' => 'j_school_leads_1j_school_ida',
  'link-type' => 'one',
);
$dictionary["Lead"]["fields"]["j_school_leads_1_name"] = array (
  'name' => 'j_school_leads_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_SCHOOL_LEADS_1_FROM_J_SCHOOL_TITLE',
  'save' => true,
  'id_name' => 'j_school_leads_1j_school_ida',
  'link' => 'j_school_leads_1',
  'table' => 'j_school',
  'module' => 'J_School',
  'rname' => 'name',
);
$dictionary["Lead"]["fields"]["j_school_leads_1j_school_ida"] = array (
  'name' => 'j_school_leads_1j_school_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_SCHOOL_LEADS_1_FROM_LEADS_TITLE_ID',
  'id_name' => 'j_school_leads_1j_school_ida',
  'link' => 'j_school_leads_1',
  'table' => 'j_school',
  'module' => 'J_School',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
