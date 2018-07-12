<?php
$popupMeta = array (
    'moduleMain' => 'User',
    'varName' => 'USER',
    'orderBy' => 'user_name',
    'whereStatement' => ' (users.for_portal_only != "1" AND users.portal_user != "1") ',
    'whereClauses' => array (
  'user_name' => 'users.user_name',
  'title' => 'users.title',
  'department' => 'users.department',
  'phone' => 'users.phone',
  'email' => 'users.email',
  'team_name' => 'users.team_name',
  'name' => 'users.name',
  'status' => 'users.status',
),
    'searchInputs' => array (
  2 => 'user_name',
  6 => 'title',
  7 => 'department',
  8 => 'phone',
  10 => 'email',
  15 => 'team_name',
  17 => 'name',
  18 => 'status',
),
    'searchdefs' => array (
  'name' =>
  array (
    'type' => 'varchar',
    'label' => 'LBL_NAME',
    'width' => '10%',
    'name' => 'name',
  ),
  'title' =>
  array (
    'name' => 'title',
    'width' => '10%',
  ),
  'user_name' =>
  array (
    'name' => 'user_name',
    'width' => '10%',
  ),
  'department' =>
  array (
    'name' => 'department',
    'width' => '10%',
  ),
  'phone' =>
  array (
    'name' => 'phone',
    'label' => 'LBL_ANY_PHONE',
    'type' => 'name',
    'width' => '10%',
  ),
  'email' =>
  array (
    'name' => 'email',
    'label' => 'LBL_ANY_EMAIL',
    'type' => 'name',
    'width' => '10%',
  ),
  'team_name' =>
  array (
    'width' => '10%',
    'label' => 'LBL_TEAMS',
    'name' => 'team_name',
  ),
  'status' =>
  array (
    'name' => 'status',
    'width' => '10%',
  ),
),
    'listviewdefs' => array (
  'NAME' =>
  array (
    'width' => '30%',
    'label' => 'LBL_LIST_NAME',
    'link' => true,
    'related_fields' =>
    array (
      0 => 'last_name',
      1 => 'first_name',
    ),
    'orderBy' => 'last_name',
    'default' => true,
    'name' => 'name',
  ),
  'USER_NAME' =>
  array (
    'width' => '5%',
    'label' => 'LBL_USER_NAME',
    'link' => true,
    'default' => true,
    'name' => 'user_name',
  ),
  'TITLE' =>
  array (
    'width' => '15%',
    'label' => 'LBL_TITLE',
    'link' => true,
    'default' => true,
    'name' => 'title',
  ),
  'DEPARTMENT' =>
  array (
    'width' => '15%',
    'label' => 'LBL_DEPARTMENT',
    'link' => true,
    'default' => true,
    'name' => 'department',
  ),
  'EMAIL1' =>
  array (
    'width' => '30%',
    'sortable' => false,
    'label' => 'LBL_LIST_EMAIL',
    'link' => true,
    'default' => true,
    'name' => 'email1',
  ),
  'STATUS' =>
  array (
    'width' => '10%',
    'label' => 'LBL_STATUS',
    'link' => false,
    'default' => true,
    'name' => 'status',
  ),
  'IS_ADMIN' =>
  array (
    'width' => '10%',
    'label' => 'LBL_ADMIN',
    'link' => false,
    'default' => true,
    'name' => 'is_admin',
  ),
  'DATE_ENTERED' =>
  array (
    'type' => 'datetime',
    'studio' =>
    array (
      'editview' => false,
      'quickcreate' => false,
      'wirelesseditview' => false,
    ),
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => true,
    'name' => 'date_entered',
  ),
  'TEAM_NAME' =>
  array (
    'width' => '5%',
    'label' => 'LBL_LIST_TEAM',
    'default' => true,
    'name' => 'team_name',
  ),
),
);
