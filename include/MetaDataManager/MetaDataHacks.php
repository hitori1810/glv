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

/**
 * Assists in modifying the Metadata in places that the core cannot handle at this time.
 * 
 */
class MetaDataHacks {
    /**
     * Fix the ACLs for non-db fields that actually do need ACLs for
     *
     * @param array $fieldsAcls array of fields that have ACLs
     * @return array Array of fixed ACL's 
     */
	public function fixAcls(array $fieldsAcls) {
		if(isset($fieldsAcls['email1'])) {
			$fieldsAcls['email'] = $fieldsAcls['email1'];
		}
		return $fieldsAcls;
	}
}