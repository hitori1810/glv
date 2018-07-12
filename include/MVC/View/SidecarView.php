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


/**
 * SidecarView.php
 *
 * This class extends SugarView to provide sidecar framework specific support.  Modules
 * that may wish to use the sidecar framework may extend this class to provide module
 * specific support.
 *
 * @see include/MVC/View/views/view.sidecar.php
 */

require_once('include/MVC/View/SugarView.php');

class SidecarView extends SugarView
{
    protected $configFile = "config.js";

    /**
     * This method checks to see if the configuration file exists and, if not, creates one by default
     *
     */
    public function preDisplay()
    {
        //Rebuild config file if it doesn't exist
        if(!file_exists('config.js')) {
           require_once('install/install_utils.php');
           handleSidecarConfig();
        }
        $this->ss->assign("configFile", $this->configFile);
    }

    /**
     * This method sets the config file to use and renders the template
     *
     */
    public function display()
    {
        $this->ss->display(get_custom_file_if_exists('include/MVC/View/tpls/sidecar.tpl'));
    }

    /**
     * This method returns the theme specific CSS code to be used for the view
     *
     * @return string HTML formatted string of the CSS stylesheet files to use for view
     */
    public function getThemeCss()
    {
        // this is left empty since we are generating the CSS via the API
    }

}
