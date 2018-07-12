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

 * Description: Schedules email for delivery. emailman table holds emails for delivery.
 * A cron job polls the emailman table and delivers emails when intended send date time is reached.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/





global $timedate;
global $current_user;
global $mod_strings;

$campaign = new Campaign();
$campaign->retrieve($_REQUEST['record']);
$err_messages=array();

$test=false;
if (isset($_REQUEST['mode']) && $_REQUEST['mode'] =='test') {
	$test=true;
}

//this is to account for the case of sending directly from summary page in wizards
$from_wiz =false;
if (isset($_REQUEST['wiz_mass'])) {
    $mass[] = $_REQUEST['wiz_mass'];
    $_POST['mass'] = $mass;
    $from_wiz =true;
}
if (isset($_REQUEST['from_wiz'])) {
    $from_wiz =true;
}

//if campaign status is 'sending' disallow this step.
if (!empty($campaign->status) && $campaign->status == 'sending') {
	$err_messages[]=$mod_strings['ERR_SENDING_NOW'];
}
$current_date = $campaign->db->now();

//start scheduling now.....
foreach ($_POST['mass'] as $message_id) {

	//fetch email marketing definition.
	if (!class_exists('EmailMarketing')) require_once('modules/EmailMarketing/EmailMarketing.php');


	$marketing = new EmailMarketing();
	$marketing->retrieve($message_id);

	//make sure that the marketing message has a mailbox.
	//Lap Nguyen - Fix bugs Send SMS
	if (empty($marketing->inbound_email_id) && $this->bean->campaign_type != 'SMS') {

		echo "<p>";
		echo "<h4>{$mod_strings['ERR_NO_MAILBOX']}</h4>";
		echo "<BR><a href='index.php?module=EmailMarketing&action=EditView&record={$marketing->id}'>$marketing->name</a>";
		echo "</p>";
		sugar_die('');
	}


	global $timedate;
	$mergedvalue=$timedate->merge_date_time($marketing->date_start,$marketing->time_start);
	if($test) {
	    $send_date_time = $timedate->getNow()->get("-60 seconds")->asDb();
	} else {
	    $send_date_time = $timedate->to_db($mergedvalue);
	}
    $send_date_time = $campaign->db->convert($campaign->db->quoted($send_date_time), "datetime");

	//find all prospect lists associated with this email marketing message.
	if ($marketing->all_prospect_lists == 1) {
		$query="SELECT prospect_lists.id prospect_list_id from prospect_lists ";
		$query.=" INNER JOIN prospect_list_campaigns plc ON plc.prospect_list_id = prospect_lists.id";
		$query.=" WHERE plc.campaign_id='{$campaign->id}'";
		$query.=" AND prospect_lists.deleted=0";
		$query.=" AND plc.deleted=0";
		if ($test) {
			$query.=" AND prospect_lists.list_type='test'";
		} else {
			$query.=" AND prospect_lists.list_type!='test' AND prospect_lists.list_type not like 'exempt%'";
		}
	} else {
		$query="select email_marketing_prospect_lists.* FROM email_marketing_prospect_lists ";
		$query.=" inner join prospect_lists on prospect_lists.id = email_marketing_prospect_lists.prospect_list_id";
		$query.=" WHERE prospect_lists.deleted=0 and email_marketing_id = '$message_id' and email_marketing_prospect_lists.deleted=0";

		if ($test) {
			$query.=" AND prospect_lists.list_type='test'";
		} else {
			$query.=" AND prospect_lists.list_type!='test' AND prospect_lists.list_type not like 'exempt%'";
		}
	}
	$result=$campaign->db->query($query);
	while (($row=$campaign->db->fetchByAssoc($result))!=null ) {
		$prospect_list_id=$row['prospect_list_id'];

		//delete all messages for the current campaign and current email marketing message.
		$delete_emailman_query="delete from emailman where campaign_id='{$campaign->id}' and marketing_id='{$message_id}' and list_id='{$prospect_list_id}'";
		$campaign->db->query($delete_emailman_query);
        $auto = $campaign->db->getAutoIncrementSQL("emailman", "id");

		$insert_query= "INSERT INTO emailman (date_entered, user_id, campaign_id, marketing_id,list_id, related_id, related_type, send_date_time";
		$insert_query.= empty($auto)?"":",id";
		$insert_query.=')';
		$insert_query.= " SELECT $current_date,'{$current_user->id}',plc.campaign_id,'{$message_id}',plp.prospect_list_id, plp.related_id, plp.related_type,{$send_date_time}";
		$insert_query.= empty($auto)?"":",$auto";
		$insert_query.= " FROM prospect_lists_prospects plp ";
		$insert_query.= "INNER JOIN prospect_list_campaigns plc ON plc.prospect_list_id = plp.prospect_list_id ";
		$insert_query.= "WHERE plp.prospect_list_id = '{$prospect_list_id}' ";
		$insert_query.= "AND plp.deleted=0 ";
		$insert_query.= "AND plc.deleted=0 ";
		$insert_query.= "AND plc.campaign_id='{$campaign->id}'";

		$campaign->db->query($insert_query);
	}
}

//delete all entries from the emailman table that belong to the exempt list.
//TODO:SM: may want to move this to query clause above instead
if (!$test) {
    $delete_query =  "
    DELETE FROM emailman WHERE id IN (
        SELECT em.id FROM (
            SELECT emailman.id id
            FROM emailman 
            INNER JOIN prospect_lists_prospects plp
            ON emailman.related_id =  plp.related_id AND emailman.related_type =  plp.related_type
            INNER JOIN prospect_lists pl 
            ON pl.id = plp.prospect_list_id 
            INNER JOIN prospect_list_campaigns plc 
            ON plp.prospect_list_id = plc.prospect_list_id 
            WHERE plp.deleted = 0 AND plc.deleted = 0
            AND pl.deleted = 0 AND pl.list_type = 'exempt'
            AND plc.campaign_id = '{$campaign->id}') em
    )";
    $campaign->db->query($delete_query);
}

$return_module=isset($_REQUEST['return_module'])?$_REQUEST['return_module']:'Campaigns';
$return_action=isset($_REQUEST['return_action'])?$_REQUEST['return_action']:'DetailView';
$return_id=$_REQUEST['record'];

if ($test) {
	//navigate to EmailManDelivery..
// add by Hai Duc For SMS
$str_file = ($campaign->campaign_type == "SMS") ? "SMSManDelivery" : "EmailManDelivery";
	$header_URL = "Location: index.php?action={$str_file}&module=EmailMan&campaign_id={$_REQUEST['record']}&return_module={$return_module}&return_action={$return_action}&return_id={$return_id}&mode=test";
    if($from_wiz){$header_URL .= "&from_wiz=true";}
} else {
	//navigate back to campaign detail view...
	$header_URL = "Location: index.php?action={$return_action}&module={$return_module}&record={$return_id}";
    if($from_wiz){$header_URL .= "&from=send";}
}
$GLOBALS['log']->debug("about to post header URL of: $header_URL");
header($header_URL);
?>