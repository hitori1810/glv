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



class AuthenticationController
{
	public $loggedIn = false; //if a user has attempted to login
	public $authenticated = false;
	public $loginSuccess = false;// if a user has successfully logged in

	protected static $authcontrollerinstance = null;

    /**
     * @var SugarAuthenticate
     */
    public $authController;

	/**
	 * Creates an instance of the authentication controller and loads it
	 *
	 * @param STRING $type - the authentication Controller
	 * @return AuthenticationController -
	 */
	public function __construct($type = null)
	{
        $this->authController = $this->getAuthController($type);
	}

    /**
     * Get auth controller object
     * @param string $type 
     * @return SugarAuthenticate
     */
    protected function getAuthController($type)
    {
        if (!$type) {
            $type = !empty($GLOBALS['sugar_config']['authenticationClass'])
                ? $GLOBALS['sugar_config']['authenticationClass'] : 'SugarAuthenticate';
        }

        if ($type == 'SugarAuthenticate' && !empty($GLOBALS['system_config']->settings['system_ldap_enabled']) && empty($_SESSION['sugar_user'])) {
            $type = 'LDAPAuthenticate';
        }

        // check in custom dir first, in case someone want's to override an auth controller
        if(!SugarAutoLoader::requireWithCustom('modules/Users/authentication/'.$type.'/' . $type . '.php')) {
            require_once('modules/Users/authentication/SugarAuthenticate/SugarAuthenticate.php');
            $type = 'SugarAuthenticate';
        }

        if (!empty($_REQUEST['no_saml']) 
            && (is_subclass_of($type, 'SAMLAuthenticate') || 'SAMLAuthenticate' == $type)) {
            $type = 'SugarAuthenticate';
        }

        return new $type();
    }

	/**
	 * Returns an instance of the authentication controller
	 *
	 * @param string $type this is the type of authetnication you want to use default is SugarAuthenticate
	 * @return an instance of the authetnciation controller
	 */
	public static function getInstance($type = null)
	{
		if (empty(self::$authcontrollerinstance)) {
			self::$authcontrollerinstance = new AuthenticationController($type);
		}

		return self::$authcontrollerinstance;
	}

	/**
	 * This function is called when a user initially tries to login.
	 *
	 * @param string $username
	 * @param string $password
	 * @param array $PARAMS
	 * @return boolean true if the user successfully logs in or false otherwise.
	 */
	public function login($username, $password, $PARAMS = array())
	{
		//kbrill bug #13225
		$_SESSION['loginAttempts'] = (isset($_SESSION['loginAttempts']))? $_SESSION['loginAttempts'] + 1: 1;
		unset($GLOBALS['login_error']);

		if($this->loggedIn)return $this->loginSuccess;
		LogicHook::initialize()->call_custom_logic('Users', 'before_login');

		$this->loginSuccess = $this->authController->loginAuthenticate($username, $password, false, $PARAMS);
		$this->loggedIn = true;

		if($this->loginSuccess){
			//Ensure the user is authorized
			checkAuthUserStatus();

			loginLicense();
			if(!empty($GLOBALS['login_error'])){
				unset($_SESSION['authenticated_user_id']);
				$GLOBALS['log']->fatal('FAILED LOGIN: potential hack attempt:'.$GLOBALS['login_error']);
				$this->loginSuccess = false;
				return false;
			}

			//call business logic hook
			if(isset($GLOBALS['current_user']))
				$GLOBALS['current_user']->call_custom_logic('after_login');

			// Check for running Admin Wizard
			$config = new Administration();
			$config->retrieveSettings();
		    if ( is_admin($GLOBALS['current_user']) && empty($config->settings['system_adminwizard']) && isset($_REQUEST['action']) && $_REQUEST['action'] != 'AdminWizard' ) {

                if ( isset($PARAMS['noRedirect']) && $PARAMS['noRedirect'] == true ) {
                    $this->nextStep = array('module'=>'Configurator','action'=>'AdminWizard');
                } else {
                    ob_clean();
                    $GLOBALS['module'] = 'Configurator';
                    $GLOBALS['action'] = 'AdminWizard';
                    header("Location: index.php?module=Configurator&action=AdminWizard");
                    sugar_cleanup(true);
                }
			}

			$ut = $GLOBALS['current_user']->getPreference('ut');
			$checkTimeZone = true;
			if (is_array($PARAMS) && !empty($PARAMS) && isset($PARAMS['passwordEncrypted'])) {
				$checkTimeZone = false;
			} // if
			if(empty($ut) && $checkTimeZone && isset($_REQUEST['action']) && $_REQUEST['action'] != 'SetTimezone' && $_REQUEST['action'] != 'SaveTimezone' ) {
                if ( isset($PARAMS['noRedirect']) && $PARAMS['noRedirect'] == true && empty($this->nextStep) ) {
                    $this->nextStep = array('module'=>'Users','action'=>'Wizard');
                } else {
                    $GLOBALS['module'] = 'Users';
                    $GLOBALS['action'] = 'Wizard';
                    ob_clean();
                    header("Location: index.php?module=Users&action=Wizard");
                    sugar_cleanup(true);
                }
			}
		}else{
			//kbrill bug #13225
			LogicHook::initialize();
			$GLOBALS['logic_hook']->call_custom_logic('Users', 'login_failed');
			$GLOBALS['log']->fatal('FAILED LOGIN:attempts[' .$_SESSION['loginAttempts'] .'] - '. $username);
		}
		// if password has expired, set a session variable

		return $this->loginSuccess;
	}

	/**
	 * This is called on every page hit.
	 * It returns true if the current session is authenticated or false otherwise
	 *
	 * @return booelan
	 */
	public function sessionAuthenticate()
	{
		if(!$this->authenticated){
			$this->authenticated = $this->authController->sessionAuthenticate();
		}
		if($this->authenticated){
			if(!isset($_SESSION['userStats']['pages'])){
			    $_SESSION['userStats']['loginTime'] = time();
			    $_SESSION['userStats']['pages'] = 0;
			}
			$_SESSION['userStats']['lastTime'] = time();
			$_SESSION['userStats']['pages']++;

		}
		return $this->authenticated;
	}

	/**
	 * Called when a user requests to logout. Should invalidate the session and redirect
	 * to the login page.
	 */
	public function logout()
	{
		$GLOBALS['current_user']->call_custom_logic('before_logout');
		$this->authController->logout();
		LogicHook::initialize();
		$GLOBALS['logic_hook']->call_custom_logic('Users', 'after_logout');
	}
}
