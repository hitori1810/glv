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




$listViewDefs['Holidays'] = array(
    'HOLIDAY_DATE' => array(
        'width' => '20',  
        'label' => 'LBL_HOLIDAY_DATE', 
        'link' => true,
        'default' => true),
    'TYPE' => array(
        'width' => '20',  
        'label' => 'LBL_TYPE',
        'default' => true),
    'APPLY_FOR' => array(
        'width' => '20',  
        'label' => 'LBL_APPLY_FOR',
        'default' => true),
    'DESCRIPTION' => array(
        'width' => '20', 
        'label' => 'LBL_DESCRIPTION',
        'sortable' => false,
        'default' => true)
);
?>
