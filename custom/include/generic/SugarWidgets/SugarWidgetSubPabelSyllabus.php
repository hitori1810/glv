<?php
    //Create Delete all session button
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
          
    class SugarWidgetSubPabelSyllabus extends SugarWidgetSubPanelTopSelectButton
    {    
        //This default function is used to create the HTML for a simple button
        function display(&$widget_data){   
            $button2 .= '<input type="button" id="btn_reload_syllabus" value="'.translate('LBL_RELOAD_SYLLABUS').'" />';
            return $button2;
        }
    }
