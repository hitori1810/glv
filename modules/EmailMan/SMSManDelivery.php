<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Professional Subscription
 * Agreement ("License") which can be viewed at
 * http://www.sugarcrm.com/crm/products/sugar-professional-eula.html
 * By installing or using this file, You have unconditionally agreed to the
 * terms and conditions of the License, and You may not use this file except in
 * compliance with the License.  Under the terms of the license, You shall not,
 * among other things: 1) sublicense, resell, rent, lease, redistribute, assign
 * or otherwise transfer Your rights to the Software, and 2) use the Software
 * for timesharing or service bureau purposes such as hosting the Software for
 * commercial gain and/or for the benefit of a third party.  Use of the Software
 * may be subject to applicable fees and any use of the Software without first
 * paying applicable fees is strictly prohibited.  You do not have the right to
 * remove SugarCRM copyrights from the source code or user interface.
 *
 * All copies of the Covered Code must include on each user interface screen:
 *  (i) the "Powered by SugarCRM" logo and
 *  (ii) the SugarCRM copyright notice
 * in the same form as they appear in the distribution.  See full license for
 * requirements.
 *
 * Your Warranty, Limitations of liability and Indemnity are expressly stated
 * in the License.  Please refer to the License for the specific language
 * governing these rights and limitations under the License.  Portions created
 * by SugarCRM are Copyright (C) 2004-2010 SugarCRM, Inc.; All Rights Reserved.
 ********************************************************************************/


require_once('include/SugarPHPMailer.php');

$test=false;
if (isset($_REQUEST['mode']) && $_REQUEST['mode']=='test') {
	$test=true;
}
if (isset($_REQUEST['send_all']) && $_REQUEST['send_all']== true) {
	$send_all= true;
}
else  {
	$send_all=true; //if set to true email delivery will continue..to run until all email have been delivered.
}
$GLOBALS['log'] = LoggerManager::getLogger('SugarCRM');
 
$admin = new Administration();
$admin->retrieveSettings();  

if (isset($admin->settings['massemailer_campaign_emails_per_run'])) {
	$max_emails_per_run=$admin->settings['massemailer_campaign_emails_per_run'];
}
if (empty($max_emails_per_run)) {
	$max_emails_per_run=500;//default
}

# tracy: this config applies to SMS also so leave it alone
//save email copies?
$massemailer_email_copy=0;  //default: save copies of the email.
if (isset($admin->settings['massemailer_email_copy'])) {
    $massemailer_email_copy=$admin->settings['massemailer_email_copy'];
}
 
$emailsPerSecond = 10; 

$campaign_id=null;
if (isset($_REQUEST['campaign_id']) && !empty($_REQUEST['campaign_id'])) {
	$campaign_id=$_REQUEST['campaign_id'];
}

$db = DBManagerFactory::getInstance();
$emailman = new EmailMan();
 
    if($test){
        //if this is in test mode, then
        //find all the message that meet the following criteria.
        //1. scheduled send date time is now
        //2. campaign matches the current campaign
        //3. recipient belongs to a propsect list of type test, attached to this campaign 
    
        $select_query =" SELECT em.* FROM emailman em";
	$select_query.=" LEFT OUTER JOIN campaigns c ON em.campaign_id = c.id";
        $select_query.=" join prospect_list_campaigns plc on em.campaign_id = plc.campaign_id";
        $select_query.=" join prospect_lists pl on pl.id = plc.prospect_list_id ";
        $select_query.=" WHERE em.list_id = pl.id and pl.list_type = 'test' AND c.campaign_type='SMS' ";
        $select_query.=" AND em.send_date_time <= ". db_convert("'".gmdate($GLOBALS['timedate']->get_db_date_time_format())."'" ,"datetime");    
        $select_query.=" AND (em.in_queue ='0' OR ( em.in_queue ='1' AND em.in_queue_date <= " .db_convert("'". gmdate($GLOBALS['timedate']->get_db_date_time_format(), strtotime("-1 day")) ."'" ,"datetime")."))";
        $select_query.=" AND em.campaign_id='{$campaign_id}'";
        $select_query.=" ORDER BY em.campaign_id, em.user_id, em.list_id";
    }else{
        //this is not a test.. 
        //find all the message that meet the following criteria.
        //1. scheduled send date time is now
        //2. were never processed or last attempt was 24 hours ago
        $select_query =" SELECT em.*";
        $select_query.=" FROM {$emailman->table_name} em";
	$select_query.=" LEFT OUTER JOIN campaigns c ON em.campaign_id=c.id ";
        $select_query.=" WHERE c.campaign_type='SMS' AND em.send_date_time <= ". db_convert("'".gmdate($GLOBALS['timedate']->get_db_date_time_format())."'" ,"datetime");
        $select_query.=" AND (em.in_queue ='0' OR ( em.in_queue ='1' AND em.in_queue_date <= " .db_convert("'". gmdate($GLOBALS['timedate']->get_db_date_time_format(), strtotime("-1 day")) ."'" ,"datetime")."))"; 
        
        if (!empty($campaign_id)) {
            $select_query.=" AND em.campaign_id='{$campaign_id}'";
        }
        $select_query.=" ORDER BY em.campaign_id, em.user_id, em.list_id";
    }   
do {

	$no_items_in_queue = true;	
	
	$result = $db->limitQuery($select_query,0,$max_emails_per_run);
 	
	global $current_user;
	if(isset($current_user)){
		$temp_user = $current_user;
	}	
	$current_user = new User();
	$startTime = microtime(true);   
	
	while(($row = $db->fetchByAssoc($result))!= null){

        //verify the queue item before further processing.
        //we have found cases where users have taken away access to email templates while them message is in queue.
        if (empty($row['campaign_id'])) {
            $GLOBALS['log']->fatal('Skipping emailman entry with empty campaign id' . print_r($row,true));
            continue;  
        }
        if (empty($row['marketing_id'])) {
            $GLOBALS['log']->fatal('Skipping emailman entry with empty marketing id' . print_r($row,true));
            continue;  //do not process this row .
        }

        //fetch user that scheduled the campaign.
        if(empty($current_user) or $row['user_id'] != $current_user->id){
            $current_user->retrieve($row['user_id']);
        }
        
        if (!$emailman->verify_campaign($row['marketing_id'])) {
            $GLOBALS['log']->fatal('Error verifying templates for the campaign, exiting');
            continue; 
        } 
        
        //verify the email template too..
        //find the template associated with marketing message. make sure that template has a subject and 
        //a non-empty body
        if (!isset($template_status[$row['marketing_id']])) {
            if (!class_exists('EmailMarketing')) {
                
            }
            $current_emailmarketing=new EmailMarketing();
            $current_emailmarketing->retrieve($row['marketing_id']);

            if (!class_exists('EmailTemplate')) {
                
            }
            $current_emailtemplate= new EmailTemplate();
            $current_emailtemplate->retrieve($current_emailmarketing->template_id);
                        
        }
		
		//acquire a lock.
		//if the database does not support repeatable read isolation by default, we might get data that does not meet 
        //the criteria in the original query, and we care most about the in_queue_date and process_date_time, 
        //if they are null or in past(older than 24 horus) then we are okay.
		$lock_query="UPDATE emailman SET in_queue=1, in_queue_date='". gmdate($GLOBALS['timedate']->get_db_date_time_format()) ."' WHERE id = '${row['id']}'";
		$lock_query.=" AND (in_queue ='0' OR ( in_queue ='1' AND in_queue_date <= " .db_convert("'". gmdate($GLOBALS['timedate']->get_db_date_time_format(), strtotime("-1 day")) ."'" ,"datetime")."))"; 
 		//if the query fails to execute.. terminate campaign email process.
 		$lock_result=$db->query($lock_query,true,'Error acquiring a lock for emailman entry.');
		if ($db->dbType=='oci8') {
		} else {
			$lock_count=$db->getAffectedRowCount();
		}

		//do not process the message if unable to acquire lock.

		if ($lock_count!= 1) {
			$GLOBALS['log']->fatal("Error acquiring lock for the emailman entry, skipping sms delivery. lock status=$lock_count " . print_r($row,true));
			continue;  //do not process this row we will examine it after 24 hrs. the email address based dupe check is in place too.
		}
		
//		$no_items_in_queue=false;	 
	
		foreach($row as $name=>$value){
			$emailman->$name = $value;
		} 
	
		//for the campaign process the supression lists.
		if (!isset($current_campaign_id) or empty($current_campaign_id) or $current_campaign_id != $row['campaign_id']) {
			$current_campaign_id= $row['campaign_id'];

			//is this email address suppressed?
			$plc_query= " SELECT prospect_list_id, prospect_lists.list_type, prospect_lists.domain_name FROM prospect_list_campaigns ";
			$plc_query.=" LEFT JOIN prospect_lists on prospect_lists.id = prospect_list_campaigns.prospect_list_id";
			$plc_query.=" WHERE ";
			$plc_query.=" campaign_id='{$current_campaign_id}' ";
			$plc_query.=" AND prospect_lists.list_type in ('exempt_address')";		# tracy: domain is not applicable
			$plc_query.=" AND prospect_list_campaigns.deleted=0";
			$plc_query.=" AND prospect_lists.deleted=0";
			$result1 = $db->query($plc_query);		
			 
			$emailman->restricted_phone_numbers = array(); 
			
			require_once('custom/modules/Administration/smsPhone/sms_enzyme.php');
			while($row1 = $db->fetchByAssoc($result1)){  
				
				# tracy: mktg thru sms is limited to contacts, leads, and targets=prospects
				$rel_type_tables = array("Contacts"=>"contacts", "Leads"=>"leads", "Prospects"=>"prospects", "Accounts"=>"accounts");
				foreach($rel_type_tables as $mod => $tbl) {

					$e = new sms_enzyme($mod);
					$phone_field = $e->get_custom_phone_field();
					if (empty($phone_field))
						$phone_field = "phone_mobile";
					# tracy: now get all the phone numbers that should not rcv text msg 
					$sql = "SELECT 
						  module.{$phone_field}
						FROM {$tbl} module
						  INNER JOIN prospect_lists_prospects ON (module.id = prospect_lists_prospects.related_id)
						WHERE
						  (prospect_lists_prospects.prospect_list_id = \"{$row1['prospect_list_id']}\") AND 
						  (prospect_lists_prospects.deleted = 0)";
					$rs_fones = $db->query($sql); 
					while($row_fone = $db->fetchByAssoc($rs_fones)){
						$fone_num = preg_replace('/[^0-9]/', '', $row_fone[$phone_field]);
						//ignore empty phone numbers
						if (!empty($fone_num))  $emailman->restricted_phone_numbers[$fone_num] = 1; 
					}  
				} 
					
			}
		}
		
 		if(!$emailman->sendSMS($massemailer_email_copy,$test,$user_id)){
			$GLOBALS['log']->fatal("SMS delivery FAILED:" . print_r($row,true));		
		} else {
			$GLOBALS['log']->debug("SMS delivery SUCCESS:" . print_r($row,true));		
	 	}
	 	 
	}

	$send_all = $send_all ? !$no_items_in_queue : $send_all;  

} while ($send_all == true); 
  
 
if(isset($temp_user)){
	$current_user = $temp_user;	
}
if (isset($_REQUEST['return_module']) && isset($_REQUEST['return_action']) && isset($_REQUEST['return_id'])) {
    $from_wiz=' ';
    if(isset($_REQUEST['from_wiz'])&& $_REQUEST['from_wiz']==true){
        header("Location: index.php?module={$_REQUEST['return_module']}&action={$_REQUEST['return_action']}&record={$_REQUEST['return_id']}&from=test");
    }else{
		header("Location: index.php?module={$_REQUEST['return_module']}&action={$_REQUEST['return_action']}&record={$_REQUEST['return_id']}");
    } 	
} else {
	/* this will be triggered when manually sending off Email campaigns from the
	 * Mass Email Queue Manager.
 	*/
	if(isset($_POST['manual'])) {
		header("Location: index.php?module=EmailMan&action=index"); 
	}
}
?>
