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


 require_once ('modules/ModuleBuilder/parsers/views/ListLayoutMetaDataParser.php') ;
 require_once ('modules/ModuleBuilder/parsers/views/SearchViewMetaDataParser.php') ;
 require_once 'modules/ModuleBuilder/parsers/constants.php' ;

 class PopupMetaDataParser extends ListLayoutMetaDataParser
 {

 	// Columns is used by the view to construct the listview - each column is built by calling the named function
 	public $columns = array ( 'LBL_DEFAULT' => 'getDefaultFields' , 'LBL_AVAILABLE' => 'getAdditionalFields' , 'LBL_HIDDEN' => 'getAvailableFields' ) ;

 	public static $reserveProperties = array('moduleMain', 'varName' , 'orderBy', 'whereClauses', 'searchInputs', 'create','addToReserve');

 	public static $defsMap = array(MB_POPUPSEARCH => 'searchdefs' , MB_POPUPLIST => 'listviewdefs');

 	/*
 	 * Constructor
 	 * Must set:
 	 * $this->columns   Array of 'Column LBL'=>function_to_retrieve_fields_for_this_column() - expected by the view
 	 *
 	 * @param string moduleName     The name of the module to which this listview belongs
 	 * @param string packageName    If not empty, the name of the package to which this listview belongs
 	 */
 	 function __construct ($view, $moduleName , $packageName = '')
 	 {
 	 	$this->search = ($view == MB_POPUPSEARCH) ? true : false;
 	 	$this->_moduleName = $moduleName;
 	 	$this->_packageName = $packageName;
 	 	$this->_view = $view ;
 	 	$this->columns = array ( 'LBL_DEFAULT' => 'getDefaultFields' , 'LBL_HIDDEN' => 'getAvailableFields' ) ;

		if ($this->search)
 	 	{
 	 		$this->columns = array ( 'LBL_DEFAULT' => 'getSearchFields' , 'LBL_HIDDEN' => 'getAvailableFields' ) ;
 	 		parent::__construct ( MB_POPUPSEARCH, $moduleName, $packageName ) ;
 	 	} else
 	 	{
 	 		parent::__construct ( MB_POPUPLIST, $moduleName, $packageName ) ;
 	 	}

 	 	$this->_viewdefs = $this->mergeFieldDefinitions($this->_viewdefs, $this->_fielddefs);
 	 }

 	 /**
 	  * Dashlets contain both a searchview and list view definition, therefore we need to merge only the relevant info
 	  */
    function mergeFieldDefinitions ( $viewdefs, $fielddefs ) {
        $viewdefs = $this->_viewdefs = array_change_key_case($viewdefs );
		$viewdefs = $this->_viewdefs = $this->convertSearchToListDefs($viewdefs);
    	return $viewdefs;
    }

    function convertSearchToListDefs($defs) {
    	$temp = array();
    	foreach($defs as $key=>$value) {
    			if(!is_array($value)){
    				$temp[$value] = array('name'=>$value);
    			}else{
    				$temp[$key] = $value;
    				if(isset($value['name']) && $value['name'] != $key){
    					$temp[$value['name']] = $value;
    					unset($temp[$key] );
    				}else if( !isset($value['name']) ){
    					$temp[$key]['name'] = $key;
    				}
    			}
    	}
    	return $temp;
    }

	function getOriginalViewDefs(){
		$defs = parent::getOriginalViewDefs();
		return $this->convertSearchToListDefs($defs);
	}

	public function getSearchFields()
	{
		$searchFields = array ( ) ;
        foreach ( $this->_viewdefs as $key => $def )
        {
            if (isset($this->_fielddefs [ $key ] )) {
				$searchFields [ $key ] = self::_trimFieldDefs ( $this->_fielddefs [ $key ] ) ;
				if (!empty($def['label']))
				   $searchFields [ $key ]['label'] = $def['label'];
            }
			else {
				$searchFields [ $key ] = $def;
			}
        }

        return $searchFields ;
	}

    function handleSave ($populate = true)
   {
    	if (empty (  $this->_packageName ))
        {
        	foreach(array(MB_CUSTOMMETADATALOCATION , MB_BASEMETADATALOCATION) as $value){
        		$file = $this->implementation->getFileName(MB_POPUPLIST, $this->_moduleName, $value);
        		if(file_exists($file)){
	        		break;
	        	}
        	}
        	$writeFile = $this->implementation->getFileName(MB_POPUPLIST, $this->_moduleName);
        	if(!file_exists($writeFile)){
        		mkdir_recursive ( dirname ( $writeFile ) ) ;
    		}
    	}
    	else{
    		$writeFile = $file = $this->implementation->getFileName(MB_POPUPLIST, $this->_moduleName, $this->_packageName);
    	}
    	$this->implementation->_history->append ( $file ) ;
    	if ($populate)
    	   $this->_populateFromRequest() ;
    	$out = "<?php\n" ;

		//Load current module languages
		global $mod_strings , $current_language;
		$oldModStrings = $mod_strings;
		$GLOBALS['mod_strings'] = return_module_language($current_language , $this->_moduleName);
    	require($file);
    	if (!isset($popupMeta)) {
    		sugar_die ("unable to load Module Popup Definition");
    	}

    	if ($this->_view == MB_POPUPSEARCH)
    	{
    		foreach($this->_viewdefs as $k => $v){
    			if(isset($this->_viewdefs[$k]) && isset($this->_viewdefs[$k]['default'])){
    				unset($this->_viewdefs[$k]['default']);
    			}
    		}
    		$this->_viewdefs = $this->convertSearchToListDefs($this->_viewdefs);
    		$popupMeta['searchdefs'] = $this->_viewdefs;
    		$this->addNewSearchDef($this->_viewdefs , $popupMeta);
    	} else
    	{
    		$popupMeta['listviewdefs'] = array_change_key_case($this->_viewdefs , CASE_UPPER );
    	}

        //provide a way for users to add to the reserve properties list via the 'addToReserve' element
    	$totalReserveProps = self::$reserveProperties;
        if(!empty($popupMeta['addToReserve'])){
           $totalReserveProps =  array_merge(self::$reserveProperties,$popupMeta['addToReserve']);
        }
    	$allProperties = array_merge($totalReserveProps , array('searchdefs', 'listviewdefs'));

    	$out .= "\$popupMeta = array (\n";
    	foreach( $allProperties as $p){
    		if(isset($popupMeta[$p])){
    			$out .= "    '$p' => ". var_export_helper ($popupMeta[$p]) . ",\n";
    		}
    	}
    	$out .= ");\n";
    	file_put_contents($writeFile, $out);
    	SugarAutoLoader::addToMap($writeFile);
    	//return back mod strings
    	$GLOBALS['mod_strings'] = $oldModStrings;
    }

    public function addNewSearchDef($searchDefs, &$popupMeta){
    	if(!empty($searchDefs)){
			$this->__diffAndUpdate( $searchDefs , $popupMeta['whereClauses'] , true);
			$this->__diffAndUpdate( $searchDefs , $popupMeta['searchInputs'] );
    	}
    }

    private function __diffAndUpdate($newDefs , &$targetDefs , $forWhere = false){
    	if(!is_array($targetDefs)){
    		$targetDefs = array();
    	}
    	foreach($newDefs as $key =>$def){
    		if(!isset($targetDefs[$key]) && $forWhere){
    			$targetDefs[$key] = $this->__getTargetModuleName($def).'.'.$key;
    		}else if( !in_array($key , $targetDefs ) && !$forWhere){
				array_push($targetDefs , $key);
    		}
    	}

    	if($forWhere){
    		foreach(array_diff(  array_keys($targetDefs) , array_keys($newDefs) ) as $key ){
	    		unset($targetDefs[$key]);
	    	}
    	}else{
    		foreach($targetDefs as $key =>$value){
    			if(!isset($newDefs[$value]))
	    			unset($targetDefs[$key]);
	    	}
    	}

    }

    private function __getTargetModuleName($def){
    	$dir = strtolower($this->implementation->getModuleDir());
    	if(isset($this->_fielddefs[$def['name']]) && isset($this->_fielddefs[$def['name']]['source']) && $this->_fielddefs[$def['name']]['source'] == 'custom_fields'){
    		return $dir.'_cstm';
    	}

    	return $dir;
    }

 }
 ?>
