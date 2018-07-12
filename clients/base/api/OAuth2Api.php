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


require_once('include/SugarOAuth2/SugarOAuth2Server.php');

class OAuth2Api extends SugarApi {
    public function registerApiRest() {
        return array(
            'token' => array(
                'reqType' => 'POST',
                'path' => array('oauth2','token'),
                'pathVars' => array('',''),
                'method' => 'token',
                'shortHelp' => 'OAuth2 token requests.',
                'longHelp' => 'include/api/help/oauth2_token.html',
                'rawReply' => true, // The OAuth server sets specific headers and outputs in the exact format requested by the spec, so we don't want to go around messing with it.
                'noLoginRequired' => true,
            ),
            'oauth_logout' => array(
                'reqType' => 'POST',
                'path' => array('oauth2','logout'),
                'pathVars' => array('',''),
                'method' => 'logout',
                'shortHelp' => 'OAuth2 logout.',
                'longHelp' => 'include/api/help/oauth2_logout.html',
            ),
        );
    }

    public function token($api, $args) {
        $validVersion = $this->isSupportedClientVersion($api, $args);

        if ( !$validVersion ) {
            throw new SugarApiExceptionClientOutdated();
        }

        $platform = empty($args['platform']) ? 'base' : $args['platform'];
        $oauth2Server = SugarOAuth2Server::getOAuth2Server();
        $oauth2Server->setPlatform($platform);

        $oauth2Server->grantAccessToken($args);
        
        // grantAccessToken directly echo's (BAD), but it's a 3rd party library, so what are you going to do?
        return '';
    }

    public function logout($api, $args) {
        $oauth2Server = SugarOAuth2Server::getOAuth2Server();

        if ( isset($args['refresh_token']) ) {
            // Nuke the refresh token as well.
            // No security checks needed here to make sure the refresh token is theirs, 
            // because if someone else has your refresh token logging out is the nicest possible thing they could do.
            $oauth2Server->storage->unsetRefreshToken($args['refresh_token']);
        }

        // The OAuth access token is actually just a session, so we can nuke that here.
        $_SESSION = array();
        session_regenerate_id(true);

        return array('success'=>true);
    }

    /*
     * This function checks to make sure that if a client version is supplied it is up to date.
     * 
     * @param ServiceBase $api The service api
     * @param array $args The arguments passed in to the function
     * @return bool True if the version was good, false if it wasn't
     */
    public function isSupportedClientVersion(ServiceBase $api, array $args)
    {
        if (!empty($args['client_info']) // portal doesn't send client info
            && !empty($args['client_info']['app']['name'])
            && $args['client_info']['app']['name'] == 'nomad'
            && !empty($args['client_info']['app']['version'])
            && version_compare('1.0.4',$args['client_info']['app']['version'],'>') ) {
            // Version is too old, force them to upgrade.
            return false;
        }

        return true;
    }
}