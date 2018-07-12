<?php
// created: 2015-08-18 08:27:49
$dictionary["J_Discount"]["fields"]["j_partnership_j_discount_1"] = array (
  'name' => 'j_partnership_j_discount_1',
  'type' => 'link',
  'relationship' => 'j_partnership_j_discount_1',
  'source' => 'non-db',
  'module' => 'J_Partnership',
  'bean_name' => 'J_Partnership',
  'side' => 'right',
  'vname' => 'LBL_J_PARTNERSHIP_J_DISCOUNT_1_FROM_J_DISCOUNT_TITLE',
  'id_name' => 'j_partnership_j_discount_1j_partnership_ida',
  'link-type' => 'one',
);
$dictionary["J_Discount"]["fields"]["j_partnership_j_discount_1_name"] = array (
  'name' => 'j_partnership_j_discount_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_PARTNERSHIP_J_DISCOUNT_1_FROM_J_PARTNERSHIP_TITLE',
  'save' => true,
  'id_name' => 'j_partnership_j_discount_1j_partnership_ida',
  'link' => 'j_partnership_j_discount_1',
  'table' => 'j_partnership',
  'module' => 'J_Partnership',
  'rname' => 'name',
);
$dictionary["J_Discount"]["fields"]["j_partnership_j_discount_1j_partnership_ida"] = array (
  'name' => 'j_partnership_j_discount_1j_partnership_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_PARTNERSHIP_J_DISCOUNT_1_FROM_J_DISCOUNT_TITLE_ID',
  'id_name' => 'j_partnership_j_discount_1j_partnership_ida',
  'link' => 'j_partnership_j_discount_1',
  'table' => 'j_partnership',
  'module' => 'J_Partnership',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
