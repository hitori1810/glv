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


if(isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'show_raw') {
	$email = new Email();
	$email->retrieve($_REQUEST['metadata']);
    echo nl2br($email->raw_source);
} else {
	require_once('include/Popups/Popup_picker.php');
	$popup = new Popup_Picker();
	echo $popup->process_page();
}

?>
