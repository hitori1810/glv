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





require_once('modules/ModuleBuilder/views/view.listview.php');

class ViewPortalListView extends ViewListView 
{
    public function __construct()
    {
        $this->editModule = $_REQUEST['view_module'];
        $this->editLayout = $_REQUEST['view'];
        $this->subpanel = (!empty($_REQUEST['subpanel'])) ? $_REQUEST['subpanel'] : false;
        $this->fromModuleBuilder = ! empty ( $_REQUEST [ 'view_package' ] ) ;
    }

    /**
	 * @see SugarView::_getModuleTitleParams()
	 */
	protected function _getModuleTitleParams($browserTitle = false)
	{
	    global $mod_strings;
	    
    	return array(
    	   translate('LBL_MODULE_NAME','Administration'),
    	   ModuleBuilderController::getModuleTitle(),
    	   );
    }

    function display() 
    {
        require_once('modules/ModuleBuilder/parsers/ParserFactory.php');
        $parser = ParserFactory::getParser(MB_PORTALLISTVIEW,$this->editModule,null,null,MB_PORTAL);

        $smarty = $this->constructSmarty($parser);
        $smarty->assign('fromPortal',true); // flag for form submittal - when the layout is submitted the actions are the same for layouts and portal layouts, but the parsers must be different...
        //Override the list view buttons to remove references to the history feature as the portal editors do not support it.
        $buttons = array ( 
            array ( 
                'id' =>'savebtn', 
                'name' => 'savebtn', 
                'text' => translate('LBL_BTN_SAVEPUBLISH'), 
                'actionScript' => "onclick='studiotabs.generateGroupForm(\"edittabs\");" 
                    . "if (countListFields()==0) ModuleBuilder.layoutValidation.popup() ; else ModuleBuilder.handleSave(\"edittabs\" )'" 
            )
        ) ;
        $smarty->assign ( 'buttons', $this->_buildImageButtons ( $buttons ) ) ;
        
        
        $ajax = $this->constructAjax();
        $ajax->addSection('center', translate('LBL_EDIT_LAYOUT', 'ModuleBuilder'), $smarty->fetch("modules/ModuleBuilder/tpls/listView.tpl") );
        echo $ajax->getJavascript();

    }

    function constructAjax()
    {
        require_once('modules/ModuleBuilder/MB/AjaxCompose.php');
        $ajax = new AjaxCompose();

		$ajax->addCrumb(translate('LBL_SUGARPORTAL', 'ModuleBuilder'), 'ModuleBuilder.main("sugarportal")');
        $ajax->addCrumb(translate('LBL_LAYOUTS', 'ModuleBuilder'), 'ModuleBuilder.getContent("module=ModuleBuilder&action=wizard&portal=1&layout=1")');
  		$ajax->addCrumb(ucwords(translate('LBL_MODULE_NAME',$this->editModule)), 'ModuleBuilder.getContent("module=ModuleBuilder&action=wizard&portal=1&view_module='.$this->editModule.'")');
		$ajax->addCrumb(ucwords($this->editLayout), '');

        return $ajax;
    }
}
