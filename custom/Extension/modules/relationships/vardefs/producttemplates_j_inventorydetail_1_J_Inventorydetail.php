<?php
// created: 2015-07-28 14:50:29
$dictionary["J_Inventorydetail"]["fields"]["producttemplates_j_inventorydetail_1"] = array (
  'name' => 'producttemplates_j_inventorydetail_1',
  'type' => 'link',
  'relationship' => 'producttemplates_j_inventorydetail_1',
  'source' => 'non-db',
  'module' => 'ProductTemplates',
  'bean_name' => 'ProductTemplate',
  'side' => 'right',
  'vname' => 'LBL_PRODUCTTEMPLATES_J_INVENTORYDETAIL_1_FROM_J_INVENTORYDETAIL_TITLE',
  'id_name' => 'producttemplates_j_inventorydetail_1producttemplates_ida',
  'link-type' => 'one',
);
$dictionary["J_Inventorydetail"]["fields"]["producttemplates_j_inventorydetail_1_name"] = array (
  'name' => 'producttemplates_j_inventorydetail_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_PRODUCTTEMPLATES_J_INVENTORYDETAIL_1_FROM_PRODUCTTEMPLATES_TITLE',
  'save' => true,
  'id_name' => 'producttemplates_j_inventorydetail_1producttemplates_ida',
  'link' => 'producttemplates_j_inventorydetail_1',
  'table' => 'product_templates',
  'module' => 'ProductTemplates',
  'rname' => 'name',
);
$dictionary["J_Inventorydetail"]["fields"]["producttemplates_j_inventorydetail_1producttemplates_ida"] = array (
  'name' => 'producttemplates_j_inventorydetail_1producttemplates_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_PRODUCTTEMPLATES_J_INVENTORYDETAIL_1_FROM_J_INVENTORYDETAIL_TITLE_ID',
  'id_name' => 'producttemplates_j_inventorydetail_1producttemplates_ida',
  'link' => 'producttemplates_j_inventorydetail_1',
  'table' => 'product_templates',
  'module' => 'ProductTemplates',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
