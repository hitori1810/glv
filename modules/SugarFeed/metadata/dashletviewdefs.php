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




global $current_user;

$dashletData['SugarFeedDashlet']['searchFields'] = array('date_entered'     => array('default' => ''),
                                                          'date_modified'    => array('default' => ''),
                                                          'team_id'          => array('default' => ''),
                                                          'assigned_user_id' => array('type'    => 'assigned_user_name',
                                                                                      'default' => $current_user->name));
$dashletData['SugarFeedDashlet']['columns'] =  array(   'name' => array('width'   => '40',
                                                                      'label'   => '',
                                                                      'link'    => false,
																	  'sortable'=>false,
                                                                      'default' => true), 

                                               );