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


require_once('data/SugarBeanApiHelper.php');

class ContactsApiHelper extends SugarBeanApiHelper
{
    /**
     * This function checks the sync_contact var and does the appropriate actions
     * @param SugarBean $bean
     * @param array $submittedData
     * @param array $options
     * @return array
     */
    public function populateFromApi(SugarBean $bean, array $submittedData, array $options = array())
    {
        global $current_user;
        $data = parent::populateFromApi($bean, $submittedData, $options);

        if(!empty($submittedData['sync_contact'])) {
            $bean->contacts_users_id = $current_user->id;
        }
        else {
            if (!isset($bean->users)) {
                $bean->load_relationship('user_sync');
            }
            $bean->contacts_users_id = null;
            $bean->user_sync->delete($bean->id, $current_user->id);            
        }

        return $data;
    }


}
