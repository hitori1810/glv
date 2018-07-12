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


require_once('modules/Forecasts/clients/base/api/ForecastsFiltersApi.php');
require_once('modules/Forecasts/clients/base/api/ForecastsChartApi.php');
require_once("include/SugarTheme/SidecarTheme.php");

class ForecastsViewSidecar extends SidecarView
{
    public function __construct($bean = null, $view_object_map = array())
    {
        //Override constructor to hide footer, subpanels and search.  Also, do not use the table container for view
        $this->options['show_footer'] = false;
        $this->options['show_subpanels'] = false;
        $this->options['show_search'] = false;
        $this->options['use_table_container'] = false;
        parent::__construct($bean = null, $view_object_map = array());
    }

    /**
     * Override the display method to set Forecasts specific variables and use a custom layout template
     *
     */
    public function display()
    {
        //Load sidecar theme css
        $theme = new SidecarTheme();

        // sprites
        $sprites = "";
        if(!empty($GLOBALS['sugar_config']['use_sprites']) && $GLOBALS['sugar_config']['use_sprites']) {

            // system wide sprites
            if(file_exists(sugar_cached("sprites/default/sprites.css"))) {
                $sprites .= '<link rel="stylesheet" type="text/css" href="'.getJSPath('cache/sprites/default/sprites.css').'" />';
            }

            // theme specific sprites
            if(file_exists(sugar_cached("sprites/RacerX/sprites.css"))) {
                $sprites .= '<link rel="stylesheet" type="text/css" href="'.getJSPath('cache/sprites/RacerX/sprites.css').'" />';
            }

            // repeatable sprites
            if(file_exists(sugar_cached("sprites/Repeatable/sprites.css"))) {
                $sprites .= '<link rel="stylesheet" type="text/css" href="'.getJSPath('cache/sprites/Repeatable/sprites.css').'" />';
            }
        }

        $this->ss->assign("css_url", getVersionedPath($theme->getCSSURL()));
        $this->ss->assign("sprite_url", $sprites);
        $this->ss->assign("yui_widget_css_url", getVersionedPath("cache/include/javascript/sugar_grp_yui_widgets.css"));

        $module = $this->module;
        $displayTemplate = SugarAutoLoader::existingCustomOne("modules/Forecasts/tpls/SidecarView.tpl");

        // begin initializing all default params
        $this->ss->assign("token", session_id());
        if (!isset($_SESSION['platform']) ) {
            $_SESSION['platform'] = 'forecasts';
        }
        $this->ss->assign("module", $module);

        global $app_strings;
        $this->ss->assign("app_strings", $app_strings);

        $url = 'javascript:void(window.open(\'index.php?module=Administration&action=SupportPortal&view=documentation&version='.
            $GLOBALS['sugar_version'].'&edition='.$GLOBALS['sugar_flavor'].'&lang='.$GLOBALS['current_language'].
            '&help_module='.$module.'&key='.$GLOBALS['server_unique_key'].'\'))';

        $this->ss->assign('HELP_URL', $url);
        $this->ss->assign('MODULE_NAME', isset($GLOBALS['app_list_strings']['moduleList'][$module]) ? $GLOBALS['app_list_strings']['moduleList'][$module] : $module);
        $this->ss->assign('copyYear', date('Y'));

        if (SugarConfig::getInstance()->get('calculate_response_time', false)) {
            $this->ss->assign('STATISTICS',$this->_getStatistics());
        }

        $this->ss->display($displayTemplate);
    }

    /**
     * Returns an Array of initial default data settings for Forecasts module
     *
     * @param bool $returnOnlyUserData skip all the other initial data?
     * @return array Array of initial default data for Forecasts module
     */
    public function forecastsInitialization($returnOnlyUserData=false) {
        global $current_user, $app_list_strings;

        $returnInitData = array();

        return $returnInitData;

        $defaultSelections = array();

        require_once('modules/Forecasts/clients/base/api/ForecastsCurrentUserApi.php');
        $forecastsCurrentUserApi = new ForecastsCurrentUserApi();
        $data = $forecastsCurrentUserApi->retrieveCurrentUser($forecastsCurrentUserApi,array());
        $selectedUser = $data["current_user"];
        $returnInitData["initData"]["selectedUser"] = $selectedUser;
        $defaultSelections["selectedUser"] = $selectedUser;

        if(!$returnOnlyUserData) {
            $forecasts_timeframes_dom = TimePeriod::get_not_fiscal_timeperiods_dom();
            // TODO:  These should probably get moved in with the config/admin settings, or by themselves since this file will probably going away.
            $id = TimePeriod::getCurrentId();
            $defaultSelections["timeperiod_id"]["id"] = $id ? $id : '';
            $defaultSelections["timeperiod_id"]["label"] = $id ? $forecasts_timeframes_dom[$id] : '';

            // INVESTIGATE:  these need to be more dynamic and deal with potential customizations based on how filters are built in admin and/or studio
            $admin = BeanFactory::getBean("Administration");
            $forecastsSettings = $admin->getConfigForModule("Forecasts", "base");
            $defaultSelections["ranges"] = array("include");
            $defaultSelections["group_by"] = 'forecast';
            $defaultSelections["dataset"] = 'likely';
        }
        // push in defaultSelections
        $returnInitData["defaultSelections"] = $defaultSelections;

        return $returnInitData;
    }


    /**
     * Override the _displayJavascript function to output sidecar libraries for this view
     * Todo: Change to use minified libraries or at least allow for some way (developerMode?) to switch to non-minified
     */
    public function _displayJavascript()
    {
        $this->_displayJavascriptCore();

        //load 3rd party libs for sidecar
        echo getVersionedScript("cache/include/javascript/sugar_grp1_sidecar_libs.js") . "\n";

        if ( !inDeveloperMode() )
        {
            echo getVersionedScript("sidecar/minified/sidecar.lite.min.js") . "\n";

            if  ( !is_file(sugar_cached("include/javascript/sidecar_forecasts.js")) ) {
                $_REQUEST['root_directory'] = ".";
                require_once("jssource/minify_utils.php");
                ConcatenateFiles(".");
            }
            echo getVersionedScript('cache/include/javascript/sidecar_forecasts.js') . "\n";

        } else {

            //Need to make sure that we really do have sidecar/src directory
            if(file_exists('sidecar/src/include-manifest.php')) {
                require('sidecar/src/include-manifest.php');
                if(!empty($buildFiles['sidecar.lite'])) {
                    foreach ( $buildFiles['sidecar.lite'] as $file)
                    {
                        echo "<script type='text/javascript' src='sidecar/{$file}'></script>\n";
                    }
                }
            } else {
                echo getVersionedScript("sidecar/minified/sidecar.lite.min.js") . "\n";
            }

            require_once('jssource/JSGroupings.php');
            if ( !empty($sidecar_forecasts) && is_array($sidecar_forecasts) )
            {
                foreach ( $sidecar_forecasts as $_file => $dist )
                {
                    echo "<script src='".$_file."'></script>\n";
                }
            }
        }
    }

    protected function _displayJavascriptCore()
    {
        global $locale, $sugar_config, $timedate;


        if ($this->_getOption('show_javascript')) {
            if (!$this->_getOption('show_header')) {
                $langHeader = get_language_header();

                echo <<<EOHTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html {$langHeader}>
<head>
EOHTML;
            }

            $js_vars = array(
                "sugar_cache_dir" => "cache/",
                );

            if(isset($this->bean->module_dir)){
                $js_vars['module_sugar_grp1'] = $this->bean->module_dir;
            }
            if(isset($_REQUEST['action'])){
                $js_vars['action_sugar_grp1'] = $_REQUEST['action'];
            }
            echo '<script>jscal_today = 1000*' . $timedate->asUserTs($timedate->getNow()) . '; if(typeof app_strings == "undefined") app_strings = new Array();</script>';
            if (!is_file(sugar_cached("include/javascript/sugar_grp1.js")) || !is_file(sugar_cached("include/javascript/sugar_grp1_yui.js")) || !is_file(sugar_cached("include/javascript/sugar_grp1_jquery_core.js")) || !is_file(sugar_cached("include/javascript/sugar_grp1_jquery_menus.js"))) {
                $_REQUEST['root_directory'] = ".";
                require_once("jssource/minify_utils.php");
                ConcatenateFiles(".");
            }
            echo getVersionedScript('cache/include/javascript/sugar_grp1_jquery_core.js');
            echo getVersionedScript('cache/include/javascript/sugar_grp1_jquery_menus.js');
            echo getVersionedScript('cache/include/javascript/sugar_grp1_bootstrap.js');
            echo getVersionedScript('cache/include/javascript/sugar_grp1_yui.js');
            echo getVersionedScript('cache/include/javascript/sugar_grp_yui_widgets.js');
            echo getVersionedScript('cache/include/javascript/sugar_grp1.js');
            echo getVersionedScript('include/javascript/calendar.js');


            // output necessary config js in the top of the page
            $config_js = $this->getSugarConfigJS();
            if(!empty($config_js)){
                echo "<script>\n".implode("\n", $config_js)."</script>\n";
            }

            if ( isset($sugar_config['email_sugarclient_listviewmaxselect']) ) {
                echo "<script>SUGAR.config.email_sugarclient_listviewmaxselect = {$GLOBALS['sugar_config']['email_sugarclient_listviewmaxselect']};</script>";
            }

            $image_server = (defined('TEMPLATE_URL'))?TEMPLATE_URL . '/':'';
            echo '<script type="text/javascript">SUGAR.themes.image_server="' . $image_server . '";</script>'; // cn: bug 12274 - create session-stored key to defend against CSRF
            echo '<script type="text/javascript">var name_format = "' . $locale->getLocaleFormatMacro() . '";</script>';
            echo self::getJavascriptValidation();
            if (!is_file(sugar_cached('jsLanguage/') . $GLOBALS['current_language'] . '.js')) {
                require_once ('include/language/jsLanguage.php');
                jsLanguage::createAppStringsCache($GLOBALS['current_language']);
            }
            echo getVersionedScript('cache/jsLanguage/'. $GLOBALS['current_language'] . '.js', $GLOBALS['sugar_config']['js_lang_version']);

            echo $this->_getModLanguageJS();
            echo getVersionedScript('include/javascript/productTour.js');
            if(isset( $sugar_config['disc_client']) && $sugar_config['disc_client'])
                echo getVersionedScript('modules/Sync/headersync.js');

            if (!is_file(sugar_cached("Expressions/functions_cache.js"))) {
                $GLOBALS['updateSilent'] = true;
                include("include/Expressions/updatecache.php");
            }
            if(inDeveloperMode())
                echo getVersionedScript('cache/Expressions/functions_cache_debug.js');
            else
                echo getVersionedScript('cache/Expressions/functions_cache.js');

            require_once("include/Expressions/DependencyManager.php");
            echo "\n" . '<script type="text/javascript">' . DependencyManager::getJSUserVariables($GLOBALS['current_user']) . "</script>\n";

            //echo out the $js_vars variables as javascript variables
            echo "<script type='text/javascript'>\n";
            foreach($js_vars as $var=>$value)
            {
                echo "var {$var} = '{$value}';\n";
            }
            echo "</script>\n";
        }
    }
}
