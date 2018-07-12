<?php
    $dictionary["C_Classes"]["fields"]["type"] = array (
        'name' => 'type',
        'vname' => 'LBL_TYPE',
        'type' => 'enum',
        'default' => 'Practice',
        'reportable' => true,
        'len' => 50,
        'size' => '20',
        'options' => 'class_type_list',
    );
    $dictionary["C_Classes"]["fields"]["stage"] = array (
        'name' => 'stage',
        'vname' => 'LBL_STAGE',
        'type' => 'enum',
        'reportable' => true,
        'len' => 50,
        'size' => '20',
        'options' => 'stage_score_list',
    );
    $dictionary["C_Classes"]["fields"]["level"] = array (
        'name' => 'level',
        'vname' => 'LBL_LEVEL',
        'type' => 'enum',
        'default' => '-none-',
        'reportable' => true,
        'len' => 10,
        'size' => '20',
        'options' => 'level_score_list',
    );
    $dictionary["C_Classes"]["fields"]["stage_2"] = array (
        'name' => 'stage_2',
        'vname' => 'LBL_STAGE_CONNECT_SKILL',
        'type' => 'multienum',
        'isMultiSelect' => true,
        'reportable' => true,
        'len' => 170,
        'size' => '20',
        'options' => 'stage_score_list',
    );
    //Custom Relationship. Class - Meeting

    $dictionary['C_Classes']['relationships']['classes_meetings'] = array(
        'lhs_module'        => 'C_Classes',
        'lhs_table'            => 'c_classes',
        'lhs_key'            => 'id',
        'rhs_module'        => 'Meetings',
        'rhs_table'            => 'meetings',
        'rhs_key'            => 'class_id',
        'relationship_type'    => 'one-to-many',
    );

    $dictionary['C_Classes']['fields']['meetings'] = array(
        'name' => 'meetings',
        'type' => 'link',
        'relationship' => 'classes_meetings',
        'module' => 'Meetings',
        'bean_name' => 'Meetings',
        'source' => 'non-db',
        'vname' => 'LBL_MEETING',
    );
    //END: Custom Relationship
    //Field JSON Save Session Detail
    $dictionary['C_Classes']['fields']['content'] = array(
      'name' => 'content',
    'vname' => 'LBL_CONTENT',
    'type' => 'text',
     'reportable' => false,
  );
