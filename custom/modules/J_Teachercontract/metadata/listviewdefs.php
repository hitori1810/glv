<?php
$module_name = 'J_Teachercontract';
$listViewDefs[$module_name] = 
array (
  'name' => 
  array (
    'width' => '15%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'c_teachers_j_teachercontract_1_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_C_TEACHERS_J_TEACHERCONTRACT_1_FROM_C_TEACHERS_TITLE',
    'id' => 'C_TEACHERS_J_TEACHERCONTRACT_1C_TEACHERS_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'contract_type' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_CONTRACT_TYPE',
    'width' => '10%',
  ),
  'day_off' => 
  array (
    'type' => 'multienum',
    'studio' => 'visible',
    'label' => 'LBL_DAY_OFF',
    'width' => '10%',
    'default' => true,
  ),
  'contract_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_CONTRACT_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'contract_until' => 
  array (
    'type' => 'date',
    'label' => 'LBL_CONTRACT_UNTIL',
    'width' => '10%',
    'default' => true,
  ),
  'status' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '10%',
  ),
  'assigned_user_name' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'team_name' => 
  array (
    'width' => '9%',
    'label' => 'LBL_TEAM',
    'default' => false,
  ),
  'no_contract' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_NO_CONTRACT',
    'width' => '10%',
    'default' => false,
  ),
);
