<?php
    $module_name = 'C_SMS';
    $listViewDefs [$module_name] = 
    array (
        'NAME' => 
        array (
            'width' => '10%',
            'label' => 'LBL_NAME',
            'default' => true,
            'link' => true,
        ),
        'DELIVERY_STATUS' => 
        array (
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'label' => 'LBL_DELIVERY_STATUS',
            'width' => '10%',
        ),
        'PARENT_NAME' => 
        array (
            'type' => 'parent',
            'studio' => 'visible',
            'label' => 'LBL_LIST_RELATED_TO',
            'link' => true,
            'sortable' => false,
            'ACLTag' => 'PARENT',
            'dynamic_module' => 'PARENT_TYPE',
            'id' => 'PARENT_ID',
            'related_fields' => 
            array (
                0 => 'parent_id',
                1 => 'parent_type',
            ),
            'width' => '10%',
            'default' => true,
        ),
        'DATE_SEND' => 
        array (
            'type' => 'date',
            'label' => 'LBL_DATE_SEND',
            'width' => '10%',
            'default' => true,
        ),
        'PHONE_NUMBER' => 
        array (
            'type' => 'varchar',
            'label' => 'LBL_PHONE_NUMBER',
            'width' => '10%',
            'default' => true,
        ),
        'DESCRIPTION' => 
        array (
            'type' => 'text',
            'label' => 'LBL_DESCRIPTION',
            'sortable' => false,
            'width' => '20%',
            'default' => true,
        ),
        'ASSIGNED_USER_NAME' => 
        array (
            'width' => '9%',
            'label' => 'LBL_ASSIGNED_TO_NAME',
            'module' => 'Employees',
            'id' => 'ASSIGNED_USER_ID',
            'default' => true,
        ),
    );
?>
