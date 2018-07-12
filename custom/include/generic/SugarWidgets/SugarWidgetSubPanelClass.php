<?php
    //Create Delete all session button in Sessions subpanel - 24/07/2014 - by MTN
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
          
    class SugarWidgetSubPanelClass extends SugarWidgetSubPanelTopSelectButton
    {    
        //This default function is used to create the HTML for a simple button
        function display(&$widget_data){   
            $button2 .= '<input type="button" id="schedule_teacher" value="'.translate('LBL_SCHEDULE_TEACHER').'" onclick="schedule_teacher($(this));"/>';
            return $button2;
        }
    }
