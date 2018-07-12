<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


class SugarWidgetSubPanelCheckAllTeacher extends SugarWidgetField{
	function displayHeaderCell(&$layout_def){
        return '<input type="checkbox" id="checkall_teacher" onClick="toggleCheckAllTeacher(this)" />';
    }
}
?>
