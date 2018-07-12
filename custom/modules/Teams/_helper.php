<?php
/***
* Get Team members are not include User in Parent Team
*
* @param mixed $team_id
* @param mixed $parent_team_id
*/


function getTeamMembers($team_id, $parent_team_id, $search = "", $orderBy = "full_name", $orderType = "asc", $offset = "", $limit = ""){
    //filter
    $searchFilter = "";
    if(!empty($search)){
        $searchFilter .= " AND ( CONCAT( IFNULL(l1.last_name, ''), ' ', IFNULL(l1.first_name, '')) LIKE '%".$search."%'";                 
        $searchFilter .= " OR l1.user_name LIKE '%" .$search. "%') ";                  
    }
    //order    
    $orderSql = "ORDER BY l1_full_name ";
    if($orderBy == "user_name") $orderSql = "ORDER BY l1.user_name ";
    elseif($orderBy == "title") $orderSql = "ORDER BY l1.title ";
    elseif($orderBy == "status") $orderSql = "ORDER BY l1.status ";    
    $orderSql .= $orderType;

    //limit
    $limitSql = "";
    if($limit != "" && $offset != ""){
        $limitSql = "LIMIT $limit OFFSET $offset"; 
    }

    $ext_parent = '';
    if(!empty($parent_team_id) && $parent_team_id != '1')
        $ext_parent = "AND (l1.id NOT IN (SELECT DISTINCT
        IFNULL(tm.id, '') tm_id
        FROM
        teams t
        INNER JOIN
        team_memberships tm_1 ON t.id = tm_1.team_id
        AND tm_1.deleted = 0
        INNER JOIN
        users tm ON tm.id = tm_1.user_id AND tm.deleted = 0
        WHERE
        (((t.id = '$parent_team_id')))
        AND t.deleted = 0))";



    $q1 = "SELECT DISTINCT
    IFNULL(l1.id, '') user_id,
    l1.user_name l1_user_name,
    CONCAT( IFNULL(l1.last_name, ''), ' ', IFNULL(l1.first_name, '')) l1_full_name,
    l1.title l1_title,
    l1.is_admin is_admin,
    IFNULL(l2.id, '') l2_id,
    IFNULL(l2.name, '') l2_name,
    l1.phone_mobile l1_phone_mobile,
    IFNULL(l3.id, '') l3_id,
    l3.email_address l3_email_address,
    l1.status l1_employee_status
    FROM
    teams
    INNER JOIN team_memberships l1_1 ON teams.id = l1_1.team_id AND l1_1.deleted = 0
    INNER JOIN users l1 ON l1.id = l1_1.user_id AND l1.deleted = 0 AND for_portal_only <> 1
    LEFT JOIN teams l2 ON l1.default_team = l2.id AND l2.deleted = 0
    LEFT JOIN email_addr_bean_rel l3_1 ON l1.id = l3_1.bean_id AND l3_1.deleted = 0 AND l3_1.primary_address = '1'
    LEFT JOIN email_addresses l3 ON l3.id = l3_1.email_address_id AND l3.deleted = 0
    WHERE
    (((teams.id = '$team_id')
    $ext_parent
    ))
    $searchFilter
    AND teams.deleted = 0
    AND l1.is_template <> '1'
    $orderSql
    ";

    $totalArr   = $GLOBALS['db']->fetchArray($q1);
    $limitArr   = $GLOBALS['db']->fetchArray($q1.$limitSql);

    return array(
        "member_list"   => $limitArr,
        "count"         => count($totalArr),
    );
}        

function getTeamsForAllUsers(){
    $resultArray = array();

    $allTeams = array();
    $sql = "
    SELECT id, name
    FROM teams 
    WHERE deleted <> 1
    AND private <> 1";
    $result = $GLOBALS['db']->query($sql);                              
    while($row = $GLOBALS['db']->fetchByAssoc($result)){
        $allTeams[$row['id']] = $row['name'];
    }

    $query = "SELECT DISTINCT
    IFNULL(users.id, '') user_id,
    IFNULL(users.is_admin, '') is_admin,
    IFNULL(users.user_name, '') user_name,
    IFNULL(l1.id, '') defaut_team_id,
    IFNULL(l1.name, '') defaut_team_name,
    IFNULL(l2.id, '') team_id,
    IFNULL(l2.name, '') team_name
    FROM users
    LEFT JOIN teams l1 ON users.default_team = l1.id AND l1.deleted = 0
    LEFT JOIN team_memberships l2_1 ON users.id = l2_1.user_id AND l2_1.deleted = 0
    LEFT JOIN teams l2 ON l2.id = l2_1.team_id AND l2.deleted = 0
    WHERE                        
    users.deleted = 0
    AND l2.private <> '1'";   
    $result = $GLOBALS['db']->query($query);                              
    while($row = $GLOBALS['db']->fetchByAssoc($result)){
        if(!is_array($resultArray[$row["user_id"]])) $resultArray[$row["user_id"]] = array(
            "user_name"         => $row["user_name"],    
            "default_team_id"    => $row["defaut_team_id"],    
            "default_team_name"  => $row["defaut_team_name"],    
            "teams"             => array(),    
            );

        if($row['is_admin'] == "1"){
            $resultArray[$row["user_id"]]["teams"] = $allTeams;
        }
        else{   
            $resultArray[$row["user_id"]]["teams"][$row["team_id"]] = $row["team_name"];   
        }

    }                      
    return $resultArray;   
}

function getTeamsForUser($teamsArr){     
    $html = "<select class='selectpicker select_team' data-width='100%'>";

    foreach($teamsArr["teams"] as $teamId => $teamName){
        if($teamId == $teamsArr["default_team_id"]){
            $html .= "<option selected value='{$teamId}'>{$teamName}</option>";
        }
        else{
            $html .= "<option value='{$teamId}'>{$teamName}</option>";
        }   
    }

    $html .= "</select>";
    return $html;   
}

function getListRole(){
    //Get list Role
    $q1 = "SELECT id, name FROM acl_roles
    WHERE acl_roles.deleted=0 AND name <> 'Customer Self-Service Portal Role' ORDER BY name";
    $data = array();
    $rs1 = $GLOBALS['db']->query($q1);
    while($row = $GLOBALS['db']->fetchByAssoc($rs1))
        $data[$row['id']] = $row['name'];

    return $data;
}

function getRolesForAllUsers($roles){
    $resultArray = array();

    //get list of Role for a given user id
    $user_roles = array();
    $q2 = "SELECT
    users.id user_id
    ,acl_roles.id id
    ,acl_roles.name name
    FROM acl_roles
    INNER JOIN acl_roles_users ON acl_roles_users.role_id = acl_roles.id 
    AND acl_roles_users.deleted = 0
    INNER JOIN users ON users.id = acl_roles_users.user_id AND users.deleted <> 1
    WHERE acl_roles.deleted = 0
    ";
    $rs2 = $GLOBALS['db']->query($q2);
    while($row = $GLOBALS['db']->fetchByAssoc($rs2) ){
        if(!is_array($resultArray[$row["user_id"]])) $resultArray[$row["user_id"]] = array(); 

        $resultArray[$row["user_id"]][] = $row["id"];     
    }

    return $resultArray;
}

function getRolesForUser($roles, $user_roles){
    // Make Colorzing
    $label_color = array(
        0 => 'label-info',
        1 => 'label-primary',
        2 => 'label-success',
        3 => 'label-danger',
        4 => 'label-default',
        5 => 'label-warning',
        6 => 'highlight_blue',
        7 => 'highlight_bluelight',
        8 => 'highlight_red',
        9 => 'highlight_dream',
        10 => 'highlight_black',
        11 => 'highlight_yellow',
        12 => 'highlight_yellowlight',
        13 => 'highlight_green',
        14 => 'highlight_violet',
        15 => 'highlight_orange',
        16 => 'highlight_crimson',
        17 => 'highlight_blood',
        18 => 'highlight_redlight',
    );
    $i = 0;
    $html = "<select class='selectpicker select_role' data-live-search='true' multiple data-width='200px' title=''>";
    foreach($roles as $role_id => $role_name){
        in_array($role_id, $user_roles) ? $sel = 'selected' : $sel = '';
        $html .= "<option data-content=\"<span class='label $label_color[$i]'>{$role_name}</span>\" $sel value='{$role_id}'>{$role_name}</option>";
        $i > 17 ? $i = 0 : $i++;
    }
    $html .= "</select>";
    return $html;
}

function makeDropdownStatus($selected){
    $html = "<select class='selectpicker select_status' data-width='100%'>";  
    $html .= get_select_options($GLOBALS['app_list_strings']['user_status_dom'],$selected);
    return $html .= "</select>";
}

function getMembershipForUser($team_id){
    if($team_id == '1')
        return 'Global';
    else
        return 'Member';
}            

function getTeamNodes($selected = null){
    $node_arr = array();
    $q1 = "SELECT id, name, parent_id, description 
    FROM teams WHERE private <> 1 AND deleted <> 1
    ORDER BY teams.team_order, teams.name
    ";
    $rs1 = $GLOBALS['db']->query($q1);
    while($row = $GLOBALS['db']->fetchByAssoc($rs1)){
        if($row['id'] == '1'){
            $open = true;
            $icon = "custom/include/javascripts/Ztree/img/diy/1_open.png";   
        }
        else{
            $open = true;
            $icon = '';                    
        } 

        $node_arr[] = array('id'=>$row['id'] , 'pId'=> $row['parent_id'], 'name'=>$row['name'], 'open'=>$open, 'icon'=>$icon,  'isParent'=>true);  
    }
    return $node_arr;
}  

function getAllChildIds($teamId){
    $childList = array();

    // Get children of team
    $q1 = "SELECT id, name, parent_id FROM teams WHERE private <> 1 AND deleted = 0 AND parent_id = '{$teamId}'";
    $rs1 = $GLOBALS['db']->query($q1);
    while($row = $GLOBALS['db']->fetchByAssoc($rs1)){
        if(!in_array($row['id'], $childList)){
            $childList[] = $row['id'];
        }

        $subChildList = getAllChildIds($row['id']);
        foreach($subChildList as $value){
            if(!in_array($value, $childList)){
                $childList[] = $value;
            }    
        }                                                              
    }

    return $childList;
}
?>
