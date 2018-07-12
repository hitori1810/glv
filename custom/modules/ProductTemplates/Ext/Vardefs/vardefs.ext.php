<?php 
 //WARNING: The contents of this file are auto-generated


    $dictionary["ProductTemplate"]["fields"]["j_inventorydetail"] = array (
        'name' => 'j_inventorydetail',
        'type' => 'link',
        'relationship' => 'inventoryline_book',
        'module' => 'J_Inventorydetail',
        'bean_name' => 'J_Inventorydetail',
        'source' => 'non-db',
        'vname' => 'LBL_DETAIL',

    );
    $dictionary["ProductTemplate"]["relationships"]['inventoryline_book'] = array(
        'lhs_module'        => 'ProductTemplate',
        'lhs_table'            => 'product_templates',
        'lhs_key'            => 'id',
        'rhs_module'        => 'J_Inventorydetail',
        'rhs_table'            => 'j_inventorydetail',
        'rhs_key'            => 'book_id',
        'relationship_type'    => 'one-to-many',
    );


?>