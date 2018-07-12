<?php
$module_name = 'J_GradebookConfig';
$listViewDefs[$module_name] = 
array (
  'team_name' => 
  array (
    'label' => 'LBL_TEAM',
    'link' => false,
    'id' => 'ID',
    'module' => 'J_GradebookConfig',
    'width' => '9%',
    'default' => true,
  ),
  'name' => 
  array (
    'width' => '15%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'koc_name' => 
  array (
    'type' => 'relate',
    'link' => false,
    'label' => 'LBL_KOC_NAME',
    'id' => 'KOC_ID',
    'width' => '10%',
    'default' => true,
  ),
  'type' => 
  array (
    'type' => 'enum',
    'label' => 'LBL_TYPE',
    'width' => '10%',
    'default' => true,
  ),
  'weight' => 
  array (
    'type' => 'int',
    'default' => true,
    'label' => 'LBL_WEIGHT',
    'width' => '10%',
  ),
  'minitest' => 
  array (
    'type' => 'enum',
    'label' => 'LBL_MINITEST',
    'width' => '10%',
    'default' => true,
  ),
  'created_by_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CREATED',
    'id' => 'CREATED_BY',
    'width' => '10%',
    'default' => false,
  ),
  'level' => 
  array (
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_LEVEL',
    'width' => '10%',
  ),
  'date_modified' => 
  array (
    'type' => 'datetime',
    'studio' => 
    array (
      'portaleditview' => false,
    ),
    'label' => 'LBL_DATE_MODIFIED',
    'width' => '10%',
    'default' => false,
  ),
  'date_entered' => 
  array (
    'type' => 'datetime',
    'studio' => 
    array (
      'portaleditview' => false,
    ),
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => false,
  ),
);
