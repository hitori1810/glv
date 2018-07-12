<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point'); 

global $db, $current_user;

if(is_array($_REQUEST['department'])){
    $group_ids = implode("','",$_REQUEST['department']);
}

$query = "SELECT DISTINCT 
IFNULL(users.id, '') id,
CONCAT(
IFNULL(users.first_name, ''),
' ',
IFNULL(users.last_name, '')
) full_name 
FROM
teams 
INNER JOIN team_memberships  
ON teams.id = team_memberships.team_id 
AND team_memberships.deleted = 0 
INNER JOIN users  
ON users.id = team_memberships.user_id 
AND users.deleted = 0 
WHERE
(((teams.id IN ('$group_ids'))
AND (users.status = 'Active')
AND ((COALESCE(LENGTH(users.is_admin), 0) > 0))))
AND teams.deleted = 0
AND teams.private = 0
ORDER BY full_name";

$result = $db->query($query);
$i = 0;
$user_option_list = '';
while($row = $db->fetchByAssoc($result)){
    $user_option_list .= '<option label="'.$row['full_name'].'" value="'.$row['id'].'">'.$row['full_name'].'</option>';
}
echo  $user_option_list;
?>