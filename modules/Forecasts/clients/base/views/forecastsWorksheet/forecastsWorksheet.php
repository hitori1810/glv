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


$viewdefs['Forecasts']['base']['view']['forecastsWorksheet'] = array(
    'panels' =>
    array(
        0 =>
        array(
            'label' => 'LBL_PANEL_1',
            'fields' =>
            array(
                array(
                    'name' => 'commit_stage',
                    'type' => 'commitStage',
                    'options' => 'commit_stage_dom',
                    'searchBarThreshold' => 5,
                    'label' => 'LBL_FORECAST',
                    'default' => true,
                    'enabled' => true
                ),
                array(
                    'name' => 'name',
                    'label' => 'LBL_NAME',
                    'link' => true,
                    'route' =>
                    array(
                        'module'=>'Opportunities',
                        'action'=>'DetailView',
                        'recordID'=>'id'
                    ),
                    'default' => true,
                    'enabled' => true,
                    'type' => 'recordLink'
                ),

                array(
                    'name' => 'date_closed',
                    'label' => 'LBL_DATE_CLOSED',
                    'default' => true,
                    'enabled' => true,
                    'type' => 'date',
                    'view' => 'default',					
                ),

                array(
                    'name' => 'sales_stage',
                    'label' => 'LBL_SALES_STAGE',
                    'default' => true,
                    'enabled' => true,
                ),

                array(
                    'name' => 'probability',
                    'label' => 'LBL_OW_PROBABILITY',
                    'type' => 'editableInt',
                    'default' => true,
                    'enabled' => true,
                    'maxValue' => 100,
                    'minValue' => 0,
                ),

                array(
                    'name' => 'likely_case',
                    'label' => 'LBL_LIKELY_CASE',
                    'type' => 'editableCurrency',
                    'default' => true,
                    'enabled' => true,
                    'convertToBase'=> true,
                    'showTransactionalAmount'=>true,
                ),

                array(
                    'name' => 'best_case',
                    'label' => 'LBL_BEST_CASE',
                    'type' => 'editableCurrency',
                    'default' => true,
                    'enabled' => true,
                    'convertToBase'=> true,
                    'showTransactionalAmount'=>true,
                ),

                array(
                    'name' => 'worst_case',
                    'type' => 'editableCurrency',
                    'label' => 'LBL_WORST_CASE',
                    'default' => true,
                    'enabled' => true,
                    'convertToBase'=> true,
                    'showTransactionalAmount'=>true,
                ),
            ),
        ),
    ),
);
