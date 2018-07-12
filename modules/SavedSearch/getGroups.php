<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point'); 
      
    global $db, $current_user;

    $query = "SELECT id, name FROM teams WHERE private = 0 AND deleted = 0";

    if(!is_admin($current_user)){
        $query = "SELECT DISTINCT rel.team_id AS id, teams.name, teams.name_2, rel.implicit_assign FROM team_memberships rel RIGHT JOIN teams ON (rel.team_id = teams.id) WHERE rel.user_id = '{$current_user->id}' AND rel.deleted = 0 ORDER BY teams.name ASC"; 
    }
    $result = $db->query($query);
    $i = 0;
    $group_option_list = '';
    while($row = $db->fetchByAssoc($result)){
        $row['name'] = $row['name']? $row['name'] : $row['name2'];
        $group_option_list .= '<option label="'.$row['name'].'" value="'.$row['id'].'">'.$row['name'].'</option>';
    }
    echo  $group_option_list;

?>