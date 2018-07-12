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

/*********************************************************************************

 * Description:  Contains field arrays that are used for caching
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
$fields_array['Project'] = array ('column_fields' => array(
        'id',
        'date_entered',
        'date_modified',
        'assigned_user_id',
        'modified_user_id',
        'created_by',
        'team_id',
        'name',
        'description',
        'deleted',
        'priority',
        'status',
        'estimated_start_date',
        'estimated_end_date',
        
    ),
        'list_fields' =>  array(
        'id',
        'assigned_user_id',
        'assigned_user_name',
        'team_id',
        'team_name',
        'name',
        'relation_id',
        'relation_name',
        'relation_type',
        'total_estimated_effort',
        'total_actual_effort',
        'status',
        'priority',     
        
    ),
    'required_fields' =>  array('name'=>1, 'estimated_start_date'=>2, 'estimated_end_date'=>3),
);
?>