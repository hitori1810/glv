<?php
if (! defined ( 'sugarEntry' ) || ! sugarEntry)
    die ( 'Not A Valid Entry Point' ) ;

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


require_once ('modules/ModuleBuilder/parsers/ParserFactory.php') ;
require_once ('modules/ModuleBuilder/MB/AjaxCompose.php') ;
require_once 'modules/ModuleBuilder/parsers/constants.php' ;

class ViewLayoutView extends SugarView
{
    function ViewLayoutView ()
    {
        $GLOBALS [ 'log' ]->debug ( 'in ViewLayoutView' ) ;
        $this->editModule = $_REQUEST [ 'view_module' ] ;
        $this->editLayout = $_REQUEST [ 'view' ] ;
        $this->package = null;
        $this->fromModuleBuilder = isset ( $_REQUEST [ 'MB' ] ) || !empty($_REQUEST [ 'view_package' ]);
        if ($this->fromModuleBuilder)
        {
            $this->package = $_REQUEST [ 'view_package' ] ;
            $this->type = $this->editLayout;
        } else
        {
            global $app_list_strings ;
            $moduleNames = array_change_key_case ( $app_list_strings [ 'moduleList' ] ) ;
            $this->translatedEditModule = $moduleNames [ strtolower ( $this->editModule ) ] ;
            $this->sm = StudioModuleFactory::getStudioModule($this->editModule);
            $this->type = $this->sm->getViewType($this->editLayout);
        }
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

    // DO NOT REMOVE - overrides parent ViewEdit preDisplay() which attempts to load a bean for a non-existent module
    function preDisplay ()
    {
    }

    function display ($preview = false)
    {

        global $mod_strings ;
        $parser = ParserFactory::getParser($this->editLayout,$this->editModule,$this->package);
        $history = $parser->getHistory () ;
        $smarty = new Sugar_Smarty ( ) ;
        //Add in the module we are viewing to our current mod strings
		if (! $this->fromModuleBuilder) {
			global $current_language;
			$editModStrings = return_module_language($current_language, $this->editModule);
			$mod_strings = sugarArrayMerge($editModStrings, $mod_strings);
		}
        $smarty->assign('mod', $mod_strings);
		$smarty->assign('MOD', $mod_strings);
        // assign buttons
        $images = array ( 'icon_save' => 'studio_save' , 'icon_publish' => 'studio_publish' , 'icon_address' => 'icon_Address' , 'icon_emailaddress' => 'icon_EmailAddress' , 'icon_phone' => 'icon_Phone' ) ;
        foreach ( $images as $image => $file )
        {
            $smarty->assign ( $image, SugarThemeRegistry::current()->getImage($file,'',null,null,'.gif',$file) ) ;
        }

        $requiredFields = implode($parser->getRequiredFields () , ',');
        $slashedRequiredFields = addslashes($requiredFields);
        $buttons = array ( ) ;
        $disableLayout = false;

        if ($preview)
        {
            $smarty->assign ( 'layouttitle', translate ( 'LBL_LAYOUT_PREVIEW', 'ModuleBuilder' ) ) ;
        } else
        {
            $smarty->assign ( 'layouttitle', translate ( 'LBL_CURRENT_LAYOUT', 'ModuleBuilder' ) ) ;

            //Check if we need to synch edit view to other layouts
            if($this->editLayout == MB_DETAILVIEW || $this->editLayout == MB_QUICKCREATE){
		        $parser2 = ParserFactory::getParser(MB_EDITVIEW,$this->editModule,$this->package);
                if($this->editLayout == MB_DETAILVIEW){
		            $disableLayout = $parser2->getSyncDetailEditViews();
                }
                if(!empty($_REQUEST['copyFromEditView'])){
                    $editViewPanels = $parser2->convertFromCanonicalForm ( $parser2->_viewdefs [ 'panels' ] , $parser2->_fielddefs ) ;
                    $parser->_viewdefs [ 'panels' ] = $editViewPanels;
                    $parser->_fielddefs = $parser2->_fielddefs;
                    $parser->setUseTabs($parser2->getUseTabs());
                    $parser->setTabDefs($parser2->getTabDefs());
                }
		    }

            if (! $this->fromModuleBuilder)
            {
	            $buttons [] = array (
                    'id' => 'saveBtn' , 
                    'text' => translate ( 'LBL_BTN_SAVE' ) , 
                    'actionScript' => "onclick='if(Studio2.checkGridLayout(\"{$this->editLayout}\")) Studio2.handleSave();'",
                	'disabled' => $disableLayout, 
                ) ;
                $buttons [] = array ( 
                    'id' => 'publishBtn' , 
                    'text' => translate ( 'LBL_BTN_SAVEPUBLISH' ) , 
                    'actionScript' => "onclick='if(Studio2.checkGridLayout(\"{$this->editLayout}\")) Studio2.handlePublish();'",
                	'disabled' => $disableLayout, 
                ) ;
                $buttons [] = array ( 'id' => 'spacer' , 'width' => '33px' ) ;
                $buttons [] = array ( 
	                'id' => 'historyBtn' , 
	                'text' => translate ( 'LBL_HISTORY' ) , 
	                'actionScript' => "onclick='ModuleBuilder.history.browse(\"{$this->editModule}\", \"{$this->editLayout}\")'",
                    'disabled' => $disableLayout,
                ) ;
                $buttons [] = array ( 
	                'id' => 'historyDefault' , 
	                'text' => translate ( 'LBL_RESTORE_DEFAULT' ) , 
	                'actionScript' => "onclick='ModuleBuilder.history.revert(\"{$this->editModule}\", \"{$this->editLayout}\", \"{$history->getLast()}\", \"\")'",
                	'disabled' => $disableLayout, 
                ) ;
            } else
            {
                $buttons [] = array ( 
                    'id' => 'saveBtn' , 
                    'text' => $GLOBALS [ 'mod_strings' ] [ 'LBL_BTN_SAVE' ] , 
                    'actionScript' => "onclick='if(Studio2.checkGridLayout(\"{$this->editLayout}\")) Studio2.handlePublish();'",
                    'disabled' => $disableLayout,
                ) ;
                $buttons [] = array ( 'id' => 'spacer' , 'width' => '33px' ) ;
                $buttons [] = array (
                    'id' => 'historyBtn' , 
                    'text' => translate ( 'LBL_HISTORY' ) , 
                    'actionScript' => "onclick='ModuleBuilder.history.browse(\"{$this->editModule}\", \"{$this->editLayout}\")'",
                    'disabled' => $disableLayout, 
                ) ;
                $buttons [] = array ( 
                    'id' => 'historyDefault' , 
                    'text' => translate ( 'LBL_RESTORE_DEFAULT' ) , 
                    'actionScript' => "onclick='ModuleBuilder.history.revert(\"{$this->editModule}\", \"{$this->editLayout}\", \"{$history->getLast()}\", \"\")'",
                    'disabled' => $disableLayout, 
                ) ;
            }


            if($this->editLayout == MB_DETAILVIEW || $this->editLayout == MB_QUICKCREATE){
                $buttons [] = array (
                'id' => 'copyFromEditView' ,
                'text' => translate ( 'LBL_COPY_FROM_EDITVIEW' ) ,
                'actionScript' => "onclick='ModuleBuilder.copyFromView(\"{$this->editModule}\", \"{$this->editLayout}\")'",
                'disabled' => $disableLayout,
                ) ;
            }
        }

        $html = "" ;
        foreach ( $buttons as $button )
        {
            if ($button['id'] == "spacer") {
            	$html .= "<td style='width:{$button['width']}'> </td>";
            } else {
        	    $html .= "<td><input id='{$button['id']}' type='button' valign='center' class='button' style='cursor:pointer' "
        	       . "onmousedown='this.className=\"buttonOn\";return false;' onmouseup='this.className=\"button\"' "
        	       . "onmouseout='this.className=\"button\"' {$button['actionScript']} value = '{$button['text']}'" ;
        	    if(!empty($button['disabled'])){
        	    	 $html .= " disabled";
        	    }
        	    $html .= "></td>";
            }
        }

        $smarty->assign ( 'buttons', $html ) ;

        // assign fields and layout
        $smarty->assign ( 'available_fields', $parser->getAvailableFields () ) ;
        
        $smarty->assign ( 'disable_layout', $disableLayout) ;
        $smarty->assign ( 'required_fields', $requiredFields) ;
        $smarty->assign ( 'layout', $parser->getLayout () ) ;
        $smarty->assign ( 'field_defs', $parser->getFieldDefs () ) ;
        $smarty->assign ( 'view_module', $this->editModule ) ;
        $smarty->assign ( 'view', $this->editLayout ) ;
        $smarty->assign ( 'maxColumns', $parser->getMaxColumns() ) ;
        $smarty->assign ( 'nextPanelId', $parser->getFirstNewPanelId() ) ;
        $smarty->assign ( 'displayAsTabs', $parser->getUseTabs() ) ;
        $smarty->assign ( 'tabDefs', $parser->getTabDefs() ) ;
        $smarty->assign ( 'syncDetailEditViews', $parser->getSyncDetailEditViews() ) ;
        $smarty->assign ( 'fieldwidth', 150 ) ;
        // Bug 57260 - LBL_PANEL_DEFAULT not translated for undeployed modules in layout editor
        $smarty->assign ( 'translate', true ) ;

        if ($this->fromModuleBuilder)
        {
            $smarty->assign ( 'fromModuleBuilder', $this->fromModuleBuilder ) ;
            $smarty->assign ( 'view_package', $this->package ) ;
        }

        $labels = array (
        			MB_EDITVIEW => 'LBL_EDITVIEW' ,
        			MB_DETAILVIEW => 'LBL_DETAILVIEW' ,
        			MB_QUICKCREATE => 'LBL_QUICKCREATE',
        			MB_WIRELESSEDITVIEW => 'LBL_WIRELESSEDITVIEW' ,
        			MB_WIRELESSDETAILVIEW => 'LBL_WIRELESSDETAILVIEW' ,
        			) ;

        $layoutLabel = 'LBL_LAYOUTS' ;
        $layoutView = 'layouts' ;

        if ( in_array ( $this->editLayout , array ( MB_WIRELESSEDITVIEW , MB_WIRELESSDETAILVIEW ) ) )
        {
        	$layoutLabel = 'LBL_WIRELESSLAYOUTS' ;
        	$layoutView = 'wirelesslayouts' ;
        	$smarty->assign('wireless', true);
        }

        $ajax = new AjaxCompose ( ) ;

        $translatedViewType = '' ;
		if ( isset ( $labels [ strtolower ( $this->editLayout ) ] ) )
			$translatedViewType = translate ( $labels [ strtolower( $this->editLayout ) ] , 'ModuleBuilder' ) ;
        else if (isset($this->sm))
        {
            foreach($this->sm->sources as $file => $def)
            {
                if (!empty($def['view']) && $def['view'] == $this->editLayout && !empty($def['name']))
                {
                    $translatedViewType = $def['name'];
                }
            }
            if(empty($translatedViewType))
            {
                $label = "LBL_" . strtoupper($this->editLayout);
                $translated = translate($label, $this->editModule);
                if ($translated != $label)
                    $translatedViewType =  $translated;
            }
        }




        if ($this->fromModuleBuilder)
        {
            $ajax->addCrumb ( translate ( 'LBL_MODULEBUILDER', 'ModuleBuilder' ), 'ModuleBuilder.main("mb")' ) ;
            $ajax->addCrumb ( $this->package, 'ModuleBuilder.getContent("module=ModuleBuilder&action=package&package=' . $this->package . '")' ) ;
            $ajax->addCrumb ( $this->editModule, 'ModuleBuilder.getContent("module=ModuleBuilder&action=module&view_package=' . $this->package . '&view_module=' . $this->editModule . '")' ) ;
            $ajax->addCrumb ( translate ( $layoutLabel, 'ModuleBuilder' ), 'ModuleBuilder.getContent("module=ModuleBuilder&MB=true&action=wizard&view='.$layoutView.'&view_module=' . $this->editModule . '&view_package=' . $this->package . '")' ) ;
            $ajax->addCrumb ( $translatedViewType, '' ) ;
        } else
        {
            $ajax->addCrumb ( translate ( 'LBL_STUDIO', 'ModuleBuilder' ), 'ModuleBuilder.main("studio")' ) ;
            $ajax->addCrumb ( $this->translatedEditModule, 'ModuleBuilder.getContent("module=ModuleBuilder&action=wizard&view_module=' . $this->editModule . '")' ) ;
            $ajax->addCrumb ( translate ( $layoutLabel, 'ModuleBuilder' ), 'ModuleBuilder.getContent("module=ModuleBuilder&action=wizard&view='.$layoutView.'&view_module=' . $this->editModule . '")' ) ;
            $ajax->addCrumb ( $translatedViewType, '' ) ;
        }

        // set up language files
		$smarty->assign ( 'language', $parser->getLanguage() ) ; // for sugar_translate in the smarty template
        $smarty->assign('from_mb',$this->fromModuleBuilder);
        $smarty->assign('calc_field_list', json_encode($parser->getCalculatedFields()));
		if ($this->fromModuleBuilder) {
			$mb = new ModuleBuilder ( ) ;
            $module = & $mb->getPackageModule ( $this->package, $this->editModule ) ;
		    $smarty->assign('current_mod_strings', $module->getModStrings());
		}

        $ajax->addSection ( 'center', $translatedViewType, $smarty->fetch ( 'modules/ModuleBuilder/tpls/layoutView.tpl' ) ) ;
        if ($preview) {
        	echo $smarty->fetch ( 'modules/ModuleBuilder/tpls/Preview/layoutView.tpl' );
		} else {
			echo $ajax->getJavascript () ;
    	}
    }
}
