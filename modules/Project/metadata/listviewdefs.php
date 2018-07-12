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




$listViewDefs['Project'] = array(
	'NAME' => array(
		'width' => '40',  
		'label' => 'LBL_LIST_NAME', 
		'link' => true,
        'default' => true),
    'ESTIMATED_START_DATE' => array(
        'width' => '20',  
        'label' => 'LBL_DATE_START', 
        'link' => false,
        'default' => true),    
    'ESTIMATED_END_DATE' => array(
        'width' => '20',  
        'label' => 'LBL_DATE_END', 
        'link' => false,
        'default' => true), 
    'STATUS' => array(
        'width' => '20',  
        'label' => 'LBL_STATUS', 
        'link' => false,
        'default' => true),         
	'ASSIGNED_USER_NAME' => array(
		'width' => '10', 
		'label' => 'LBL_LIST_ASSIGNED_USER_ID',
		'module' => 'Employees',
        'id' => 'ASSIGNED_USER_ID',
        'default' => true),
    'TEAM_NAME' => array(
        'width' => '2', 
        'label' => 'LBL_LIST_TEAM',
        'related_fields' => array('team_id'),        
        'default' => false),        

);

$listViewDefs['ProjectTemplates'] = array(
	'NAME' => array(
		'width' => '40',  
		'label' => 'LBL_LIST_NAME', 
		'link' => true,
        'default' => true,
        'customCode'=>'<a href="index.php?offset={$OFFSET}&record={$ID}&action=ProjectTemplatesDetailView&module=Project" >{$NAME}</a>'),
    'ESTIMATED_START_DATE' => array(
        'width' => '20',  
        'label' => 'LBL_DATE_START', 
        'link' => false,
        'default' => true),    
    'ESTIMATED_END_DATE' => array(
        'width' => '20',  
        'label' => 'LBL_DATE_END', 
        'link' => false,
        'default' => true), 
    'TEAM_NAME' => array(
        'width' => '2', 
        'label' => 'LBL_LIST_TEAM',
        'related_fields' => array('team_id'),        
        'default' => false),        
);

?>
