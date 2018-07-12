<?php
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


function loadParentView($type)
{
    SugarAutoLoader::requireWithCustom('include/MVC/View/views/view.'.$type.'.php');
}


function getPrintLink()
{
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "ajaxui")
    {
        return "javascript:SUGAR.ajaxUI.print();";
    }
    return "javascript:void window.open('index.php?{$GLOBALS['request_string']}',"
         . "'printwin','menubar=1,status=0,resizable=1,scrollbars=1,toolbar=0,location=1')";
}


function ajaxBannedModules(){
    $bannedModules = array(
        'Calendar',
        'Reports',
        'Emails',
        'Campaigns',
        'Documents',
        'DocumentRevisions',
        'Project',
        'ProjectTask',
        'EmailMarketing',
        'CampaignLog',
        'CampaignTrackers',
        'Releases',
        'Groups',
        'EmailMan',
        'ACLFields',
        'ACLRoles',
        'ACLActions',
        'TrackerSessions',
        'TrackerPerfs',
        'TrackerQueries',
        'Teams',
        'TeamMemberships',
        'TeamSets',
        'TeamSetModules',
        'Quotes',
        'Products',
        'ProductBundles',
        'ProductBundleNotes',
        'ProductTemplates',
        'ProductTypes',
        'ProductCategories',
        'Manufacturers',
        'Shippers',
        'TaxRates',
        'TeamNotices',
        'TimePeriods',
        'Forecasts',
        'ForecastSchedule',
        'Worksheet',
        'ForecastOpportunities',
        'Quotas',
        'WorkFlow',
        'WorkFlowTriggerShells',
        'WorkFlowAlertShells',
        'WorkFlowAlerts',
        'WorkFlowActionShells',
        'WorkFlowActions',
        'Expressions',
        'Contracts',
        'KBDocuments',
        'KBDocumentRevisions',
        'KBTags',
        'KBDocumentKBTags',
        'KBContents',
        'ContractTypes',
        'Holidays',
        'ProjectResources',
        'CustomQueries',
        'DataSets',
        'ReportMaker',
        "Administration",
        "ModuleBuilder",
        'Schedulers',
        'SchedulersJobs',
        'DynamicFields',
        'EditCustomFields',
        'EmailTemplates',
		'PdfManager',
        'Users',
        'Currencies',
        'Trackers',
        'Connectors',
        'Import_1',
        'Import_2',
        'Versions',
        'vCals',
        'CustomFields',
        'Roles',
        'Audit',
        'InboundEmail',
        'SavedSearch',
        'UserPreferences',
        'MergeRecords',
        'EmailAddresses',
        'Relationships',
        'Employees',
        'Import',
        'OAuthKeys'
    );

    if(!empty($GLOBALS['sugar_config']['addAjaxBannedModules'])){
        $bannedModules = array_merge($bannedModules, $GLOBALS['sugar_config']['addAjaxBannedModules']);
    }
    if(!empty($GLOBALS['sugar_config']['overrideAjaxBannedModules'])){
        $bannedModules = $GLOBALS['sugar_config']['overrideAjaxBannedModules'];
    }

    return $bannedModules;
}

function ajaxLink($url)
{
    global $sugar_config;
    $match = array();

    preg_match('/module=([^&]*)/i', $url, $match);

    if(!empty($sugar_config['disableAjaxUI'])){
        return $url;
    }
    else if(isset($match[1]) && in_array($match[1], ajaxBannedModules())){
        return $url;
    }
    //Don't modify direct link
    else if (preg_match('/^[a-z][a-z0-9\+\.-]*:/i', $url)) {
        return $url;
    }
    else
    {
        return "?action=ajaxui#ajaxUILoc=" . urlencode($url);
    }
}

?>
