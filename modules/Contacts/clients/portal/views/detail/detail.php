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


$viewdefs ['Contacts']['portal']['view']['detail'] =
    array(
        'buttons' =>
        array(
            array(
                'name' => 'edit_button',
                'type' => 'button',
                'label' => 'LBL_EDIT_BUTTON_LABEL',
                'value' => 'edit',
                'class' => 'edit-profile',
                'css_class' => 'btn-primary',
                'events' =>
                array(
                    'click' => 'function(e){ app.router.navigate("profile/edit", {trigger:true});}'
                ),
            ),
        ),
        'templateMeta' =>
        array(
            'maxColumns' => '2',
            'widths' =>
            array(
                array(
                    'label' => '10',
                    'field' => '30',
                ),
                array(
                    'label' => '10',
                    'field' => '30',
                ),
            ),
            'useTabs' => false,
        ),
        'panels' =>
        array(
            array(
                'label' => 'LBL_PANEL_DEFAULT',
                'fields' =>
                array(
                    array(
                        'name' => 'first_name',
                        'displayParams' =>
                        array(
                            'colspan' => 2,
                        ),
                    ),
                    array(
                        'name' => 'last_name',
                        'displayParams' =>
                        array(
                            'colspan' => 2,
                        ),
                    ),
                    array(
                        'name' => 'title',
                        'displayParams' =>
                        array(
                            'colspan' => 2,
                        ),
                    ),
                    array(
                        'name' => 'email',
                        'type' => 'email',
                        'displayParams' =>
                        array(
                            'colspan' => 2,
                        ),
                    ),
                    array(
                        'name' => 'phone_work',
                        'type' => 'text',
                        'displayParams' =>
                        array(
                            'colspan' => 2,
                        ),
                    ),
                    array (
                        'name' => 'primary_address_street',
                        'label' => 'LBL_PRIMARY_ADDRESS',
                        'type' => 'address',
                        'fields' => array(
                            'primary_address_street',
                            'primary_address_city',
                            'primary_address_state',
                            'primary_address_postalcode',
                            'primary_address_country'
                        ),
                        'displayParams' =>
                        array (
                            'colspan' => 2
                        ),
                    ),
                    array (
                        'name' => 'preferred_language',
                        'type' => 'enum',
                        'options' => 'available_language_dom',
                    ),
                ),
            ),
        ),
    );
?>
