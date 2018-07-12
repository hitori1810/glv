{*
/**
 * DetailView Of Survey Pages And Questions 
 * 
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
*}
{literal}
    <script type="text/javascript" src="custom/include/js/survey_js/drag-drop.js"></script>
{/literal}
<form action="index.php" method="post" name="DetailView" id="formDetailView">
    <input type="hidden" name="module" value="bc_survey_template">
    <input type="hidden" name="record" value="{$template->id}">
    <input type="hidden" name="return_action" value="index">
    <input type="hidden" name="return_module" value="{$module}">
    <input type="hidden" name="return_id" value="{$template->id}">
    <input type="hidden" name="module_tab">
    <input type="hidden" name="isDuplicate" value="false">
    <input type="hidden" name="offset" value="1">
    <input type="hidden" name="action" value="EditView">
    <input type="hidden" name="sugar_body_only">
    <div class="survey-view-section">
        
    </div>
</form>