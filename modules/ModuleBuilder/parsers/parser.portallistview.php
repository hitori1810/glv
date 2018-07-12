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






require_once ('modules/ModuleBuilder/parsers/parser.modifylistview.php');
require_once 'modules/ModuleBuilder/parsers/views/History.php' ;

class ParserPortalListView extends ParserModifyListView
{
	
	var $listViewDefs = false;
	var $defaults = array();
	var $additional = array();
	var $available = array();
	var $language_module;
    var $columns = array('LBL_DEFAULT' => 'getDefaultFields', 'LBL_AVAILABLE' => 'getAvailableFields');
    var $customFile; // private
    var $originalFile; // private
	
	function init ($module_name)
	{
		global $app_list_strings;
		$this->module_name = $module_name;
		$this->mod_strings = return_module_language($GLOBALS ['current_language'], $this->module_name);


        // get our bean
		$class = $GLOBALS ['beanList'] [$this->module_name];
		require_once ($GLOBALS ['beanFiles'] [$class]);
		$this->module = new $class();

        // Set our def file
        $defFile = 'modules/' . $this->module_name . '/metadata/portal.listviewdefs.php';
        $this->originalFile = $defFile;
		include $defFile;

		$this->originalListViewDefs = $viewdefs[$this->module_name]['listview'];
		$this->fixKeys($this->originalListViewDefs);
		$this->customFile = 'custom/' . $defFile;


		if (file_exists($this->customFile)) {
			include $this->customFile;
			$this->listViewDefs = $viewdefs[$this->module_name]['listview'];
			$this->fixKeys($this->listViewDefs);
		} else
		{
			$this->listViewDefs = & $this->originalListViewDefs;
		}
		
		$this->_fromNewToOldMetaData();

		$this->language_module = $this->module_name;
		
		$this->_history = new History ($this->customFile);
	}
	
	function _fromNewToOldMetaData()
	{
	    foreach($this->listViewDefs as $key=>$value)
	    {
	        $value['default'] = 'true';
	        $this->listViewDefs[$key] = $value;
	    }
	}

	function addRelateData($fieldname, $listfielddef) {
		$modFieldDef = $this->module->field_defs [ strtolower ( $fieldname ) ];
		if (!empty($modFieldDef['module']) && !empty($modFieldDef['id_name'])) {
			$listfielddef['module'] = $modFieldDef['module'];
			$listfielddef['id'] = strtoupper($modFieldDef['id_name']);
			$listfielddef['link'] = in_array($listfielddef['module'], array('Cases', 
			                                                                'Bugs', 
			                                                                'KBDocuments'));
			$listfielddef['related_fields'] = array (strtolower($modFieldDef['id_name']));
		}
		return $listfielddef;
	}	
	
	function handleSave ()
	{
		if (!file_exists($this->customFile)) {
			//Backup the orginal layout to the history
			$this->_history->append($this->originalFile);
		}
		
		$requestfields = $this->_loadLayoutFromRequest();
	    $fields = array();
        foreach($requestfields as $key=>$value) {
            if ($value['default'] == 'true') {
                unset($value['default']);
                $fields[strtoupper($key)] = $value;
            }
        }	
	    mkdir_recursive(dirname($this->customFile));
        if (! write_array_to_file("viewdefs['{$this->module_name}']['listview']", $fields, $this->customFile)) {
            $GLOBALS ['log']->fatal("Could not write $newFile");
        }
	}	

	
	/**
	 * returns unused fields that are available for using in either default or additional list views
	 */
	function getAvailableFields ()
	{
		$this->availableFields = array ( ) ;
		$lowerFieldList = array_change_key_case ( $this->listViewDefs ) ;
		foreach ( $this->originalListViewDefs as $key => $def )
		{
			$key = strtolower ( $key ) ;
			if (! isset ( $lowerFieldList [ $key ] ))
			{
				$this->availableFields [ $key ] = $def ;
			}
		}
		$GLOBALS['log']->debug('parser.modifylistview.php->getAvailableFields(): field_defs='.print_r($this->availableFields,true));
		$modFields = !empty($this->module->field_name_map) ? $this->module->field_name_map : $this->module->field_defs;
		$invalidTypes = array('iframe', 'encrypt');
		foreach ( $modFields as $key => $def )
		{
			$fieldName = strtolower ( $key ) ;
			if (!isset ( $lowerFieldList [ $fieldName ] )) // bug 16728 - check this first, so that other conditions (e.g., studio == visible) can't override and add duplicate entries
			{
                //Similar parsing rules as in parser.portallayoutview.php
                if ((empty($def ['source']) || $def ['source'] == 'db' || $def ['source'] == 'custom_fields') &&
	                empty($def['function']) &&
	                strcmp($key, 'deleted') != 0 &&
	                $def['type'] != 'id' && (empty($def ['dbType']) || $def ['dbType'] != 'id') &&
	                (isset($def['type']) && !in_array($def['type'], $invalidTypes)))
					{
						$label = (isset ( $def [ 'vname' ] )) ? $def [ 'vname' ] : (isset($def [ 'label' ]) ? $def['label'] : $def['name']) ;
						$this->availableFields [ $fieldName ] = array ( 'width' => '10' , 'label' => $label ) ;
					}
			}
		}
		return $this->availableFields;
	}       
	
	function getHistory ()
	{
		return $this->_history ;
	}

    
}

?>
