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

$viewdefs['Forecasts']['base']['view']['forecastsConfigRanges'] = array(
    'registerLabelAsBreadCrumb' => true,
    'panels' => array(
        array(
            'label' => 'LBL_FORECASTS_CONFIG_BREADCRUMB_RANGES',
            'fields' => array(
                array(
                    'name' =>'forecast_ranges',
                    'type' => 'radioenum',
                    'label' => 'LBL_FORECASTS_CONFIG_RANGES_OPTIONS',
                    'view' => 'edit',
                    'options' => 'forecasts_config_ranges_options_dom',
                    'default' => false,
                    'enabled' => true,
                ),
                array(
                    'name' => 'category_ranges',
                    'ranges' => array(
                        array(
                            'name' => 'include',
                            'type' => 'range',
                            'view' => 'edit',
                            'sliderType' => 'connected',
                            'minRange' => 0,
                            'maxRange' => 100,
                            'default' => true,
                            'enabled' => true,
                        ),
                        array(
                            'name' => 'upside',
                            'type' => 'range',
                            'view' => 'edit',
                            'sliderType' => 'connected',
                            'minRange' => 0,
                            'maxRange' => 100,
                            'default' => true,
                            'enabled' => true,
                        ),
// TODO-sfa: 6.8 - SFA-196: implement custom buckets
                    ),
                ),
                array(
                    'name' => 'buckets_dom',
                    'options' => array(
                        'show_binary' => 'commit_stage_binary_dom',
                        'show_buckets' => 'commit_stage_dom',
// TODO-sfa: 6.8 - SFA-196: implement custom buckets
                    )
                )
            )
        ),
    ),
);
