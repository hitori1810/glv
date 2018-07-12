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

/*********************************************************************************gf

 * Description:  Executes a step in the installation process.
 ********************************************************************************/

$moduleList = array();
// this list defines the modules shown in the top tab list of the app
//the order of this list is the default order displayed - do not change the order unless it is on purpose
$moduleList[] = 'Home';
$moduleList[] = 'Cases';
$moduleList[] = 'Bugs';
$moduleList[] = 'Newsletters';
$moduleList[] = 'KBDocuments';
$moduleList[] = 'FAQ';


// this list defines all of the module names and bean names in the app
// to create a new module's bean class, add the bean definition here
$beanList = array();
$beanList['DynamicFields']  = 'DynamicField';
$beanList['EditCustomFields']   = 'FieldsMetaData';

$beanList['Bugs']           = 'Bug';
$beanList['Cases']          = 'aCase';
$beanList['Notes']          = 'Note';
$beanList['Leads']          = 'Lead';

$beanList['Users']          = 'User';
$beanList['Employees']      = 'Employee';

$beanList['Administration'] = 'Administration';
$beanList['Teams']          = 'Team';

$beanList['CustomFields']       = 'CustomFields';

$beanList['Roles']  = 'Role';
$beanList['Audit']  = 'Audit';

$beanList['UserPreferences']        = 'UserPreference';



// this list defines all of the files that contain the SugarBean class definitions from $beanList
// to create a new module's bean class, add the file definition here
$beanFiles = array();
$beanFiles['Relationship']  = 'modules/Relationships/Relationship.php';
$beanFiles['Bug']           = 'modules/Bugs/Bug.php';
$beanFiles['aCase']         = 'modules/Cases/Case.php';
$beanFiles['Note']          = 'modules/Notes/Note.php';
$beanFiles['Lead']          = 'modules/Leads/Lead.php';
$beanFiles['User']          = 'modules/Users/User.php';
$beanFiles['Employee']      = 'modules/Employees/Employee.php';
$beanFiles['Administration']= 'modules/Administration/Administration.php';
$beanFiles['UpgradeHistory']= 'modules/Administration/UpgradeHistory.php';
$beanFiles['Team']          = 'modules/Teams/Team.php';
$beanFiles['TeamMembership']            = 'modules/Teams/TeamMembership.php';
$beanFiles['System']      = 'modules/Administration/System.php';
$beanFiles['Role']          = 'modules/Roles/Role.php';
$beanFiles['FieldsMetaData']    = 'modules/DynamicFields/FieldsMetaData.php';

//$beanFiles['Audit']           = 'modules/Audit/Audit.php';



$beanFiles['UserPreference']  = 'modules/UserPreferences/UserPreference.php';




// added these lists for security settings for tabs
$modInvisList = array('Administration', 'Currencies', 'CustomFields',
    'Dropdown', 'Dynamic', 'DynamicFields', 'DynamicLayout', 'EditCustomFields',
    'Help', 'Import',  'MySettings', 'EditCustomFields','FieldsMetaData',



    'Users',  'Employees', 'OptimisticLock', 'MergeRecords',

    'ACLActions', 'ACLRoles',
    );
$adminOnlyList = array(
                    //module => list of actions  (all says all actions are admin only)
                    'Administration'=>array('all'=>1, 'SupportPortal'=>'allow'),
                    'Dropdown'=>array('all'=>1),
                    'Dynamic'=>array('all'=>1),
                    'DynamicFields'=>array('all'=>1),
                    'Currencies'=>array('all'=>1),
                    'EditCustomFields'=>array('all'=>1),
                    'FieldsMetaData'=>array('all'=>1),
                    'LabelEditor'=>array('all'=>1),
                    'ACL'=>array('all'=>1),
                    'ACLActions'=>array('all'=>1),
                    'ACLRoles'=>array('all'=>1),
                    //'Groups'=>array('all'=>1),
                    );

$modInvisList[] = 'Configurator';
$modInvisList[] = 'UserPreferences';

// deferred
//$modInvisList[] = 'Queues';
$modInvisList[] = 'Studio';
if (file_exists('include/modules_override.php'))
{
    include('include/modules_override.php');
}
if (file_exists('custom/application/Ext/Include/modules.ext.php'))
{
    include('custom/application/Ext/Include/modules.ext.php');
}
?>
