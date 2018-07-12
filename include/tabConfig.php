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




$GLOBALS['tabStructure'] = array(
    "LBL_TABGROUP_SALES" => array(
        'label' => 'LBL_TABGROUP_SALES',
        'modules' => array(
            "Home",
            "Accounts",
            "Contacts",
            "Opportunities",
            "Leads",
            "Contracts",
            "Quotes",
            "Products",
            "Forecasts",
        )
    ),
    "LBL_TABGROUP_MARKETING" => array(
        'label' => 'LBL_TABGROUP_MARKETING',
        'modules' => array(
            "Home",
            "Accounts",
            "Contacts",
            "Leads",
            "Campaigns",
            "Prospects",
            "ProspectLists",
        )
    ),
    "LBL_TABGROUP_SUPPORT" => array(
        'label' => 'LBL_TABGROUP_SUPPORT',
        'modules' => array(
            "Home",
            "Accounts",
            "Contacts",
            "Cases",
            "Bugs",
            "KBDocuments",
        )
    ),
    "LBL_TABGROUP_ACTIVITIES" => array(
        'label' => 'LBL_TABGROUP_ACTIVITIES',
        'modules' => array(
            "Home",
            "Calendar",
            "Calls",
            "Meetings",
            "Emails",
            "Tasks",
            "Notes",
        )
    ),
    "LBL_TABGROUP_COLLABORATION"=>array(
        'label' => 'LBL_TABGROUP_COLLABORATION',
        'modules' => array(
            "Home",
            "Emails",
            "Documents",
            "Project",
            "KBDocuments",
        )
    ),
    "LBL_TABGROUP_REPORTS"=>array(
        'label' => 'LBL_TABGROUP_REPORTS',
        'modules' => array(
            "Home",
            "Reports",
            "Forecasts",
        )
    ),
);
