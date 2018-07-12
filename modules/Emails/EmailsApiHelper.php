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

class EmailsApiHelper extends SugarBeanApiHelper
{
    public $emailFieldEmptyChecks = array(
                                            'to_addrs_names' => 'to_addrs',
                                            'from_addr_name' => 'from_addr',
                                            'cc_addrs_names' => 'cc_addrs',
                                            'bcc_addrs_names' => 'bcc_addrs',
                                    );
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
        $data = parent::formatForApi($bean, $fieldList, $options);

        foreach($this->emailFieldEmptyChecks AS $fieldToCheck => $replacementField) {
            if(empty($data[$fieldToCheck]) && !empty($bean->$replacementField)) {
                $data[$fieldToCheck] = $bean->$replacementField;
            }
        }

        /**
         * Bug62759 - 6.7.2
         * Email attachments are essentially notes related to the email
         * This code block gets all related notes that have files
         * associated to them and adds them to the attachment_list variable
         * Similar to KBDocuments
         */

        $note = BeanFactory::newBean('Notes');
        $where = "parent_id = {$note->db->quoted($bean->id)} AND parent_type = {$note->db->quoted($bean->module_dir)}";
        $query = $note->create_new_list_query('notes.filename', $where, array('id', 'filename'));
        $ret = $note->db->query($query,true);
        $files = array();
        while ( $row = $note->db->fetchByAssoc($ret) ) {
            $thisFile = array();
            $thisFile['id'] = $row['id'];
            $thisFile['filename'] = $row['filename'];
            $thisFile['uri'] = $this->api->getResourceURI(array('Notes',$row['id'],'file','filename'));
            $files[] = $thisFile;
        }
        
        $data['attachment_list'] = $files;        
                
        return $data;
    }    

}
