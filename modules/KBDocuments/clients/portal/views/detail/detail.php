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

$viewdefs['KBDocuments']['portal']['view']['detail'] = array(
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
    ),
    'panels' =>
    array(
        array(
            'label' => 'LBL_PANEL_DEFAULT',
            'fields' => array(
                array (
                    'name' => 'name',
                    'displayParams' =>
                    array (
                        'colspan' => 2,
                    ),
                ),
                array (
                    'name' => 'body',
                    'displayParams' =>
                    array (
                        'colspan' => 2,
                    ),
                ),
                array (
                    'name' => 'active_date',
                    'label' => 'LBL_DOC_ACTIVE_DATE',
                    'displayParams' =>
                    array (
                        'colspan' => 2,
                    ),
                ),
                array (
                    'name' => 'date_modified',
                    'displayParams' =>
                    array (
                        'colspan' => 2,
                    ),
                ),
                array (
                    'name' => 'exp_date',
                    'displayParams' =>
                    array (
                        'colspan' => 2,
                    ),
                ),
                array(
                    'name'  => 'attachment_list',
                    'label' => 'LBL_ATTACHMENTS',
                    'type' => 'file'
                )
            )
        )
    ),
);
?>
