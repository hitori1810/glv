<?php
$searchdefs['Opportunities'] = 
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
      'sales_stage' => 
      array (
        'name' => 'sales_stage',
        'default' => true,
        'width' => '10%',
      ),
      'added_to_class' => 
      array (
        'type' => 'bool',
        'label' => 'LBL_ADDED_TO_CLASS',
        'width' => '10%',
        'default' => true,
        'name' => 'added_to_class',
      ),
    ),
    'advanced_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'contact_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_CONTACT_NAME',
        'id' => 'CONTACT_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'contact_name',
      ),
      'c_packages_opportunities_1_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_C_PACKAGES_OPPORTUNITIES_1_FROM_C_PACKAGES_TITLE',
        'id' => 'C_PACKAGES_OPPORTUNITIES_1C_PACKAGES_IDA',
        'width' => '10%',
        'default' => true,
        'name' => 'c_packages_opportunities_1_name',
      ),
      'oder_id' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_ORDER_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'oder_id',
      ),
      'assigned_user_id' => 
      array (
        'name' => 'assigned_user_id',
        'type' => 'enum',
        'label' => 'LBL_COMMISSIONER',
        'function' => 
        array (
          'name' => 'get_user_array',
          'params' => 
          array (
            0 => false,
          ),
        ),
        'default' => true,
        'width' => '10%',
      ),
      'created_by' => 
      array (
        'type' => 'assigned_user_name',
        'label' => 'LBL_CREATED',
        'width' => '10%',
        'default' => true,
        'name' => 'created_by',
      ),
      'opportunity_type' => 
      array (
        'type' => 'enum',
        'label' => 'LBL_TYPE',
        'width' => '10%',
        'default' => true,
        'name' => 'opportunity_type',
      ),
      'sales_stage' => 
      array (
        'name' => 'sales_stage',
        'default' => true,
        'width' => '10%',
      ),
      'team_name' => 
      array (
        'width' => '10%',
        'label' => 'LBL_TEAMS',
        'default' => true,
        'name' => 'team_name',
      ),
      'date_closed' => 
      array (
        'name' => 'date_closed',
        'default' => true,
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
        'default' => true,
        'name' => 'date_entered',
      ),
      'expire_date' => 
      array (
        'type' => 'date',
        'label' => 'LBL_EXPIRE_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'expire_date',
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
