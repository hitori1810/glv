<?php
$listViewDefs['Leads'] =
array (
    'picture' =>
    array (
        'width' => '10%',
        'label' => 'LBL_PICTURE_FILE',
        'default' => true,
    ),
    'name' =>
    array (
        'width' => '15%',
        'label' => 'LBL_LIST_NAME',
        'link' => true,
        'orderBy' => 'name',
        'default' => true,
        'related_fields' =>
        array (
            0 => 'first_name',
            1 => 'last_name',
            2 => 'salutation',
        ),
    ),
    'birthdate' =>
    array (
        'type' => 'date',
        'label' => 'LBL_BIRTHDATE',
        'width' => '7%',
        'default' => true,
    ),
    'phone_mobile' =>
    array (
        'width' => '7%',
        'label' => 'LBL_MOBILE_PHONE',
        'default' => true,
    ),
    'description' =>
    array (
        'type' => 'text',
        'label' => 'LBL_DESCRIPTION',
        'sortable' => false,
        'width' => '14%',
        'default' => true,
    ),
    'status' =>
    array (
        'width' => '7%',
        'label' => 'LBL_LIST_STATUS',
        'default' => true,
    ),
    'last_pt_result' =>
    array (
        'width' => '10%',
        'label' => 'LBL_LAST_PT_RESULT',
        'default' => true,
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
        'width' => '7%',
        'label' => 'LBL_LEAD_SOURCE',
        'default' => true,
    ),        
    'assigned_user_name' =>
    array (
        'width' => '8%',
        'label' => 'LBL_ASSIGNED_TO_NAME',
        'module' => 'Employees',
        'id' => 'ASSIGNED_USER_ID',
        'default' => true,
    ),
    'date_entered' =>
    array (
        'width' => '8%',
        'label' => 'LBL_DATE_ENTERED',
        'default' => true,
        'orderBy' => 'date_entered',
        'sortOrder' => 'desc',
    ),
    'team_name' =>
    array (
        'width' => '7%',
        'label' => 'LBL_LIST_TEAM',
        'default' => true,
    ),
    'guardian_name' =>
    array (
        'type' => 'varchar',
        'label' => 'LBL_GUARDIAN_NAME',
        'width' => '7%',
        'default' => false,
    ),
    'other_mobile' =>
    array (
        'type' => 'phone',
        'label' => 'LBL_OTHER_MOBILE',
        'width' => '7%',
        'default' => false,
    ),
    'guardian_name_2' =>
    array (
        'type' => 'varchar',
        'label' => 'LBL_GUARDIAN_NAME_2',
        'width' => '10%',
        'default' => false,
    ),
    'phone_other' =>
    array (
        'width' => '10%',
        'label' => 'LBL_OTHER_PHONE',
        'default' => false,
    ),
    'school_name' =>
    array (
        'type' => 'varchar',
        'label' => 'LBL_SCHOOL_NAME',
        'width' => '10%',
        'default' => false,
    ),
    'gender' =>
    array (
        'type' => 'enum',
        'default' => false,
        'studio' => 'visible',
        'label' => 'LBL_GENDER',
        'width' => '10%',
    ),
    'created_by' =>
    array (
        'width' => '10%',
        'label' => 'LBL_CREATED',
        'default' => false,
    ),
);
