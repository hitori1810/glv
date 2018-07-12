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
 * Assists in backporting 6.6 Metadata formats to legacy style in order to
 * maintain backward compatibility with old clients consuming the V3 and V4 apis.
 */
class MetaDataConverter {
    /**
     * An instantiated object of MetaDataConverter type
     * 
     * @var MetaDataConverter 
     */
    protected static $converter = null;

    /**
     * Converts edit and detail view defs that contain fieldsets to a compatible
     * defs that does not contain fieldsets. In essence, it splits up any fieldsets
     * and moves them out of their grouping into individual fields within the panel.
     * 
     * This method assumes that the defs have already been converted to a legacy 
     * format.
     * 
     * @param array $defs
     * @return array
     */
    public static function fromGridFieldsets(array $defs) {
        if (isset($defs['panels']) && is_array($defs['panels'])) {
            $newpanels = array();
            $offset = 0;
            foreach ($defs['panels'] as $row) {
                if (is_array($row[0]) && isset($row[0]['type']) && $row[0]['type'] == 'fieldset' && isset($row[0]['related_fields'])) {
                    // Fieldset.... convert
                    foreach ($row[0]['related_fields'] as $fName) {
                        $newpanels[$offset] = array($fName);
                        $offset++;
                    }
                } else {
                    // do nothing
                    $newpanels[$offset] = $row;
                    $offset++;
                }
            }
            
            $defs['panels'] = $newpanels;
        }
        
        return $defs;
    }
    
    /**
     * Static entry point, will instantiate an object of itself to run the process.
     * Will convert $defs to legacy format $viewtype if there is a converter for 
     * it, otherwise will return the defs as-is with no modification.
     * 
     * @static
     * @param string $viewtype One of list|edit|detail
     * @param array $defs The defs to convert 
     * @return array Converted defs if there is a converter, else the passed in defs
     */
    public static function toLegacy($viewtype, $defs) {
        if (null === self::$converter) {
            self::$converter = new self;
        }
        
        $method = 'toLegacy' . ucfirst(strtolower($viewtype));
        if (method_exists(self::$converter, $method)) {
            return self::$converter->$method($defs);
        }
        
        return $defs;
    }
    
    /**
     * Takes in a 6.6+ version of mobile|portal|sidecar list view metadata and
     * converts it to pre-6.6 format for legacy clients. The formats of the defs
     * are pretty dissimilar so the steps are going to be:
     *  - Take in all defs
     *  - Clip everything but the fields portion of the panels section of the defs
     *  - Modify the fields array to be keyed on UPPERCASE field name
     * 
     * @param array $defs Field defs to convert
     * @return array
     */
    public function toLegacyList(array $defs) {
        $return = array();
        
        // Check our panels first
        if (isset($defs['panels']) && is_array($defs['panels'])) {
            foreach ($defs['panels'] as $panels) {
                // Handle fields if there are any (there should be)
                if (isset($panels['fields']) && is_array($panels['fields'])) {
                    // Logic here is simple... pull the name index value out, UPPERCASE it and
                    // set that as the new index name
                    foreach ($panels['fields'] as $field) {
                        if (isset($field['name'])) {
                            $name = strtoupper($field['name']);
                            unset($field['name']);
                            $return[$name] = $field;
                        }
                    }
                }
            }
        }
        
        
        return $return;
    }
    
    /**
     * Simple accessor into the grid legacy converter
     * 
     * @param array $defs Field defs to convert
     * @return array
     */
    public function toLegacyEdit(array $defs) {
        return $this->toLegacyGrid($defs);
    }
    
    /**
     * Simple accessor into the grid legacy converter
     * 
     * @param array $defs Field defs to convert
     * @return array
     */
    public function toLegacyDetail(array $defs) {
        return $this->toLegacyGrid($defs);
    }
    
    /**
     * Takes in a 6.6+ version of mobile|portal|sidecar edit|detail view metadata and
     * converts it to pre-6.6 format for legacy clients.
     * 
     * NOTE: This will only work for layouts that have only one field per row. For
     * the 6.6 upgrade that is sufficient since we were only converting portal
     * and mobile viewdefs. As is, this method will NOT convert grid layout view
     * defs that have more than one field per row.
     * 
     * @param array $defs
     * @return array
     */
    protected function toLegacyGrid(array $defs) {
        // Check our panels first
        if (isset($defs['panels']) && is_array($defs['panels'])) {
            // For our new panels
            $newpanels = array();
            foreach ($defs['panels'] as $panels) {
                // Handle fields if there are any (there should be)
                if (isset($panels['fields']) && is_array($panels['fields'])) {
                    // Logic is fairly straight forward... take each member of 
                    // the fields array and make it an array of its own
                    foreach ($panels['fields'] as $field) {
                        $newpanels[] = array($field);
                    }
                }
            }
            
            unset($defs['panels']);
            $defs['panels'] = $newpanels;
        }
        
        return $defs;
    }
}