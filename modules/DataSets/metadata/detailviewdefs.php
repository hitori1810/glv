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

$viewdefs['DataSets']['DetailView'] = array(
    'templateMeta' => array('maxColumns' => '2',
                            'widths' => array(
                                            array('label' => '10', 'field' => '30'),
                                            array('label' => '10', 'field' => '30')
                                            ),
    ),
 'panels' =>array (
  'default' =>
  array (

    array (
	  'name',
      array (
        'name' => 'report_name',
        'customCode' => '<a href="index.php?module=ReportMaker&action=DetailView&record={$fields.report_id.value}">{$fields.report_name.value}</a>',
      ),
    ),

    array (
      array('name'=>'query_name', 'type'=>'varchar'),
      'parent_name',
    ),

    array (
      array (
        'name' => 'child_name',
        'customCode' => '{if isset($bean->child_id) && !empty($bean->child_id)}
						 <a href="index.php?module=DataSets&action=DetailView&record={$bean->child_id}">{$bean->child_name}</a>
						 {else}
						 {$bean->child_name}
						 {/if}'
      ),
      'team_name',
    ),

    array (
      'description',
    ),

    array (

      array (
        'name' => 'table_width',
        'fields'=>array('table_width', 'table_width_type'),
        //'customCode' => '{$fields.table_width.value} {$APP.width_type_dom[$fields.table_width_type.value]}',
      ),
      'font_size',
    ),

    array (
      'exportable',
      'header_text_color',
    ),

    array (
      'header',
      'body_text_color',
    ),

    array (
      'prespace_y',
      'header_back_color',
    ),

    array (
      'use_prev_header',
      'body_back_color',
    ),


  ),
)


);
?>