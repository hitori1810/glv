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

$viewdefs['Forecasts']['base']['view']['forecastsConfigTabbedButtons'] = array(
    'panels' => array(
        array(
            'buttons' => array(
                array(
                    'name' => 'save_button',
                    'type' => 'button',
                    'css_class' => 'btn-primary pull-right disabled',
                    'label' => 'LBL_SAVE_BUTTON_LABEL',
                    'primary' => true,
                ),
                array(
                    'name' => 'close_button',
                    'type' => 'button',
                    'css_class' => 'btn-invisible btn-link pull-right',
                    'label' => 'LBL_EMAIL_CANCEL',
                    'primary' => false,
                ),
            ),
        ),
    ),
);
