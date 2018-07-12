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


/*
 * Created on Sep 10, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 require_once('include/ListView/ListViewSmarty.php');


 /**
  * A Facade to ListView and ListViewSmarty
  */
 class ListViewFacade{

 	var $focus = null;
 	var $module = '';
 	var $type = 0;

 	var $lv;

 	//ListView fields
 	var $template;
 	var $title;
 	var $where = '';
 	var $params = array();
 	var $offset = 0;
 	var $limit = -1;
 	var $filter_fields = array();
 	var $id_field = 'id';
 	var $prefix = '';
 	var $mod_strings = array();

 	/**
 	 * Constructor
 	 * @param $focus - the bean
 	 * @param $module - the module name
 	 * @param - 0 = decide for me, 1 = ListView.html, 2 = ListViewSmarty
 	 */
 	function ListViewFacade($focus, $module, $type = 0){
 		$this->focus = $focus;
 		$this->module = $module;
 		$this->type = $type;
 		$this->build();
 	}

 	function build(){
 		//we will assume that if the ListView.html file exists we will want to use that one
 		if(SugarAutoLoader::fileExists('modules/'.$this->module.'/ListView.html')){
 			$this->type = 1;
 			$this->lv = new ListView();
 			$this->template = 'modules/'.$this->module.'/ListView.html';
 		} else {
 		    $metadataFile = SugarAutoLoader::loadWithMetafiles($this->module, 'listviewdefs');
            if($metadataFile) {
 		        require $metadataFile;
            }

			SugarACL::listFilter($this->module, $listViewDefs[ $this->module], array("owner_override" => true));

			$this->lv = new ListViewSmarty();
			$displayColumns = array();
			if(!empty($_REQUEST['displayColumns'])) {
			    foreach(explode('|', $_REQUEST['displayColumns']) as $num => $col) {
			        if(!empty($listViewDefs[$this->module][$col]))
			            $displayColumns[$col] = $listViewDefs[$this->module][$col];
			    }
			}
			else {
			    foreach($listViewDefs[$this->module] as $col => $params) {
			        if(!empty($params['default']) && $params['default'])
			            $displayColumns[$col] = $params;
			    }
			}
			$this->lv->displayColumns = $displayColumns;
			$this->type = 2;
			$this->template = 'include/ListView/ListViewGeneric.tpl';
 		}
 	}

 	function setup($template = '', $where = '', $params = array(), $mod_strings = array(), $offset = 0, $limit = -1, $orderBy = '', $prefix = '', $filter_fields = array(), $id_field = 'id'){
 		if(!empty($template))
 			$this->template = $template;

 		$this->mod_strings = $mod_strings;

 		if($this->type == 1){
 			$this->lv->initNewXTemplate($this->template,$this->mod_strings);
 			$this->prefix = $prefix;
 			$this->lv->setQuery($where, $limit, $orderBy, $prefix);
 			$this->lv->show_select_menu = false;
			$this->lv->show_export_button = false;
			$this->lv->show_delete_button = false;
			$this->lv->show_mass_update = false;
			$this->lv->show_mass_update_form = false;
 		}else{
 			$this->lv->export = false;
			$this->lv->delete = false;
			$this->lv->select = false;
			$this->lv->mailMerge = false;
			$this->lv->multiSelect = false;
 			$this->lv->setup($this->focus, $this->template, $where, $params, $offset, $limit,  $filter_fields, $id_field);

 		}
 	}

 	function display($title = '', $section = 'main', $return = FALSE){
 		if($this->type == 1){
            ob_start();
 			$this->lv->setHeaderTitle($title);
 			$this->lv->processListView($this->focus, $section, $this->prefix);
            $output = ob_get_contents();
            ob_end_clean();
 		}else{
             $output = get_form_header($title, '', false) . $this->lv->display();
 		}
        if($return)
            return $output;
        else
            echo $output;
 	}

	function setTitle($title = ''){
		$this->title = $title;
	}
 }
?>
