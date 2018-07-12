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


require_once 'include/SugarFields/SugarFieldHandler.php';
require_once 'include/MetaDataManager/MetaDataManager.php';

/**
 * This class is here to provide functions to easily call in to the individual module api helpers
 */
class SugarBeanApiHelper
{
    /**
     * This is used when formatting records to do things like provide URI's for objects.
     */
    protected $api;

    public function __construct(ServiceBase $api)
    {
        $this->api = $api;
    }

    /**
     * Formats the bean so it is ready to be handed back to the API's client. Certian fields will get extra processing
     * to make them easier to work with from the client end.
     *
     * @param $bean SugarBean The bean you want formatted
     * @param $fieldList array Which fields do you want formatted and returned (leave blank for all fields)
     * @param $options array Currently no options are supported
     * @return array The bean in array format, ready for passing out the API to clients.
     */
    public function formatForApi(SugarBean $bean, array $fieldList = array(), array $options = array())
    {
        $sfh = new SugarFieldHandler();

        // if you are listing something the action is list
        // if any other format is called its a view
        $action = (!empty($options['action']) && $options['action'] == 'list') ? 'list' : 'view';

        $data = array();
        if (!SugarACL::moduleSupportsACL($bean->module_name) || $bean->ACLAccess($action)) {
            foreach ($bean->field_defs as $fieldName => $properties) {
                // Prune fields before ACL check because it can be expensive (Bug58133)
                if ( !empty($fieldList) && !in_array($fieldName,$fieldList) ) {
                    // They want to skip this field
                    continue;
                }

                $type = !empty($properties['custom_type']) ? $properties['custom_type'] : $properties['type'];
                if ($type == 'link') {
                    // There is a different API to fetch linked records, don't try to encode all of the related data.
                    continue;
                }
                $field = $sfh->getSugarField($type);

                if ( $field != null && isset($bean->$fieldName) ) {
                     $field->apiFormatField($data, $bean, $options, $fieldName, $properties);
                }

                if ( !$bean->ACLFieldAccess($fieldName,'read') ) {
                    // No read access to the field, eh?  Unset the field from the array of data returned
                    unset($data[$fieldName]);
                }
            }

            if (isset($bean->field_defs['email']) &&
                (empty($fieldList) || in_array('email',$fieldList))
                 && $bean->ACLFieldAccess('email1', 'access')
                 ) {
                $emailsRaw = $bean->emailAddress->getAddressesByGUID($bean->id, $bean->module_name);
                $emails = array();
                $emailProps = array(
                    'email_address',
                    'opt_out',
                    'invalid_email',
                    'primary_address'
                );
                foreach ($emailsRaw as $rawEmail) {
                    $formattedEmail = array();
                    foreach ($emailProps as $property) {
                        if (isset($rawEmail[$property])) {
                            $formattedEmail[$property] = $rawEmail[$property];
                        }
                    }
                    array_push($emails, $formattedEmail);
                }
                $data['email'] = $emails;
            }
            elseif(!$bean->ACLFieldAccess('email1', 'access')) {
                unset($data['email']);
            }



            // get favorites
            // mark if its a favorite

            if ( empty($fieldList) || !in_array('my_favorite',$fieldList) ) {
                if (!isset($bean->my_favorite)) {
                    $bean->my_favorite = SugarFavorites::isUserFavorite($bean->module_dir, $bean->id, $GLOBALS['current_user']->id);
                }
                $data['my_favorite'] = $bean->my_favorite;
            }


            // set ACL
            // if not an admin and the hashes differ, send back bean specific acl's
            $data['_acl'] = self::getBeanAcl($bean, $fieldList);
        } else {
            if (isset($bean->id)) {
                $data['id'] = $bean->id;
            }
        }

        return $data;
    }

    /**
     * Get the beans ACL's to pass back any that differ
     * @param  SugarBean $bean
     * @param  array     $fieldList
     * @return array
     */
    public function getBeanAcl(SugarBean $bean, array $fieldList)
    {
        $acl = array('fields' => (object) array());
        if (SugarACL::moduleSupportsACL($bean->module_dir)) {
            $mm = new MetaDataManager($GLOBALS['current_user']);
            $moduleAcl = $mm->getAclForModule($bean->module_dir, $GLOBALS['current_user']);

            $beanAcl = $mm->getAclForModule($bean->module_dir, $GLOBALS['current_user'], $bean);
            if ($beanAcl['_hash'] != $moduleAcl['_hash'] || !empty($fieldList)) {

                // diff the fields separately, they are usually empty anyway so we won't diff these often.
                $moduleAclFields = $moduleAcl['fields'];
                $beanAclFields = $beanAcl['fields'];
                // dont' need the fields here will append at the end
                unset($moduleAcl['fields']);
                unset($beanAcl['fields']);

                // don't need the hashes anymore
                unset($moduleAcl['_hash']);
                unset($beanAcl['_hash']);

                $acl = array_diff_assoc($beanAcl, $moduleAcl);
                $fieldAcls = array();

                /**
                 * Fields are different than module level acces
                 * if fields is empty that means all access is granted
                 * beanAclFields is empty and moduleAclFields is empty -> all access -> return empty
                 * beanAclFields is empty and moduleAclFields is !empty -> all access -> return yes's
                 * beanAclFields is !empty and moduleAclFields is empty -> beanAclFields access restrictions -> return beanAclFields
                 * beanAclFields is !empty and moduleAclFields is !empty -> return all access = "Yes" from moduleAcl and unset any in beanAcl that is in ModuleAcl [don't dupe data]
                 */

                if (!empty($beanAclFields) && empty($moduleAclFields)) {
                    $fieldAcls = $beanAclFields;
                } elseif (!empty($beanAclFields) && !empty($moduleAclFields)) {
                    // we need the ones that are moduleAclFields but not in beanAclFields
                    foreach ($moduleAclFields AS $field => $aclActions) {
                        foreach ($aclActions AS $action => $access) {
                            if (!isset($beanAclFields[$field][$action])) {
                                $beanAclFields[$field][$action] = "yes";
                            }
                            // if the bean action is set and it matches the access from module, we do not need to send it down
                            if (isset($beanAclFields[$field][$action]) && $beanAclFields[$field][$action] == $access) {
                                unset($beanAclFields[$field][$action]);
                            }
                        }
                    }

                    // cleanup BeanAclFields, we don't want to pass a field that doens't have actions
                    foreach ($beanAclFields AS $field => $actions) {
                        if (empty($actions)) {
                            unset($beanAclFields[$field]);
                        }
                    }

                    $fieldAcls = $beanAclFields;
                } elseif (empty($beanAclFields) && !empty($moduleAclFields)) {
                    // it is different because we now have access...
                    foreach ($moduleAclFields AS $field => $aclActions) {
                        foreach ($aclActions AS $action => $access) {
                            $fieldAcls[$field][$action] = "yes";
                        }
                    }
                }

                foreach ($fieldList AS $fieldName) {
                    if (empty($fieldAcls[$fieldName]) && isset($moduleAclFields[$fieldName])) {
                        $fieldAcls[$fieldName] = $moduleAclFields[$fieldName];
                    }
                }

                $acl['fields'] = (object) $fieldAcls;
            }

        }

        return $acl;
    }

    /**
     * This function
     *
     * @param $bean SugarBean The bean you want populated from the $submittedData array, this function will modify this
     *                        record
     * @param $submittedData array The data that was passed in from the client to update/create this record
     * @param $options array Options to pass in to the populateFromApi function, look at SugarBeanApiHelper:populateFromApi
     *                       for more information
     * @return array An array of validation errors, or true if the submitted data appeared to be correct
     */
    public function populateFromApi(SugarBean $bean, array $submittedData, array $options = array() )
    {
        $sfh = new SugarFieldHandler();

        $context = array();
        /**
         * We need to override because of order of fields.
         * For example, if we are changing ownership and a field that is owner read/owner write
         * The assigned_user_id could be set on the bean before we check the ACL of the field
         * Therefore we need to set the owner_override before we start manipulating the bean fields
         * so that the ACL returns correctly for owner
         */
        if (!empty($bean->assigned_user_id) && $bean->assigned_user_id == $GLOBALS['current_user']->id) {
            $context['owner_override'] = true;
        }

        foreach ($bean->field_defs as $fieldName => $properties) {
            if ( !isset($submittedData[$fieldName]) ) {
                // They aren't trying to modify this field
                continue;
            }

            if ( !$bean->ACLFieldAccess($fieldName,'save', $context) ) {
                // No write access to this field, but they tried to edit it
                throw new SugarApiExceptionNotAuthorized('Not allowed to edit field '.$fieldName.' in module: '.$submittedData['module']);
            }

            $type = !empty($properties['custom_type']) ? $properties['custom_type'] : $properties['type'];
            $field = $sfh->getSugarField($type);

            if ($field != null) {
                $field->apiSave($bean, $submittedData, $fieldName, $properties);
            }
        }

        return true;
    }
}
