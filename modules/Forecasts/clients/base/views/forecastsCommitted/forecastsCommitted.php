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


$viewdefs['Forecasts']['base']['view']['forecastsCommitted'] = array(
    'panels' =>
    array(
        0 =>
        array(
            'label' => 'LBL_PANEL_1',
            'fields' =>
            array(
                array(
                    'name' => 'id',
                    'label' => 'LBL_ID',
                    'default' => true,
                    'enabled' => true,
                ),
                array(
                    'name' => 'date_entered',
                    'label' => 'LBL_DATE_ENTERED',
                    'default' => true,
                    'enabled' => true,
                ),

                array(
                    'name' => 'best_case',
                    'label' => 'LBL_BEST_CASE',
                    'default' => true,
                    'enabled' => true,
                ),

                array(
                    'name' => 'likely_case',
                    'label' => 'LBL_LIKELY_CASE',
                    'default' => true,
                    'enabled' => true,
                ),

                array(
                    'name' => 'worst_case',
                    'label' => 'LBL_WORST_CASE',
                    'default' => true,
                    'enabled' => true,
                ),
            ),
        ),
    ),
);