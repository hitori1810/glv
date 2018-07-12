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

$viewdefs['Cases']['EditView'] = array(
    'templateMeta' => array('maxColumns' => '2',
                            'widths' => array(
                                            array('label' => '10', 'field' => '30'),
                                            array('label' => '10', 'field' => '30')
                                            ),
                           ),
    'panels' => array (

  'lbl_case_information' =>
  array(
	  array (
	    array('name'=>'case_number', 'type'=>'readonly') ,
	  ),

	  array (
	    'priority',
	  ),

	  array (
	    'status',
	    'account_name',
	  ),

	  array (
	      'type',
	  ),
	  array (
	    array (
	      'name' => 'name',
	      'displayParams' => array ('size'=>75)
	    ),
	  ),

	  array (

	    array (
	      'name' => 'description',
	      'nl2br' => true,
	    ),
	  ),

	  array (

	    array (
	      'name' => 'resolution',
	      'nl2br' => true,
	    ),
	  ),

	  array(
		  array('name'=>'portal_viewable',
		  		'label' => 'LBL_SHOW_IN_PORTAL',
		        'hideIf' => 'empty($PORTAL_ENABLED)',
		  ),
	  )
	),

	'LBL_PANEL_ASSIGNMENT' =>
	array(
	   array (
		    'assigned_user_name',
		    array('name'=>'team_name', 'displayParams'=>array('required'=>true)),
	   ),
	),
),


);
?>