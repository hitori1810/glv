<?php
$listViewDefs['Contacts'] = 
array (
  'contact_id' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_CONTACT_ID',
    'width' => '7%',
    'default' => true,
  ),
  'name' => 
  array (
    'width' => '17%',
    'label' => 'LBL_LIST_NAME',
    'link' => true,
    'contextMenu' => 
    array (
      'objectType' => 'sugarPerson',
      'metaData' => 
      array (
        'contact_id' => '{$ID}',
        'module' => 'Contacts',
        'return_action' => 'ListView',
        'contact_name' => '{$FULL_NAME}',
        'parent_id' => '{$ACCOUNT_ID}',
        'parent_name' => '{$ACCOUNT_NAME}',
        'return_module' => 'Contacts',
        'parent_type' => 'Account',
        'notes_parent_type' => 'Account',
      ),
    ),
    'orderBy' => 'name',
    'default' => true,
    'related_fields' => 
    array (
      0 => 'first_name',
      1 => 'last_name',
      2 => 'salutation',
      3 => 'account_name',
      4 => 'account_id',
    ),
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
    'width' => '7%',
    'default' => true,
  ),
  'phone_mobile' => 
  array (
    'width' => '7%',
    'label' => 'LBL_MOBILE_PHONE',
    'default' => true,
  ),
  'contact_status' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_CONTACT_STATUS',
    'width' => '7%',
  ),
);
