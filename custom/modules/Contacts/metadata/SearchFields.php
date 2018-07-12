<?php
// created: 2017-11-24 15:57:06
$searchFields['Contacts'] = array (
    'first_name' => 
    array (
        'query_type' => 'default',
    ),
    'last_name' => 
    array (
        'query_type' => 'default',
    ),
    'search_name' => 
    array (
        'query_type' => 'default',
        'db_field' => 
        array (
            0 => 'first_name',
            1 => 'last_name',
        ),
        'force_unifiedsearch' => true,
    ),   
    'lead_source' => 
    array (
        'query_type' => 'default',
        'operator' => '=',
        'options' => 'lead_source_dom',
        'template_var' => 'LEAD_SOURCE_OPTIONS',
    ),   
    'phone' => 
    array (
        'query_type' => 'default',
        'db_field' => 
        array (
            0 => 'phone_mobile',
            1 => 'phone_work',
            2 => 'phone_other',
            3 => 'phone_fax',
            4 => 'assistant_phone',
        ),
    ),
    'email' => 
    array (
        'query_type' => 'default',
        'operator' => 'subquery',
        'subquery' => 'SELECT eabr.bean_id FROM email_addr_bean_rel eabr JOIN email_addresses ea ON (ea.id = eabr.email_address_id) WHERE eabr.deleted=0 AND ea.email_address LIKE',
        'db_field' => 
        array (
            0 => 'id',
        ),
    ),
    'assistant' => 
    array (
        'query_type' => 'default',
    ),
    'contact_id' => 
    array (
        'query_type' => 'default',
    ),
    'address_street' => 
    array (
        'query_type' => 'default',
        'db_field' => 
        array (
            0 => 'primary_address_street',
            1 => 'alt_address_street',
        ),
    ),     
    'current_user_only' => 
    array (
        'query_type' => 'default',
        'db_field' => 
        array (
            0 => 'assigned_user_id',
        ),
        'my_items' => true,
        'vname' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
    ),
    'assigned_user_id' => 
    array (
        'query_type' => 'default',
    ),
    'account_id' => 
    array (
        'query_type' => 'default',
        'db_field' => 
        array (
            0 => 'accounts.id',
        ),
    ),
    'favorites_only' => 
    array (
        'query_type' => 'format',
        'operator' => 'subquery',
        'subquery' => 'SELECT sugarfavorites.record_id FROM sugarfavorites
        WHERE sugarfavorites.deleted=0
        and sugarfavorites.module = \'Contacts\'
        and sugarfavorites.assigned_user_id = \'{0}\'',
        'db_field' => 
        array (
            0 => 'id',
        ),
    ),
    'sync_contact' => 
    array (
        'query_type' => 'format',
        'operator' => 'subquery',
        'subquery_in_clause' => 
        array (
            0 => 'NOT IN',
            1 => 'IN',
        ),
        'subquery' => 'SELECT contacts_users.contact_id FROM contacts_users
        WHERE contacts_users.deleted=0
        and contacts_users.user_id = \'{1}\'',
        'db_field' => 
        array (
            0 => 'id',
        ),
    ),
    'range_date_entered' => 
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'start_range_date_entered' => 
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'end_range_date_entered' => 
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'range_date_modified' => 
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'start_range_date_modified' => 
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'end_range_date_modified' => 
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),      
    'description' => 
    array (
        'query_type' => 'default',
    ),
    'range_birthdate' => 
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'start_range_birthdate' => 
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'end_range_birthdate' => 
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),      
    'phone_mobile' => 
    array (
        'query_type' => 'default',
    ),
    'range_class_year' => 
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'start_range_class_year' => 
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'end_range_class_year' => 
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'primary_address_street' => 
    array (
        'query_type' => 'default',
    ),
    'primary_address_city' => 
    array (
        'query_type' => 'default',
    ),
    'primary_address_state' => 
    array (
        'query_type' => 'default',
    ),
    'primary_address_country' => 
    array (
        'query_type' => 'default',
    ),
    'custom_checkbox_class' => 
    array (
        'query_type' => 'default',
    ),
    'describe_relationship' => 
    array (
        'query_type' => 'default',
    ),
    'c_contacts_contacts_1_name' => 
    array (
        'query_type' => 'default',
    ),
    'range_aims_id' => 
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'start_range_aims_id' => 
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'end_range_aims_id' => 
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'range_stopped_date' => 
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'start_range_stopped_date' => 
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'end_range_stopped_date' => 
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
);