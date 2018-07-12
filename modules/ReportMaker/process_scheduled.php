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




//require_once('modules/Reports/ReportBug.php');
//require_once('modules/Reports/ReportOpportunity.php');
require_once('modules/Reports/schedule/ReportSchedule.php');
require_once('modules/Reports/templates/templates_pdf.php');
require_once("include/phpmailer/class.phpmailer.php");

$report_schedule = new ReportSchedule();

global $sugar_config;
$language = $sugar_config['default_language'];

$app_list_strings = return_app_list_strings_language($language);
$app_strings = return_application_language($language);
// retrieve the user

//Process Enterprise Schedule reports via CSV
$reports_to_email_ent = $report_schedule->get_ent_reports_to_email("", "ent");

global $report_modules,$modListHeader;
global $locale;

foreach($reports_to_email_ent as $schedule_id => $schedule_info)
{
	$user = new User();
	$user->retrieve($schedule_info['user_id']);
	$current_user =$user;
	$modListHeader = query_module_access_list($current_user);
	$report_modules = getAllowedReportModules($modListHeader);

	if(empty($user->email1))
	{
		if(empty($user->email2)){
			$address = '';
		} else {
			$address = $user->email2;
		}
	} else {
		$address = $user->email1;
	}
    $name = $locale->formatName($user);

//Aquire the enterprise report to be sent				
	$report_object = new ReportMaker();			
	$report_object->retrieve($schedule_info['report_id']);			
	$mod_strings = return_module_language($language, 'Reports');


//Process data sets into CSV files

	//loop through data sets;
	$data_set_array = $report_object->get_data_sets();
	$temp_file_array = array();
	foreach($data_set_array as $key =>$data_set_object){

		$csv_output = $data_set_object->export_csv();

		$filenamestamp = '';
		$filenamestamp .= $data_set_object->name.'_'.$user->user_name;
		$filenamestamp .= '_'.date(translate('LBL_CSV_TIMESTAMP', 'Reports'), time());

		$filename = str_replace(' ', '_', $report_object->name. $filenamestamp.  ".csv");
		$fp = sugar_fopen(sugar_cached('csv/').$filename,'w');
		fwrite($fp, $csv_output);
		fclose($fp);

		$temp_file_array[$filename] = $filename;

	}

	$mail = new PHPMailer();
	$OBCharset = $locale->getPrecedentPreference('default_email_charset');
	$mail->AddAddress($address, $locale->translateCharsetMIME(trim($name), 'UTF-8', $OBCharset));

	$admin = new Administration();
	$admin->retrieveSettings();

	if ($admin->settings['mail_sendtype'] == "SMTP")
	{
		$mail->Mailer = "smtp";
		$mail->Host = $admin->settings['mail_smtpserver'];
		$mail->Port = $admin->settings['mail_smtpport'];

		if ($admin->settings['mail_smtpauth_req'])
		{
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


	$mail->From = $admin->settings['notify_fromaddress'];
	$mail->FromName = empty($admin->settings['notify_fromname']) ?
							' ' : $admin->settings['notify_fromname'];
	$mail->Subject = empty($report_object->name) ? 'Report' : $report_object->name;

	$temp_count = 0;
	foreach($temp_file_array as $filename){
		$file_path = sugar_cached('csv/').$filename;
		$attachment_name = $mail->Subject . '_'.$temp_count.'.csv';
		$mail->AddAttachment($file_path, $attachment_name, 'base64', 'application/csv');
		$temp_count ++;
	//end foreach loop
	}

	$body = $mod_strings['LBL_HELLO'];
	if($name != '') {
		$body .= " $name";
	}
	$body .= ",\n\n";
	$body .= 	$mod_strings['LBL_SCHEDULED_REPORT_MSG_INTRO']. $report_object->date_entered . $mod_strings['LBL_SCHEDULED_REPORT_MSG_BODY1']
				 . $report_object->name . $mod_strings['LBL_SCHEDULED_REPORT_MSG_BODY2'];
	$mail->Body = $body;

	if($address == '')
	{
		$GLOBALS['log']->info("No email address for $name");
	}
	else
	{
		if($mail->Send())
		{
			$report_schedule->update_next_run_time($schedule_info['id'],
										$schedule_info['next_run'],
										$schedule_info['time_interval']);
		}
		else
		{
			$GLOBALS['log']->error("Mail error: $mail->ErrorInfo");
		}
	}

	//need unlink for loop
	foreach($temp_file_array as $filename){
		//only un rem if we need to remove cvs and we can't just stream it
		$file_path = sugar_cached('csv/').$filename;
		unlink($file_path);
	//end foreach temp_file_array
	}
}


?>
