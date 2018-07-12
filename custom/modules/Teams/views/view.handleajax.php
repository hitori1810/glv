<?php
include_once("custom/modules/Teams/_helper.php");

switch ($_POST['type']) {    
    case 'ajaxGetDetail':
        echo ajaxGetDetail($_POST['team_id']);
        break;   
    case 'ajaxGetTeamMembers':
        echo ajaxGetTeamMembers();
        break; 
    case 'ajaxAddUser':
        echo ajaxAddUser();
        break; 
    case 'ajaxRemoveUser':
        echo ajaxRemoveUser();
        break; 
    case 'ajaxUpdateUser':
        echo ajaxUpdateUser();
        break; 
    case 'ajaxUpdateTeam':
        echo ajaxUpdateTeam();
        break; 
    case 'ajaxDeleteTeam':       //done
        echo ajaxDeleteTeam();
        break; 
    default:
        echo false;
        die;
}  
die;

function ajaxGetDetail($team_id){
    global $mod_strings;
    $team = BeanFactory::getBean('Teams',$team_id);

    //Prepare Users List
    $html   = "";     

    if($team_id != "1"){
        $html .= '<input class="button primary" type="button" value="'.translate('LBL_ADD_USER','Teams').'" id="add_user_bt"><br><br>';
    } 

    $html .= "<table width='100%' class='table table-striped table-bordered dataTable' id='celebs'>";
    $html .= "<thead><tr>
    <th width='15%'>".translate('LBL_NAME','Users')."</th>
    <th width='15%'>".translate('LBL_USER_NAME','Users')."</th>
    <th width='15%'>".translate('LBL_TITLE','Users')."</th>
    <th width='15%'>".translate('LBL_DEFAULT_TEAM','Users')."</th>
    <th width='20%'>".translate('LBL_DEFAULT_SUBPANEL_TITLE','Roles')."</th>
    <th width='10%'>".$mod_strings['LBL_STATUS']."</th>
    <th style='min-width: 50px; text-align:center;'></th>
    </tr></thead>
    <tbody>"; 
    $html   .= "</tbody>";
    $html   .= "</table>"; 
    
    return json_encode(array(
        "success"       => "1",
        "html"          => $html,  
        "team_name"     => $team->name,  
        "short_name"    => $team->short_name,  
        "prefix"        => $team->code_prefix,   
        "phone_number"  => $team->phone_number,      
        "team_id"       => $team->id,
        "parent_id"     => $team->parent_id,
        "parent_name"   => empty($team->parent_name) ? $team->parent_name = '<-none->' : $team->parent_name = $team->parent_name,
        "team_order"    => $team->team_order,
        "description"   => $team->description,               
    ));    
}

function ajaxGetTeamMembers(){
    global $mod_strings;
    //get input data
    $teamId         = $_POST['team_id'];
    $teamParentId   = $GLOBALS['db']->getOne("SELECT parent_id FROM teams WHERE id = '{$teamId}'");
    $draw           = $_POST['draw'];
    $orderField     = $_POST['order'][0]['column'];
    $orderType      = $_POST['order'][0]['dir'];
    $start          = $_POST['start'];
    $length         = $_POST['length'];
    $search         = $_POST['search']['value'];

    $tableColumn = array(
        "full_name",
        "user_name",
        "title",
        "teams",
        "roles",
        "status",
        "action",
    );
    $countTotal = 0; 
    $data = array();

    //Prepare Users array
    $getMembersResult = getTeamMembers($teamId, $teamParentId, $search, $tableColumn[$orderField], $orderType, $start, $length);
    $members = $getMembersResult['member_list'];
    $countTotal = $getMembersResult['count'];
    $roles   = getListRole();
    $roleArr   = getRolesForAllUsers($roles);
    $teamArr   = getTeamsForAllUsers();

    foreach($members as $member){
        $membership = getMembershipForUser($teamId);

        //action button
        if($membership == 'Member')
            $btnAction = "<td valign='bottom'>
            <a class='login_user' style='margin-right: 5px;' href='index.php?module=Users&action=Impersonate&record={$member['user_id']}' title = '{$mod_strings['LBL_LOG_IN_TO']}{$member['l1_user_name']}'><span class='glyphicon glyphicon-log-in' aria-hidden='true'></span></a>
            <a class='save_user' style='margin-right: 5px;' href='javascript:void(0)' title = '{$GLOBALS['app_strings']['LBL_EMAIL_SAVE']}'><span class='glyphicon glyphicon-floppy-save' aria-hidden='true'></span></a>
            <a class='remove_user' href='javascript:void(0)' title = '{$GLOBALS['app_strings']['LBL_EMAIL_MENU_REMOVE']}'><span class='glyphicon glyphicon-remove' aria-hidden='true'></a>
            </td>";
        else
            $btnAction = "<td valign='bottom'>
            <a class='login_user' style='margin-right: 5px;' href='index.php?module=Users&action=Impersonate&record={$member['user_id']}' title = '{$mod_strings['LBL_LOG_IN_TO']}{$member['l1_user_name']}'><span class='glyphicon glyphicon-log-in' aria-hidden='true'></span></a>
            <a class='save_user' title = '{$GLOBALS['app_strings']['LBL_EMAIL_SAVE']}' href='javascript:void(0)'><span class='glyphicon glyphicon-floppy-save' aria-hidden='true'></span></a>
            </td>";

        $data[] = array(           
            $member['l1_full_name'],    //1  
            array(
                "custom"    => "user_name",
                "user_name" => $member['l1_user_name'],
                "id"        => $member['user_id'],
                "html"      => "<a target='_blank' style='text-decoration: none;font-weight: normal;' href='index.php?module=Users&return_module=Users&action=DetailView&record={$member['user_id']}'>{$member['l1_user_name']}</a>
                <input type='hidden' class='user_id' value='".$member['user_id']."' />
                ",
            ),                         //2
            $member['l1_title'],       //3
            array(
                'custom'            => "teams",
                'default_team_id'   => $teamArr[$member['user_id']]['default_team_id'],
                'teams'             => $teamArr[$member['user_id']]['teams'], 
                'html'              => getTeamsForUser($teamArr[$member['user_id']]), 
            ),                         //4
            array(
                'custom'            => "roles",
                'user_roles'        => $roleArr[$member['user_id']], 
                'roles'             => $roles, 
                'html'              => $member['is_admin'] ? "<p style='margin-left: 10px;'>Administrator</p>" : 
                getRolesForUser($roles, $roleArr[$member['user_id']]
                ), 
            ),                          //5
            array(
                'custom'            => "status",
                'status'            => $member['l1_employee_status'], 
                'html'              => makeDropdownStatus($member['l1_employee_status']), 
            ),                         //6
            array(
                'custom'            => "action",                     
                'html'              => $btnAction, 
            ),                         //7     
        );
    }                                            

    return json_encode(array(                                          
        "draw"              => $draw,               
        "recordsTotal"      => $countTotal,               
        "recordsFiltered"   => $countTotal,               
        "data"              => $data,               
    ));
}

function ajaxAddUser(){
    if(!empty($_POST['team_list']) && !empty($_POST['users_list'])){

        foreach ($_POST['team_list'] as $team_id){
            foreach($_POST['users_list'] as $user_id){
                $focus = new Team();
                $focus->retrieve($team_id);
                $focus->add_user_to_team($user_id);   
            } 
        }

        return json_encode(array("success" => "1"));   
    }else{
        return json_encode(array("success" => "0"));  
    } 
}

function ajaxRemoveUser(){
    if(!empty($_POST['team_list']) && !empty($_POST['user_id'])){

        foreach ($_POST['team_list'] as $team_id){
            $focus = new Team();
            $focus->retrieve($team_id);
            $focus->remove_user_from_team($_POST['user_id']);   
        }

        return json_encode(array("success" => "1"));   
    }else{
        return json_encode(array("success" => "0"));  
    }
}

function ajaxUpdateUser(){
    if(!empty($_POST['user_id']) && !empty($_POST['primary_team_id'])){
        $user = new User();
        $user->retrieve($_POST['user_id']);
        if($user->is_admin != '1'){
            //remove user from current roles
            $sql = "UPDATE acl_roles_users SET deleted='1', date_modified='{$GLOBALS['timedate']->nowDb()}' WHERE user_id='{$_POST['user_id']}'";
            $GLOBALS['db']->query($sql);
            if(!empty($_POST['roles']))   
                foreach ($_POST['roles'] as $role_id){
                    $focus = new ACLRole();
                    $focus->retrieve($role_id);
                    $focus->set_relationship('acl_roles_users', array('role_id'=>$focus->id ,'user_id'=>$_POST['user_id']), false);   
            }
        }

        //update Defaut Team
        $user->retrieve($_POST['user_id']);
        $user->team_id      = $_POST['primary_team_id'];
        $user->default_team      = $_POST['primary_team_id'];
        $user->status      = $_POST['status'];
        $user->team_set_id  = $_POST['primary_team_id'];
        $user->save(); 
        $user->retrieve($_POST['user_id']);

        return json_encode(array("success" => "1"));   
    }else{
        return json_encode(array("success" => "0"));  
    }   
}

function ajaxUpdateTeam(){
    if(empty($_POST['team_id']))
        $focus = new Team();
    else
        $focus = BeanFactory::getBean('Teams',$_POST['team_id']);

    $focus->name            = $_POST['team_name'];
    $focus->short_name      = $_POST['short_name'];
    $focus->code_prefix     = $_POST['prefix'];
    $focus->team_type       = 'Junior';
    $focus->phone_number    = $_POST['phone_number'];
    $focus->parent_id       = $_POST['parent_id'];
    $focus->parent_name     = $_POST['parent_name'];
    $focus->team_order      = $_POST['team_order'];
    $focus->description     = $_POST['description'];
    $focus->save();

    //add all users of parent to this team - Except Global
    if(empty($_POST['team_id'])){
        $call_back = 'create';

        if($focus->parent_id != '1'){
            $users_parent = getTeamMembers($focus->parent_id);
            for($i = 0; $i < count($users_parent); $i++){
                $focus->add_user_to_team($users_parent[$i]['user_id']);
            }    
        }

    }else{
        $call_back = 'update';
        //edit by Tung Bui - 06/04/2016
        if($focus->parent_id != '1' && $_POST['copyUserFlag'] == 'true'){
            //delete users of old parent taem
            $users = getTeamMembers($focus->id);
            $oldParentUsers = getTeamMembers($oldParentId);
            foreach($users as $key => $value){
                $isParentUser = false;
                //check parent team have this user?
                foreach($oldParentUsers as $parentKey => $parentValue){
                    if($value['user_id'] == $parentValue['user_id']){
                        $isParentUser = true;    
                        break;
                    }
                }
                if($isParentUser) {
                    $focus->remove_user_from_team($value['user_id']);
                }
            }

            //Copy users from parent team to this team
            $users_parent = getTeamMembers($focus->parent_id);
            for($i = 0; $i < count($users_parent); $i++){
                $focus->add_user_to_team($users_parent[$i]['user_id']);
            }    
        }
        //END - edit by Tung Bui - 06/04/2016                                       

    }
    return json_encode(array( 
        "success" => "1",
        "act" => "save",
        "call_back" => $call_back,
        "team_id" => $focus->id,
        "team_name" => $focus->name,
        "parent_id" => $focus->parent_id,
        "parent_name" => $focus->parent_name,
        "team_order" => $focus->team_order,
        "description" => $focus->description,
    ));
}

function ajaxDeleteTeam(){
    if(!empty($_POST['team_id'])){
        $focus = new Team();
        $focus->retrieve($_POST['team_id']);
        if($focus->has_records_in_modules()) {
            header("Location: index.php?module=Teams&action=ReassignTeams&record={$focus->id}");
        } else {
            //todo: Verify that no items are still assigned to this team.
            if($focus->id == $focus->global_team) {
                $msg = $GLOBALS['app_strings']['LBL_MASSUPDATE_DELETE_GLOBAL_TEAM'];
                $GLOBALS['log']->fatal($msg);
                $error_message = $app_strings['LBL_MASSUPDATE_DELETE_GLOBAL_TEAM'];
                SugarApplication::appendErrorMessage($error_message);
                header('Location: index.php?module=Teams&action=DetailView&record='.$focus->id);
                return;
            }
            //Call mark_deleted function
            $focus->mark_deleted();
            return json_encode(array(
                "success" => "1",
                "act" => "delete",
            ));
        }
    }
    else{
        return json_encode(array("success" => "0",));   
    }
}
?>
