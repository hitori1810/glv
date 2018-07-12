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


$dictionary['cron_remove_documents'] = array (
    'table' => 'cron_remove_documents',
    'fields' => array(
        array(
            'name' => 'id',
            'type' => 'varchar',
            'len' => '36'
        ),
        array(
            'name' => 'bean_id',
            'type' => 'varchar',
            'len' => '36'
        ),
        array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => '25'
        ),
        array(
            'name' =>'date_modified',
            'type' => 'datetime'
        )
    ),
    'indices' => array(
        array(
            'name' => 'cron_remove_documentspk',
            'type' =>'primary',
            'fields'=>array('id')
        ),
        array(
            'name' => 'idx_cron_remove_document_bean_id',
            'type' => 'index',
            'fields' => array('bean_id')
        ),
        array(
            'name' => 'idx_cron_remove_document_stamp',
            'type' => 'index',
            'fields' => array('date_modified')
        )
    )
);
