<?php
    //Create Delete all session button in Sessions subpanel - 24/07/2014 - by MTN
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
          
    class SugarWidgetSubPanelDeleteAllSessionButton extends SugarWidgetSubPanelTopSelectButton
    {    
        //This default function is used to create the HTML for a simple button
        function display(&$widget_data)
        {   
            $button2 .= '<input type="button" id="view_on_c" value="'.translate('LBL_VIEW_ON_C').'" onclick="location.href=\'index.php?module=Calendar&action=index&view=month&only=Session&class_id='.$this->parent_bean->id.'\'"/>';
            //$button1 = '<input type="button" id="delete_all_button" value="'.$GLOBALS['mod_strings']['LBL_DELETE_ALL_SESSION'].'"/>';
            return $button2;
        }
    }
?>
