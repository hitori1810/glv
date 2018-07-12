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

$viewdefs['KBDocuments']['listview'] = array(
	'KBDOCUMENT_NAME' => array(
		'width' => '45',
		'label' => 'LBL_NAME',
		'link' => true,
		'sortable' => false), 
	'ACTIVE_DATE' => array(
		'width' => '20',
		'label' => 'LBL_ACTIVE_DATE',
		'sortable' => false),
	'CREATED_BY' => array(
		'width' => '35',
		'label' => 'LBL_ARTICLE_AUTHOR_LIST',
		'sortable' => false),
);
?>
