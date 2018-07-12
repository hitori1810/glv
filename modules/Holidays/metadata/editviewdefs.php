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

    $viewdefs['Holidays']['EditView'] = array(
        'templateMeta' => array(
            'form' =>
            array (
                'hidden' =>
                array (
                    1 => '<link rel="stylesheet" href="{sugar_getjspath file="custom/include/javascripts/MultiDatesPicker/css/jquery-ui.theme.css"}"/>',
                ),
            ),
            'maxColumns' => '2',
            'widths' => array(
                array('label' => '10', 'field' => '30'),
                array('label' => '10', 'field' => '30')
            ),
            'javascript' => '{sugar_getscript file="modules/Holidays/js/quickcreate.js"}',
        ),
        'panels' =>array (
            'default' =>
            array (
                array (

                    array (
                        'name' => 'public_holiday',
                        'label' => 'LBL_PUBLIC_HOLIDAY',
                        'customCode' => '<input type="hidden" name="public_holiday" id="public_holiday" value="{$fields.public_holiday.value}"><div id="full_year" class="box"></div>'
                    ),
                ),

                array (

                    array (
                        'name' => 'holidays_range',
                        'customCode' => '<input type="text" name="holidays_range" id="holidays_range" size="50" maxlength="100" value=""><div id="date-range12-container" style="display: block;"></div>',
                    ),
                ),

                array (

                    array (
                        'name' => 'type',
                    ),
                ),
                array (
                    array (
                        'name' => 'description',
                    ),
                ),
            ),
        )


    );
?>