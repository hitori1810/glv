<?php
$popupMeta = array (
    'moduleMain' => 'Lead',
    'varName' => 'LEAD',
    'orderBy' => 'leads.date_entered DESC',
    'whereClauses' => array (
  'assigned_user_id' => 'leads.assigned_user_id',
  'email' => 'leads.email',
  'phone_mobile' => 'leads.phone_mobile',
  'status' => 'leads.status',
  'full_lead_name' => 'leads.full_lead_name',
  'team_name' => 'leads.team_name',
  'date_entered' => 'leads.date_entered',
  'created_by_name' => 'leads.created_by_name',
),
    'searchInputs' => array (
  5 => 'assigned_user_id',
  7 => 'email',
  8 => 'phone_mobile',
  9 => 'status',
  10 => 'full_lead_name',
  11 => 'team_name',
  12 => 'date_entered',
  13 => 'created_by_name',
),
    'searchdefs' => array (
  'full_lead_name' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_FULL_NAME',
    'width' => '10%',
    'name' => 'full_lead_name',
  ),
  'phone_mobile' => 
  array (
    'type' => 'phone',
    'label' => 'LBL_MOBILE_PHONE',
    'width' => '10%',
    'name' => 'phone_mobile',
  ),
  'status' => 
  array (
    'name' => 'status',
    'width' => '10%',
  ),
  'email' => 
  array (
    'name' => 'email',
    'width' => '10%',
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
    'name' => 'date_entered',
  ),
  'created_by_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CREATED',
    'id' => 'CREATED_BY',
    'width' => '10%',
    'name' => 'created_by_name',
  ),
  'assigned_user_id' => 
  array (
    'name' => 'assigned_user_id',
    'type' => 'enum',
    'label' => 'LBL_ASSIGNED_TO',
    'function' => 
    array (
      'name' => 'get_user_array',
      'params' => 
      array (
        0 => false,
      ),
    ),
    'width' => '10%',
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
    'name' => 'team_name',
  ),
),
    'listviewdefs' => array (
  'NAME' => 
  array (
    'width' => '15%',
    'label' => 'LBL_LIST_NAME',
    'link' => true,
    'default' => true,
    'related_fields' => 
    array (
      0 => 'first_name',
      1 => 'last_name',
      2 => 'salutation',
    ),
    'name' => 'name',
  ),
  'BIRTHDATE' => 
  array (
    'type' => 'date',
    'label' => 'LBL_BIRTHDATE',
    'width' => '7%',
    'default' => true,
    'name' => 'birthdate',
  ),
  'STATUS' => 
  array (
    'width' => '7%',
    'label' => 'LBL_LIST_STATUS',
    'default' => true,
    'name' => 'status',
  ),
  'LAST_PT_RESULT' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LAST_PT_RESULT',
    'default' => true,
    'name' => 'last_pt_result',
  ),
  'PHONE_MOBILE' => 
  array (
    'type' => 'phone',
    'label' => 'LBL_MOBILE_PHONE',
    'width' => '10%',
    'default' => true,
    'name' => 'phone_mobile',
  ),
  'LEAD_SOURCE' => 
  array (
    'width' => '7%',
    'label' => 'LBL_LEAD_SOURCE',
    'default' => true,
    'name' => 'lead_source',
  ),
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'studio' => 
    array (
      'portaleditview' => false,
    ),
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => true,
    'name' => 'date_entered',
  ),
  'TEAM_NAME' => 
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
);
