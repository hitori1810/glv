<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2014-07-15 14:47:51
$dictionary["C_Rooms"]["fields"]["c_classes_c_rooms_1"] = array (
  'name' => 'c_classes_c_rooms_1',
  'type' => 'link',
  'relationship' => 'c_classes_c_rooms_1',
  'source' => 'non-db',
  'module' => 'C_Classes',
  'bean_name' => 'C_Classes',
  'vname' => 'LBL_C_CLASSES_C_ROOMS_1_FROM_C_CLASSES_TITLE',
  'id_name' => 'c_classes_c_rooms_1c_classes_ida',
);


 // created: 2014-08-12 14:37:25
$dictionary['C_Rooms']['fields']['location']['rows']='2';
$dictionary['C_Rooms']['fields']['location']['cols']='40';

 

 // created: 2014-07-17 12:17:22
$dictionary['C_Rooms']['fields']['name']['full_text_search']=array (
);

 

    
    //Custom Relationship. Teacher - Meeting
    $dictionary['C_Rooms']['relationships']['rooms_meetings'] = array(
        'lhs_module'        => 'C_Rooms',
        'lhs_table'            => 'c_rooms',
        'lhs_key'            => 'id',
        'rhs_module'        => 'Meetings',
        'rhs_table'            => 'meetings',
        'rhs_key'            => 'room_id',
        'relationship_type'    => 'one-to-many',
    );

    $dictionary['C_Rooms']['fields']['meetings'] = array(
        'name' => 'meetings',
        'type' => 'link',
        'relationship' => 'rooms_meetings',
        'module' => 'Meetings',
        'bean_name' => 'Meetings',
        'source' => 'non-db',
        'vname' => 'LBL_MEETING',
    );
    //END: Custom Relationship


?>