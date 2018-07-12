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

$viewdefs['Cases']['EditView']  = array(
    'templateMeta' => array('maxColumns' => '2',
                            'widths' => array(
                                            array('label' => '10', 'field' => '30'),
                                            array('label' => '10', 'field' => '30')
                                            ),
                            'formId' => 'CaseEditView',
                            'formName' => 'CaseEditView',
                            'hiddenInputs' => array('module' => 'Cases',
                                                    'returnmodule' => 'Cases',
                                                    'returnaction' => 'DetailView',
                                                    'contact_id' => '{$fields.contact_id.value}',
                                                    'bug_id' => '{$fields.bug_id.value}',
                                                    'email_id' => '{$fields.email_id.value}',
                                                    'action' => 'Save',
                                                    'type' => '{$fields.type.value}',
                                                    'status' => 'New',
                                                   ),
							'hiddenFields' => array(
							    array (
	                               'name'=>'portal_viewable',
	                               'operator'=>'=',
	                               'value'=>'1',
							    ),
						    ),
                           ),
    'panels' => array(
        array(array('name' => 'case_number', 'readOnly' => true)),
        array('priority', 'status'),
        array(array('name' => 'name', 'displayParams' => array('size' => 60), 'required'=>true)),
        array(array('name' => 'description', 'displayParams' => array('rows' => '15', 'cols' => '100'))),
    )
);
?>
