<?php
    $dictionary['J_Class']['unified_search'] = true;

    $dictionary['J_Class']['unified_search_default_enabled'] = true;

    $dictionary['J_Class']['fields']['name']['unified_search'] = true; 

    $dictionary["J_Class"]["fields"]["main_teacher"] = array (
        'name' => 'main_teacher',
        'vname' => 'LBL_MAIN_TEACHER',
        'type' => 'text',
        'source' => 'non-db',
    );
    $dictionary["J_Class"]["fields"]["covered_teacher"] = array (
        'name' => 'covered_teacher',
        'vname' => 'LBL_COVERED_TEACHER',
        'type' => 'text',
        'source' => 'non-db',
    );