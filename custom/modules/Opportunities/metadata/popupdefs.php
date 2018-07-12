<?php
$popupMeta = array (
    'moduleMain' => 'Opportunity',
    'varName' => 'OPPORTUNITY',
    'orderBy' => 'name',
    'whereClauses' => array (
  'name' => 'opportunities.name',
  'oder_id' => 'opportunities.oder_id',
  'c_packages_opportunities_1_name' => 'opportunities.c_packages_opportunities_1_name',
  'contact_name' => 'opportunities.contact_name',
),
    'searchInputs' => array (
  0 => 'name',
  2 => 'oder_id',
  7 => 'c_packages_opportunities_1_name',
  12 => 'contact_name',
),
    'searchdefs' => array (
  'oder_id' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_ORDER_ID',
    'width' => '10%',
    'name' => 'oder_id',
  ),
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'contact_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CONTACT_NAME',
    'id' => 'CONTACT_ID',
    'width' => '10%',
    'name' => 'contact_name',
  ),
  'c_packages_opportunities_1_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_C_PACKAGES_OPPORTUNITIES_1_FROM_C_PACKAGES_TITLE',
    'id' => 'C_PACKAGES_OPPORTUNITIES_1C_PACKAGES_IDA',
    'width' => '10%',
    'name' => 'c_packages_opportunities_1_name',
  ),
),
    'listviewdefs' => array (
  'ODER_ID' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_ORDER_ID',
    'width' => '10%',
    'default' => true,
    'name' => 'oder_id',
  ),
  'NAME' => 
  array (
    'width' => '30%',
    'label' => 'LBL_LIST_OPPORTUNITY_NAME',
    'link' => true,
    'default' => true,
    'name' => 'name',
  ),
  'CONTACT_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CONTACT_NAME',
    'id' => 'CONTACT_ID',
    'width' => '10%',
    'default' => true,
  ),
  'SALES_STAGE' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_SALES_STAGE',
    'default' => true,
    'name' => 'sales_stage',
  ),
),
);
