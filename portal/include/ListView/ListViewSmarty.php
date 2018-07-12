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

 
require_once('include/ListView/ListViewDisplay.php');
require_once('include/Sugar_Smarty.php');
require_once('include/utils.php');

class ListViewSmarty extends ListViewDisplay {
    
    var $data;
    var $ss; // the smarty object 
    var $displayColumns;
    var $tpl;
    var $moduleString;
    var $lvd;
    
    /**
     * Constructor, Smarty object immediately available after 
     *
     */
    function ListViewSmarty() {
        parent::ListViewDisplay();
        $this->ss = new Sugar_Smarty();
    }
    
    /**
     * Processes the request. Calls ListViewData process. Also assigns all lang strings, export links,
     * This is called from ListViewDisplay
     *    
     * @param file file Template file to use
     * @param data array from ListViewData
     * @param html_var string the corresponding html var in xtpl per row
     *
     */ 
    function process($file, $data, $htmlVar) {
        global $odd_bg, $even_bg, $hilite_bg, $click_bg, $app_strings, $image_path;
        parent::process($file, $data, $htmlVar);

        $this->tpl = $file;
        $this->data = $data;
       
        $this->ss->assign('bgHilite', $hilite_bg);
        $this->ss->assign('colCount', count($this->displayColumns) + 1);
        $this->ss->assign('htmlVar', strtoupper($htmlVar));
        $this->ss->assign('moduleString', $this->moduleString);

        $this->ss->assign('imagePath', $image_path);
        
        $this->processArrows($data['pageData']['ordering']);

        $this->ss->assign('clearAll', $app_strings['LBL_CLEARALL']);
        $this->ss->assign('rowColor', array('oddListRow', 'evenListRow'));
        $this->ss->assign('bgColor', array($odd_bg, $even_bg));
    }
    
    /**
     * Assigns the sort arrows in the tpl
     *    
     * @param ordering array data that contains the ordering info
     *
     */
    function processArrows($ordering) {
        global $png_support;
        
        if(empty($GLOBALS['image_path'])) {
            global $theme;
            $GLOBALS['image_path'] = 'themes/'.$theme.'/images/';
        }
        
        if ($png_support == false) $ext = 'gif';
        else $ext = 'png';
        
        list($width,$height) = getimagesize($GLOBALS['image_path'] . 'arrow.' . (($png_support) ? 'png' : 'gif'));
        
        $this->ss->assign('arrowExt', $ext);
        $this->ss->assign('arrowWidth', $width);
        $this->ss->assign('arrowHeight', $height);
    }   



    /**
     * Displays the xtpl, either echo or returning the contents
     *    
     * @param end bool display the ending of the listview data (ie MassUpdate)
     *
     */
    function display($end = true) {
        if(empty($this->data['data'])) {
            global $app_strings;
            return '<h3>' . $app_strings['LBL_NO_RECORDS_FOUND'] . '</h3>';
        }

        $this->ss->assign('data', $this->data['data']);

        $this->data['pageData']['offsets']['lastOffsetOnPage'] = $this->data['pageData']['offsets']['current'] + count($this->data['data']);
        
        $totalWidth = 0;
        foreach($this->displayColumns as $name => $params) {
            $totalWidth += $params['width'];
        }
        $adjustment = $totalWidth / 100;

        foreach($this->displayColumns as $name => $params) {
            $this->displayColumns[$name]['width'] = round($this->displayColumns[$name]['width'] / $adjustment, 2);
            $this->displayColumns[$name]['label'] = rtrim($this->data['pageData']['labels'][strtolower($name)], ':');
        }
        
        $this->ss->assign('pageData', $this->data['pageData']);
        $this->ss->assign('rowCount', $this->rowCount);
        $this->ss->assign('displayColumns', $this->displayColumns);     
        $str = parent::display();
        $strend = parent::displayEnd();
    
        return $str . $this->ss->fetch($this->tpl) . (($end) ? '<br><br>' . $strend : '');
    }
}

?>