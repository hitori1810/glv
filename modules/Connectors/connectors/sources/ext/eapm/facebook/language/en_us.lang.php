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


$connector_strings = array (
    'LBL_LICENSING_INFO' => '<table border="0" cellspacing="1"><tr><td valign="top" width="35%" class="dataLabel">Obtain an API Key and App Secret from Facebook&#169; by creating an application for your Sugar instance.<br/><br>Steps to create an application for your instance:<br/><br/><ol><li>Go to the following Facebook&#169; to create the application: <a href=\'http://www.facebook.com/developers/createapp.php\' target=\'_blank\'>http://www.facebook.com/developers/createapp.php</a>.</li><li>Sign in to Facebook&#169; using the account under which you would like to create the application.</li><li>Within the "Create Application" page, enter a name for the application. This is the name users will see when they authenticate their Facebook&#169; accounts from within Sugar.</li><li>Select "Agree" to agree to the Facebook&#169; Terms.</li><li>Click "Create App"</li><li>Enter and submit the security words to pass the Security Check.</li><li>Within the page for your application, go to the "Web Site" area (link in lefthand menu) and enter the local URL of your Sugar instance for "Site URL."</li><li>Click "Save Changes"</li><li>Explicitly allow read_stream permission on the created Application.</li><li>Go to the "Facebook Integration" page (link in lefthand menu) and find the API Key and App Secret. Enter the Application ID and Application Secret below.</li></ol></td></tr></table>',
    'oauth_consumer_key' => 'API Key',
    'oauth_consumer_secret' => 'App Secret',
);

