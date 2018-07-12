<?php
// created: 2015-08-05 16:29:57
$subpanel_layout['list_fields'] = array (
    'pt_order' =>
    array (
        'type' => 'int',
        'vname' => 'LBL_PT_ORDER',
        'width' => '10%',
        'default' => true,
    ),    
    'leads_j_ptresult_1_name' =>
    array (
        'type' => 'relate',
        'link' => true,
        'vname' => 'LBL_LEADS_J_PTRESULT_1_FROM_LEADS_TITLE',
        'id' => 'LEADS_J_PTRESULT_1LEADS_IDA',
        'width' => '10%',
        'default' => true,
        'widget_class' => 'SubPanelDetailViewLink',
        'target_module' => 'Leads',
        'target_record_key' => 'leads_j_ptresult_1leads_ida',
    ),
    'listening' =>
    array (
        'type' => 'varchar',
        'vname' => 'LBL_LISTENING',
        'width' => '7%',
        'default' => true,
    ),
    'speaking' =>
    array (
        'type' => 'varchar',
        'vname' => 'LBL_SPEAKING',
        'width' => '7%',
        'default' => true,
    ),
    'reading' =>
    array (
        'type' => 'reading',
        'vname' => 'LBL_READING',
        'width' => '7%',
        'default' => true,
    ),
    'writing' =>
    array (
        'type' => 'varchar',
        'vname' => 'LBL_WRITING',
        'width' => '7%',
        'default' => true,
    ),
    'score' =>
    array (
        'type' => 'score',
        'vname' => 'LBL_SCORE',
        'width' => '10%',
        'default' => true,
    ),
    'result' =>
    array (
        'type' => 'result',
        'vname' => 'LBL_RESULT',
        'width' => '10%',
        'default' => true,
    ),
    'attended' =>
    array (
        'type' => 'bool',
        'default' => true,
        'vname' => 'LBL_ATTENDED',
        'width' => '10%',
    ),
    'edit_button' =>
    array (
        'vname' => 'LBL_EDIT_BUTTON',
        'widget_class' => 'SubPanelEditButton',
        'module' => 'J_PTResult',
        'width' => '4%',
        'default' => true,
    ),
    'remove_button' =>
    array (
        'vname' => 'LBL_REMOVE',
        'widget_class' => 'SubPanelRemoveButton',
        'module' => 'J_PTResult',
        'width' => '5%',
        'default' => true,
    ),
);