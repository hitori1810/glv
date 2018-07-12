<?php
// created: 2016-02-01 16:10:28
$dictionary["j_payment_j_payment_1"] = array (
    'true_relationship_type' => 'one-to-many',
    'from_studio' => true,
    'relationships' => 
    array (
        'j_payment_j_payment_1' => 
        array (
            'lhs_module' => 'J_Payment',
            'lhs_table' => 'j_payment',
            'lhs_key' => 'id',
            'rhs_module' => 'J_Payment',
            'rhs_table' => 'j_payment',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'j_payment_j_payment_1_c',
            'join_key_lhs' => 'j_payment_j_payment_1j_payment_ida',
            'join_key_rhs' => 'j_payment_j_payment_1j_payment_idb',
        ),
    ),
    'table' => 'j_payment_j_payment_1_c',
    'fields' => 
    array (
        0 => 
        array (
            'name' => 'id',
            'type' => 'varchar',
            'len' => 36,
        ),
        1 => 
        array (
            'name' => 'date_modified',
            'type' => 'datetime',
        ),
        2 => 
        array (
            'name' => 'deleted',
            'type' => 'bool',
            'len' => '1',
            'default' => '0',
            'required' => true,
        ),
        3 => 
        array (
            'name' => 'j_payment_j_payment_1j_payment_ida',
            'type' => 'varchar',
            'len' => 36,
        ),
        4 => 
        array (
            'name' => 'j_payment_j_payment_1j_payment_idb',
            'type' => 'varchar',
            'len' => 36,
        ),
        5 => 
        array (
            'name' => 'hours',
            'type' => 'decimal',
            'len' => 13,
            'precision' => '2',
        ),
        6 => 
        array (
            'name' => 'amount',
            'type' => 'decimal',
            'len' => 20,
            'precision' => '2',
        ),
    ),
    'indices' => 
    array (
        0 => 
        array (
            'name' => 'j_payment_j_payment_1spk',
            'type' => 'primary',
            'fields' => 
            array (
                0 => 'id',
            ),
        ),
        1 => 
        array (
            'name' => 'j_payment_j_payment_1_ida1',
            'type' => 'index',
            'fields' => 
            array (
                0 => 'j_payment_j_payment_1j_payment_ida',
            ),
        ),
        2 => 
        array (
            'name' => 'j_payment_j_payment_1_alt',
            'type' => 'alternate_key',
            'fields' => 
            array (
                0 => 'j_payment_j_payment_1j_payment_idb',
            ),
        ),
    ),
);