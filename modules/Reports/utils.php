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
 * Notify report owner of invalid report definition
 *
 * @param SavedReport $saved_report
 * @param int $report_id
 * @param Report $report
 */
function notify_of_invalid_report($saved_report, $report_id, $report)
{
    $report_owner = new User;
    $report_owner->retrieve($saved_report->assigned_user_id);

    $emails = array($report_owner->email1, $report_owner->email2);
    $emails = array_filter($emails);

    // report owner has no email address
    if (0 == count($emails))
    {
        return;
    }

    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();

    require_once('include/SugarPHPMailer.php');
    $mailer = new SugarPHPMailer();
    $mailer->setMailerForSystem();
    $mailer->From = $defaults['email'];
    $mailer->FromName = $defaults['name'];

    // retrieve first non-empty email address
    $email = array_shift($emails);
    $mailer->AddAddress($email);

    global $current_language;
    $mod_strings = return_module_language($current_language, 'Reports');
    $mailer->Subject = $mod_strings['ERR_REPORT_INVALID_SUBJECT'];

    $invalid_fields = $report->get_invalid_fields();
    $mailer->Body = string_format($mod_strings['ERR_REPORT_INVALID'], array(
        $report_id, implode(', ', $invalid_fields)
    ));

    $mailer->prepForOutbound();
    $mailer->Send();
}
