<?php

/**
* This file is belong to OnlineCRM
* Do not copy without permission
* 
* @package  User
* @category Helper
* @author   Thuan Nguyen
* @version  1
*/
class UserTemplateHelper {
    
    /**
    * Apply User Template to users from User Template ID
    * 
    * @author   Thuan Nguyen
    * @param    id          $userTemplateID     ID of user template
    * @param    array/id    $userArray          array of user IDs or user ID need to apply user template
    * @param    bool        $isGlobal           true if you want to copy
    */
    function applyUserTemplate($userTemplateID, $userArray, $isGlobal = true) {
        
        //check if user template ID is valid
        if (!isset($userTemplateID) || $userTemplateID == '') {
            return;
        }
        
        //check if user array is id
        if (is_string($userArray)) {
            //temp var store user ID
            $userID = $userArray;
            //set userArray as array
            $userArray = array();
            $userArray[] = $userID;
        }
        
        if ($isGlobal == false) {
            $globalWhere = ' AND category!="global"';
        } else {
            $globalWhere = '';
        }
        // Category array
        $catsArray = array();
        
        // get all category and category's content relate to user template
        $catsResult = $GLOBALS['db']->query('SELECT category, contents FROM user_preferences WHERE assigned_user_id="'.$userTemplateID.'"'.$globalWhere);
        //assign new category of template user
        while ($row = $GLOBALS['db']->fetchByAssoc($catsResult)) {
            $catsArray[$row['category']] = $row['contents'];
        }
        
        
        if (!empty($userArray)) {
            foreach($userArray as $id) {
                $user = BeanFactory::getBean('Users', $id);
                
                //get calendar pulish key
                $calendarPublishKey = $user->getPreference('calendar_publish_key');
                
                //unset the reference of user that save in session for sugar reload new
                unset($_SESSION[$user->user_name . '_PREFERENCES']);
                foreach($catsArray as $cat => $contents) {
                    $userReference = new UserPreference($user);
                    //retrive exist category
                    $userReference->retrieve_by_string_fields(array(
                        'assigned_user_id' => $id,
                        'category' => $cat,
                    ));
                    $userReference->deleted = 0;
                    $userReference->category = $cat;
                    $userReference->assigned_user_id = $id;
                    
                    //check if category is global
                    if ($row['category'] == 'global' && $isGlobal == true) {
                        $globalReference = unserialize(base64_decode($contents)); //get global category array
                        $globalReference['calendar_publish_key'] = $calendarPublishKey;
                        $row['contents'] = base64_encode(serialize($globalReference)); 
                    }
                    
                    $userReference->contents = $contents;
                    $userReference->save();
                    unset($userReference);
                }
                
                // apply User Template Team to User
                self::applyUserTeam($userTemplateID, $id);
                // apply User Template Role to User
                self::applyUserRole($userTemplateID, $id);
                //unset $user and $userRef for save memory
                unset($user);
                
            }    
        } 
    }
    //END: Apply User Template to users from User Template ID
    
    /**
    * Apply team from user template to user
    * 
    * @author   Thuan Nguyen
    * @param mixed $userTemplateID      The user template ID
    * @param mixed $userID              The user that need to apply user template team
    */
    function applyUserTeam($userTemplateID, $userID) {
        
        // Get user template team IDs array
        $userTemplateObj = BeanFactory::getBean('Users', $userTemplateID);
        $userTemplateTeamIDs = $userTemplateObj->get_my_teams();
        $userTemplatePrivateTeamID = $userTemplateObj->getPrivateTeamID();
        if (isset($userTemplateTeamIDs[$userTemplatePrivateTeamID])) {
            unset($userTemplateTeamIDs[$userTemplatePrivateTeamID]);
        }
        
        //Remove all team of $userID except private team
        $userObj = BeanFactory::getBean('Users', $userID);
        $userTeams = $userObj->get_my_teams(true);
        $userPrivateTeamID = $userObj->getPrivateTeamID();
        foreach($userTeams as $team) {
            if ($team->id == $userPrivateTeamID) {
                continue;
            } else {
                $team->remove_user_from_team($userID);
            }
        }
        
        //Add teams from User Template to User
        foreach($userTemplateTeamIDs as $teamID => $teamName) {
            $team = new Team();
            $team->retrieve($teamID);
            $team->add_user_to_team($userID);
        }
    }
    // END: Apply team from user template to user  
    
    /**
    * Apply role from user template to user
    * 
    * @author   Thuan Nguyen
    * @param mixed $userTemplateID      The user template ID
    * @param mixed $userID              The user that need to apply user template team
    */
    function applyUserRole($userTemplateID, $userID) {
        // Get user template role object array
        $userTemplateObj = BeanFactory::getBean('Users', $userTemplateID);
        $userTemplateRoleObjects = self::getRolesOfUser($userTemplateObj, true);
        
        //Remove all roles of $userID 
        $userObj = BeanFactory::getBean('Users', $userID);
        $userRoles = self::getRolesOfUser($userObj, true);
        foreach($userRoles as $role) {
            self::removeUserFromRole($userObj, $role);
        }
        
        //Add roles from User Template to User
        foreach($userTemplateRoleObjects as $role) {
            self::addUserToRole($userObj, $role);
        }
    }
    // END: Apply role from user template to user 
    
    
    /**
    * Get roles of user
    * 
    * @param object $userObj        The User Object
    * @param bool $return_obj      Option return ID or Object
    * @return array     Return array of object or ID of roles relate to this user
    */
    function getRolesOfUser($userObj, $return_obj = FALSE) {
        //if we don't have it loaded then lets check against the db
        $additional_where = '';
        $query = "SELECT acl_roles.* ".
            "FROM acl_roles ".
            "INNER JOIN acl_roles_users ON acl_roles_users.user_id = '$userObj->id' ".
                "AND acl_roles_users.role_id = acl_roles.id AND acl_roles_users.deleted = 0 ".
            "WHERE acl_roles.deleted=0 ";

        $result = $GLOBALS['db']->query($query);
        $user_roles = array();

        while($row = $GLOBALS['db']->fetchByAssoc($result) ){
            $role = new ACLRole();
            $role->populateFromRow($row);
            if($return_obj)
                $user_roles[] = $role;
            else
                $user_roles[] = $role->id;       
        }

        return $user_roles;
    }
    // END: Get roles of user by user id
    
    /**
    * Remove user from role
    * 
    * @author   Thuan Nguyen
    * @param object $userObj        The User object need to be removed from Role
    * @param object $ACLRoleObj     The ACLRole Object that relate to user
    */
    function removeUserFromRole($userObj, $ACLRoleObj) {
        if (!empty($userObj->id) && !empty($ACLRoleObj->id)) {
            $sql = 'UPDATE acl_roles_users SET deleted=1 WHERE user_id="'.$userObj->id.'" AND role_id="'.$ACLRoleObj->id.'"';
            $GLOBALS['db']->query($sql);
        }
    }
    // END: Remove user from this role
    
    /**
    * Add user to role
    * 
    * @author   Thuan Nguyen
    * @param object $userObj        The User Object need to be added to Role
    * @param object $ACLRoleObj     The ACLRole Object that you want to add user
    */
    function addUserToRole($userObj, $ACLRoleObj) {
        //check if $userId not empty
        if (!empty($userObj->id) && !empty($ACLRoleObj->id)) {
            $userObj->set_relationship('acl_roles_users', array('role_id'=>$ACLRoleObj->id ,'user_id'=>$userObj->id), false);
        }
    }
    // END: Add user to role
}