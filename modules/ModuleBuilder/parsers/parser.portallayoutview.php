<?php
if (! defined('sugarEntry') || ! sugarEntry)
die('Not A Valid Entry Point');
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


require_once ('modules/ModuleBuilder/parsers/parser.modifylayoutview.php');
require_once 'modules/ModuleBuilder/parsers/views/History.php' ;

class ParserPortalLayoutView extends ParserModifyLayoutView
{

    var $maxColumns; // number of columns in this layout
    var $usingWorkingFile = false; // if a working file exists (used by view.edit.php among others to determine the title for the layout edit panel)
    var $language_module; // set to module name for studio, passed to the smarty template and used by sugar_translate
    var $_sourceFiles = array(); // private
    var $_customFile; // private
    var $_workingFile; // private
    var $_module; // private
    var $_view; // private
    var $_viewdefs; // private
    var $_fieldDefs; // private


    /**
     * Initializer, sets up the defs, files to use and other needed parts of the
     * parser.
     *
     * @param string $module The module to load the defs for
     * @param string $view The view defs to load
     * @param boolean $submittedLayout If there is a new layout proposed
     */
    function init($module, $view, $submittedLayout = false)
    {
        $viewType = strtolower(str_ireplace('view', '', $view));
        $GLOBALS['log']->debug("in ParserPortalLayoutView");
        //$file = "modules/{$module}/metadata/portal." . strtolower($view) . "defs.php";
        $file = 'modules/' . $module . '/metadata/portal/layouts/' . $viewType . '.php';
        $this->_customFile = MetaDataFiles::PATHCUSTOM . $file;
        $this->_workingFile = MetaDataFiles::PATHWORKING . $file;
        $this->_sourceFile = $file;
        $this->_module = $module;
        $this->_view = $view;
        $this->language_module = $module;

        // Choose our source file if there is a choice to be made
        if (is_file($this->_workingFile)) {
            $this->_sourceFile = $this->_workingFile;
            $this->usingWorkingFile = true;
        } elseif (is_file($this->_customFile)) {
            $this->_sourceFile = $this->_customFile;
        }

        // Get the fieldDefs from the bean
        $class = $GLOBALS['beanList'][$module];
        require_once($GLOBALS['beanFiles'][$class]);
        $bean = new $class();
        $this->_fieldDefs = &$bean->field_defs;

        // This will load up the view defs into this parser
        $this->loadModule($this->_module, $view);

        // now fix the layout so that it is compatible with the latest metadata definition = rename data section as a panel within a panel section
        $defs =$this->_viewdefs['panels'];
        $this->_viewdefs['panels'] = array($this->_parseData($defs)); // put into a canonical format
        $this->maxColumns = $this->_viewdefs ['templateMeta'] ['maxColumns'];

        $GLOBALS['log']->debug("ParserPortalLayoutView: after loadModule");
        if ($submittedLayout) {
            // replace the definitions with the new submitted layout
            $this->_loadLayoutFromRequest();
        } else {
            $this->_padFields(); // destined for a View, so we want to add in (empty) fields
        }
		
		$this->_history = new History($this->_customFile);
    }

    function _parseData ($panel)
    {
        if (empty($panel)) {
            return;
        }

        // In for testing parsing at this point. Will be removed. rgonzalez
        $returnData = $displayData = array();

        foreach ($panel as $rowID => $row) {
            foreach ($row as $colID => $col) {
                $properties = array();

                if (!empty($col)) {
                    if (is_string($col)) {
                        $properties['name'] = $col;
                    } else {
                        if (is_array($col)) {
                            if (!empty($col['field'])) {
                                // portal metadata uses 'field' to identify the fieldname; new metadata uses 'name'
                                $col['name'] = $col['field'];
                                unset($col['field']);
                                $properties = $col;
                            }
                        }
                    }
                } else {
                    $properties['name'] = translate('LBL_FILLER');
                }

                if (!empty($properties['name'])) {
                    // get this field's label - if it has not been explicity provided, see if the fieldDefs has a label for this field, and if not fallback to the field name
                    if (!isset($properties ['label'])) {
                        if (!empty($this->_fieldDefs[$properties['name']]['vname'])) {
                            $properties['label'] = translate($this->_fieldDefs[$properties['name']]['vname'], $this->_module);
                        } else {
                            $properties['label'] = $properties['name'];
                        }
                    } else {
                    	$properties['label'] = translate($this->_fieldDefs[$properties['name']]['vname'], $this->_module);
                    }

                    $displayData[$rowID][$colID] = $properties;
                    $returnData[] = $properties;
                }
            }
        }

        return $displayData;
    }

    function _fromNewToOldMetaData()
    {
        $GLOBALS['log']->debug("_fromNewToOldMetaData(): START=".print_r($this->_viewdefs,true));
        if (isset($this->_viewdefs['panels'])) // check this as we might be called twice in succession by a save action - once to write the working file and once to handleSave
        {
            // recreate the original portal metafile format - replace the panels section with 'data', and rename field 'name' to 'field'
            $defs = $this->_viewdefs['panels'][0];
            $this->_viewdefs['data'] = $defs;
            unset($this->_viewdefs['panels']);
            foreach($this->_viewdefs['data'] as $rowID=>$row)
            {
                foreach($row as $fieldID=>$field)
                {
                    if ((! empty($this->_fieldDefs [$field ['name']] ['auto_increment']) && 
                            $this->_fieldDefs [$field ['name']] ['auto_increment']) ||
                        !empty($this->_fieldDefs [$field ['name']]['calculated']))
                    {
                        $field['readOnly'] = true;
                    }
                	$field['field'] = $field['name'];
                    unset($field['name']);
                    $this->_viewdefs['data'][$rowID][$fieldID] = $field;
                }
            }
        }
        $GLOBALS['log']->debug("_fromNewToOldMetaData(): END=".print_r($this->_viewdefs,true));
    }

    function writeWorkingFile ()
    {
        $this->_fromNewToOldMetaData();
        parent::writeWorkingFile();
    }

    function handleSave ()
    {
        $this->_fromNewToOldMetaData();
        parent::handleSave();
    }

    function _getOrigFieldViewDefs ()
    {
        $origFieldDefs = array();
        if (file_exists($this->_sourceFile))
        {
            include ($this->_sourceFile);
            $origdefs = $viewdefs [$this->_module] [$this->_view]['panels'];
            foreach ($origdefs as $row)
            {
                foreach ($row as $fieldDef)
                {
                    if (is_array($fieldDef))
                    {
                        $fieldName = $fieldDef ['field'];
                        $fieldDef['name'] = $fieldName;
                        unset($fieldDef['field']);

                    } else
                    {
                        $fieldName = $fieldDef;
                    }
                    $origFieldDefs [$fieldName] = $fieldDef;
                }
            }
        }
        return $origFieldDefs;
    }

    /* getModelFields
     *
     * Overrides _getModelFields from parent class.  For portal fields, we ignore the
     * ((!empty($def['studio']) && $def['studio'] == 'visible') check because it is
     * insufficient.  Studio visible fields do not necessary map to portal fields.  For
     * example, fields that call functions should not be permissible since the files for
     * these functions may not be present in the portal side.
     *
     */
    function _getModelFields ()
    {
        $modelFields = array();
        $origViewDefs = $this->_getOrigFieldViewDefs();
        foreach ($origViewDefs as $field => $def)
        {
            if (!empty($field))
            {
                if (! is_array($def)) {
                    $def = array('name' => $field);
                }
                // get this field's label - if it has not been explicitly provided, see if the fieldDefs has a label for this field, and if not fallback to the field name
                if (! isset($def ['label']))
                        {
                            if (! empty($this->_fieldDefs [$field] ['vname']))
                            {
                                $def ['label'] = $this->_fieldDefs [$field] ['vname'];
                            } else
                            {
                                $def ['label'] = $field;
                            }
                        }
                $modelFields[$field] = array('name' => $field, 'label' => $def ['label']);
            }
        }
        
        $invalidTypes = array('parent', 'parent_type', 'iframe', 'encrypt');
        foreach ($this->_fieldDefs as $field => $def)
        {
        	/**
        	 * Here are the checks:
        	 * 1) It is a database or custom field (not non-db)
        	 * 2) The field does not invoke a function
        	 * 3) The field is not the deleted field
        	 * 4) The field is not an id field
        	 * 5) The field type is not in the $invalidTypes Array
        	 */
        	if ((empty($def ['source']) || $def ['source'] == 'db' || $def ['source'] == 'custom_fields') &&
                empty($def['function']) && strcmp($field, 'deleted') != 0 &&
                $def['type'] != 'id' && (empty($def ['dbType']) || $def ['dbType'] != 'id') &&
                (isset($def['type']) && !in_array($def['type'], $invalidTypes)))
            {
            	$label = isset($def['vname']) ? $def ['vname'] : $def['name'];
                //$GLOBALS['log']->debug($label . ',' . $def['type']);
            	$modelFields [$field] = array('name' => $field, 'label' => $label);
            }
        }
        return $modelFields;
    }
    
    /**
     * @return Array list of fields in this module that have the calculated property
     */
    public function getCalculatedFields() {
    	$ret = array();
    	foreach ($this->_fieldDefs as $field => $def)
        {
        	if(!empty($def['calculated']) && !empty($def['formula']))
        	{
        		$ret[] = $field;
        	}
        }
        
        return $ret;
    }
	
	function getHistory ()
	{
		return $this->_history ;
	}

    function getFieldDefs()
    {
        return $this->_fieldDefs;
    }

    function getMaxColumns()
    {
        return $this->maxColumns;
    }
}
?>