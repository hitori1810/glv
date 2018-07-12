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


$viewdefs ['Contacts']['portal']['view']['edit'] = array(
    'buttons' =>
        array(
            array(
                'name' => 'cancel_button',
                'type' => 'button',
                'label' => 'LBL_CANCEL_BUTTON_LABEL',
                'value' => 'cancel',
                'events' =>
                array(
                    'click' => 'function(){ window.history.back(); }',
                ),
                'css_class' => 'btn-invisible btn-link',
            ),
            array(
                'name' => 'save_button',
                'type' => 'button',
                'label' => 'LBL_SAVE_BUTTON_LABEL',
                'value' => 'save',
                'class' => 'save-profile',
                'css_class' => 'btn-primary',
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
            'formId' => 'ContactsEditView',
            'formName' => 'ContactsEditView',
            'hiddenInputs' =>
            array(
                'module' => 'Contacts',
                'returnmodule' => 'Contacts',
                'returnaction' => 'DetailView',
                'action' => 'Save',
            ),
            'hiddenFields' =>
            array(
                array(
                    'name' => 'portal_viewable',
                    'operator' => '=',
                    'value' => '1',
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
                        'displayParams' =>
                        array(
                            'colspan' => 2,
                        ),
                    ),
                    array(
                        'name' => 'portal_password',
                        'type' => 'url',
                        'label' => 'LBL_CONTACT_EDIT_PASSWORD',
                        'class' => 'password',
                        'view' => 'detail',
                        'displayParams' =>
                        array(
                            'colspan' => 2,
                        ),
                    ),
                    array(
                        'name' => 'phone_work',
                        'displayParams' =>
                        array(
                            'colspan' => 2,
                        ),
                    ),
                    array(
                        'name' => 'primary_address_street',
                        'displayParams' =>
                        array(
                            'colspan' => 2,
                        ),
                    ),
                    array(
                        'name' => 'primary_address_city',
                        'displayParams' =>
                        array(
                            'colspan' => 2,
                        ),
                    ),
                    array(
                        'name' => 'primary_address_state',
                        'options' => 'state_dom',
                        'displayParams' =>
                        array(
                            'colspan' => 2,
                        ),
                    ),
                    array(
                        'name' => 'primary_address_postalcode',
                        'displayParams' =>
                        array(
                            'colspan' => 2,
                        ),
                    ),
                    array (
                        'name' => 'primary_address_country',
                        'options' => 'countries_dom',
                        'displayParams' =>
                        array(
                            'colspan' => 2,
                        ),
                    ),
                    array (
                        'name' => 'preferred_language',
                        'options' => 'available_language_dom',
                    ),
                ),
            ),
        ),
);
