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


require_once('include/api/ConfigModuleApi.php');

class ForecastsConfigApi extends ConfigModuleApi {

    /**
     * Save function for the config settings for a given module.
     * @param $api
     * @param $args 'module' is required, 'platform' is optional and defaults to 'base'
     */
    public function configSave($api, $args) {

        //acl check, only allow if they are module admin
        if(!parent::hasAccess("Forecasts")) {
            throw new SugarApiExceptionNotAuthorized("Current User not authorized to change Forecasts configuration settings");
        }

        $platform = (isset($args['platform']) && !empty($args['platform'])) ?$args['platform'] : 'base';

        $admin = BeanFactory::getBean('Administration');
        //track what settings have changed to determine if timeperiods need rebuilt
        $prior_forecasts_settings = $admin->getConfigForModule('Forecasts', $platform);

        //If this is a first time setup, default prior settings for timeperiods to 0 so we may correctly recalculate
        //how many timeperiods to build forward and backward.  If we don't do this we would need the defaults to be 0
        if (empty($prior_forecasts_settings['is_setup']))
        {
            $prior_forecasts_settings['timeperiod_shown_forward'] = 0;
            $prior_forecasts_settings['timeperiod_shown_backward'] = 0;
        }
        
        $upgraded = 0;
        if (!empty($prior_forecasts_settings['is_upgrade']))
        {
            $db = DBManagerFactory::getInstance();
            // check if we need to upgrade opportunities when coming from version below 6.7.x.
            $upgraded = $db->getOne("SELECT count(id) as total FROM upgrade_history WHERE type = 'patch' AND status = 'installed' AND version LIKE '6.7.%'");
            if ($upgraded == 1)
            {
                //TODO-sfa remove this once the ability to map buckets when they get changed is implemented (SFA-215).
                $args['has_commits'] = true;
            }
        }
        
        if($upgraded || empty($prior_forecasts_settings['is_setup']))
        {
        	 require_once('modules/UpgradeWizard/uw_utils.php');
             updateOpportunitiesForForecasting();
        }

        //reload the settings to get the current settings
        $current_forecasts_settings = parent::configSave($api, $args);

        //if primary settings for timeperiods have changed, then rebuild them
        if($this->timePeriodSettingsChanged($prior_forecasts_settings, $current_forecasts_settings)) {
            $timePeriod = TimePeriod::getByType($current_forecasts_settings['timeperiod_interval']);
            $timePeriod->rebuildForecastingTimePeriods($prior_forecasts_settings, $current_forecasts_settings);
        }
        return $current_forecasts_settings;
    }

    /**
     * compares two sets of forecasting settings to see if the primary timeperiods settings are the same
     *
     * @param $priorSettings
     * @param $currentSettings
     *
     * @return boolean
     */
    private function timePeriodSettingsChanged($priorSettings, $currentSettings) {
        if(!isset($priorSettings['timeperiod_shown_backward']) || (isset($currentSettings['timeperiod_shown_backward']) && ($currentSettings['timeperiod_shown_backward'] != $priorSettings['timeperiod_interval']))) {
            return true;
        }
        if(!isset($priorSettings['timeperiod_shown_forward']) || (isset($currentSettings['timeperiod_shown_forward']) && ($currentSettings['timeperiod_shown_forward'] != $priorSettings['timeperiod_type']))) {
            return true;
        }
        if(!isset($priorSettings['timeperiod_interval']) || (isset($currentSettings['timeperiod_interval']) && ($currentSettings['timeperiod_interval'] != $priorSettings['timeperiod_interval']))) {
            return true;
        }
        if(!isset($priorSettings['timeperiod_type']) || (isset($currentSettings['timeperiod_type']) && ($currentSettings['timeperiod_type'] != $priorSettings['timeperiod_type']))) {
            return true;
        }
        if(!isset($priorSettings['timeperiod_start_date']) || (isset($currentSettings['timeperiod_start_date']) && ($currentSettings['timeperiod_start_date'] != $priorSettings['timeperiod_start_date']))) {
            return true;
        }
        if(!isset($priorSettings['timeperiod_leaf_interval']) || (isset($currentSettings['timeperiod_leaf_interval']) && ($currentSettings['timeperiod_leaf_interval'] != $priorSettings['timeperiod_leaf_interval']))) {
            return true;
        }

        return false;
    }

}
