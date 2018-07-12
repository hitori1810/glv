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

require_once 'modules/ModuleBuilder/parsers/views/ListLayoutMetaDataParser.php';
require_once 'include/MetaDataManager/MetaDataManager.php';

class SidecarListLayoutMetaDataParser extends ListLayoutMetaDataParser {
    /**
     * Invalid field types for various sidecar clients. Format should be 
     * $client => array('type', 'type').
     * 
     * @var array
     * @protected
     */
    protected $invalidTypes = array(
        'portal' => array('iframe', 'encrypt', 'html','currency', 'currency_id'),
    );
            
    
    /*
     * Constructor, builds the parent ListLayoutMetaDataParser then adds the
     * panel data to it
     *
     * @param string $view           The view type, that is, editview, searchview etc
     * @param string $moduleName     The name of the module to which this listview belongs
     * @param string $packageName    If not empty, the name of the package to which this listview belongs
     * @param string $client         The client making the request for this parser
     */
    function __construct ($view , $moduleName , $packageName = '', $client = '') {
        parent::__construct($view, $moduleName, $packageName, $client);
        $this->_paneldefs = $this->implementation->getPanelDefs();
    }

    /**
     * Return a list of the default fields for a sidecar listview
     * @return array    List of default fields as an array
     */
    function getDefaultFields() {
        $defaultFields = array();
        foreach ($this->_paneldefs as $def) {
            if (isset($def['fields'])) {
                foreach ($def['fields'] as $field) {
                    if (!empty($field['name'])) {
                        if (
                            !empty($field['default']) && !empty($field['enabled']) &&
                            (!isset($field['studio']) || ($field['studio'] !== false && $field['studio'] != 'false'))
                        ) {
                            if (isset($this->_fielddefs[$field['name']])) {
                                $defaultFields[$field['name']] = self::_trimFieldDefs($this->_fielddefs[$field['name']]);
                                if (!empty($field['label'])) {
                                    $defaultFields[$field['name']]['label'] = $field['label'];
                                }
                            } else {
                                $defaultFields[$field['name']] = $field;
                            }
                        }
                    }
                }
            }
        }

        return $defaultFields ;
    }

    /**
     * Gets a list of fields that are currently hidden but can still be added to
     * the available fields list or the default fields list
     *
     * @access public
     * @return array List of additional fields as an array
     */
    public function getAdditionalFields() {
        $additionalFields = array();
        foreach ($this->_paneldefs as $def) {
            if (isset($def['fields'])) {
                foreach ($def['fields'] as $field) {
                    if (!empty($field['name'])) {
                        // Bug #25322
                        if (strtolower($field['name']) == 'email_opt_out') {
                            continue;
                        }

                        if (empty($field['default'])) {
                            if (isset($this->_fielddefs[$field['name']])) {
                                $additionalFields[$field['name']] = self::_trimFieldDefs($this->_fielddefs[$field['name']]);
                            } else {
                                $additionalFields[$field['name']] = $field;
                            }
                        }
                    }
                }
            }
        }
        return $additionalFields ;
    }

    /**
     * Gets a list of fields that are available to be added to the default fields
     * list
     *
     * @access public
     * @return array
     */
    public function getAvailableFields() {
        $availableFields = array();
        
        // Select available fields from the field definitions - don't need to worry about checking if ok to include as the Implementation has done that already in its constructor
        foreach ($this->_fielddefs as $key => $def)
        {
            if ($this->isValidField($key, $def) && !$this->panelHasField($key)) {
                $availableFields[$key] = self::_trimFieldDefs($this->_fielddefs[$key]) ;
            }
        }

        $origPanels = $this->getOriginalPanelDefs();
        foreach ($origPanels as $panel) {
            if (is_array($panel) && isset($panel['fields']) && is_array($panel['fields'])) {
                foreach ($panel['fields'] as $field) {
                    if (isset($field['name']) && !$this->panelHasField($field['name']) || (isset($field['enabled']) && $field['enabled'] == false)) {
                        $availableFields[$field['name']] = $field;
                    }
                }
            }
        }

        return $availableFields;
    }

    /**
     * Gets the original panel defs for this listview
     *
     * @return array
     */
    public function getOriginalPanelDefs() {
        $defs = $this->getOriginalViewDefs();
        $viewClient = $this->implementation->getViewClient();
        $viewType = empty($viewClient) ? 'base' : $viewClient;
        if (isset($defs[$viewType]) && is_array($defs[$viewType]) && isset($defs[$viewType]['view']) && is_array($defs[$viewType]['view'])) {
            $index = key($defs[$viewType]['view']);
            if (isset($defs[$viewType]['view'][$index]['panels'])) {
                $defs = $defs[$viewType]['view'][$index]['panels'];
            }
        }
        return $defs;
    }

    /**
     * Checks to see if a field name is in any of the panels
     *
     * @access public
     * @param string $name The name of the field to check
     * @param array $src The source array to scan
     * @return bool
     */
    public function panelHasField($name, $src = null) {
        $field = $this->panelGetField($name, $src);
        return !empty($field);
    }

    /**
     * Scans the panels/fields to see if the panel list already has a field and,
     * if it does, returns that field with its position in the panels list
     *
     * @access public
     * @param string $name The name of the field to check
     * @param array $src The array to scan for the field
     * @return array
     */
    public function panelGetField($name, $src = null) {
        // If there was a passed source, use that for the panel search
        $panels = $src !== null && is_array($src) ? $src : $this->_paneldefs;
        foreach ($panels as $panelix => $def) {
            if (isset($def['fields'])) {
                foreach ($def['fields'] as $fieldix => $field) {
                    if (isset($field['name']) && $field['name'] == $name) {
                        return array('field' => $field, 'panelix' => $panelix, 'fieldix' => $fieldix);
                    }
                }
            }
        }

        return array();
    }
    
    /**
     * Sidecar specific method that delegates validity checking to client specific
     * methods if they exists, otherwise passes through to the parent checker
     * 
     * @param string $key The field name
     * @param array $def The field defs for key
     * @return bool
     */
    public function isValidField($key, $def) {
        if (!empty($this->client)) {
            $method = 'isValidField' . ucfirst(strtolower($this->client));
            if (method_exists($this, $method)) {
                return $this->$method($key, $def);
            }
        }
        
        return parent::isValidField($key, $def);
    }
    
    /**
     * Validates portal only fields. Runs the field through a prelimiary check
     * of type before passing the field on to the parent validator.
     * 
     * @param string $key The field name to check for
     * @param array $def The field defs for the key
     * @return bool
     */
    public function isValidFieldPortal($key, $def) {
        if (isset($this->invalidTypes['portal'])) {
            if (!isset($def['type']) || in_array($def['type'], $this->invalidTypes['portal'])) {
                return false;
            }
        } 
        
        return parent::isValidField($key, $def);
    }
    
    /**
     * Populates the panel defs, and the view defs, from the request
     *
     * @return void
     */
    protected function _populateFromRequest() {
        $GLOBALS['log']->debug(get_class($this) . "->populateFromRequest() - fielddefs = ".print_r($this->_fielddefs, true));
        // Transfer across any reserved fields, that is, any where studio !== true,
        // which are not editable but must be preserved
        $newPaneldefs = array();
        $newPaneldefIndex = 0;
        $newPanelFieldMonitor = array();

    	foreach ($this->_paneldefs as $index => $panel) {
            if (isset($panel['fields'])) {
                foreach ($panel['fields'] as $field) {
                    // Build out the massive conditional structure
                    $studio  = isset($field['studio']);
                    $studioa = $studio && is_array($field['studio']);
                    $studioa = $studioa && isset($field['studio']['listview']) &&
                               ($field['studio']['listview'] === false || ($slv = strtolower($field['studio']['listview'])) == 'false' || $slv == 'required');
                    $studion = $studio && !is_array($field['studio']);
                    $studion = $studion && ($field['studio'] === false || ($slv = strtolower($field['studio'])) == 'false' || $slv == 'required');

                    $studio  = $studio && ($studioa || $studion);
                    if (isset($field['name']) && $studio) {
                        $newPaneldefs[$newPaneldefIndex++] = $field;
                        $newPanelFieldMonitor[$field['name']] = true;
                    }
                }
            }
        }
        // only take items from group_0 for searchviews (basic_search or
        // advanced_search) and subpanels (which both are missing the Available
        // column) - take group_0, _1 and _2 for all other list views
        $lastGroup = isset($this->columns['LBL_AVAILABLE']) ? 2 : 1;

        for ($i = 0; isset($_POST['group_' . $i]) && $i < $lastGroup; $i ++) {
            foreach ($_POST['group_' . $i] as $fieldname) {
                $fieldname = strtolower($fieldname);
                //Check if the field was previously on the layout
                if ($f = $this->panelGetField($fieldname)) {
                	$newPaneldefs[$newPaneldefIndex] = $f['field'];
				} else if ($f = $this->panelGetField($fieldname, $this->getOriginalPanelDefs())) { // Check if the original view def contained it
                    $newPaneldefs[$newPaneldefIndex] = $f['field'];
                } else {
                    // Create a definition from the fielddefs
	                // if we don't have a valid fieldname then just ignore it and move on...
					if (!isset($this->_fielddefs[$fieldname])) {
						continue;
                    }

	                $def = $this->_trimFieldDefs($this->_fielddefs[$fieldname]);
                    $label = isset($def['label']) ? $def['label'] : '';
                    if (empty($label)) {
                        $label = isset($def['vname']) ? $def['vname'] : $def['name'];
                    }
                    $panelfield = array('name' => $fieldname, 'label' => $label, 'enabled' => true);

                    // fixing bug #25640: Value of "Relate" custom field is not displayed as a link in list view
                    // we should set additional params such as 'link' and 'id' to be stored in custom listviewdefs.php
                    if (isset($this->_fielddefs[$fieldname]['type']) && $this->_fielddefs[$fieldname]['type'] == 'relate') {
                        $panelfield['id'] = strtoupper($this->_fielddefs[$fieldname]['id_name']);
                        $panelfield['link'] = true;
                    }
                    // sorting fields of certain types will cause a database engine problems
	                if (isset($this->_fielddefs[$fieldname]['type']) && isset($this->requestRejectTypes[$this->_fielddefs[$fieldname]['type']])) {
	                    $panelfield['sortable'] = false;
	                }

	                // Bug 23728 - Make adding a currency type field default to setting the 'currency_format' to true
	                if (isset($this->_fielddefs[$fieldname]['type']) && $this->_fielddefs[$fieldname]['type'] == 'currency') {
	                    $panelfield['currency_format'] = true;
	                }

                    $newPaneldefs[$newPaneldefIndex] = $panelfield;
                }

                if (isset($_REQUEST[strtolower($fieldname) . 'width'])) {
                    $width = substr($_REQUEST[$fieldname . 'width'], 6, 3);
                    if (strpos($width, "%") != false) {
                        $width = substr($width, 0, 2);
                    }

					if (!($width < 101 && $width > 0)) {
                        $width = 10;
                    }

                    $newPaneldefs[$newPaneldefIndex]['width'] = $width."%";
                } elseif (($def = $this->panelGetField($fieldname)) && isset($def['field']['width'])) {
                    $newPaneldefs[$newPaneldefIndex]['width'] = $def['field']['width'];
                } else {
                	$newPaneldefs[$newPaneldefIndex]['width'] = "10%";
                }

                // Set the default flag to make it a default field
                $newPaneldefs[$newPaneldefIndex]['default'] = ($i == 0);

                // Handle enabling the field (either first column or second column
                if ($i < 2 && empty($newPaneldefs[$newPaneldefIndex]['enabled'])) {
                    $newPaneldefs[$newPaneldefIndex]['enabled'] = true;
                }

                $newPaneldefIndex++;
            }
        }

        // Add in the non named field meta
        foreach ($this->_paneldefs as $panel) {
            foreach ($panel['fields'] as $field) {
                if (!isset($field['name'])) {
                    $newPaneldefs[$newPaneldefIndex++] = $field;
                }
            }
        }

        // We need to add panels back into the viewdefs at the point where we got them
        $panelDefsPath = $this->implementation->getPanelDefsPath();
        $stack = &$this->_viewdefs;
        foreach ($panelDefsPath as $path) {
            if (isset($stack[$path])) {
                $stack = &$stack[$path];
            }
        }
        if (isset($stack['panels'])) {
            $stack['panels'][0]['fields'] = $newPaneldefs;
        }

        $this->_paneldefs[0]['fields'] = $newPaneldefs;
    }
    
    /*
     * Removes a field from the layout
     * 
     * @param string $fieldName Name of the field to remove
     * @return boolean True if the field was removed; false otherwise
     */
    public function removeField($fieldName)
    {
        $return = false;
        // Start with out current viewdefs
        if (isset($this->_viewdefs[$this->client]['view'])) {
            // list, edit or detail
            $type = key($this->_viewdefs[$this->client]['view']);
            
            // The current panels, should be the same as $this->_paneldefs
            $panels = $this->_viewdefs[$this->client]['view'][$type]['panels'];
            
            if (!empty($panels) && is_array($panels)) {
                foreach ($panels as $panelIndex => $def) {
                    if (isset($def['fields']) && is_array($def['fields'])) {
                        $newFields = array();
                        foreach ($def['fields'] as $fieldIndex => $field) {
                            if (!empty($field['name']) && $field['name'] == $fieldName) {
                                $return = true;
                                continue;
                            }
                            
                            $newFields[] = $field;
                        }
                        
                        // Reset the panel defs for now
                        $this->_paneldefs[$panelIndex]['fields'] = $newFields;
                        
                        // Now handle the change in the viewdefs for saving
                        $this->_viewdefs[$this->client]['view'][$type]['panels'][$panelIndex]['fields'] = $newFields;
                    }
                }
            }
        }
        
        return $return;
    }

    /**
     * Clears mobile and portal metadata caches that have been created by the API
     * to allow immediate rendering of changes at the client
     */
    protected function _clearCaches() {
        if ($this->implementation->isDeployed()) {
            MetaDataFiles::clearModuleClientCache($this->_moduleName,'view');
            MetaDataManager::clearAPICache(false);
        }
    }
}