<?php
    
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
