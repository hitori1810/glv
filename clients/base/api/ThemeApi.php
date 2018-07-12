<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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



require_once('include/lessphp/lessc.inc.php');
require_once('include/SugarTheme/SidecarTheme.php');

class ThemeApi extends SugarApi
{
    public function registerApiRest()
    {
        return array(
            'previewCSS' => array(
                'reqType' => 'GET',
                'path' => array('css'),
                'pathVars' => array(''),
                'method' => 'previewCSS',
                'shortHelp' => 'Generate the bootstrap.css file',
                'longHelp' => 'include/api/help/themePreview.html',
                'noLoginRequired' => true,
            ),
            'getCustomThemeVars' => array(
                'reqType' => 'GET',
                'path' => array('theme'),
                'pathVars' => array(''),
                'method' => 'getCustomThemeVars',
                'shortHelp' => 'Get the customizable variables of a custom theme',
                'longHelp' => 'include/api/help/themeGet.html',
                'noLoginRequired' => true,
            ),
            'updateCustomTheme' => array(
                'reqType' => 'POST',
                'path' => array('theme'),
                'pathVars' => array(''),
                'method' => 'updateCustomTheme',
                'shortHelp' => 'Update the customizable variables of a custom theme',
                'longHelp' => 'include/api/help/themePost.html',
            ),
        );
    }

    /**
     * Generate bootstrap.css
     * @param $api
     * @param $args
     * @return plain text/css or css file url
     */
    public function previewCSS($api, $args)
    {
        // Validating arguments
        $platform = isset($args['platform']) ? $args['platform'] : 'base';
        $themeName = isset($args['themeName']) ? $args['themeName'] : 'default';
        $minify = isset($args['min']) ? true : false;

        $theme = new SidecarTheme($platform, $themeName);

        // If `preview` is defined, it means that the call was made by the Theme Editor in Studio so we want to return
        // plain text/css
        if (isset($args['preview'])) {
            $variables = $theme->getThemeVariables();
            $variables = array_merge($variables, $args);
            $variables['baseUrl'] = '"../../styleguide/assets"';
            $css = $theme->compileBootstrapCss($variables, $minify);

            header('Content-type: text/css');
            exit($css);
        } else {
            // Otherwise we just return the CSS Url so the application can load the CSS file.
            // getCSSURL method takes of generating bootstrap.css if it doesn't exist in cache.
            return array("url" => $theme->getCSSURL());
        }

    }

    /**
     * Parses variables.less and returns a collection of objects {"name": varName, "value": value}
     * @param $api
     * @param $args
     * @return array
     */
    public function getCustomThemeVars($api, $args)
    {
        // Validating arguments
        $platform = isset($args['platform']) ? $args['platform'] : 'base';
        $themeName = isset($args['themeName']) ? $args['themeName'] : null;

        $theme = new SidecarTheme($platform, $themeName);
        $paths = $theme->getPaths();
        $variables = $theme->getThemeVariables(true);

        return $variables;
    }

    /**
     * Updates variables.less with the values given in the request.
     * @param $api
     * @param $args
     * @return mixed|string
     * @throws SugarApiExceptionMissingParameter
     */
    public function updateCustomTheme($api, $args)
    {
        if(!is_admin($GLOBALS['current_user'])) {
            throw new SugarApiExceptionNotAuthorized();
        }

        if (empty($args)) {
            throw new SugarApiExceptionMissingParameter('Missing colors');
        }

        // Validating arguments
        $platform = isset($args['platform']) ? $args['platform'] : 'base';
        $themeName = isset($args['themeName']) ? $args['themeName'] : null;

        $theme = new SidecarTheme($platform, $themeName);

        // if reset=true is passed
        if (isset($args['reset']) && $args['reset'] == true) {
            $theme->resetDefault();

        } else {
            // else
            // Override the custom variables.less with the given vars
            $variables = array_diff_key($args, array('platform' => 0, 'themeName' => 0, 'reset' => 0 ));
            $theme->overrideThemeVariables($variables);
        }

        // Write the bootstrap.css file
        $theme->compileTheme(true);

        // saves the bootstrap.css URL in the portal settings
        $url = $GLOBALS['sugar_config']['site_url'] . '/' . $theme->getCSSURL();
        $GLOBALS ['system_config']->saveSetting($args['platform'], 'css', json_encode($url));

        return $url;
    }

}
