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

/*********************************************************************************

 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/


 function canSendPassword() {
 	require_once('include/SugarPHPMailer.php');
    global $mod_strings;
	global $current_user;
	global $app_strings;
	$mail = new SugarPHPMailer();
 	$emailTemp = new EmailTemplate();
 	$mail->setMailerForSystem();
    $emailTemp->disable_row_level_security = true;


    if ($current_user->is_admin){
    	if ($emailTemp->retrieve($GLOBALS['sugar_config']['passwordsetting']['generatepasswordtmpl']) == '')
        	return $mod_strings['LBL_EMAIL_TEMPLATE_MISSING'];
    	if(empty($emailTemp->body) && empty($emailTemp->body_html))
    		return $app_strings['LBL_EMAIL_TEMPLATE_EDIT_PLAIN_TEXT'];
    	if($mail->Mailer == 'smtp' && $mail->Host =='')
    		return $mod_strings['ERR_SERVER_SMTP_EMPTY'];

		$email_errors=$mod_strings['ERR_EMAIL_NOT_SENT_ADMIN'];
		if ($mail->Mailer == 'smtp')
			$email_errors.="<br>-".$mod_strings['ERR_SMTP_URL_SMTP_PORT'];
		if ($mail->SMTPAuth)
		 	$email_errors.="<br>-".$mod_strings['ERR_SMTP_USERNAME_SMTP_PASSWORD'];
		$email_errors.="<br>-".$mod_strings['ERR_RECIPIENT_EMAIL'];
		$email_errors.="<br>-".$mod_strings['ERR_SERVER_STATUS'];
		return $email_errors;
	}
	else
		return $mod_strings['LBL_EMAIL_NOT_SENT'];
}

function  hasPasswordExpired($username){
    global $current_user;
    if ($current_user->user_name === $username) {
        $user = $current_user;
    } else {
        $user = BeanFactory::getBean('Users')->retrieve_by_string_fields(array('user_name'=>$username));
    }
    $type = '';
	if ($user->system_generated_password == '1'){
        $type='syst';
    }
    else{
        $type='user';
    }

    if (!$user->portal_only){
	    global $mod_strings, $timedate;
	    $res=$GLOBALS['sugar_config']['passwordsetting'];
	  	if ($type != '') {
		    switch($res[$type.'expiration']){

	        case '1':
		    	global $timedate;
                if (empty($user->pwd_last_changed)) {
		    		$user->pwd_last_changed= $timedate->nowDb();
		    		$user->save();
		    		}

		        $expireday = $res[$type.'expirationtype']*$res[$type.'expirationtime'];
		        $expiretime = $timedate->fromUser($user->pwd_last_changed)->get("+{$expireday} days")->ts;

			    if ($timedate->getNow()->ts < $expiretime)
			    	return false;
			    else{
			    	$_SESSION['expiration_type']= $mod_strings['LBL_PASSWORD_EXPIRATION_TIME'];
			    	return true;
			    	}
				break;


		    case '2':
		    	$login=$user->getPreference('loginexpiration');
		    	$user->setPreference('loginexpiration',$login+1);
		        $user->save();
		        if ($login+1 >= $res[$type.'expirationlogin']){
		        	$_SESSION['expiration_type']= $mod_strings['LBL_PASSWORD_EXPIRATION_LOGIN'];
		        	return true;
		        }
		        else
		            {
			    	return false;
			    	}
		    	break;

		    case '0':
		        return false;
		   	 	break;
		    }
		}
    }
}
