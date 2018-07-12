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


$viewdefs['Forecasts']['base']['view']['forecastsWorksheetManager'] = array(
    'panels' =>
    array(
        0 =>
        array(
            'label' => 'LBL_PANEL_1',
            'fields' =>
            array(

                array(
                    'name' => 'name',
                    'type' => 'userLink',
                    'label' => 'LBL_NAME',
                    'link' => true,
                    'route' =>
                    array(
                        'recordID'=>'user_id'
                    ),
                    'default' => true,
                    'enabled' => true,
                ),

                array(
                    'name' => 'amount',
                    'type' => 'currency',
                    'label' => 'LBL_AMOUNT',
                    'default' => true,
                    'enabled' => false,
                    'convertToBase' => true,
                ),

                array(
                    'name' => 'quota',
                    'type' => 'editableCurrency',
                    'label' => 'LBL_QUOTA',
                    'default' => true,
                    'enabled' => true,
                    'convertToBase'=> true,
                ),

                array(
                    'name' => 'likely_case',
                    'type' => 'currency',
                    'label' => 'LBL_LIKELY_CASE',
                    'default' => true,
                    'enabled' => true,
                    'convertToBase'=> true,
                ),

                array(
                    'name' => 'likely_adjusted',
                    'type' => 'editableCurrency',
                    'label' => 'LBL_LIKELY_CASE_VALUE',
                    'default' => true,
                    'enabled' => true,
                    'convertToBase'=> true,
               ),

                array(
                    'name' => 'best_case',
                    'type' => 'currency',
                    'label' => 'LBL_BEST_CASE',
                    'default' => true,
                    'enabled' => true,
                    'convertToBase'=> true,
                ),

                array(
                    'name' => 'best_adjusted',
                    'type' => 'editableCurrency',
                    'label' => 'LBL_BEST_CASE_VALUE',
                    'default' => true,
                    'enabled' => true,
                    'convertToBase'=> true,
                ),

                array(
                    'name' => 'worst_case',
                    'type' => 'currency',
                    'label' => 'LBL_WORST_CASE',
                    'default' => true,
                    'enabled' => true,
                    'convertToBase'=> true,
                ),

                array(
                    'name' => 'worst_adjusted',
                    'type' => 'editableCurrency',
                    'label' => 'LBL_WORST_CASE_VALUE',
                    'default' => true,
                    'enabled' => true,
                    'convertToBase'=> true,
                ),

                array(
                    'name' => 'user_history_log',
                    'type' => 'historyLog',
                    'label' => '',
                    'default' => true,
                    'enabled' => true,
               ),
            ),
        ),
    ),
);
