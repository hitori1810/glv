<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * By installing or using this file, you are confirming on behalf of the entity
 * subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
 * the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
 * http://www.sugarcrm.com/master-subscription-agreement
 *
 * If Company is not bound by the MSA, then by installing or using this file
 * you are agreeing unconditionally that Company will be bound by the MSA and
 * certifying that you have authority to bind Company accordingly.
 *
 * Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
 ********************************************************************************/


require_once('data/BeanFactory.php');
require_once('include/SugarFields/SugarFieldHandler.php');
require_once('include/MetaDataManager/MetaDataManager.php');
require_once('include/api/SugarApi.php');

class CurrentUserApi extends SugarApi {
    public function registerApiRest() {
        return array(
            'retrieve' => array(
                'reqType' => 'GET',
                'path' => array('me',),
                'pathVars' => array(),
                'method' => 'retrieveCurrentUser',
                'shortHelp' => 'Returns current user',
                'longHelp' => 'include/api/help/me.html',
            ),
            'update' => array(
                'reqType' => 'PUT',
                'path' => array('me',),
                'pathVars' => array(),
                'method' => 'updateCurrentUser',
                'shortHelp' => 'Updates current user',
                'longHelp' => 'include/api/help/me.html',
            ),
            'updatePassword' =>  array(
                'reqType' => 'PUT',
                'path' => array('me','password'),
                'pathVars'=> array(''),
                'method' => 'updatePassword',
                'shortHelp' => "Updates current user's password",
                'longHelp' => 'include/api/help/change_password.html',
            ),
            'verifyPassword' =>  array(
                'reqType' => 'POST',
                'path' => array('me','password'),
                'pathVars'=> array(''),
                'method' => 'verifyPassword',
                'shortHelp' => "Verifies current user's password",
                'longHelp' => 'include/api/help/verify_password.html',
            ),

            'userPreferences' =>  array(
                'reqType' => 'GET',
                'path' => array('me','preferences'),
                'pathVars'=> array(),
                'method' => 'userPreferences',
                'shortHelp' => "Returns all the current user's stored preferences",
                'longHelp' => 'include/api/help/user_preferences.html',
            ),

            'userPreferencesSave' =>  array(
                'reqType' => 'PUT',
                'path' => array('me','preferences'),
                'pathVars'=> array(),
                'method' => 'userPreferencesSave',
                'shortHelp' => "Mass Save Updated Preferences For a User",
                'longHelp' => 'include/api/help/user_preferences.html',
            ),

            'userPreference' =>  array(
                'reqType' => 'GET',
                'path' => array('me','preference', '?'),
                'pathVars'=> array('', '', 'preference_name'),
                'method' => 'userPreference',
                'shortHelp' => "Returns a specific preference for the current user",
                'longHelp' => 'include/api/help/user_preferences.html',
            ),

            'userPreferenceCreate' =>  array(
                'reqType' => 'POST',
                'path' => array('me','preference', '?'),
                'pathVars'=> array('', '', 'preference_name'),
                'method' => 'userPreferenceSave',
                'shortHelp' => "Create a preference for the current user",
                'longHelp' => 'include/api/help/user_preferences.html',
            ),
            'userPreferenceUpdate' =>  array(
                'reqType' => 'PUT',
                'path' => array('me','preference', '?'),
                'pathVars'=> array('', '', 'preference_name'),
                'method' => 'userPreferenceSave',
                'shortHelp' => "Update a specific preference for the current user",
                'longHelp' => 'include/api/help/user_preferences.html',
            ),
            'userPreferenceDelete' =>  array(
                'reqType' => 'DELETE',
                'path' => array('me','preference', '?'),
                'pathVars'=> array('', '', 'preference_name'),
                'method' => 'userPreferenceDelete',
                'shortHelp' => "Delete a specific preference for the current user",
                'longHelp' => 'include/api/help/user_preferences.html',
            ),
        );
    }

    /**
     * Retrieves the current user info
     *
     * @param $api
     * @param $args
     * @return array
     */
    public function retrieveCurrentUser($api, $args) {

        $current_user = $this->getUserBean();
        
        // Get the basics
        $user_data = $this->getBasicUserInfo();

        if ( isset($args['platform']) ) {
            $platform = array(basename($args['platform']),'base');
        } else {
            $platform = array('base');
        }
        // Fill in the rest
        $user_data['type'] = 'user';
        $user_data['id'] = $current_user->id;
        $current_user->_create_proper_name_field();
        $user_data['full_name'] = $current_user->full_name;
        $user_data['user_name'] = $current_user->user_name;
        $user_data['acl'] = $this->getAcls($platform);
        require_once('modules/Teams/TeamSetManager.php');

        $teams = $current_user->get_my_teams();
        $my_teams = array();
        foreach($teams AS $id => $name) {
            $my_teams[] = array("id" => $id, "name" => $name,);
        }
        $user_data['my_teams'] = $my_teams;

        $defaultTeams = TeamSetManager::getTeamsFromSet($current_user->team_set_id);
        foreach($defaultTeams AS $id => $team) {
            $defaultTeams[$id]['primary'] = false;
            if($team['id'] == $current_user->team_id) {
                $defaultTeams[$id]['primary'] = true;
            }
        }
        $user_data['preferences']['default_teams'] = $defaultTeams;


        return array('current_user' => $user_data);
    }
    
    /**
     * Updates current user info
     *
     * @param $api
     * @param $args
     * @return array
     */
    public function updateCurrentUser($api, $args) {
        $bean = $this->getUserBean();

        // setting these for the loadBean
        $args['module'] = $bean->module_name;
        $args['record'] = $bean->id;

        $id = $this->updateBean($bean, $api, $args);

        return $this->retrieveCurrentUser($api, $args);
    }

    /**
     * Updates the current user's password
     *
     * @param $api
     * @param $args
     * @return array
     * @throws SugarApiExceptionMissingParameter|SugarApiExceptionNotFound
     */
    public function updatePassword($api, $args) {
        $user_data['valid'] = false;
        
        // Deals with missing required args else assigns oldpass and new paswords
        if (empty($args['old_password']) || empty($args['new_password'])) {
            // @TODO Localize this exception message
            throw new SugarApiExceptionMissingParameter('Error: Missing argument.');
        } else {
            $oldpass = $args['old_password'];
            $newpass = $args['new_password'];
        }
        
        $bean = $this->getUserIfPassword($oldpass);
        if (null !== $bean) {
            $change = $this->changePassword($bean, $oldpass, $newpass);
            if (!$change) {
                $user_data['message'] = 'Error: There was a problem updating password for this user.';
            } else {
                $user_data = array_merge($user_data, $change);
            }
        } else {
            $user_data['message'] = 'Error: Incorrect password.'; 
        }
        
        return $user_data;
    }

    /**
     * Verifies against the current user's password
     *
     * @param $api
     * @param $args
     * @return array
     */
    public function verifyPassword($api, $args) {
        $user_data['valid'] = false;
        
        // Deals with missing required args else assigns oldpass and new paswords
        if (empty($args['password_to_verify'])) {
            // @TODO Localize this exception message
            throw new SugarApiExceptionMissingParameter('Error: Missing argument.');
        }
        
        // If the user password is good, send that messaging back
        if (!is_null($this->getUserIfPassword($args['password_to_verify']))) {
            $user_data['valid'] = true;
            $user_data['message'] = 'Password verified.'; 
            $user_data['expiration'] = $this->getUserLoginExpirationPreference();
        }
        return $user_data;
    }

    protected function getMetadataManager( $platform = 'base', $public = false) {
        $current_user = $this->getUserBean();
        return new MetaDataManager($current_user, $platform, $public);
    }

    /**
     * Gets acls given full module list passed in.
     * @param string The platform e.g. portal, mobile, base, etc.
     * @return array
     */  
    public function getAcls($platform) {
        // in this case we should always have current_user be the user
        global $current_user;        
        $mm = $this->getMetadataManager($platform);
        $fullModuleList = array_keys($GLOBALS['app_list_strings']['moduleList']);
        $acls = array();
        foreach ($fullModuleList as $modName) {
            $bean = BeanFactory::newBean($modName);
            if (!$bean || !is_a($bean,'SugarBean') ) {
                // There is no bean, we can't get data on this
                continue;
            }


            $acls[$modName] = $mm->getAclForModule($modName,$current_user);
            $acls[$modName] = $this->verifyACLs($acls[$modName]);
        }
        // Handle enforcement of acls for clients that override this (e.g. portal)
        $acls = $this->enforceModuleACLs($acls);

        return $acls;
    }

    /**
     * Manipulates the ACLs as needed, per client
     * 
     * @param array $acls
     * @return array
     */
    protected function verifyACLs(Array $acls) {
        // No manipulation for base acls
        return $acls;
    }

    /**
     * Enforces module specific ACLs for users without accounts, as needed
     * 
     * @param array $acls
     * @return array
     */
    protected function enforceModuleACLs(Array $acls) {
        // No manipulation for base acls
        return $acls;
    }

    /**
     * Checks a given password and sends back the user bean if the password matches
     * 
     * @param string $passwordToVerify
     * @return User
     */
    protected function getUserIfPassword($passwordToVerify) {
        $user = BeanFactory::getBean('Users', $GLOBALS['current_user']->id); 
        $currentPassword = $user->user_hash;
        if (User::checkPassword($passwordToVerify, $currentPassword)) {
            return $user;
        }
        
        return null;
    }

    /**
     * Gets the basic user data that all users that are logged in will need. Client
     * specific user information will be filled in within the client API class.
     * 
     * @return array
     */
    protected function getBasicUserInfo() {
        global $current_user;
        global $locale;

        $user_data = array(
            'preferences' => array(
                'timezone' => $current_user->getPreference('timezone'),
                'datepref' => $current_user->getPreference('datef'),
                'timepref' => $current_user->getPreference('timef'),
            ),
        );

        // FIXME getPreference is already calling the system defaults but not for timezone
        if (!isset($user_data['preferences']['timezone'])) {
            $user_data['preferences']['timezone'] = 'GMT';
        }

        // user currency prefs
        $currency = BeanFactory::getBean('Currencies');
        $currency_id = $current_user->getPreference('currency');
        $currency->retrieve($currency_id);
        $user_data['preferences']['currency_id'] = $currency->id;
        $user_data['preferences']['currency_name'] = $currency->name;
        $user_data['preferences']['currency_symbol'] = $currency->symbol;
        $user_data['preferences']['currency_iso'] = $currency->iso4217;
        $user_data['preferences']['currency_rate'] = $currency->conversion_rate;
        // user number formatting prefs
        $user_data['preferences']['decimal_precision'] = $locale->getPrecision();
        $user_data['preferences']['decimal_separator'] = $locale->getDecimalSeparator();
        $user_data['preferences']['number_grouping_separator'] = $locale->getNumberGroupingSeparator();
        $user_data['module_list'] = $this->getModuleList();

        // use their current auth language if it exists
        if(!empty($_SESSION['authenticated_user_language'])) {
            $language = $_SESSION['authenticated_user_language'];
        }
        // if current auth language doesn't exist get their preferred lang from the user obj
        elseif(!empty($current_user->preferred_language)) {
            $language = $current_user->preferred_language;
        }
        // if nothing exists, get the sugar_config default language
        else {
            $language = $GLOBALS['sugar_config']['default_language'];
        }

        $user_data['preferences']['language'] = $language;


        return $user_data;
    }

    /**
     * Gets the user bean for the user of the api
     * 
     * @return User
     */
    protected function getUserBean() {
        global $current_user;
        return $current_user;
    }

    /**
     * Changes a password for a user from old to new
     * 
     * @param User $bean User bean
     * @param string $old Old password 
     * @param string $new New password
     * @return array
     */
    protected function changePassword($bean, $old, $new) {
        if ($bean->change_password($old, $new)) {
            return array(
                'valid' => true,
                'message' => 'Password updated.',
                'expiration' => $bean->getPreference('loginexpiration'),
            );
        }
        
        return array();
    }
    
    /**
     * Gets the preference for user login expiration
     * 
     * @return string
     */
    protected function getUserLoginExpirationPreference() {
        global $current_user;
        return $current_user->getPreference('loginexpiration');
    }

    /**
     * Return all the current users preferences
     *
     * @param RestService $api          Api Service
     * @param array $args               Array of arguments from the rest call
     * @return mixed                    User Preferences, if the category exists.  If it doesn't then return an empty array
     */
    public function userPreferences($api, $args) {
        $current_user = $this->getUserBean();

        $category = 'global';
        if(isset($args['category'])) {
            $category = $args['category'];
        }

        $prefs = (isset($current_user->user_preferences[$category])) ?
                        $current_user->user_preferences[$category] :
                        array();

        return $prefs;
    }
    /**
     * Update multiple user preferences at once
     *
     * @param RestService $api          Api Service
     * @param array $args               Array of arguments from the rest call
     * @return mixed                    Return the updated keys with their values
     */
    public function userPreferencesSave($api, $args) {
        $current_user = $this->getUserBean();

        $category = 'global';
        if(isset($args['category'])) {
            $category = $args['category'];
            unset($args['category']);
        }

        // set each of the args in the array
        foreach($args as $key => $value) {
            $current_user->setPreference($key, $value, 0, $category);
        }

        // save the preferences to the db
        $current_user->savePreferencesToDB();

        return $args;
    }

    /**
     * Return a specific preference for the key that was passed in.
     *
     * @param RestService $api
     * @param array $args
     * @return mixed
     * @return mixed
     */
    public function userPreference($api, $args) {
        $current_user = $this->getUserBean();

        $category = 'global';
        if(isset($args['category'])) {
            $category = $args['category'];
        }

        $pref = $current_user->getPreference($args['preference_name'], $category);

        return (!is_null($pref)) ? $pref : "";
    }

    /**
     * Update a preference.  The key is part of the url and the value comes from the value $args variable
     *
     * @param RestService $api
     * @param array $args
     * @return array
     */
    public function userPreferenceSave($api, $args) {
        $current_user = $this->getUserBean();

        $category = 'global';
        if(isset($args['category'])) {
            $category = $args['category'];
        }

        $current_user->setPreference($args['preference_name'], $args['value'], 0, $category);
        $current_user->savePreferencesToDB();

        return array($args['preference_name'] => $args['value']);
    }

    /**
     * Delete a preference.  Since there is no way to actually delete with out resetting the whole category, we just
     * set the value of the key = null.
     *
     * @param RestService $api
     * @param array $args
     * @return mixed
     */
    public function userPreferenceDelete($api, $args) {
        $current_user = $this->getUserBean();

        $category = 'global';
        if(isset($args['category'])) {
            $category = $args['category'];
        }

        $current_user->setPreference($args['preference_name'], null, 0, $category);
        $current_user->savePreferencesToDB();

        return $args['preference_name'];
    }

    /**
     * Gets display module list per user defined tabs
     * @return array
     */
    public function getModuleList() {
        $current_user = $this->getUserBean();
        // Loading a standard module list
        require_once("modules/MySettings/TabController.php");
        $controller = new TabController();
        $moduleList = $this->list2Array($controller->get_user_tabs($current_user));
        // always add back in employees see Bug58563
        if (!in_array('Employees',$moduleList)) {
            $moduleList[] = 'Employees';
        }
        return $moduleList;
    }
    /**
     * Filters a list of modules against the display modules
     * @param $moduleList
     * @return array
     */
    protected function filterDisplayModules($moduleList)
    {
        $current_user = $this->getUserBean();
        // Loading a standard module list
        require_once("modules/MySettings/TabController.php");
        $controller = new TabController();
        $ret = array_intersect_key($controller->get_user_tabs($current_user), $moduleList);
        return $this->list2Array($ret);

    }

    /**
     * converts hash into flat array preserving order
     * @param $ret
     * @return array
     */
    public function list2Array($ret) {
        $output = array();
        foreach($ret as $mod => $lbl)
        {
            $output[] = $mod;
        }
        return $output;
    }
}
