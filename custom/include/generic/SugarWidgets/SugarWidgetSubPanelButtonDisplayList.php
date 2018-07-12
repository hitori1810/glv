<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    class SugarWidgetSubPanelButtonDisplayList extends SugarWidgetField
    {

        ///////---------Button on only row add by Quyen.Cao---////////
        function displayList($layout_def)
        {
            global $subpanel_item_count;
            $unique_id = $layout_def['subpanel_id']."_btn_".$subpanel_item_count; //bug 51512
            $class_btn=$layout_def['subpanel_id'];
            $html="<input type=\"button\" id=\"$unique_id\" class=\"$class_btn\" value=\"'.translate('LBL_BTN_ROW').'\" onclick=\"handleClick()\"/>";
            return $html;

        }
    }
?>
