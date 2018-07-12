<?php
$module_name = 'J_GradebookConfig';
$searchdefs[$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'koc_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_KOC_NAME',
        'width' => '10%',
        'default' => true,
        'id' => 'KOC_ID',
        'name' => 'koc_name',
      ),
      'minitest' => 
      array (
        'type' => 'enum',
        'label' => 'LBL_MINITEST',
        'width' => '10%',
        'default' => true,
        'name' => 'minitest',
      ),
      'type' => 
      array (
        'type' => 'enum',
        'label' => 'LBL_TYPE',
        'width' => '10%',
        'default' => true,
        'name' => 'type',
      ),
    ),
    'advanced_search' => 
    array (
      'name' => 
      array (
        'type' => 'name',
        'link' => true,
        'default' => true,
        'width' => '10%',
        'label' => 'LBL_NAME',
        'name' => 'name',
      ),
      'koc_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_KOC_NAME',
        'id' => 'KOC_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'koc_name',
      ),
      'minitest' => 
      array (
        'type' => 'enum',
        'label' => 'LBL_MINITEST',
        'width' => '10%',
        'default' => true,
        'name' => 'minitest',
      ),
      'type' => 
      array (
        'type' => 'enum',
        'label' => 'LBL_TYPE',
        'width' => '10%',
        'default' => true,
        'name' => 'type',
      ),
      'weight' => 
      array (
        'type' => 'int',
        'default' => true,
        'label' => 'LBL_WEIGHT',
        'width' => '10%',
        'name' => 'weight',
      ),
      'team_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'studio' => 
        array (
          'portallistview' => false,
          'portaldetailview' => false,
          'portaleditview' => false,
        ),
        'label' => 'LBL_TEAMS',
        'id' => 'TEAM_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'team_name',
      ),
    ),
  ),
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'maxColumnsBasic' => '4',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
);
