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

require_once 'modules/ModuleBuilder/parsers/views/GridLayoutMetaDataParser.php';
require_once 'modules/ModuleBuilder/parsers/constants.php';
require_once 'include/MetaDataManager/MetaDataManager.php';

class SidecarGridLayoutMetaDataParser extends GridLayoutMetaDataParser {
    /**
     * Invalid field types for various sidecar clients. Format can be either
     * $client => array('type', 'type') or 
     * $client => array('edit' => array('type', 'type'), 'detail' => array('type', 'type'))
     * 
     * @var array
     * @protected
     */
    protected $invalidTypes = array(
        'portal' => array(
            // Detail support one set of fields...
            'detail' => array('parent', 'parent_type', 'iframe', 'encrypt', 'html','currency','currency_id'),
            // Edit supports another
            'edit' => array('parent', 'parent_type', 'iframe', 'encrypt', 'relate', 'html','currency','currency_id'),
        ),
    );
        
    /**
     * Checks for the existence of the view variable for portal metadata
     *
     * @param array $viewdefs The viewdef array
     * @param string $view The view to check for
     * @return bool
     */
    public function hasViewVariable($viewdefs, $view) {
        return $this->getNestedDefs($viewdefs, $view, true);
    }

    /**
     * Gets the viewdefs for portal from the entire viewdef array
     *
     * @param array $viewdefs The full viewdef collection below $viewdefs[$module]
     * @param string $view The view to fetch the defs for
     * @return array
     */
    public function getDefsFromArray($viewdefs, $view) {
        return $this->getNestedDefs($viewdefs, $view);
    }

    protected function getNestedDefs($viewdefs, $view, $validateOnly = false) {
        // Get the view variable, or in Sidecar's case, the path
        $var = MetaDataFiles::getViewDefVar($view);

        // Sidecar should always be an array of metadata path elements
        if (is_array($var)) {
            $levels = count($var); // For example, 3 - portal -> view -> edit
            $checks = 0;

            for ($i = 0; $i < $levels; $i++) {
                if (isset($viewdefs[$var[$i]])) {
                    $checks++;
                    $viewdefs = $viewdefs[$var[$i]];
                }
            }

            $valid = $checks == $levels;

            return $validateOnly ? $valid : $viewdefs;
        }

        return $validateOnly ? false : array();
    }

    /**
     * Gets panel defs from the viewdef array
     * @param array $viewdef The viewdef array
     * @return array
     */
    protected function getPanelsFromViewDef($viewdef) {
        $defs = $this->getDefsFromArray($viewdef, $this->_view);
        if (isset($defs['panels'])) {
            return $defs['panels'];
        }

        return array();
    }

    /**
     * Checks for necessary elements of the metadata array and fails the request
     * if not found
     *
     * @param array $viewdefs The view defs being requested
     * @return void
     */
    public function validateMetaData($viewdefs) {
        if (!isset($viewdefs['panels'])) {
            sugar_die(get_class($this) . ': missing panels section in layout definition (case sensitive)');
        }
    }
    
    /**
     * Validates a field
     * 
     * @param string $key The name of the field
     * @param array $def The defs for this field
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
     * Validates portal only fields. Runs the field through a preliminary check
     * of view type and field type before passing the field on to the parent validator.
     * 
     * @param string $key The field
     * @param array $def Teh field def for this field
     * @return bool
     */
    public function isValidFieldPortal($key, $def) {
        if (isset($this->invalidTypes['portal'])) {
            $view = $this->_view == MB_PORTALDETAILVIEW ? 'detail' : 'edit';
            
            if (isset($this->invalidTypes['portal'][$view])) {
                $blacklist = $this->invalidTypes['portal'][$view];
            } else {
                $blacklist = $this->invalidTypes['portal'];
            }
            
            if (!isset($def['type']) || in_array($def['type'], $blacklist)) {
                return false;
            }
        } 
        
        return parent::isValidField($key, $def);
    }
    
    /**
     * helper to pack a row with $cols members of [empty]
     * @param $row
     * @param $cols
     * @return void
     *
     */
    protected function _packRowWithEmpty(&$row, $cols)
    {
        for ($i=0; $i<$cols; $i++) {
            $row[] = $this->_addInternalCell(MBConstants::$EMPTY);
        }
    }

    /**
     * helper to add a field (name) to the internal formatted row
     * used in case internal format goes to wanting arrays
     * @param $field
     * @return string value to add
     */
    protected function _addInternalCell($field) {
        return is_array($field) ? $field['name'] : $field;
    }


    /*
     * helper methods for doing field comparisons
     */
    protected function isFiller($field)
    {
        if (is_array($field))  {
            return ($field == MBConstants::$FILLER);
        }

        return ($field == $this->FILLER['name']);
    }

    protected function isEmpty($field)
    {
        if (is_array($field))  {
            return ($field == MBConstants::$EMPTY);
        }

        return ($field == MBConstants::$EMPTY['name']);
    }

    // return an array of cells to be appended to the fieldlist
    protected function _addCell($field, $colspan)
    {
        // for fillers, if we ever have a 'filler' with colspan = n, just sub n 'fillers'
        if ($field === '')
        {
            return array_fill(0,$colspan,'');
        }

        // add the displayParam field if necessary
        if ($colspan > 1) {
            if (!is_array($field)) {
                $field = array('name' => $field);
            }

            $field['displayParams']['colspan'] = $colspan;
        }
        return array($field);
    }

    /**
     * here we convert from internal metadata format to file (canonical) metadata
     * @param $panels
     * @param $fielddefs
     * @return array - viewdefs in canonical file format
     */
    protected function _convertToCanonicalForm($panels , $fielddefs)
    {
        //$previousViewDef = $this->getFieldsFromLayout($this->implementation->getViewDefs());
        //$currentFields = $this->getFieldsFromLayout($panels);

        $canonicalPanels = array();
        
        $maxCols = $this->getMaxColumns();

        foreach ($panels as $pName => $panel) {
            $fields = array();
            foreach ($panel as $row) {
                $offset = 1; // reset
                $lastField = null; // holder for the field to put in
                foreach ($row as $cell) {

                    // empty => get rid of it, and assign to previous field as colspan
                    if ($this->isEmpty($cell)) {
                        $offset++; // count our columns
                        continue;
                    }

                    // dump out the last field we stored and reset column count
                    // leading empty => should not occur, but assign to next field as colspan
                    if ($lastField !== null) {
                        $fields = array_merge($fields,$this->_addCell($lastField,$offset));
                        $offset = 1;
                    }

                    // filler => ''
                    if ($this->isFiller($cell)) {
                        // 58308 - Adjust displayColumns on the last field if it 
                        // is set and we are an end column
                        if ($maxCols - $offset == 1) {
                            $lastRowIndex = count($fields) - 1;
                            if (is_array($fields[$lastRowIndex]) && isset($fields[$lastRowIndex]['displayParams']['colspan'])) {
                                $redux = $maxCols - $fields[$lastRowIndex]['displayParams']['colspan'];
                                if ($redux > 0) {
                                    $fields[$lastRowIndex]['displayParams']['colspan'] = $redux;
                                } else {
                                    // Remove the colspan altogether
                                    unset($fields[$lastRowIndex]['displayParams']['colspan']);
                                    
                                    // If displayParams is empty now, get rid of it too
                                    if (empty($fields[$lastRowIndex]['displayParams'])) {
                                        unset($fields[$lastRowIndex]['displayParams']);
                                    }
                                }
                            }
                        }
                        
                        $lastField = '';
                    }
                    else {
                        // field => add the field def.
                        $fieldName = is_array($cell) ? $cell['name'] : $cell;
                        if (isset($this->_originalViewDef[$fieldName]))  {
                            $source = $this->_originalViewDef[$fieldName];
                        }
                        elseif (isset($fielddefs[$fieldName])) {
                            $source = self::_trimFieldDefs($fielddefs[$fieldName]);
                        }
                        else {
                            $source = $cell;
                        }

                        $lastField = $this->getNewRowItem($source, $fielddefs[$fieldName]);
                    }

                }

                // dump out the last field we stored
                if ($lastField !== null) {
                    $fields = array_merge($fields,$this->_addCell($lastField,$offset));
                }

            }
            $canonicalPanels[] = array('label' => $pName, 'fields' => $fields);
        }
        return $canonicalPanels;
    }

    /**
     * here we convert from file (canonical) metadata => internal metadata format
     * @param $panels
     * @param $fielddefs
     * @return array $internalPanels
     */
    protected function _convertFromCanonicalForm($panels , $fielddefs)
    {
        // canonical form has format:
        // $panels[n]['label'] = label for panel n
        //           ['fields'] = array of fields


        // internally we want:
        // $panels[label for panel] = fields of panel in rows,cols format

        $internalPanels = array();
        foreach ($panels as $n => $panel) {
            $pLabel = !empty($panel['label']) ? $panel['label'] : $n;

            // going from a list of fields to putting them in rows,cols format.
            $internalFieldRows = array();
            $row = array();
            foreach ($panel['fields'] as $field) {
                // try to find the column span of the field. It can range from 1 to max columns of the panel.
                $colspan = is_array($field) && isset($field['displayParams']['colspan']) ?
                        $field['displayParams']['colspan'] :
                        1;
                $colspan = min($colspan, $this->getMaxColumns()); // we can't put in a field wider than the panel.
                $cols_left = $this->getMaxColumns() - count($row);

                if ($cols_left < $colspan) {
                    // add $cols_left of (empty) to $row and put it in
                   $this->_packRowWithEmpty($row, $cols_left);
                   $internalFieldRows[] = $row;
                   $row = array();
                }

                // add field to row + enough (empty) to make it to colspan
                $row[] = $this->_addInternalCell(empty($field) ? $this->FILLER : $field);
                $this->_packRowWithEmpty($row, $colspan-1);
            }

            // add the last incomplete row if necessary
            if (!empty($row)) {
                $cols_left = $this->getMaxColumns() - count($row);
                // add $cols_left of (empty) to $row and put it in
                $this->_packRowWithEmpty($row, $cols_left);
                $internalFieldRows[] = $row;
            }
            $internalPanels[$pLabel] = $internalFieldRows;
        }

        return $internalPanels;
    }


    /**
     * Returns a list of fields, generally from the original (not customized) viewdefs
     * @param $viewdef
     * @return array array of fields, indexed by field name
     */
    protected function getFieldsFromLayout($viewdef)
    {
        $panels = $this->getPanelsFromViewDef($viewdef);

        // not canonical form... try parent method
        if (!isset($panels[0]['fields'])) {
            return parent::getFieldsFromLayout($viewdef);
        }

        $out = array();
        foreach ($panels as $panel) {
            foreach($panel['fields'] as $field) {
                $name = (is_array($field)) ? $field['name'] : $field; // we either have a name or a bare string
                $out[$name] = $field;
            }
        }
        return $out;
    }

    /*
     * Remove a field from the layout
     * 
     * @param string $fieldName Name of the field to remove
     * @return boolean True if the field was removed; false otherwise
     */
    function removeField ($fieldName)
    {
        // Set the return result
        $result = false;
        
        // Get our original view defs
        $originalDefs = $this->getImplementation()->getOriginalViewdefs();
        
        // Get the panel label
        $nestedDefs = $this->getNestedDefs($originalDefs, $this->_view);
        $panelKey = key($nestedDefs['panels']);
        $label = isset($nestedDefs['panels'][$panelKey]['label']) ? $nestedDefs['panels'][$panelKey]['label'] : $panelKey;
        
        // Loop and find
        if (isset($this->_viewdefs['panels'][$label]) && is_array($this->_viewdefs['panels'][$label])) {
            foreach ($this->_viewdefs['panels'][$label] as $rowIndex => $row) {
                if (is_array($row)) {
                    foreach ($row as $fieldIndex => $field) {
                        if ($field == $fieldName) {
                            $this->_viewdefs['panels'][$label][$rowIndex][$fieldIndex] = MBConstants::$EMPTY['name'];
                            $result = true;
                            break 2;
                        }
                    }
                }
            }
            
            // Now check to see if any of our rows are totally empty, and if they
            // are, pluck them completely
            $newRows = array();
            foreach ($this->_viewdefs['panels'][$label] as $rowIndex => $row) {
                if (is_array($row)) {
                    $cols = count($row);
                    $empties = 0;
                    foreach ($row as $field) {
                        if ($field == MBConstants::$EMPTY['name']) {
                            $empties++;
                        }
                    }
                    
                    if ($empties == $cols) {
                        // All empties, remove it and keep looping
                        //unset($this->_viewdefs['panels'][$label][$rowIndex]);
                        continue;
                    }
                    
                    $newRows[] = $row;
                }
            }
            
            $this->_viewdefs['panels'][$label] = $newRows;
        }
        
        return $result;
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
