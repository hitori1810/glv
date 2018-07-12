<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2014-09-23 08:55:51
$dictionary["Campaign"]["relationships"]["campaign_sponsors"] = array (
    'lhs_module'        => 'Campaigns',
    'lhs_table'            => 'campaigns',
    'lhs_key'            => 'id',
    'rhs_module'        => 'C_Sponsors',
    'rhs_table'            => 'c_sponsors',
    'rhs_key'            => 'campaign_id',
    'relationship_type'    => 'one-to-many',
);

$dictionary["Campaign"]["fields"]["sponsor_link"] = array (
    'name' => 'sponsor_link',
    'type' => 'link',
    'relationship' => 'campaign_sponsors',
    'module' => 'C_Sponsors',
    'bean_name' => 'C_Sponsors',
    'source' => 'non-db',
    'vname' => 'LBL_SPONSOR_LINK',
);

$dictionary["Campaign"]["fields"]["other_type"] = array (
    'name' => 'other_type',
    'vname' => 'LBL_OTHER_TYPE',
    'type' => 'varchar',
    'len' => '255',
);

?>