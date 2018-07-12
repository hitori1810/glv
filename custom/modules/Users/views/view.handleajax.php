<?php
switch ($_POST['type']) {
    case 'createDemoUser':
        echo createDemoUser();
        break;  
    default:
        break;
}
die();

function createDemoUser(){
    $teamName = $_POST['team_name'];
    $expiryDate = $_POST['expiry_date'];
    $userList = $_POST['user_list'];
    $data = $_POST['data_array'];     



    //check duplicate user name
    $usernameStr = "'".implode("','", $userList)."'";

    $sql = "
    SELECT user_name
    FROM users
    WHERE user_name IN ($usernameStr) AND deleted <> 1
    ";
    $result = $GLOBALS['db']->query($sql); 
    while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
        return json_encode(array(
            'success' => -1,
            'error_label' => 'ERROR_USER_NAME_DUPLICATE_IN_DB',
            'index' => array_search($row['user_name'], $userList),
        ));  
    } 

    //check dupliate team name
    $teamId = $GLOBALS['db']->getOne("SELECT id FROM teams WHERE deleted <> 1 AND name = '$teamName'");

    if(empty($teamId)){
        //create team
        $team = new Team();
        $team->name = $teamName;
        $team->private = false;
        $team->description = "Demo team for ".$teamName;
        $team->short_name = $teamName;
        $team->team_type = "Junior";
        $team->parent_id = "da912929-6ea5-cc3f-b994-599c5337288a";
        $team->save();

        $teamId = $team->id;  
    }   
                             
    $team = new Team();
    $team->retrieve($teamId);                                            

    //create user
    foreach($data as $key => $value){
        $user = new User(); 

        $user->user_name        = $value['user_name'];  
        $user->password         = 'crm123';
        $user->first_name       = $value['user_first_name'];
        $user->last_name        = $value['user_last_name'];
        $user->status           = 'Active';
        $user->is_admin         = 0;                               
        $user->email1           = $value['user_email'];
           
        $user->user_template_id = '16614ada-b0c4-671a-41d9-5afd497798c5';

        $user->save();                                    
        $user->setPreference('subpanel_tabs', 1, 0, 'global');
        $user->setPreference('timef', 'H:i');
        $user->setPreference('timezone', 'Asia/Ho_Chi_Minh');
        $user->savePreferencesToDB();
        
        
        $userId = $user->id; 
                                                      
                                  
        $user->default_team     = $teamId;  
        $user->team_id          = $teamId;  
        $user->team_set_id      = $teamId; 
        $user->save();  
        
        //Add to team            
        $result = $team->add_user_to_team($userId);                                                               
                                                          

        $additionalData = array(
            'link' => false,
            'password' => $user->password,
        );
        $emailTemp_id = $GLOBALS['sugar_config']['passwordsetting']['generatepasswordtmpl'];

        $userHash = md5($additionalData['password']);
        $query = "UPDATE users SET user_hash='{$userHash}', system_generated_password='1', pwd_last_changed = NOW() where id='{$userId}'";
        $GLOBALS['db']->query($query);                      

        //Add user role          
        $focus = new ACLRole();
        $focus->retrieve($value['user_role']);
        $focus->set_relationship('acl_roles_users', array('role_id'=>$focus->id ,'user_id'=>$userId), false);

        
    }    

    return json_encode(array(
        'success' => 1,
    ));
}
?>
