<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


class SugarWidgetSubPanelCheckAll extends SugarWidgetField
{
	function displayHeaderCell(&$layout_def)
    {
        return '<input type="checkbox" id="checkall" onClick="toggleCheckAll(this)" />';
    }
}
?>
