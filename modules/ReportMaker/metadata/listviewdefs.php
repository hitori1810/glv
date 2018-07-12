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




$listViewDefs['ReportMaker'] = array(
    'NAME' => array(
        'width' => '20', 
        'label' => 'LBL_NAME', 
        'link' => true,
        'default' => true),
    'IS_SCHEDULED' => array(
        'width' => '10', 
        'label' => 'LBL_LIST_SCHEDULED', 
        'default' => true,
        'customCode' => '<a  href="#" onclick=\'window.open("index.php?module=Reports&action=add_schedule&to_pdf=true&id={$ID}&schedule_type=ent","test","width=500,height=200,resizable=1,scrollbars=1");\' class="listViewTdToolsS1">{$IS_SCHEDULED_IMG} {$IS_SCHEDULED}</a>'),
);
