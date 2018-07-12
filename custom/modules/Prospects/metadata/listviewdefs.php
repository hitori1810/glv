<?php
$listViewDefs['Prospects'] =
array (
  'full_name' =>
  array (
    'width' => '17%',
    'label' => 'LBL_LIST_NAME',
    'link' => true,
    'related_fields' =>
    array (
      0 => 'first_name',
      1 => 'last_name',
    ),
    'orderBy' => 'last_name',
    'default' => true,
  ),
  'birthdate' =>
  array (
    'type' => 'date',
    'label' => 'LBL_BIRTHDATE',
    'width' => '7%',
    'default' => true,
  ),
  'guardian_name' =>
  array (
    'type' => 'varchar',
    'label' => 'LBL_GUARDIAN_NAME',
    'width' => '10%',
    'default' => true,
  ),
  'phone_mobile' =>
  array (
    'type' => 'phone',
    'label' => 'LBL_MOBILE_PHONE',
    'width' => '10%',
    'default' => true,
  ),
  'email1' =>
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_EMAIL_ADDRESS',
    'sortable' => false,
    'link' => false,
    'default' => true,
  ),
  'status' =>
  array (
    'type' => 'enum',
    'default' => true,
    'label' => 'LBL_STATUS',
    'width' => '7%',
  ),
  'potential' =>
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_POTENTIAL',
    'width' => '7%',
  ),
  'lead_source' =>
  array (
    'type' => 'enum',
    'label' => 'LBL_LEAD_SOURCE',
    'width' => '7%',
    'default' => true,
  ),
  'campaign_name' =>
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CAMPAIGN',
    'id' => 'CAMPAIGN_ID',
    'width' => '10%',
    'default' => true,
  ),
  'assigned_user_name' =>
  array (
    'link' => true,
    'type' => 'relate',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'id' => 'ASSIGNED_USER_ID',
    'width' => '10%',
    'default' => true,
  ),
  'date_entered' =>
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => true,
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
  ),
  'gender' =>
  array (
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_GENDER',
    'width' => '10%',
  ),
  'converted' =>
  array (
    'type' => 'bool',
    'default' => false,
    'label' => 'LBL_CONVERTED',
    'width' => '10%',
  ),
);
if (($GLOBALS['current_user']->team_type == 'Junior')){
    unset($listViewDefs['Prospects']['email1']);
}

