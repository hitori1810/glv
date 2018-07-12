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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
global $beanFiles;





$db = DBManagerFactory::getInstance();
global $app_strings;
if(!ACLController::checkAccess('Opportunities', 'edit', true)){
	ACLController::displayNoAccess(true);
	sugar_cleanup(true);
}
function send_to_url($redirect_Url)
{
	echo "<script language=javascript>\n";
	echo "<!-- //\n";
	echo "	window.location.href=\"{$redirect_Url}\";\n";
	echo "// -->\n";
	echo "</script>\n";
}

// returns value of number of rows where the name already exists
function query_opportunity_subject_exists($subj)
{
	global $db;

	$subject = $db->quoted($subj);
	$query = "select count(id) as num from opportunities where name = $subject and deleted = 0";
	$check = $db->query($query);

	$row = $db->fetchByAssoc($check);

	return $row["num"];
}

function generate_name_form(&$var)
{
	global $app_strings;
	$retval =  "<br><br>
	<form method=POST action=index.php name=QuoteToOpportunity>
<table class=\"tabForm\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"50%\">
<tbody><tr><td>
	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
	<tbody><tr>
		<td class=\"dataLabel\" nowrap=\"nowrap\">{$app_strings['LBL_OPPORTUNITY_NAME']}:&nbsp;&nbsp;<input size=\"20\" name=\"opportunity_subject\" class=\"dataField\" value=\"{$var["opportunity_subject"]}\" type=\"text\"></td>
		<td align=\"right\"><input name=\"action\" value=\"index\" type=\"hidden\" nowrap=\"nowrap\">
				<input type=\"hidden\" name=\"module\" value=\"Quotes\">
				<input type=\"hidden\" name=\"record\" value=\"{$var['record']}\">
				<input type=\"hidden\" name=\"team_id\" value=\"{$var['team_id']}\">
				<input type=\"hidden\" name=\"user_id\" value=\"{$var['user_id']}\">
				<input type=\"hidden\" name=\"user_name\" value=\"{$var['user_name']}\">
				<input type=\"hidden\" name=\"action\" value=\"QuoteToOpportunity\">
				<input type=\"hidden\" name=\"opportunity_name\" value=\"{$var['opportunity_name']}\">
				<input type=\"hidden\" name=\"opportunity_id\" value=\"{$var['opportunity_id']}\">
				<input type=\"hidden\" name=\"currency_id\" value=\"{$var['currency_id']}\">
				<input type=\"hidden\" name=\"amount\" value=\"{$var['amount']}\">
				<input type=\"hidden\" name=\"valid_until\" value=\"{$var['valid_until']}\">
		<input title=\"{$app_strings['LBL_SAVE_BUTTON_TITLE']}\" accesskey=\"{$app_strings['LBL_SAVE_BUTTON_KEY']}\" class=\"button\" name=\"button\" value=\"{$app_strings['LBL_SAVE_BUTTON_LABEL']}\" type=\"submit\"></form>
		</td>
		<td align=\"right\">
				<form method=POST action=index.php name=BackToQuote>
				<input type=\"hidden\" name=\"module\" value=\"Quotes\">
				<input type=\"hidden\" name=\"record\" value=\"{$var['record']}\">
				<input type=\"hidden\" name=\"action\" value=\"DetailView\">
				<input title=\"{$app_strings['LBL_CANCEL_BUTTON_TITLE']}\" accesskey=\"{$app_strings['LBL_CANCEL_BUTTON_KEY']}\" class=\"button\" name=\"button\" value=\"{$app_strings['LBL_CANCEL_BUTTON_LABEL']}\" type=\"submit\">
				</form>
      	</td>
	</tr>
	</tbody></table>
</td></tr></tbody></table>";

	echo $retval;
}

if(empty($_REQUEST["opportunity_subject"]))
{
	$LBLITUP = $app_strings['ERR_OPPORTUNITY_NAME_MISSING'];

	printf("<span class=\"error\">$LBLITUP</span>");
	generate_name_form($_REQUEST);
}
elseif(query_opportunity_subject_exists($_REQUEST["opportunity_subject"]) > 0)
{
	$LBLITUP = $app_strings['ERR_OPPORTUNITY_NAME_DUPE'];

	printf("<span class=\"error\">$LBLITUP</span>", $_REQUEST["opportunity_subject"]);
	generate_name_form($_REQUEST);
}
else
{
	$opp = new Opportunity();
	printf("%s<br><br>", $opp->id);
	$opp->assigned_user_id = $_REQUEST["user_id"];
	$opp->date_closed = $_REQUEST["valid_until"];
	$opp->name = $_REQUEST["opportunity_subject"];
	$opp->assigned_user_name = $_REQUEST["user_name"];
	if (!empty($dictionary['Opportunity']['fields']['sales_stage']['default'])) {
	    $opp->sales_stage = $dictionary['Opportunity']['fields']['sales_stage']['default'];
	}
	else {
	    $opp->sales_stage = isset($app_list_strings['sales_stage_dom']['Prospecting']) ? 'Prospecting': null;
	}
	if (!empty($dictionary['Opportunity']['fields']['probability']['default'])) {
	    $opp->probability = $dictionary['Opportunity']['fields']['probability']['default'];
	}
	else {
	    if (!empty($opp->sales_stage)) {
	        $opp->probability = isset($app_list_strings['sales_probability_dom'][$opp->sales_stage]) ? $app_list_strings['sales_probability_dom'][$opp->sales_stage]: null;
	    }
	    else {
	        $opp->probability = isset($app_list_strings['sales_probability_dom']['Prospecting']) ? $app_list_strings['sales_probability_dom']['Prospecting']: null;
	    }
	}
	$opp->team_id = $_REQUEST["team_id"];
	if(empty($_REQUEST["amount"])) {
		$amount = (float)0;
	} else {
        // We need to unformat the amount before we try and stick it in a bean
        $amount=(float)$_REQUEST["amount"];
	}
	$account_id = $_REQUEST["opportunity_id"];
	$opp->amount = $amount;
	$opp->quote_id = $_REQUEST['record'];
	$opp->currency_id = $_REQUEST['currency_id'];
	$opp->account_id = $account_id;
	$opp->save();


	//link quote contracts with the opportunity.
	$quote = new Quote();
	$quote->retrieve($_REQUEST['record']);
	$quote->load_relationship('contracts');
	$contracts=$quote->contracts->get();

	if (is_array($contracts)) {
		$opp->load_relationship('contracts');
		foreach ($contracts as $id) {
			$opp->contracts->add($id);
		}
	}

	$redirect_Url = "index.php?action=DetailView&module=Opportunities&record=" . $opp->id;
	send_to_url($redirect_Url);
}

?>
