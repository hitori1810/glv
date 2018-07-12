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



$viewdefs['Cases']['portal']['view']['list'] = array(
    'panels' =>
    array(
        0 =>
        array(
            'label' => 'LBL_PANEL_DEFAULT',
            'fields' =>
            array(
                array(
                    'name' => 'case_number',
                    'width' =>  8,
                    'link' => true,
                    'label' => 'LBL_LIST_NUMBER',
                    'enabled' => true,
                    'default' => true
                ),
                array(
                    'name' => 'name',
                    'width' =>  49,
                    'link' => true,
                    'label' => 'LBL_LIST_SUBJECT',
                    'enabled' => true,
                    'default' => true
                ),
                array(                 
                    'name' => 'status',    
                    'width' =>  17,     
                    'label' => 'LBL_LIST_STATUS',
                    'enabled' => true,
                    'default' => true
                ),
                array(
                    'name' => 'priority',
                    'width' =>  13,
                    'label' => 'LBL_LIST_PRIORITY',
                    'enabled' => true,
                    'default' => true
                ),
                array(               
                    'name' => 'type',  
                    'width' =>  13,  
                    'label' => 'LBL_TYPE',
                    'enabled' => true,
                    'default' => true
                ),
                array (
                    'name' => 'date_entered',
                    'label' => 'LBL_LIST_DATE_CREATED',
                    'enabled' => true,
                    'width' => 13,
                    'default' => true,
                ),
            ),
        ),
    ),
);

