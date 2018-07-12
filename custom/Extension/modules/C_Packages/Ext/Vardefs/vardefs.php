<?php
//Create by leduytan - New field 
$dictionary["C_Packages"]["fields"]["expire_duration"] = array (
   'required' => false,
   'name' => 'expire_duration',
   'vname' => 'LBL_EXPIRE_DURATION',
   'type'=>'enum',
   'options'=>'expire_duration_list',
   'len' => '20',
);

$dictionary["C_Packages"]["fields"]["start_date"] = array (
                'required' => false,
                'name' => 'start_date',
                'vname' => 'LBL_START_DATE',
                'type' => 'date',
                'massupdate' => 0,
                'no_default' => false,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => false,
                'reportable' => true,
                'unified_search' => false,
                'merge_filter' => 'disabled',
                'calculated' => false,
                'size' => '20',
                'enable_range_search' => true,
                'options' => 'date_range_search_dom',
            );
$dictionary["C_Packages"]["fields"]["end_date"] = array (
                'required' => false,
                'name' => 'end_date',
                'vname' => 'LBL_END_DATE',
                'type' => 'date',
                'massupdate' => 0,
                'no_default' => false,
                'comments' => '',
                'help' => '',
                'importable' => 'false',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => false,
                'reportable' => true,
                'unified_search' => false,
                'merge_filter' => 'disabled',
                'calculated' => false,
                'size' => '20',
                'enable_range_search' => true,
                'options' => 'date_range_search_dom',
            );
//end
