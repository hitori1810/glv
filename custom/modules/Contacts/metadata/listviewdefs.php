<?php
$listViewDefs['Contacts'] =
array (
    'picture' =>
    array (
        'width' => '10%',
        'label' => 'LBL_PICTURE_FILE',
        'default' => true,
    ),
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
    'email1' =>
    array (
        'width' => '10%',
        'label' => 'LBL_LIST_EMAIL_ADDRESS',
        'sortable' => false,
        'link' => true,
        'customCode' => '{$EMAIL1_LINK}{$EMAIL1}</a>',
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
        'width' => '7%',
        'default' => true,
    ),
    'assigned_user_name' =>
    array (
        'width' => '10%',
        'label' => 'LBL_LIST_ASSIGNED_USER',
        'module' => 'Employees',
        'id' => 'ASSIGNED_USER_ID',
        'default' => true,
    ),
    'date_entered' =>
    array (
        'width' => '10%',
        'label' => 'LBL_DATE_ENTERED',
        'default' => true,
    ),
    'team_name' =>
    array (
        'width' => '7%',
        'label' => 'LBL_LIST_TEAM',
        'default' => true,
    ),
    'j_school_contacts_1_name' =>
    array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_J_SCHOOL_CONTACTS_1_FROM_J_SCHOOL_TITLE',
        'id' => 'J_SCHOOL_CONTACTS_1J_SCHOOL_IDA',
        'width' => '10%',
        'default' => false,
    ),
    'created_by_name' =>
    array (
        'width' => '10%',
        'label' => 'LBL_CREATED',
        'default' => false,
    ), 
);
if (($GLOBALS['current_user']->team_type == 'Junior')){
    unset($listViewDefs['Contacts']['email1']);
}
