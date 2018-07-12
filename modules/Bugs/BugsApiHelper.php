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

class BugsApiHelper extends SugarBeanApiHelper
{
    /**
     * This function adds the contact and account relationships for new bugs submitted via portal users
     * @param SugarBean $bean
     * @param array $submittedData
     * @param array $options
     * @return array
     */
    public function populateFromApi(SugarBean $bean, array $submittedData, array $options = array())
    {
        $data = parent::populateFromApi($bean, $submittedData, $options);
        if (isset($_SESSION['type']) && $_SESSION['type'] == 'support_portal') {
            if (empty($bean->id)) {
                $bean->id = create_guid();
                $bean->new_with_id = true;
            }
            $contact = BeanFactory::getBean('Contacts',$_SESSION['contact_id']);
            $account = $contact->account_id;

            $bean->assigned_user_id = $contact->assigned_user_id;

            $support_portal_user = BeanFactory::getBean('Users', $_SESSION['authenticated_user_id']);

            $bean->team_id = $contact->fetched_row['team_id'];
            $bean->team_set_id = $contact->fetched_row['team_set_id'];
            $bean->load_relationship('contacts');
            $bean->contacts->add($contact->id);
            $bean->load_relationship('accounts');
            $bean->accounts->add($account);
        }
        // not support_ports
        else
        {
            $bean->assigned_user_id = $_SESSION['authenticated_user_id'];
        }
        return $data;
    }
}
