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

$viewdefs['Forecasts']['base']['view']['forecastsConfigWizardButtons'] = array(
    'panels' => array(
        array(
            'buttons' => array(
                array(
                    'name' => 'start_button',
                    'type' => 'button',
                    'css_class' => 'btn-primary pull-right',
                    'label' => 'LBL_START_BUTTON_LABEL',
                    'primary' => true,
                ),
                array(
                    'name' => 'done_button',
                    'type' => 'button',
                    'css_class' => 'btn-primary pull-right hide',
                    'label' => 'LBL_FINISH_BUTTON_LABEL',
                    'primary' => true,
                ),
                array(
                    'name' => 'next_button',
                    'type' => 'button',
                    'css_class' => 'btn-primary pull-right hide',
                    'label' => 'LNK_LIST_NEXT',
                    'primary' => false,
                ),
                array(
                    'name' => 'previous_button',
                    'type' => 'button',
                    'css_class' => 'disabled pull-right hide',
                    'label' => 'LNK_LIST_PREVIOUS',
                    'primary' => false,
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