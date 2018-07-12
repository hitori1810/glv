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




/**
 * This file is where the user authentication occurs. No redirection should happen in this file.
 *
 */
require_once('modules/Users/authentication/SugarAuthenticate/SugarAuthenticateUser.php');
class EmailAuthenticateUser extends SugarAuthenticateUser {
    var $passwordLength = 4;


    /**
	 * this is called when a user logs in
	 *
	 * @param STRING $name
	 * @param STRING $password
	 * @return boolean
	 */
    function loadUserOnLogin($name, $password) {

        global $login_error;

        $GLOBALS['log']->debug("Starting user load for ". $name);
        if(empty($name) || empty($password)) return false;

        if(empty($_SESSION['lastUserId'])){
            $input_hash = SugarAuthenticate::encodePassword($password);
            $user_id = $this->authenticateUser($name, $input_hash);
            if(empty($user_id)) {
                $GLOBALS['log']->fatal('SECURITY: User authentication for '.$name.' failed');
                return false;
            }
        }

        if(empty($_SESSION['emailAuthToken'])){
            $_SESSION['lastUserId'] = $user_id;
            $_SESSION['lastUserName'] = $name;
            $_SESSION['emailAuthToken'] = '';
            for($i = 0; $i < $this->passwordLength; $i++){
                $_SESSION['emailAuthToken'] .= chr(mt_rand(48,90));
            }
            $_SESSION['emailAuthToken']  =  str_replace(array('<', '>'), array('#', '@'), $_SESSION['emailAuthToken']);
            $_SESSION['login_error'] = 'Please Enter Your User Name and Emailed Session Token';
            $this->sendEmailPassword($user_id, $_SESSION['emailAuthToken']);
            return false;
        }else{
            if(strcmp($name, $_SESSION['lastUserName']) == 0 && strcmp($password, $_SESSION['emailAuthToken']) == 0){
                $this->loadUserOnSession($_SESSION['lastUserId']);
                unset($_SESSION['lastUserId']);
                unset($_SESSION['lastUserName']);
                unset($_SESSION['emailAuthToken']);
                return true;
            }

        }

         $_SESSION['login_error'] = 'Please Enter Your User Name and Emailed Session Token';
        return false;
    }


    /**
     * Sends the users password to the email address or sends
     *
     * @param unknown_type $user_id
     * @param unknown_type $password
     */
    function sendEmailPassword($user_id, $password){

	    $result = $GLOBALS['db']->query("SELECT email1, email2, first_name, last_name FROM users WHERE id='$user_id'");
	    $row = $GLOBALS['db']->fetchByAssoc($result);

	    global $sugar_config;
	    if(empty($row['email1']) && empty($row['email2'])){

	        $_SESSION['login_error'] = 'Please contact an administrator to setup up your email address associated to this account';
	       return;
	    }

	    require_once("include/SugarPHPMailer.php");
		global $locale;
        $OBCharset = $locale->getPrecedentPreference('default_email_charset');
        $notify_mail = new SugarPHPMailer();
		$notify_mail->CharSet = $sugar_config['default_charset'];
		$notify_mail->AddAddress(((!empty($row['email1']))?$row['email1']: $row['email2']),$locale->translateCharsetMIME(trim($row['first_name'] . ' ' . $row['last_name']), 'UTF-8', $OBCharset));

		if (empty($_SESSION['authenticated_user_language'])) {
			$current_language = $sugar_config['default_language'];
		}
		else {
			$current_language = $_SESSION['authenticated_user_language'];
		}
        $notify_mail->Subject = 'Sugar Token';
        $notify_mail->Body = 'Your sugar session authentication token  is: ' . $password;
        $notify_mail->setMailerForSystem();
        $notify_mail->From = 'no-reply@sugarcrm.com';
        $notify_mail->FromName = 'Sugar Authentication';

        if(!$notify_mail->Send()) {
            $GLOBALS['log']->warn("Notifications: error sending e-mail (method: {$notify_mail->Mailer}), (error: {$notify_mail->ErrorInfo})");
        }
        else {
            $GLOBALS['log']->info("Notifications: e-mail successfully sent");
        }



	}


}

?>
