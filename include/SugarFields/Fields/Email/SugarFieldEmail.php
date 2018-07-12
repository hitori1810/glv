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

require_once('include/SugarFields/Fields/Base/SugarFieldBase.php');

class SugarFieldEmail extends SugarFieldBase {
    public function apiFormatField(&$data, $bean, $args, $fieldName, $properties) {
    	// need to remove Email fields if Email1 is not allowed
    	if(!empty($bean->field_defs['email']) && !empty($bean->field_defs['email1']) && !$bean->ACLFieldAccess('email1', 'access') && isset($data['email'])) {
    		unset($data['email']);
    	}
    	parent::apiFormatField($data, $bean, $args, $fieldName, $properties);
    }
	/**
     * This should be called when the bean is saved from the API. Most fields can just use default, which calls the field's individual ->save() function instead.
     * @param SugarBean $bean - the bean performing the save
     * @param array $params - an array of paramester relevant to the save, which will be an array passed up to the API
     * @param string $field - The name of the field to save (the vardef name, not the form element name)
     * @param array $properties - Any properties for this field
     */
    public function apiSave(SugarBean $bean, array $params, $field, $properties) {
		if(!empty($bean->field_defs['email']) && !empty($bean->field_defs['email1']) && !$bean->ACLFieldAccess('email1', 'edit')) {
        	throw new SugarApiExceptionNotAuthorized('No access to edit records for module: '.$bean->module);
        }
        parent::apiSave($bean, $params, $field, $properties);
    }    
}
