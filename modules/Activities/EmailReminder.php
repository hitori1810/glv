<?php
if ( !defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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

 
require_once("modules/Meetings/Meeting.php");
require_once("modules/Calls/Call.php");
require_once("modules/Users/User.php");
require_once("modules/Contacts/Contact.php");
require_once("modules/Leads/Lead.php");

/**
 * Class for sending email reminders of meetings and call to invitees
 * 
 */
class EmailReminder
{
    
    /**
     * string db datetime of now
     */
    protected $now;
    
    /**
     * string db datetime will be fetched till
     */
    protected $max;
    
    /**
     * constructor
     */
    public function __construct()
    {
        $max_time = 0;
        if(isset($GLOBALS['app_list_strings']['reminder_time_options'])){
            foreach($GLOBALS['app_list_strings']['reminder_time_options'] as $seconds => $value ) {
                if ( $seconds > $max_time ) {
                    $max_time = $seconds;
                }
            }
        }else{
            $max_time = 8400;
        }
        $this->now = $GLOBALS['timedate']->nowDb();
        $this->max = $GLOBALS['timedate']->getNow()->modify("+{$max_time} seconds")->asDb();
    }
    
    /**
     * main method that runs reminding process
     * @return boolean
     */
    public function process()
    {

        $admin = new Administration();
        $admin->retrieveSettings();
        
        $meetings = $this->getMeetingsForRemind();
        foreach($meetings as $id ) {
            $recipients = $this->getRecipients($id,'Meetings');
            $bean = new Meeting();
            $bean->retrieve($id);
            if ( $this->sendReminders($bean, $admin, $recipients) ) {
                $bean->email_reminder_sent = 1;
                $bean->save();
            }            
        }
        
        $calls = $this->getCallsForRemind();
        foreach($calls as $id ) {
            $recipients = $this->getRecipients($id,'Calls');
            $bean = new Call();
            $bean->retrieve($id);
            if ( $this->sendReminders($bean, $admin, $recipients) ) {
                $bean->email_reminder_sent = 1;
                $bean->save();
            }
        }
        
        return true;
    }
    
    /**
     * send reminders
     * @param SugarBean $bean
     * @param Administration $admin
     * @param array $recipients
     * @return boolean
     */
    protected function sendReminders(SugarBean $bean, Administration $admin, $recipients)
    {
        
        if ( empty($_SESSION['authenticated_user_language']) ) {
            $current_language = $GLOBALS['sugar_config']['default_language'];
        }else{
            $current_language = $_SESSION['authenticated_user_language'];
        }            
                
                if ( !empty($bean->created_by) ) {
            $user_id = $bean->created_by;
        }else if ( !empty($bean->assigned_user_id) ) {
            $user_id = $bean->assigned_user_id;
        }else {
            $user_id = $GLOBALS['current_user']->id;
        }
        $user = new User();
        $user->retrieve($bean->created_by);
            
        $OBCharset = $GLOBALS['locale']->getPrecedentPreference('default_email_charset');
        require_once("include/SugarPHPMailer.php");
        $mail = new SugarPHPMailer();
        $mail->setMailerForSystem();
        
        if(empty($admin->settings['notify_send_from_assigning_user']))
        {
            $from_address = $admin->settings['notify_fromaddress'];
            $from_name = $admin->settings['notify_fromname'] ? "" : $admin->settings['notify_fromname'];
        }
        else
        {
            $from_address = $user->emailAddress->getReplyToAddress($user);
            $from_name = $user->full_name;
        }

        $mail->From = $from_address;
        $mail->FromName = $from_name;
        
        $xtpl = new XTemplate(get_notify_template_file($current_language));
        $xtpl = $this->setReminderBody($xtpl, $bean, $user);
        
        $template_name = $GLOBALS['beanList'][$bean->module_dir].'Reminder';
        $xtpl->parse($template_name);
        $xtpl->parse($template_name . "_Subject");
        
        $mail->Body = from_html(trim($xtpl->text($template_name)));
               $mail->Subject = from_html($xtpl->text($template_name . "_Subject"));
               
               $oe = new OutboundEmail();
        $oe = $oe->getSystemMailerSettings();
        if ( empty($oe->mail_smtpserver) ) {
            $GLOBALS['log']->fatal("Email Reminder: error sending email, system smtp server is not set");
            return;
        }

        foreach($recipients as $r ) {
            $mail->ClearAddresses();
            $mail->AddAddress($r['email'],$GLOBALS['locale']->translateCharsetMIME(trim($r['name']), 'UTF-8', $OBCharset));    
            $mail->prepForOutbound();
            if ( !$mail->Send() ) {
                $GLOBALS['log']->fatal("Email Reminder: error sending e-mail (method: {$mail->Mailer}), (error: {$mail->ErrorInfo})");
            }
        }
    
        return true;
    }
    
    /**
     * set reminder body
     * @param XTemplate $xtpl
     * @param SugarBean $bean
     * @param User $user
     * @return XTemplate 
    */
    protected function setReminderBody(XTemplate $xtpl, SugarBean $bean, User $user)
    {
    
        $object = strtoupper($bean->object_name);

        $xtpl->assign("{$object}_SUBJECT", $bean->name);
        $date = $GLOBALS['timedate']->fromUser($bean->date_start,$GLOBALS['current_user']);
        $xtpl->assign("{$object}_STARTDATE", $GLOBALS['timedate']->asUser($date, $user)." ".TimeDate::userTimezoneSuffix($date, $user));
        if ( isset($bean->location) ) {
            $xtpl->assign("{$object}_LOCATION", $bean->location);
        }
        $xtpl->assign("{$object}_CREATED_BY", $user->full_name);
        $xtpl->assign("{$object}_DESCRIPTION", $bean->description);

        return $xtpl;
    }
    
    /**
     * get meeting ids list for remind
     * @return array
     */
    public function getMeetingsForRemind()
    {
        global $db;
        $query = "
            SELECT id, date_start, email_reminder_time FROM meetings 
            WHERE email_reminder_time != -1
            AND deleted = 0
            AND email_reminder_sent = 0
            AND status != 'Held'
            AND date_start >= '{$this->now}'
            AND date_start <= '{$this->max}'
        ";
        $re = $db->query($query);
        $meetings = array();
        while($row = $db->fetchByAssoc($re) ) {
            $remind_ts = $GLOBALS['timedate']->fromDb($db->fromConvert($row['date_start'],'datetime'))->modify("-{$row['email_reminder_time']} seconds")->ts;
            $now_ts = $GLOBALS['timedate']->getNow()->ts;
            if ( $now_ts >= $remind_ts ) {
                $meetings[] = $row['id'];
            }
        }
        return $meetings;
    }
    
    /**
     * get calls ids list for remind
     * @return array
     */
    public function getCallsForRemind()
    {
        global $db;
        $query = "
            SELECT id, date_start, email_reminder_time FROM calls
            WHERE email_reminder_time != -1
            AND deleted = 0
            AND email_reminder_sent = 0
            AND status != 'Held'
            AND date_start >= '{$this->now}'
            AND date_start <= '{$this->max}'
        ";
        $re = $db->query($query);
        $calls = array();
        while($row = $db->fetchByAssoc($re) ) {
            $remind_ts = $GLOBALS['timedate']->fromDb($db->fromConvert($row['date_start'],'datetime'))->modify("-{$row['email_reminder_time']} seconds")->ts;
            $now_ts = $GLOBALS['timedate']->getNow()->ts;
            if ( $now_ts >= $remind_ts ) {
                $calls[] = $row['id'];
            }
        }
        return $calls;
    }
    
    /**
     * get recipients of reminding email for specific activity
     * @param string $id
     * @param string $module
     * @return array
     */
    protected function getRecipients($id, $module = "Meetings")
    {
        global $db;
    
        switch($module ) {
            case "Meetings":
                $field_part = "meeting";
                break;
            case "Calls":
                $field_part = "call";
                break;
            default:
                return array();
        }
    
        $emails = array();
        // fetch users
        $query = "SELECT user_id FROM {$field_part}s_users WHERE {$field_part}_id = '{$id}' AND accept_status != 'decline' AND deleted = 0
        ";
        $re = $db->query($query);
        while($row = $db->fetchByAssoc($re) ) {
            $user = new User();
            $user->retrieve($row['user_id']);
            if ( !empty($user->email1) ) {
                $arr = array(
                    'type' => 'Users',
                    'name' => $user->full_name,
                    'email' => $user->email1,
                );
                $emails[] = $arr;
            }
        }        
//        // fetch contacts
//        $query = "SELECT contact_id FROM {$field_part}s_contacts WHERE {$field_part}_id = '{$id}' AND accept_status != 'decline' AND deleted = 0";
//        $re = $db->query($query);
//        while($row = $db->fetchByAssoc($re) ) {
//            $contact = new Contact();
//            $contact->retrieve($row['contact_id']);
//            if ( !empty($contact->email1) ) {
//                $arr = array(
//                    'type' => 'Contacts',
//                    'name' => $contact->full_name,
//                    'email' => $contact->email1,
//                );
//                $emails[] = $arr;
//            }
//        }        
//        // fetch leads
//        $query = "SELECT lead_id FROM {$field_part}s_leads WHERE {$field_part}_id = '{$id}' AND accept_status != 'decline' AND deleted = 0";
//        $re = $db->query($query);
//        while($row = $db->fetchByAssoc($re) ) {
//            $lead = new Lead();
//            $lead->retrieve($row['lead_id']);
//            if ( !empty($lead->email1) ) {
//                $arr = array(
//                    'type' => 'Leads',
//                    'name' => $lead->full_name,
//                    'email' => $lead->email1,
//                );
//                $emails[] = $arr;
//            }
//        }
        return $emails;
    }
}

