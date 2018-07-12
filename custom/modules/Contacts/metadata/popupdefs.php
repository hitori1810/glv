<?php
$popupMeta = array (
    'moduleMain' => 'Contact',
    'varName' => 'CONTACT',
    'orderBy' => 'contacts.date_entered DESC',
    'whereClauses' => array (
  'contact_id' => 'contacts.contact_id',
  'phone_mobile' => 'contacts.phone_mobile',
  'lead_source' => 'contacts.lead_source',
  'team_name' => 'contacts.team_name',
  'assistant' => 'contacts.assistant',
  'date_entered' => 'contacts.date_entered',
  'campaign_name' => 'contacts.campaign_name',
  'created_by_name' => 'contacts.created_by_name',
  'email' => 'contacts.email',
  'assigned_user_id' => 'contacts.assigned_user_id',
),
    'searchInputs' => array (
  4 => 'contact_id',
  6 => 'phone_mobile',
  7 => 'lead_source',
  13 => 'team_name',
  14 => 'assistant',
  15 => 'date_entered',
  17 => 'campaign_name',
  18 => 'created_by_name',
  19 => 'email',
  20 => 'assigned_user_id',
),
    'searchdefs' => array (
  'contact_id' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_CONTACT_ID',
    'width' => '10%',
    'name' => 'contact_id',
  ),
  'assistant' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_FULL_NAME',
    'width' => '10%',
    'name' => 'assistant',
  ),
  'phone_mobile' => 
  array (
    'type' => 'phone',
    'label' => 'LBL_MOBILE_PHONE',
    'width' => '10%',
    'name' => 'phone_mobile',
  ),
  'lead_source' => 
  array (
    'name' => 'lead_source',
    'width' => '10%',
  ),
  'email' => 
  array (
    'name' => 'email',
    'width' => '10%',
  ),
  'campaign_name' => 
  array (
    'name' => 'campaign_name',
    'displayParams' => 
    array (
      'hideButtons' => 'true',
      'size' => 30,
      'class' => 'sqsEnabled sqsNoAutofill',
    ),
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
  'CONTACT_ID' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_CONTACT_ID',
    'width' => '10%',
    'default' => true,
    'name' => 'contact_id',
  ),
  'NAME' => 
  array (
    'width' => '15%',
    'label' => 'LBL_LIST_NAME',
    'link' => true,
    'default' => true,
    'related_fields' => 
    array (
      0 => 'last_name',
      1 => 'first_name',
    ),
    'name' => 'name',
  ),
  'CONTACT_STATUS' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_CONTACT_STATUS',
    'width' => '10%',
    'name' => 'contact_status',
  ),
  'BIRTHDATE' => 
  array (
    'type' => 'date',
    'label' => 'LBL_BIRTHDATE',
    'width' => '7%',
    'default' => true,
    'name' => 'birthdate',
  ),
  'PHONE_MOBILE' => 
  array (
    'type' => 'phone',
    'label' => 'LBL_MOBILE_PHONE',
    'width' => '10%',
    'default' => true,
    'name' => 'phone_mobile',
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'link' => true,
    'type' => 'relate',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'id' => 'ASSIGNED_USER_ID',
    'width' => '10%',
    'default' => true,
    'name' => 'assigned_user_name',
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
