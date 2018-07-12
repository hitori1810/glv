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



$modListHeader = array();
require_once('modules/Reports/SavedReport.php');
require_once('modules/Reports/schedule/ReportSchedule.php');
require_once('modules/Reports/templates/templates_pdf.php');
require_once('include/SugarPHPMailer.php');
require_once('include/modules.php');
require_once('config.php');

/** @var Localization $locale */
global $sugar_config, $current_language, $app_list_strings, $app_strings, $locale;
$language = $sugar_config['default_language'];//here we'd better use English, because pdf coding problem.

$app_list_strings = return_app_list_strings_language($language);
$app_strings = return_application_language($language);

$report_schedule = new ReportSchedule();
$reports_to_email = $report_schedule->get_reports_to_email();

//Process Enterprise Schedule reports via CSV
//bug: 23934 - enable Advanced reports
require_once('modules/ReportMaker/process_scheduled.php');



global $report_modules,$modListHeader,$current_user;

foreach($reports_to_email as $schedule_info)
{
	$GLOBALS['log']->debug('-----> in Reports foreach() loop');
	
	$user = new User();
	$user->retrieve($schedule_info['user_id']);
	
	$current_user = $user;
	
	$modListHeader = query_module_access_list($current_user);
	$report_modules = getAllowedReportModules($modListHeader);

	if(empty($user->email1)) {
		if(empty($user->email2)) {
			$address = '';
		} else {
			$address = $user->email2;
		}
	} else {
		$address = $user->email1;
	}

    $name = $locale->formatName($user);

	$theme = $sugar_config['default_theme'];
	$saved_report = new SavedReport();
	$saved_report->retrieve($schedule_info['report_id']);
	
	
	$GLOBALS['log']->debug('-----> Generating Reporter');
	$reporter = new Report(html_entity_decode($saved_report->content));
	
    $mod_strings = return_module_language($current_language, 'Reports');

    // prevent invalid report from being processed
    if (!$reporter->is_definition_valid())
    {
        $invalid_fields = $reporter->get_invalid_fields();

        $message = string_format($mod_strings['ERR_REPORT_INVALID'], array(
            $schedule_info['report_id'], implode(', ', $invalid_fields)
        ));

        $GLOBALS['log']->fatal('-----> ' . $message);

        require_once 'modules/Reports/utils.php';
        notify_of_invalid_report($saved_report, $schedule_info['report_id'], $reporter);
        continue;
    }

	$GLOBALS['log']->debug('-----> Reporter settings attributes');
	$reporter->layout_manager->setAttribute("no_sort",1);
	$module_for_lang = $reporter->module;

	$GLOBALS['log']->debug('-----> Reporter Handling PDF output');
	$report_filename = template_handle_pdf($reporter, false);

	$GLOBALS['log']->debug('-----> Generating SugarPHPMailer');
	$mail = new SugarPHPMailer();
    global $locale;
    $OBCharset = $locale->getPrecedentPreference('default_email_charset');
	
	$mail->AddAddress($address, $locale->translateCharsetMIME(trim($name), 'UTF-8', $OBCharset));

	$admin = new Administration();
	$admin->retrieveSettings();
	
	if($admin->settings['mail_sendtype'] == "SMTP") 
	{
    	$mail->Mailer = "smtp";
    	$mail->Host = $admin->settings['mail_smtpserver'];
    	$mail->Port = $admin->settings['mail_smtpport'];
    
    	if($admin->settings['mail_smtpauth_req']) {
    		$mail->SMTPAuth = TRUE;
    		$mail->Username = $admin->settings['mail_smtpuser'];
    		$mail->Password = $admin->settings['mail_smtppass'];
    	}
    	if ($admin->settings['mail_smtpssl'] == 1) {
                $mail->SMTPSecure = 'ssl';
    	}
    	else if ($admin->settings['mail_smtpssl'] == 2) {
                $mail->SMTPSecure = 'tls';
    	}
	}
	else 
		$mail->Mailer = 'sendmail';
	
	$mail->From = $admin->settings['notify_fromaddress'];
	$mail->FromName = empty($admin->settings['notify_fromname']) ? ' ' : $admin->settings['notify_fromname'];
	$mail->Subject = empty($saved_report->name) ? 'Report' : $saved_report->name;
	$cr = array("\r", "\n");
	$attachment_name = str_replace(' ', '_', str_replace($cr,'',$mail->Subject).'.pdf');
	$mail->AddAttachment($report_filename, $locale->translateCharsetMIME(trim($attachment_name), 'UTF-8', $OBCharset), 'base64', 'application/pdf');

	$body = $mod_strings['LBL_HELLO'];
	if($name != '') {
		$body .= " $name";
	}
	$body .= ",\n\n";
	$body .= 	$mod_strings['LBL_SCHEDULED_REPORT_MSG_INTRO']. $saved_report->date_entered . $mod_strings['LBL_SCHEDULED_REPORT_MSG_BODY1']
				 . $saved_report->name . $mod_strings['LBL_SCHEDULED_REPORT_MSG_BODY2'];
	$mail->Body = $body;

	if($address == '') {
		$GLOBALS['log']->info("No email address for $name");
	} else {
		$GLOBALS['log']->debug('-----> Sending PDF via Email to [ '.$address.' ]');
		
	$mail->prepForOutbound();
		
		if($mail->Send()) {
			$GLOBALS['log']->debug('-----> Send successful');
			$report_schedule->update_next_run_time($schedule_info['id'], $schedule_info['next_run'], $schedule_info['time_interval']);
		} else {
			$GLOBALS['log']->fatal("Mail error: $mail->ErrorInfo");
		}
	}
	$GLOBALS['log']->debug('-----> Removing temporary PDF file');
	unlink($report_filename);
}
sugar_cleanup(false); // continue script execution so that if run from Scheduler, job status will be set back to "Active"
?>
