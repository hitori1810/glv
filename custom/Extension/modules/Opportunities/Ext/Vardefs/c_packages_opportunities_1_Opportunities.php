<?php
// created: 2014-04-12 01:02:18
$dictionary["Opportunity"]["fields"]["c_packages_opportunities_1"] = array (
  'name' => 'c_packages_opportunities_1',
  'type' => 'link',
  'relationship' => 'c_packages_opportunities_1',
  'source' => 'non-db',
  'module' => 'C_Packages',
  'bean_name' => 'C_Packages',
  'side' => 'right',
  'vname' => 'LBL_C_PACKAGES_OPPORTUNITIES_1_FROM_OPPORTUNITIES_TITLE',
  'id_name' => 'c_packages_opportunities_1c_packages_ida',
  'link-type' => 'one',
);
$dictionary["Opportunity"]["fields"]["c_packages_opportunities_1_name"] = array (
  'name' => 'c_packages_opportunities_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_C_PACKAGES_OPPORTUNITIES_1_FROM_C_PACKAGES_TITLE',
  'save' => true,
  'id_name' => 'c_packages_opportunities_1c_packages_ida',
  'link' => 'c_packages_opportunities_1',
  'table' => 'c_packages',
  'module' => 'C_Packages',
  'rname' => 'name',
);
$dictionary["Opportunity"]["fields"]["c_packages_opportunities_1c_packages_ida"] = array (
  'name' => 'c_packages_opportunities_1c_packages_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_C_PACKAGES_OPPORTUNITIES_1_FROM_OPPORTUNITIES_TITLE_ID',
  'id_name' => 'c_packages_opportunities_1c_packages_ida',
  'link' => 'c_packages_opportunities_1',
  'table' => 'c_packages',
  'module' => 'C_Packages',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
